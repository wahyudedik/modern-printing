<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice {{ $transaksi->kode }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            background-color: white;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            padding: 30px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid black;
        }

        .header h1 {
            color: black;
            margin-bottom: 10px;
        }

        .invoice-info {
            margin-bottom: 30px;
            padding: 15px;
            border: 1px solid black;
        }

        .invoice-info p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            padding: 12px 15px;
            border: 1px solid black;
        }

        th {
            background-color: white;
            color: black;
            font-weight: bold;
        }

        .total {
            text-align: right;
            font-weight: bold;
            font-size: 18px;
            padding: 15px;
            border: 1px solid black;
            margin-bottom: 30px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid black;
            color: black;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>{{ $transaksi->vendor->name }}</h1>
            <p>{{ $transaksi->vendor->address }}</p>
        </div>

        <div class="invoice-info">
            <p><strong>Invoice #:</strong> {{ $transaksi->kode }}</p>
            <p><strong>Date:</strong> {{ $transaksi->tanggal_dibuat->format('d/m/Y') }}</p>
            <p><strong>Customer:</strong> {{ $transaksi->pelanggan->nama }}</p>
            <p><strong>Payment Method:</strong> {{ ucfirst($transaksi->payment_method) }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Specifications</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->transaksiItem as $item)
                    <tr>
                        <td>{{ $item->produk->nama_produk }}</td>
                        <td>
                            @foreach ($item->transaksiItemSpecifications as $spec)
                                {{ $spec->spesifikasiProduk->spesifikasi->nama_spesifikasi }}:
                                @if ($spec->input_type === 'select')
                                    {{ $spec->bahan->nama_bahan }}
                                @else
                                    {{ $spec->value }} {{ $spec->spesifikasiProduk->spesifikasi->satuan }}
                                @endif
                                (Rp {{ number_format($spec->price, 0, ',', '.') }})
                                <br>
                            @endforeach
                        </td>
                        <td>{{ $item->kuantitas }}</td>
                        <td>Rp {{ number_format($item->harga_satuan * $item->kuantitas, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total">
            <p>Total: Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Estimated Completion: {{ \Carbon\Carbon::parse($transaksi->estimasi_selesai)->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>

</html>
