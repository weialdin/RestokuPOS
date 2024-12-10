@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <!-- Sidebar Menu -->
            @include('management.import.sidebar')

            <!-- Main Content Area -->
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-header text-white d-flex justify-content-between align-items-center" style="background-color: #abc32f;">
                        <h5 class="mb-0"><i class="fa fa-folder-open me-2"></i>Menus</h5>
                        <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addMenuModal">
                            <i class="fa fa-plus"></i> Add New Menu
                        </button>
                    </div>

                    <div class="card-body">
                        <!-- Display Success or Error Messages -->
                        @if(session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Menu Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Description</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($menus as $index => $menu)
                                    <tr>
                                        <td>{{ $loop->iteration + ($menus->currentPage() - 1) * $menus->perPage() }}</td>
                                        <td>
                                            @if($menu->image)
                                                <img src="{{ asset('storage/' . $menu->image) }}" alt="Menu Image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                            @else
                                                <span>No Image</span>
                                            @endif
                                        </td>
                                        <td>{{ $menu->name }}</td>
                                        <td>{{ $menu->category->name ?? 'Uncategorized' }}</td>
                                        <td>{{ $menu->description }}</td>
                                        <td>Rp. {{ number_format($menu->price, 0, ',', '.') }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $menu->id }}"><i class="fa fa-edit"></i> Edit</button>
                                            <form action="{{ route('menu.destroy', $menu->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this menu?')"><i class="fa fa-trash"></i> Delete</button>
                                            </form>
                                        </td>
                                    </tr>

                                    <!-- Modal for Editing Menu -->
                                    <div class="modal fade" id="editMenuModal{{ $menu->id }}" tabindex="-1" aria-labelledby="editMenuModalLabel{{ $menu->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background-color: #abc32f;">
                                                    <h5 class="modal-title text-white" id="editMenuModalLabel{{ $menu->id }}">Edit Menu</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form enctype="multipart/form-data" action="{{ route('menu.update', $menu->id) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="mb-3">
                                                            <label for="menuName" class="form-label">Menu Name</label>
                                                            <input type="text" class="form-control" name="name" value="{{ $menu->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="category" class="form-label">Category</label>
                                                            <select class="form-control" name="category_id" required>
                                                                <option value="">Select Category</option>
                                                                @foreach($categories as $category)
                                                                    <option value="{{ $category->id }}" {{ $menu->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="menuPrice" class="form-label">Menu Price</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text">Rp.</span>
                                                                <input type="text" name="price" class="form-control" value="{{ $menu->price }}" required>
                                                                <span class="input-group-text">,00</span>
                                                            </div>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="menuDescription" class="form-label">Description</label>
                                                            <textarea class="form-control" name="description" rows="3" required>{{ $menu->description }}</textarea>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="menuImage" class="form-label">Upload Image</label>
                                                            <input type="file" name="image" class="form-control">
                                                        </div>
                                                        <button type="submit" class="btn btn-primary w-100">Update Menu</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-end mt-3">
                            {{ $menus->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
     <!-- Modal for Editing Menu -->
    {{-- <div class="modal fade" id="editMenuModal{{ $menu->id }}" tabindex="-1" aria-labelledby="editMenuModalLabel{{ $menu->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #abc32f;">
                    <h5 class="modal-title text-white" id="editMenuModalLabel{{ $menu->id }}">Edit Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('menu.update', $menu->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="menuName" class="form-label">Menu Name</label>
                            <input type="text" class="form-control" name="name" value="{{ $menu->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control" name="category_id" required>
                                 <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $menu->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                         </div>
                         <div class="mb-3">
                            <label for="menuPrice" class="form-label">Menu Price</label>
                            <div class="input-group">
                                 <span class="input-group-text">Rp.</span>
                                <input type="text" name="price" class="form-control" value="{{ $menu->price }}" required>
                            <span class="input-group-text">,00</span>
                            </div>
                        </div>
                        <div class="mb-3">
                             <label for="menuDescription" class="form-label">Description</label>
                             <textarea class="form-control" name="description" rows="3" required>{{ $menu->description }}</textarea>
                         </div>
                        <div class="mb-3">
                             <label for="menuImage" class="form-label">Upload Image</label>
                             <input type="file" name="image" class="form-control">
                         </div>
                         <button type="submit" class="btn btn-primary w-100">Update Menu</button>
                     </form>
                 </div>
               </div>
         </div>
    </div> --}}
    <!-- Modal for Adding New Menu -->
    <div class="modal fade" id="addMenuModal" tabindex="-1" aria-labelledby="addMenuModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #abc32f;">
                    <h5 class="modal-title text-white" id="addMenuModalLabel">Add New Menu</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form enctype="multipart/form-data" action="{{ route('menu.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="menuName" class="form-label">Menu Name</label>
                            <input type="text" class="form-control" id="menuName" name="name" placeholder="Enter menu name" required>
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control" id="category" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="menuPrice" class="form-label">Menu Price</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp.</span>
                                <input type="text" name="price" id="menuPrice" class="form-control" aria-label="Amount (to the nearest Rupiah)" placeholder="Enter price" required>
                                <span class="input-group-text">,00</span>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="menuDescription" class="form-label">Description</label>
                            <textarea class="form-control" id="menuDescription" name="description" rows="3" placeholder="Enter menu description" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="menuImage" class="form-label">Upload Image</label>
                            <input type="file" name="image" class="form-control" id="menuImage">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Menu</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
