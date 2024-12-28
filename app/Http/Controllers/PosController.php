<?php

namespace App\Http\Controllers;

use App\Models\Bahan;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use App\Models\EstimasiProduk;
use App\Models\WholesalePrice;
use Filament\Facades\Filament;
use App\Models\SpesifikasiProduk;

class PosController extends Controller
{
    // menampilkan halaman pos
    public function index()
    {
        $products = Produk::with([
            'vendor',
            'kategori',
            'spesifikasiProduk.spesifikasi',  // Make sure this relationship is loaded
            'spesifikasiProduk.bahans', // Add this relationship
            'wholesalePrice',
            'estimasiProduk'
        ])->get();

        $categories = $products->pluck('kategori')->unique();
        return view('pos.pos-home', compact('products', 'categories'));
    }

    // menampilkan halaman kategori
    public function category($tenant, $slug)
    {
        $products = Produk::whereHas('kategori', function ($query) use ($slug) {
            $query->where('slug', $slug);
        })->with('vendor', 'kategori', 'spesifikasiProduk', 'wholesalePrice', 'estimasiProduk')->get();

        $categories = Produk::with('kategori')->get()->pluck('kategori')->unique();

        return view('pos.pos-home', compact('products', 'categories'));
    }

    // menampilkan halaman pencarian
    public function search(Request $request, $tenant)
    {
        $search = $request->get('search');

        $products = Produk::where('nama_produk', 'like', "%{$search}%")
            ->orWhere('deskripsi', 'like', "%{$search}%")
            ->with('vendor', 'kategori', 'spesifikasiProduk', 'wholesalePrice', 'estimasiProduk')
            ->get();

        $categories = Produk::with('kategori')->get()->pluck('kategori')->unique();

        return view('pos.pos-home', compact('products', 'categories'));
    }

    // fungsi untuk menambahkan produk ke keranjang
    public function addToCart(Request $request)
    {
        $product = Produk::findOrFail($request->product_id);
        $quantity = $request->quantity;
        $specifications = $request->specifications;
        $basePrice = $product->harga_dasar * $quantity; // Multiply base price by quantity
        $specDetails = [];
        $totalSpecPrice = 0;

        foreach ($specifications as $specId => $value) {
            $spesifikasiProduk = SpesifikasiProduk::with(['spesifikasi', 'bahans'])->find($specId);
            $bahan = Bahan::find($value);

            if ($spesifikasiProduk && $bahan) {
                $specPrice = $bahan->harga_per_satuan * $quantity; // Multiply spec price by quantity
                $totalSpecPrice += $specPrice;

                $specDetails[$specId] = [
                    'value' => $value,
                    'bahan_id' => $bahan->id,
                    'harga_per_satuan' => $bahan->harga_per_satuan,
                    'price' => $specPrice
                ];
            }
        }

        $cartItem = [
            'product_id' => $product->id,
            'product_name' => $product->nama_produk,
            'base_price' => $product->harga_dasar,
            'quantity' => $quantity,
            'specifications' => $specDetails,
            'total_price' => $basePrice + $totalSpecPrice,
            'estimated_time' => EstimasiProduk::where('produk_id', $product->id)
                ->first()?->calculateTotalProductionTime($quantity) ?? 0
        ];

        $cart = session()->get(
            'cart',
            []
        );
        $cart[] = $cartItem;
        session()->put('cart', $cart);

        return redirect()->route('pos.cart', ['tenant' => request()->route('tenant')])
            ->with('success', 'Product added to cart successfully');
    }


    // fungsi untuk menampilkan keranjang
    public function cart()
    {
        $cartItems = session('cart', []);
        $products = Produk::all();

        foreach ($cartItems as &$item) {
            $itemTotal = $item['base_price'];
            $quantity = $item['quantity'] ?? 1;

            foreach ($item['specifications'] as $specId => $spec) {
                $spesifikasiProduk = SpesifikasiProduk::with(['spesifikasi', 'bahans'])->find($specId);
                $bahan = Bahan::find($spec['bahan_id']);

                $hargaPerSatuan = $bahan ? $bahan->harga_per_satuan : 0;
                $specPrice = $spesifikasiProduk->calculatePrice($spec['value'], $spec['bahan_id'], $item['quantity']);

                $itemTotal += $specPrice;
            }

            $item['total'] = $itemTotal * $quantity;
        }

        return view('pos.cart', compact('cartItems', 'products'));
    }

    // fungsi untuk menghapus item dari keranjang
    public function removeItem($tenant, $index)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$index])) {
            unset($cart[$index]);
            $cart = array_values($cart); // Reindex array
            session()->put('cart', $cart);
        }

        return redirect()->route('pos.cart', ['tenant' => $tenant])
            ->with('success', 'Item removed successfully');
    }

    // fungsi untuk menghapus semua item dari keranjang
    public function clearCart($tenant)
    {
        session()->forget('cart');

        return redirect()->route('pos.cart', ['tenant' => $tenant])
            ->with('success', 'Cart cleared successfully');
    }
}
