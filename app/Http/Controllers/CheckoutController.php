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
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function process(Request $request)
    {
        $vendor = \App\Models\Vendor::where('slug', request()->route('tenant'))->firstOrFail();

        $validatedData = $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'payment_method' => 'required|in:cash,transfer,qris',
            'catatan' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $cartItems = session('cart', []);
            $totalTime = collect($cartItems)->sum(function ($item) {
                $product = Produk::with('estimasiProduk.alat')->find($item['product_id']);
                return $product->getEstimatedProductionTime($item['quantity']);
            });

            $latestPending = Transaksi::where('status', 'pending')
                ->where('vendor_id', $vendor->id)
                ->latest('estimasi_selesai')
                ->first();

            $startTime = $latestPending ? Carbon::parse($latestPending->estimasi_selesai) : now();
            $estimatedCompletion = $startTime->addMinutes($totalTime);

            $transaksi = Transaksi::create([
                'vendor_id' => $vendor->id,
                'kode' => 'TRX-' . date('Ymd') . '-' . rand(1000, 9999),
                'user_id' => Auth::id(),
                'pelanggan_id' => $validatedData['pelanggan_id'],
                'total_harga' => collect($cartItems)->sum('total_price'),
                'status' => 'pending',
                'payment_method' => $validatedData['payment_method'],
                'estimasi_selesai' => $estimatedCompletion,
                'tanggal_dibuat' => now(),
                'progress_percentage' => 0,
                'catatan' => $validatedData['catatan']
            ]);

            foreach ($cartItems as $item) {
                $transaksiItem = $transaksi->transaksiItem()->create([
                    'vendor_id' => $vendor->id,
                    'produk_id' => $item['product_id'],
                    'kuantitas' => $item['quantity'],
                    'harga_satuan' => $item['total_price'] / $item['quantity']
                ]);

                foreach ($item['specifications'] as $specId => $spec) {
                    $transaksiItem->transaksiItemSpecifications()->create([
                        'vendor_id' => $vendor->id,
                        'spesifikasi_produk_id' => $specId,
                        'bahan_id' => $spec['bahan_id'],
                        'value' => $spec['value'],
                        'input_type' => $spec['input_type'],
                        'price' => $spec['price']
                    ]);

                    // Update stock
                    $bahan = Bahan::find($spec['bahan_id']);
                    if ($spec['input_type'] === 'number') {
                        $bahan->decrement('stok', $spec['value'] * $item['quantity']);
                        $bahan->checkStockLevel();
                    } else {
                        $bahan->decrement('stok', $item['quantity']);
                        $bahan->checkStockLevel();
                    }
                }
            }

            // Update customer's last transaction timestamp
            $pelanggan = Pelanggan::find($validatedData['pelanggan_id']);
            $pelanggan->update([
                'transaksi_terakhir' => now()
            ]);

            DB::commit();
            session()->forget('cart');
            return response()->json([
                'success' => true,
                'invoiceUrl' => route('invoice.show', [
                    'tenant' => request()->route('tenant'),
                    'transaksi' => $transaksi->id
                ]),
                'downloadUrl' => route('pos.invoice.download', [
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
                'message' => 'Transaction failed: ' . $e->getMessage()
            ], 500);
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
        $totalTime = collect($cartItems)->sum(function ($item) {
            $product = Produk::with('estimasiProduk.alat')->find($item['product_id']);
            return $product->getEstimatedProductionTime($item['quantity']);
        });

        $customers = Pelanggan::all();
        $products = Produk::all();

        return view('pos.checkout', compact(
            'cartItems',
            'totalAmount',
            'totalTime',
            'customers',
            'products'
        ));
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
