<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;

class VoucherController extends Controller
{
    public function index()
    {
        // Hapus voucher yang sudah kedaluwarsa
        Voucher::where('end_date', '<', now())->delete();

        // Ambil semua voucher aktif
        $vouchers = Voucher::orderBy('end_date', 'asc')->get();

        return view('discounts.index', compact('vouchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:vouchers,code',
            'discount_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        Voucher::create($request->all());

        return redirect()->route('vouchers.index')->with('status', 'Voucher berhasil ditambahkan!');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();

        return redirect()->route('vouchers.index')->with('status', 'Voucher berhasil dihapus!');
    }
}
