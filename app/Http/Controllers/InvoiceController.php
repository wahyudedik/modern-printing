<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\PDF;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function download(Request $request, $tenant, Transaksi $transaksi)
    {
        // Ensure transaction exists and load relationships
        $transaksi = Transaksi::with(['transaksiItem.produk', 'pelanggan', 'vendor'])
            ->findOrFail($transaksi->id);

        if (!$transaksi->pelanggan) {
            abort(404, 'Customer not found for this transaction');
        }

        $pdf = app(PDF::class)->loadView('pos.print-invoice', compact('transaksi'));
        return $pdf->download("invoice-{$transaksi->kode}.pdf");
    }
}
