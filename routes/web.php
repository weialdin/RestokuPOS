<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Management\CategoryController;
use App\Http\Controllers\Management\MenuController;
use App\Http\Controllers\Management\TableController;
use App\Http\Controllers\Cashier\CashierController;
use App\Http\Controllers\Management\CustomerController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\Cashier\ReportController;
use App\Http\Controllers\Statistics\StatisticsController;

// Redirect root URL to the login page
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Auth::routes();

// Route untuk halaman home yang hanya dapat diakses setelah login
Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware('auth');

// Route group untuk halaman management yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {
    // Halaman utama management
    Route::get('/management', function () {
        return view('management.index');
    })->name('management');

    Route::resource('management/category', CategoryController::class);
    Route::resource('management/menu', MenuController::class);
    Route::resource('management/table', TableController::class);
});

// Route group untuk halaman cashier yang membutuhkan autentikasi

Route::middleware(['auth'])->prefix('cashier')->name('cashier.')->group(function () {
    Route::get('/', [CashierController::class, 'index'])->name('index');
    Route::post('/checkout', [CashierController::class, 'checkout'])->name('checkout');
    Route::get('/checkout-details', [CashierController::class, 'showCheckoutDetails'])->name('checkoutDetails');
    Route::post('/finalize-checkout', [CashierController::class, 'finalizeCheckout'])->name('finalizeCheckout');
    Route::get('/download-pdf', [CashierController::class, 'downloadCheckoutPdf'])->name('downloadCheckoutPdf');
    Route::get('/history', [CashierController::class, 'transactionHistory'])->name('cashier.history');
    Route::get('/customer-report', [CashierController::class, 'customerReport'])->name('cashier.customerReport');

});



Route::middleware(['auth'])->group(function (){
    Route::resource('customers', CustomerController::class)->except(['show']);
    Route::get('/vouchers', [VoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/vouchers', [VoucherController::class, 'store'])->name('vouchers.store');
    Route::delete('/vouchers/{voucher}', [VoucherController::class, 'destroy'])->name('vouchers.destroy');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel']);
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf']);
});


