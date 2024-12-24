<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\PDF;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function show(Transaksi $transaksi)
    {
        $transaksi->load([
            'pelanggan',
            'transaksiItem.produk',
            'transaksiItem.bahan',
            'vendor'
        ]);

        return view('pos.invoice', compact('transaksi'));
    }

    public function print(Transaksi $transaksi)
    {
        $transaksi->load([
            'pelanggan',
            'transaksiItem.produk',
            'transaksiItem.bahan',
            'vendor'
        ]);

        $pdf = app('dompdf.wrapper')->loadView('pos.print-invoice', compact('transaksi'));
        return $pdf->stream('Invoice-' . $transaksi->kode . '.pdf');
    }
}
