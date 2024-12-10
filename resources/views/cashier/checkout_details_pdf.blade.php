<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Checkout</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }
        h2, h3 {
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
            text-align: left;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        .total {
            font-weight: bold;
            text-align: right;
        }
        .header {
            margin-bottom: 20px;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            font-size: 0.9rem;
            text-align: center;
            color: #555;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Nama Restoran</h2>
        <p>Alamat: Jalan Contoh No.123, Kota Contoh</p>
        <p>Nomor Telepon: (021) 123-4567</p>
    </div>

    <h3>Rincian Pesanan</h3>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($cart as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td>Rp{{ number_format($item['price'], 0, ',', '.') }}</td>
                    <td>{{ $item['quantity'] }}</td>
                    <td>Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <table>
        <tr>
            <td style="text-align: right; font-weight: bold;">Subtotal:</td>
            <td style="text-align: right;">Rp{{ number_format($totalAmount, 0, ',', '.') }}</td>
        </tr>
        @if(isset($discount) && $discount > 0)
        <tr>
            <td style="text-align: right; font-weight: bold; color: green;">Diskon:</td>
            <td style="text-align: right; color: green;">- Rp{{ number_format($discount, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td style="text-align: right; font-weight: bold;">Subtotal Setelah Diskon:</td>
            <td style="text-align: right;">Rp{{ number_format($totalAfterDiscount, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight: bold;">Pajak (12%):</td>
            <td style="text-align: right;">Rp{{ number_format($totalAfterDiscount * 0.12, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight: bold;">Total Keseluruhan:</td>
            <td style="text-align: right;">Rp{{ number_format($totalAfterDiscount + ($totalAfterDiscount * 0.12), 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="footer">
        <p>Terima kasih atas pesanan Anda!</p>
        <p>Silakan simpan bukti ini sebagai referensi.</p>
    </div>
</body>
</html>
