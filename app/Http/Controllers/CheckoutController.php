<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\TransaksiItem;
use App\Models\EstimasiProduk;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        DB::beginTransaction();
        try {
            // Reserve stock for each item
            foreach (session('cart') as $item) {
                $bahan = Bahan::find($item['specifications']['bahan_id']);
                if ($bahan && $bahan->stok < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$bahan->nama_bahan}");
                }
            }

            $transaksi = Transaksi::create([
                'vendor_id' => Filament::getTenant()->id,
                'kode' => 'TRX-' . date('YmdHis'),
                'pelanggan_id' => $request->pelanggan_id,
                'total_harga' => collect(session('cart'))->sum('total_price'),
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'estimasi_selesai' => $this->calculateEstimatedCompletion(session('cart')),
                'tanggal_dibuat' => now()
            ]);

            foreach (session('cart') as $item) {
                TransaksiItem::create([
                    'vendor_id' => Filament::getTenant()->id,
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['product_id'],
                    'bahan_id' => $item['specifications']['bahan_id'],
                    'kuantitas' => $item['quantity'],
                    'harga_satuan' => $item['base_price'],
                    'spesifikasi' => $item['specifications']
                ]);
            }

            DB::commit();
            session()->forget('cart');

            return redirect()->route('pos.invoice', [
                'tenant' => request()->route('tenant'),
                'transaksi' => $transaksi->id
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    private function calculateEstimatedCompletion($cartItems)
    {
        $equipmentSchedule = [];

        foreach ($cartItems as $item) {
            $estimasiProduk = EstimasiProduk::where('produk_id', $item['product_id'])->first();
            if (!$estimasiProduk) continue;

            $alat = $estimasiProduk->alat;
            $productionTime = $estimasiProduk->calculateTotalProductionTime($item['quantity']);

            if (!isset($equipmentSchedule[$alat->id])) {
                $equipmentSchedule[$alat->id] = $alat->getNextAvailableSlot();
            }

            $startTime = $equipmentSchedule[$alat->id];
            $equipmentSchedule[$alat->id] = $startTime->addMinutes($productionTime);
        }

        return collect($equipmentSchedule)->max();
    }


    public function show()
    {
        $cartItems = session('cart', []);
        $totalAmount = collect($cartItems)->sum('total_price');
        $customers = Pelanggan::all();
        $products = Produk::all(); // Add this line

        return view('pos.checkout', compact('cartItems', 'totalAmount', 'customers', 'products')); // Add products to compact
    }

    public function createCustomer(Request $request)
    {
        $vendor = \App\Models\Vendor::where('slug', request()->route('tenant'))->firstOrFail();

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telp' => 'required|string',
            'email' => 'nullable|email'
        ]);

        $customer = Pelanggan::create([
            'vendor_id' => $vendor->id,
            'kode' => 'PLG-' . date('YmdHis'),
            'nama' => $validated['nama'],
            'alamat' => $validated['alamat'],
            'no_telp' => $validated['no_telp'],
            'email' => $validated['email']
        ]);

        return redirect()->back()->with('success', 'Customer created successfully');
    }
}
