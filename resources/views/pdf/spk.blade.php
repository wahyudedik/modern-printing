<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>SPK {{ $transaksi->kode }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.3;
            color: #2d3748;
            font-size: 11px;
        }

        .container {
            padding: 1rem;
        }

        .header {
            text-align: center;
            margin-bottom: 1rem;
            border-bottom: 2px solid #4a5568;
            padding-bottom: 0.5rem;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 14px;
        }

        .header p {
            margin: 0;
        }

        .company-logo {
            max-width: 150px;
            margin-bottom: 0.5rem;
        }

        .spk-info {
            margin-bottom: 1rem;
            padding: 0.5rem;
            background: #f7fafc;
            border-radius: 4px;
        }

        .section-title {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #2b6cb0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        th,
        td {
            padding: 0.3rem;
            border: 1px solid #e2e8f0;
            font-size: 10px;
        }

        th {
            background: #edf2f7;
            text-align: left;
        }

        .footer {
            margin-top: 1rem;
            text-align: center;
            font-size: 10px;
        }

        .signatures {
            width: 100%;
            margin-top: 1rem;
            display: table;
            table-layout: fixed;
        }

        .signature-box {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 0 0.5rem;
        }

        .signature-line {
            border-top: 1px solid #4a5568;
            margin-top: 2rem;
            padding-top: 0.3rem;
            min-height: 40px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>SURAT PERINTAH KERJA</h1>
            <h2>{{ $transaksi->vendor->name }}</h2>
            <p>{{ $transaksi->vendor->address }}</p>
        </div>

        <div class="spk-info">
            <table>
                <tr>
                    <td><strong>No. SPK</strong></td>
                    <td>: {{ $transaksi->kode }}</td>
                    <td><strong>Tanggal</strong></td>
                    <td>: {{ $transaksi->created_at->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Customer</strong></td>
                    <td>: {{ $transaksi->pelanggan->nama }}</td>
                    <td><strong>Estimasi Selesai</strong></td>
                    <td>: {{ \Carbon\Carbon::parse($transaksi->estimasi_selesai)->format('d/m/Y H:i') }}</td>
                </tr>
            </table>
        </div>

        <div class="section-title">DETAIL PEKERJAAN</div>
        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Spesifikasi</th>
                    <th>Quantity</th>
                    <th>Catatan Produksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->transaksiItem as $item)
                    <tr>
                        <td>{{ $item->produk->nama_produk }}</td>
                        <td>
                            @foreach ($item->transaksiItemSpecifications as $spec)
                                <div>
                                    <strong>{{ $spec->spesifikasiProduk->spesifikasi->nama_spesifikasi }}:</strong>
                                    @if ($spec->input_type === 'select')
                                        {{ $spec->bahan->nama_bahan }}
                                    @else
                                        {{ $spec->value }} {{ $spec->spesifikasiProduk->spesifikasi->satuan }}
                                    @endif
                                </div>
                            @endforeach
                        </td>
                        <td>{{ $item->kuantitas }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="section-title">TIMELINE PRODUKSI</div>
        <table>
            <tr>
                <th>Status</th>
                <th>Target Waktu</th>
                <th>Paraf</th>
            </tr>
            <tr>
                <td>Desain</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Produksi</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Quality Control</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td>Finishing</td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <div class="signatures">
            <div class="signature-box">
                <div class="signature-line">
                    Dibuat Oleh
                    <br>
                    {{ auth()->user()->name }}
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    Operator Produksi
                </div>
            </div>
            <div class="signature-box">
                <div class="signature-line">
                    Quality Control
                </div>
            </div>
        </div>

        <div class="footer">
            <p>{{ $transaksi->vendor->name }} - {{ $transaksi->vendor->address }}</p>
        </div>
    </div>
</body>

</html>
