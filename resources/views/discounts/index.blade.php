@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <!-- Sidebar -->
        @include('management.import.sidebar')

        <!-- Main Content -->
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #abc32f;">
                    <h5 class="mb-0"><i class="fa fa-tags me-2"></i> Manajemen Diskon</h5>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addVoucherModal">
                        <i class="fa fa-plus"></i> Tambah Diskon
                    </button>
                </div>
                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success">{{ session('status') }}</div>
                    @endif

                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kode Voucher</th>
                                <th>Diskon</th>
                                <th>Periode</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vouchers as $voucher)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $voucher->code }}</td>
                                    <td>Rp{{ number_format($voucher->discount_amount, 0, ',', '.') }}</td>
                                    <td>{{ $voucher->start_date }} - {{ $voucher->end_date }}</td>
                                    <td>
                                        <form action="{{ route('vouchers.destroy', $voucher->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus voucher ini?')">
                                                <i class="fa fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Voucher -->
<div class="modal fade" id="addVoucherModal" tabindex="-1" aria-labelledby="addVoucherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #abc32f;">
                <h5 class="modal-title text-white" id="addVoucherModalLabel">Tambah Voucher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('vouchers.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="code" class="form-label">Kode Voucher</label>
                        <input type="text" class="form-control" name="code" required>
                    </div>
                    <div class="mb-3">
                        <label for="discount_amount" class="form-label">Diskon (Rp)</label>
                        <input type="number" class="form-control" name="discount_amount" required>
                    </div>
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" name="start_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="end_date" class="form-label">Tanggal Berakhir</label>
                        <input type="date" class="form-control" name="end_date" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
