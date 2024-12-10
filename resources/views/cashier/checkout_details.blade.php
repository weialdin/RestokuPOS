@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="background: #abc32f; color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <i class="fa fa-check-circle me-2" style="font-size: 1.5rem;"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Error Alert -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
            <i class="fa fa-exclamation-circle me-2" style="font-size: 1.5rem;"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Sidebar Menu -->
        @include('management.import.sidebar')

        <!-- Main Content Area -->
        <div class="col-md-9">
            <div class="card shadow-lg border-0">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #abc32f;">
                    <h5 class="mb-0"><i class="fa fa-check-circle me-2"></i> Detail Checkout</h5>
                </div>
                <div class="card-body">
                    <!-- Customer Information -->
                    <h5 class="fw-bold">Informasi Pengguna</h5>
                    <p><strong>Nama:</strong> {{ $user_name }}</p>
                    <p><strong>Meja:</strong> {{ $table_number }}</p>

                    <!-- Cart Details -->
                    <h6 class="fw-bold mt-4">Rincian Pesanan</h6>
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart as $item)
                                <tr>
                                    <td>{{ $item['name'] }}</td>
                                    <td>Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                                    <td>{{ $item['quantity'] }}</td>
                                    <td>Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <!-- Total Amount -->
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Subtotal:</span>
                        <span>Rp{{ number_format($totalAmount, 0, ',', '.') }}</span>
                    </div>
                    @if(isset($discount) && $discount > 0)
                        <div class="d-flex justify-content-between fw-bold text-success">
                            <span>Diskon:</span>
                            <span>- Rp{{ number_format($discount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total Setelah Diskon:</span>
                        <span>Rp{{ number_format($totalAfterDiscount, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Pajak (12%):</span>
                        <span>Rp{{ number_format($totalAfterDiscount * 0.12, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total Keseluruhan:</span>
                        <span>Rp{{ number_format($totalAfterDiscount + ($totalAfterDiscount * 0.12), 0, ',', '.') }}</span>
                    </div>

                    <!-- Payment Information -->
                    <h6 class="fw-bold mt-4">Informasi Pembayaran</h6>
                    <p><strong>Jenis Pembayaran:</strong> {{ ucfirst($payment_type) }}</p>

                    <!-- Confirmation Button -->
                    <form action="{{ route('cashier.finalizeCheckout') }}" method="POST">
                        @csrf
                        <input type="hidden" name="cart" value="{{ json_encode($cart) }}">
                        <input type="hidden" name="table_id" value="{{ $table_id }}">
                        <input type="hidden" name="table_number" value="{{ $table_number }}">
                        <input type="hidden" name="user_name" value="{{ $user_name }}">
                        <input type="hidden" name="payment_type" value="{{ $payment_type }}">
                        <input type="hidden" name="discount" value="{{ $discount ?? 0 }}">
                        <button type="submit" class="btn btn-success w-100 mt-3" style="background-color: #abc32f;">Proses Pembayaran</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
