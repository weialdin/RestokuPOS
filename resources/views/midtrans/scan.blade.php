@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Checkout</div>
                    <div class="card-body">
                        <button id="pay-button">Bayar Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function(){
        snap.pay('{{ $snapToken }}', {
            // Callback setelah pembayaran berhasil
            onSuccess: function(result){
                console.log('success:', result);
                window.location.href = '/cashier?status=success';
            },
            // Callback jika pembayaran pending
            onPending: function(result){
                console.log('pending:', result);
                window.location.href = '/cashier?status=pending';
            },
            // Callback jika pembayaran gagal
            onError: function(result){
                console.log('error:', result);
                alert('Pembayaran gagal! Silakan coba lagi.');
            },
            // Callback jika user membatalkan pembayaran
            onClose: function(){
                alert('Transaksi dibatalkan!');
            }
        });
    };
</script>
@endsection