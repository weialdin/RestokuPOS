@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg border-0">
        <div class="card-header text-white" style="background-color: #abc32f;">
            <h5 class="mb-0"><i class="fa fa-history me-2"></i> Riwayat Transaksi</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($transactions->isEmpty())
                <div class="alert alert-info">Belum ada transaksi yang tercatat.</div>
            @else
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>Menu</th>
                            <th>Jumlah</th>
                            <th>Total Pembelian</th>
                            <th>Jenis Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->created_at }}</td>
                                <td>{{ $transaction->customer_name }}</td>
                                <td>{{ $transaction->menu_name }}</td>
                                <td>{{ $transaction->quantity }}</td>
                                <td>Rp{{ number_format($transaction->total_purchase, 0, ',', '.') }}</td>
                                <td>{{ ucfirst($transaction->payment_type) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
