@extends('pos.index')

@section('content')
    <!-- invoice.blade.php -->
    <div class="invoice-container">
        <!-- Header -->
        <div class="company-header">
            <img src="{{ asset('storage/' . $transaksi->vendor->logo) }}" alt="Logo">
            <div class="company-info">
                <h2>{{ $transaksi->vendor->name }}</h2>
                <p>{{ $transaksi->vendor->address }}</p>
                <p>Tel: {{ $transaksi->vendor->phone }}</p>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            <div class="invoice-number">
                <h3>Invoice #{{ $transaksi->kode }}</h3>
                <p>Date: {{ $transaksi->created_at->format('d/m/Y') }}</p>
            </div>

            <div class="customer-details">
                <h4>Customer Details:</h4>
                <p>{{ $transaksi->pelanggan->nama }}</p>
                <p>{{ $transaksi->pelanggan->alamat }}</p>
                <p>Tel: {{ $transaksi->pelanggan->no_telp }}</p>
            </div>
        </div>

        <!-- Order Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Specifications</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaksi->transaksiItem as $item)
                    <tr>
                        <td>{{ $item->produk->nama_produk }}</td>
                        <td>
                            @foreach ($item->spesifikasi as $spec)
                                <div class="spec-item">
                                    <strong>{{ $spec['nama_spesifikasi'] }}:</strong>
                                    {{ $spec['value'] }} {{ $spec['satuan'] }}
                                </div>
                            @endforeach
                        </td>
                        <td>{{ $item->kuantitas }}</td>
                        <td>Rp {{ number_format($item->harga_satuan) }}</td>
                        <td>Rp {{ number_format($item->harga_satuan * $item->kuantitas) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="invoice-summary">
            <div class="production-info">
                <h4>Production Details:</h4>
                <p>Estimated Start: {{ $transaksi->created_at->format('d/m/Y H:i') }}</p>
                <p>Estimated Completion: {{ $transaksi->estimasi_selesai->format('d/m/Y H:i') }}</p>
            </div>

            <div class="total-amount">
                <h3>Total Amount: Rp {{ number_format($transaksi->total_harga) }}</h3>
                <p>Payment Method: {{ $transaksi->payment_method }}</p>
                <p>Status: {{ ucfirst($transaksi->status) }}</p>
            </div>
        </div>

        <!-- Order Progress -->
        <div class="order-progress">
            <h4>Order Progress</h4>
            <div class="progress-bar">
                <div class="progress" style="width: {{ $transaksi->progress_percentage }}%"></div>
            </div>
            <div class="current-stage">
                Current Stage: {{ $transaksi->getProgressStages()[$transaksi->current_stage] }}
            </div>
        </div>

        <!-- Footer -->
        <div class="invoice-footer">
            <p>Thank you for your business!</p>
            <p>For any inquiries, please contact us at {{ $transaksi->vendor->email }}</p>
        </div>
    </div>
@endsection
