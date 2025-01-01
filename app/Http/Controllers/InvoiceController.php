<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Filament\Facades\Filament;

class InvoiceController extends Controller
{
    public function show($tenant, Transaksi $transaksi)
    {
        $vendor = \App\Models\Vendor::where('slug', $tenant)->firstOrFail();

        $transaksi = Transaksi::with([
            'transaksiItem.produk',
            'transaksiItem.transaksiItemSpecifications.spesifikasiProduk.spesifikasi',
            'transaksiItem.transaksiItemSpecifications.bahan',
            'pelanggan',
            'vendor'
        ])->where('vendor_id', $vendor->id)
            ->findOrFail($transaksi->id);

        return view('pos.show', compact('transaksi')); // Web view version
    }

    public function download($tenant, Transaksi $transaksi)
    {
        $vendor = \App\Models\Vendor::where('slug', $tenant)->firstOrFail();

        $transaksi = Transaksi::with([
            'transaksiItem.produk',
            'transaksiItem.transaksiItemSpecifications.spesifikasiProduk.spesifikasi',
            'transaksiItem.transaksiItemSpecifications.bahan',
            'pelanggan',
            'vendor'
        ])->where('vendor_id', $vendor->id)
            ->findOrFail($transaksi->id);

        $pdf = Pdf::loadView('pos.print-invoice', compact('transaksi'));
        return $pdf->download("invoice-{$transaksi->kode}.pdf");
    }
}
