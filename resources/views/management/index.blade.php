@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <!-- Sidebar Menu -->
            @include('management.import.sidebar')
            <!-- Main Content Area -->
            <div class="col-md-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header text-white text-center" style="background-color: #abc32f;">
                        <h5 class="mb-0">Absensi</h5>
                    </div>
                    <div class="card-body">
                        halaman absensi disini
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
