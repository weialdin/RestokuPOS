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
                    <h5 class="mb-0"><i class="fa fa-folder-open me-2"></i> Category</h5>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <i class="fa fa-plus"></i> Add New Category
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $index => $category)
                                    <tr>
                                        <td>{{ $loop->iteration + ($categories->currentPage() - 1) * $categories->perPage() }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button class="btn btn-outline-warning btn-sm" title="Edit" data-bs-toggle="modal" data-bs-target="#editCategoryModal{{ $category->id }}">
                                                    <i class="fa fa-edit"></i>
                                                </button>
                                                <form action="{{ route('category.destroy', $category->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure want to delete this category?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Enhanced Pagination Links -->
                    <div class="d-flex justify-content-end mt-3">
                        {{ $categories->onEachSide(1)->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Category Modal -->
@foreach($categories as $category)
<div class="modal fade" id="editCategoryModal{{ $category->id }}" tabindex="-1" aria-labelledby="editCategoryModalLabel{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #abc32f;">
                <h5 class="modal-title text-white" id="editCategoryModalLabel{{ $category->id }}">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('category.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="categoryName{{ $category->id }}" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="categoryName{{ $category->id }}" name="name" value="{{ $category->name }}" required>
                    </div>
                    <button type="submit" class="btn text-white w-100" style="background-color: #abc32f;">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #abc32f;">
                <h5 class="modal-title text-white" id="addCategoryModalLabel">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('category.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="categoryName" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="categoryName" name="name" required>
                    </div>
                    <button type="submit" class="btn text-white w-100" style="background-color: #abc32f;">Save Category</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
