<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Table;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;



class CashierController extends Controller
{
    public function index()
    {
        $categories = Category::with('menus')->get();
        $tables = Table::all();

        return view('cashier.index', compact('categories', 'tables'));
    }

    public function showCheckoutDetails(Request $request)
{
    $validatedData = $this->validateCheckoutRequest($request);

    $cart = json_decode($validatedData['cart'], true);

    if (empty($cart)) {
        return redirect()->route('cashier.index')->withErrors(['cart' => 'Keranjang tidak boleh kosong.']);
    }

    try {
        $totalAmount = $this->validateCartItems($cart);

        // Cari voucher aktif berdasarkan tanggal

        $activeVoucher = Voucher::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();

        // Hitung diskon jika ada voucher aktif

        $discount = $activeVoucher ? $activeVoucher->discount_amount : 0;
        $totalAfterDiscount = max(0, $totalAmount - $discount); // Pastikan total tidak negatif

        return view('cashier.checkout_details', [
            'cart' => $cart,
            'totalAmount' => $totalAmount,
            'discount' => $discount,
            'totalAfterDiscount' => $totalAfterDiscount,
            'user_name' => $validatedData['user_name'],
            'table_number' => $validatedData['table_number'],
            'payment_type' => $validatedData['payment_type'],
            'table_id' => $validatedData['table_id'],
        ]);
    } catch (\Exception $e) {
        return redirect()->route('cashier.index')->withErrors(['cart' => $e->getMessage()]);
    }
}

    public function finalizeCheckout(Request $request)
    {
         // Validasi request
        $validated = $request->validate([
            'user_name' => 'required|string',
            'cart' => 'required|json',
            'payment_type' => 'required|string',
        ]);

        // Tambahkan atau update pelanggan
        $customer = Customer::firstOrCreate(
            ['name' => $validated['user_name']],
            [
                'loyalty_points' => 0,
                'unique_code' => Str::random(9), // Generate kode unik
            ]
        );

        // Tambahkan 50 poin loyalitas
        $customer->increment('loyalty_points', 50);

        $validatedData = $this->validateCheckoutRequest($request);

        $cart = json_decode($validatedData['cart'], true);

        if (empty($cart)) {
            return redirect()->route('cashier.index')->withErrors(['cart' => 'Keranjang tidak boleh kosong.']);
        }

        try {
            $totalAmount = $this->validateCartItems($cart);

            if ($validatedData['payment_type'] === 'online') {
                return $this->processOnlinePayment($cart, $validatedData['user_name'], $totalAmount);
            }

            
            // Validasi diskon
            $activeVoucher = Voucher::where('start_date', '<=', now())
                ->where('end_date', '>=', now())
                ->first();

            $discount = $activeVoucher ? $activeVoucher->discount_amount : 0;

            // Hitung total harga setelah diskon
            $totalAmount = $this->validateCartItems($cart);
            $totalAmount -= $discount;
            $totalAmount = max(0, $totalAmount);

            //hitung pajak 12%
            $taxRate = 0.12;
            $taxAmount = $totalAmount * $taxRate;
            $grandTotal = $totalAmount + $taxAmount;

            DB::beginTransaction();

            $orderId = DB::table('orders')->insertGetId([
                'user_name' => $validatedData['user_name'],
                'table_id' => $validatedData['table_id'],
                'table_number' => $validatedData['table_number'],
                'payment_type' => $validatedData['payment_type'],
                'total_amount' => $totalAmount,
                'tax_amount' => $taxAmount,
                'grand_total' => $grandTotal,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($cart as $item) {
                DB::table('order_details')->insert([
                    'order_id' => $orderId,
                    'menu_id' => $item['id'],
                    'menu_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            foreach ($cart as $item) {
                DB::table('transactions')->insert([
                    'customer_name' => $validatedData['user_name'],
                    'menu_name' => $item['name'],
                    'quantity' => $item['quantity'],
                    'total_purchase' => $item['price'] * $item['quantity'],
                    'payment_type' => $validatedData['payment_type'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::commit();

            // Generate PDF URL for download
            $pdfUrl = route('cashier.downloadCheckoutPdf', [
                'cart' => json_encode($cart),
                'user_name' => $validatedData['user_name'],
                'table_number' => $validatedData['table_number'],
                'payment_type' => $validatedData['payment_type'],
            ]);

            // Store URL in session for client-side download
            session()->flash('pdf_download_url', $pdfUrl);

            return redirect()->route('cashier.index')->with('success', 'Pesanan berhasil diproses!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error saat memproses pesanan: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan. Silakan coba lagi.']);
        }
    }

    // Metode validasi input request
    protected function validateCheckoutRequest(Request $request): array
    {
        return $request->validate([
            'user_name' => 'required|string|max:255',
            'table_id' => 'nullable|exists:tables,id',
            'table_number' => 'nullable|string|max:50',
            'payment_type' => 'required|in:cash,card,online',
            'cart' => 'required|json',
        ]);
    }

    protected function validateCartItems(array $cart)
    {
        $totalPrice = 0;

        foreach ($cart as $item) {
            if (!isset($item['id'], $item['price'], $item['quantity']) ||
                !is_numeric($item['price']) ||
                !is_numeric($item['quantity']) ||
                $item['quantity'] <= 0) {
                throw new \Exception('Data keranjang tidak valid!');
            }

            $menu = Menu::find($item['id']);
            if (!$menu) {
                throw new \Exception("Menu dengan ID {$item['id']} tidak ditemukan!");
            }

            if ($menu->price != $item['price']) {
                throw new \Exception("Harga menu {$menu->name} tidak sesuai!");
            }

            $totalPrice += $menu->price * $item['quantity'];
        }

        return $totalPrice;
    }

   public function downloadCheckoutPdf(Request $request)
    {
        $cart = json_decode($request->input('cart'), true);
        $totalAmount = $this->validateCartItems($cart);

        // Hitung diskon dan total setelah diskon
        $activeVoucher = Voucher::where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->first();
        $discount = $activeVoucher ? $activeVoucher->discount_amount : 0;
        $totalAfterDiscount = max(0, $totalAmount - $discount);

        // Hitung pajak dan total keseluruhan
        $taxAmount = $totalAfterDiscount * 0.12;
        $grandTotal = $totalAfterDiscount + $taxAmount;

        $pdf = Pdf::loadView('cashier.checkout_details_pdf', [
            'cart' => $cart,
            'totalAmount' => $totalAmount,
            'discount' => $discount,
            'totalAfterDiscount' => $totalAfterDiscount,
            'taxAmount' => $taxAmount,
            'grandTotal' => $grandTotal,
        ]);

        return $pdf->download('invoice.pdf');
    }

    public function transactionHistory()
    {
        $transactions = DB::table('transactions')
            ->orderBy('created_at', 'desc')
            ->get();

        Log::info($transactions);

        return view('cashier.history', compact('transactions'));
    }

    public function customerReport(Request $request)
    {
        $dateFilter = $request->input('date', now()->format('Y-m-d'));

        $dailyReport = DB::table('transactions')
            ->selectRaw('DATE(created_at) as date, COUNT(DISTINCT customer_name) as total_customers')
            ->whereDate('created_at', $dateFilter)
            ->groupBy('date')
            ->get();

        $monthlyReport = DB::table('transactions')
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(DISTINCT customer_name) as total_customers')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        $yearlyReport = DB::table('transactions')
            ->selectRaw('YEAR(created_at) as year, COUNT(DISTINCT customer_name) as total_customers')
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();

        return view('cashier.reports', compact('dailyReport', 'monthlyReport', 'yearlyReport', 'dateFilter'));
    }


}