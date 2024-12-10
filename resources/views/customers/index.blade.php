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
                        <h5 class="mb-0"><i class="fa fa-users me-2"></i>Customers</h5>
                    </div>

                    <div class="card-body">
                        <!-- Display Success or Error Messages -->
                        @if(session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <!-- Customer Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered text-center">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Loyalty Points</th>
                                        <th>Unique Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $customer)
                                    <tr>
                                        <td>{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}</td>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->loyalty_points }}</td>
                                        <td>{{ $customer->unique_code }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-end mt-3">
                            {{ $customers->onEachSide(1)->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
