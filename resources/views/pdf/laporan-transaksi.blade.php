<!DOCTYPE html>
<html>

<head>
    <title>Transaction Report</title>
    <style>
        @page {
            size: landscape;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            margin: 30px;
            color: #1f2937;
            line-height: 1.5;
            background-color: #f9fafb;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 20px;
            background-color: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .header h2 {
            margin: 0;
            color: #111827;
            font-size: 24px;
            font-weight: 600;
        }

        .company-info {
            margin-top: 10px;
            color: #4b5563;
            font-size: 14px;
        }

        .report-meta {
            margin-bottom: 20px;
            font-size: 14px;
            background-color: white;
            padding: 12px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 20px;
            background-color: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #e5e7eb;
            font-size: 12px;
        }

        th {
            background-color: #f8fafc;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #000000;
        }

        tr:hover {
            background-color: #f9fafb;
        }

        .summary-section {
            margin-top: 30px;
            background-color: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .summary-section h3 {
            margin-top: 0;
            color: #111827;
            font-size: 18px;
            font-weight: 600;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-pending {
            background: #fef3c7;
            color: #92400e;
        }

        .status-success {
            background: #d1fae5;
            color: #065f46;
        }

        .status-failed {
            background: #fee2e2;
            color: #991b1b;
        }

        .product-list,
        .material-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .product-list li,
        .material-list li {
            padding: 3px 0;
            color: #4b5563;
        }

        .total-price {
            font-weight: 600;
            color: #059669;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 15px;
        }

        .summary-item {
            background-color: #f8fafc;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
        }

        .summary-item p {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
            color: #1f2937;
        }

        .summary-item span {
            font-size: 12px;
            color: #6b7280;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Transaction Report</h2>
        <div class="company-info">
            {{ auth()->user()->vendor->first()->name }}
        </div>
    </div>

    <div class="report-meta">
        <strong>Period:</strong> {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }} -
        {{ \Carbon\Carbon::parse($untilDate)->format('d M Y') }}
    </div>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Products</th>
                <th>Materials</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->kode }}</td>
                    <td>{{ $transaction->pelanggan->nama }}</td>
                    <td>
                        <ul class="product-list">
                            @foreach ($transaction->transaksiItem as $item)
                                <li>
                                    {{ $item->produk->nama_produk }}
                                    <ul>
                                        @foreach ($item->transaksiItemSpecifications as $spec)
                                            <li>
                                                {{ $spec->spesifikasiProduk->spesifikasi->nama_spesifikasi }}:
                                                {{ $spec->bahan->nama_bahan }}
                                                ({{ $spec->value }}
                                                {{ $spec->spesifikasiProduk->spesifikasi->satuan }})
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <ul class="product-list">
                            @foreach ($transaction->transaksiItem as $item)
                                <li>
                                    <strong>{{ $item->produk->nama_produk }}</strong>
                                    <ul>
                                        @foreach ($item->transaksiItemSpecifications as $spec)
                                            <li>
                                                {{ $spec->spesifikasiProduk->spesifikasi->nama_spesifikasi }}:
                                                @if ($spec->input_type === 'select')
                                                    {{ $spec->bahan->nama_bahan }} (x{{ $item->kuantitas }}
                                                    {{ $spec->spesifikasiProduk->spesifikasi->satuan }})
                                                @else
                                                    {{ $spec->bahan->nama_bahan }} ({{ $spec->value }}
                                                    {{ $spec->spesifikasiProduk->spesifikasi->satuan }})
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ $transaction->transaksiItem->sum('kuantitas') }}</td>
                    <td class="total-price">Rp {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <span class="status-badge status-{{ $transaction->status }}">
                            {{ strtoupper($transaction->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="summary-section">
        <h3>Summary</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <p>{{ $transactions->count() }}</p>
                <span>Total Transactions</span>
            </div>
            <div class="summary-item">
                <p>Rp {{ number_format($transactions->sum('total_harga'), 0, ',', '.') }}</p>
                <span>Total Revenue</span>
            </div>
            <div class="summary-item">
                <p>{{ $transactions->sum(function ($t) {
                    return $t->transaksiItem->sum('kuantitas');
                }) }}
                </p>
                <span>Total Items Sold</span>
            </div>
        </div>
    </div>
</body>

</html>
