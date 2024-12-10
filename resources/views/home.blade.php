@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-lg border-0 rounded-4">
                <!-- Header dengan warna utama -->
                <div class="card-header text-white text-center" style="background-color: #abc32f;">
                    <h4 class="mb-0">{{ __('Dashboard') }}</h4>
                </div>
                <div class="card-body">
                    <!-- Logout Button with Font Awesome Icon -->
                    <div class="d-flex justify-content-end mb-4">
                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-sign-out-alt"></i> <!-- Font Awesome icon -->
                                {{ __('Logout') }}
                            </button>
                        </form>
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Row untuk menu dashboard -->
                    <div class="row text-center d-flex justify-content-center align-items-center my-4">
                        <!-- Card untuk Management -->
                        <div class="col-md-4 col-sm-6 mb-4">
                            <a href="/management" class="text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 hover-card">
                                    <div class="card-body d-flex flex-column align-items-center py-4">
                                        <img width="60px" src="{{asset('assets/icon/computer.svg')}}" alt="Management Icon" class="mb-3 icon-effect">
                                        <h5 class="card-title text-center" style="color: #abc32f;">Management</h5>
                                    </div>
                                    <!-- Footer dengan gaya yang mirip dengan header -->
                                    <div class="card-footer text-white text-center" style="background-color: #abc32f;">
                                        <p class="mb-0">Access and manage all resources</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Card untuk Cashier -->
                        <div class="col-md-4 col-sm-6 mb-4">
                            <a href="/cashier" class="text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 hover-card">
                                    <div class="card-body d-flex flex-column align-items-center py-4">
                                        <img width="60px" src="{{asset('assets/icon/calculator.svg')}}" alt="Cashier Icon" class="mb-3 icon-effect">
                                        <h5 class="card-title text-center" style="color: #abc32f;">Cashier</h5>
                                    </div>
                                    <!-- Footer dengan gaya yang mirip dengan header -->
                                    <div class="card-footer text-white text-center" style="background-color: #abc32f;">
                                        <p class="mb-0">Fast and accurate transactions</p>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Card untuk Reports -->
                        <div class="col-md-4 col-sm-6 mb-4">
                            <a href="/reports" class="text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 hover-card">
                                    <div class="card-body d-flex flex-column align-items-center py-4">
                                        <img width="60px" src="{{asset('assets/icon/receipt.svg')}}" alt="Reports Icon" class="mb-3 icon-effect">
                                        <h5 class="card-title text-center" style="color: #abc32f;">Reports</h5>
                                    </div>
                                    <!-- Footer dengan gaya yang mirip dengan header -->
                                    <div class="card-footer text-white text-center" style="background-color: #abc32f;">
                                        <p class="mb-0">Comprehensive data insights</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
