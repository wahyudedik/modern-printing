<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $record }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f8fafc;
            padding: 2rem;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .company-details h2 {
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-info h1 {
            color: #2563eb;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .customer-details {
            margin-bottom: 2rem;
        }

        .customer-details h3 {
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        th {
            background: #f3f4f6;
            padding: 0.75rem;
            text-align: left;
            color: #1f2937;
        }

        td {
            padding: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .totals {
            float: right;
            width: 300px;
        }

        .totals table {
            margin-bottom: 1rem;
        }

        .totals table td {
            border: none;
        }

        .totals table td:last-child {
            text-align: right;
        }

        .grand-total {
            background: #2563eb;
            color: white;
            font-weight: bold;
        }

        .footer {
            clear: both;
            margin-top: 4rem;
            padding-top: 1rem;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .invoice-container {
                box-shadow: none;
            }
        }
    </style>
</head>

<body>
    {{-- // Add this at top of print.blade.php to check data
@php
    $transaksi = \App\Models\Transaksi::with(['pelanggan', 'produk'])->find($record);
    dd([
        'transaksi' => $transaksi->toArray(),
        'pelanggan' => $transaksi->pelanggan->toArray(),
        'produk' => $transaksi->produk->toArray(),
    ]);
@endphp --}}

    <div class="invoice-container">
        <div class="invoice-header">
            <div class="company-details">
                <h2>Modern Printing</h2>
                <p>Jl. Contoh No. 123</p>
                <p>Jakarta, Indonesia</p>
                <p>Phone: (021) 123-4567</p>
            </div>
            <div class="invoice-info">
                <h1>INVOICE</h1>
                <p>Invoice #: {{ $record }}</p>
                <p>Date: {{ now()->format('d/m/Y') }}</p>
            </div>
        </div>

        @php
            $transaksi = \App\Models\Transaksi::with(['pelanggan', 'produk'])->find($record);
            $pelanggan = $transaksi->pelanggan->first();
        @endphp

        <div class="customer-details">
            <h3>Bill To:</h3>
            <p>{{ $pelanggan->nama ?? 'N/A' }}</p>
            <p>{{ $pelanggan->alamat ?? 'N/A' }}</p>
            <p>{{ $pelanggan->no_telp ?? 'N/A' }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->produk as $produk)
                    <tr>
                        <td>{{ $produk->nama_produk }}</td>
                        {{-- <td>{{ $produk->pivot->qty }}</td>
                        <td>Rp {{ number_format($produk->harga, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($produk->pivot->qty * $produk->harga, 0, ',', '.') }}</td> --}}
                        <td>{{ $produk->pivot->quantity }}</td>
                        <td>Rp {{ number_format($produk->total_harga, 0, ',', '.') }}</td>
                        <td>Rp
                            {{ number_format($produk->total_harga * $produk->pivot->quantity, 0, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                </tr>
                <tr class="grand-total">
                    <td>Total:</td>
                    <td>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        <div class="footer">
            <p>Thank you for your business!</p>
            <p>Payment Method: {{ $transaksi->metode_pembayaran }}</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
