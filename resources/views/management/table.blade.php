@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Success Alert -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert" style="background: linear-gradient(135deg, #6ecf68, #46a049); color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <i class="fa fa-check-circle me-2" style="font-size: 1.5rem;"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="color: #fff; opacity: 1;">
                <i class="fa fa-times" style="font-size: 1.2rem;"></i>
            </button>
        </div>
    @endif

    <!-- Error Alert -->
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert" style="background: linear-gradient(135deg, #f44a4a, #d13535); color: #fff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);">
            <i class="fa fa-exclamation-circle me-2" style="font-size: 1.5rem;"></i>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="color: #fff; opacity: 1;">
                <i class="fa fa-times" style="font-size: 1.2rem;"></i>
            </button>
        </div>
    @endif

    <div class="row justify-content-center">
        <!-- Sidebar Menu -->
        @include('management.import.sidebar')

        <!-- Main Content Area -->
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #abc32f;">
                    <h5 class="mb-0"><i class="fa fa-table me-2"></i> Manajemen Meja Restoran</h5>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addTableModal">
                        <i class="fa fa-plus"></i> Tambah Meja Baru
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nomor Meja</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($tables as $table)
                                    <tr>
                                        <td>{{ $table->id }}</td>
                                        <td>{{ $table->table_number }}</td>
                                        <td>{{ ucfirst($table->status) }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-outline-warning btn-sm" title="Edit" data-bs-toggle="modal" data-bs-target="#editTableModal{{ $table->id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <form action="{{ route('table.destroy', $table->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus meja ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Hapus"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Tambahkan kontrol paginasi di sini -->
                    <div class="d-flex justify-content-end mt-3">
                        {{ $tables->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Meja -->
@foreach($tables as $table)
<div class="modal fade" id="editTableModal{{ $table->id }}" tabindex="-1" aria-labelledby="editTableModalLabel{{ $table->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #abc32f;">
                <h5 class="modal-title text-white" id="editTableModalLabel{{ $table->id }}">Edit Meja #{{ $table->table_number }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('table.update', $table->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="table_number_{{ $table->id }}" class="form-label">Nomor Meja</label>
                        <input type="text" class="form-control" id="table_number_{{ $table->id }}" name="table_number" value="{{ $table->table_number }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="status_{{ $table->id }}" class="form-label">Status</label>
                        <select id="status_{{ $table->id }}" name="status" class="form-control">
                            <option value="available" {{ $table->status == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="occupied" {{ $table->status == 'occupied' ? 'selected' : '' }}>Terisi</option>
                            <option value="reserved" {{ $table->status == 'reserved' ? 'selected' : '' }}>Dipesan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn text-white w-100" style="background-color: #abc32f;">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Tambah Meja -->
<div class="modal fade" id="addTableModal" tabindex="-1" aria-labelledby="addTableModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #abc32f;">
                <h5 class="modal-title text-white" id="addTableModalLabel">Tambah Meja Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('table.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="table_number" class="form-label">Nomor Meja</label>
                        <input type="text" class="form-control" id="table_number" name="table_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="available">Tersedia</option>
                            <option value="occupied">Terisi</option>
                            <option value="reserved">Dipesan</option>
                        </select>
                    </div>
                    <button type="submit" class="btn text-white w-100" style="background-color: #abc32f;">Tambah Meja</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
