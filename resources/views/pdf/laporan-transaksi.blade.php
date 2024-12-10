<!DOCTYPE html>
<html>

<head>
    <title>Laporan Transaksi</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 40px;
            background-color: #f8fafc;
            color: #1f2937;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e5e7eb;
        }

        .header h2 {
            color: #1f2937;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            color: #6b7280;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px 16px;
            text-align: left;
            font-size: 13px;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background-color: #f9fafb;
            font-weight: 600;
            color: #374151;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.05em;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover {
            background-color: #f9fafb;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        .success {
            background-color: #d1fae5;
            color: #065f46;
        }

        .danger {
            background-color: #fee2e2;
            color: #991b1b;
        }

        .product-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .product-item {
            padding: 2px 0;
            color: #4b5563;
        }

        .price {
            font-weight: 600;
            color: #1f2937;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Transaksi</h2>
        <p>Periode: {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($untilDate)->format('d M Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Pelanggan</th>
                <th>Produk</th>
                <th>Total Qty</th>
                <th>Total Harga</th>
                <th>Pembayaran</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>
                        @foreach ($transaction->pelanggan as $pelanggan)
                            {{ $pelanggan->nama }}
                        @endforeach
                    </td>
                    <td>
                        <ul class="product-list">
                            @foreach ($transaction->produk as $produk)
                                <li class="product-item">• {{ $produk->nama_produk }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $transaction->total_qty }}</td>
                    <td class="price">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <span
                            class="badge {{ $transaction->metode_pembayaran === 'transfer' ? 'warning' : ($transaction->metode_pembayaran === 'cash' ? 'success' : 'primary') }}">
                            {{ strtoupper($transaction->metode_pembayaran) }}
                        </span>
                    </td>
                    <td>
                        <span
                            class="badge {{ $transaction->status === 'pending' ? 'warning' : ($transaction->status === 'success' ? 'success' : 'danger') }}">
                            {{ strtoupper($transaction->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
