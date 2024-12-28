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
use App\Models\SpesifikasiProduk;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $vendor = \App\Models\Vendor::where('slug', request()->route('tenant'))->firstOrFail();

        DB::beginTransaction();

        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'payment_method' => 'required|in:cash,transfer,qris'
        ]);

        try {
            $cart = session('cart');
            if (empty($cart)) {
                throw new \Exception('Cart is empty');
            }

            // Calculate total with verification
            $totalHarga = collect($cart)->sum(function ($item) {
                $baseTotal = $item['base_price'] * $item['quantity'];
                $specTotal = collect($item['specifications'])->sum(function ($spec) use ($item) {
                    return $spec['harga_per_satuan'] * $item['quantity'];
                });
                return $baseTotal + $specTotal;
            });

            $transaksi = Transaksi::create([
                'vendor_id' => $vendor->id, // Use vendor ID directly
                'kode' => 'TRX-' . date('YmdHis'),
                'user_id' => \Illuminate\Support\Facades\Auth::id(),
                'pelanggan_id' => $request->pelanggan_id,
                'total_harga' => $totalHarga,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'estimasi_selesai' => $this->calculateEstimatedCompletion($cart),
                'tanggal_dibuat' => now(),
                'progress_percentage' => 0,
                'current_stage' => 'pending'
            ]);

            foreach ($cart as $item) {
                $firstSpec = collect($item['specifications'])->first();

                TransaksiItem::create([
                    'vendor_id' => $vendor->id,
                    'transaksi_id' => $transaksi->id,
                    'produk_id' => $item['product_id'],
                    'bahan_id' => $firstSpec['bahan_id'],
                    'kuantitas' => $item['quantity'],
                    'harga_satuan' => $item['base_price'],
                    'spesifikasi' => $item['specifications']
                ]);
            }

            // Update customer's last transaction timestamp
            $pelanggan = Pelanggan::find($request->pelanggan_id);
            $pelanggan->update([
                'transaksi_terakhir' => now()
            ]);

            DB::commit();
            session()->forget('cart');

            return response()->json([
                'success' => true,
                'invoiceUrl' => route('pos.invoice.download', [
                    'tenant' => request()->route('tenant'),
                    'transaksi' => $transaksi->id
                ]),
                'redirectUrl' => route('pos.index', [
                    'tenant' => request()->route('tenant')
                ])
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    private function calculateEstimatedCompletion($cartItems)
    {
        $equipmentSchedule = [];
        $maxCompletionTime = now(); // Start with current time as Carbon instance

        foreach ($cartItems as $item) {
            $estimasiProduk = EstimasiProduk::where('produk_id', $item['product_id'])->first();
            if (!$estimasiProduk) continue;

            $alat = $estimasiProduk->alat;
            $productionTime = $estimasiProduk->calculateTotalProductionTime($item['quantity']);

            if (!isset($equipmentSchedule[$alat->id])) {
                $equipmentSchedule[$alat->id] = $alat->getNextAvailableSlot();
            }

            $startTime = $equipmentSchedule[$alat->id];
            $equipmentSchedule[$alat->id] = Carbon::parse($startTime)->addMinutes($productionTime);

            // Update max completion time
            if ($equipmentSchedule[$alat->id]->gt($maxCompletionTime)) {
                $maxCompletionTime = $equipmentSchedule[$alat->id];
            }
        }

        return $maxCompletionTime;
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
