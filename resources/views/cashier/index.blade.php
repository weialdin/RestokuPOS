@extends('layouts.app')

@section('content')
<div class="container mt-5">
   @if(session('pdf_download_url'))
<script>
    window.onload = function () {
        const pdfUrl = "{{ session('pdf_download_url') }}";

        if (pdfUrl) {
            // Buat elemen link untuk membuka PDF
            const link = document.createElement('a');
            link.href = pdfUrl;

            // Opsional: Jika ingin langsung mengunduh
            link.download = 'invoice.pdf';

            // Tambahkan ke DOM, klik, lalu hapus
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        } else {
            console.error("URL PDF tidak ditemukan.");
        }
    };
</script>
@endif




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
                    <h5 class="mb-0"><i class="fa fa-shopping-cart me-2"></i> Halaman Kasir</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Produk Section -->
                        <div class="col-md-6">
                            <h6 class="fw-bold">Pilih Kategori</h6>
                            <select id="categorySelect" class="form-control mb-3">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>

                            <div id="menuContainer">
                                <h6 class="fw-bold">Menu</h6>
                                <div class="menu-list d-flex flex-wrap gap-2 mb-3">
                                    @foreach($categories as $category)
                                        <div class="menu-category" data-category-id="{{ $category->id }}" style="display: none;">
                                            @foreach($category->menus as $menu)
                                                <button class="btn btn-outline-primary btn-sm add-to-cart" 
                                                    data-id="{{ $menu->id }}" 
                                                    data-name="{{ $menu->name }}" 
                                                    data-price="{{ $menu->price }}">
                                                    {{ $menu->name }} - Rp{{ number_format($menu->price, 0, ',', '.') }}
                                                </button>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-4">
                                <h6 class="fw-bold">Pilih Nomor Meja</h6>
                                <select id="tableNumber" class="form-control">
                                    <option value="">-- Pilih Nomor Meja --</option>
                                    @foreach($tables as $table)
                                        <option value="{{ $table->id }}">{{ $table->table_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Keranjang Section -->
                        <div class="col-md-6">
                            <h6 class="fw-bold">Keranjang</h6>
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Harga</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="cartTable"></tbody>
                            </table>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span id="totalAmount">Rp0</span>
                            </div>
                            <form id="checkoutForm" action="{{ route('cashier.checkoutDetails') }}" method="GET">
                                @csrf
                                <input type="hidden" name="cart" id="cartInput">
                                <input type="hidden" name="table_id" id="selectedTableId">
                                <input type="hidden" name="table_number" id="selectedTableNumber">
                                <input type="text" name="user_name" class="form-control mb-2" placeholder="Masukkan Nama Pengguna" required>
                                <select name="payment_type" class="form-control mb-2" required>
                                    <option value="">-- Pilih Jenis Pembayaran --</option>
                                    <option value="cash">Tunai</option>
                                    <option value="online">Pembayaran Digital</option>
                                </select>
                                <button type="submit" class="btn btn-success w-100 mt-3" style="background-color: #abc32f;">Bayar Sekarang</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
