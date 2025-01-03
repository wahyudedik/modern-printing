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
            'spesifikasiProduk.spesifikasi',
            'spesifikasiProduk.bahans.wholesalePrice', // Added wholesalePrice
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
        })->with([
            'vendor',
            'kategori',
            'spesifikasiProduk.bahans.wholesalePrice', // Added wholesalePrice
            'estimasiProduk'
        ])->get();

        $categories = Produk::with('kategori')->get()->pluck('kategori')->unique();
        return view('pos.pos-home', compact('products', 'categories'));
    }

    // menampilkan halaman pencarian
    public function search(Request $request, $tenant)
    {
        $search = $request->get('search');

        $products = Produk::where('nama_produk', 'like', "%{$search}%")
            ->orWhere('deskripsi', 'like', "%{$search}%")
            ->with([
                'vendor',
                'kategori',
                'spesifikasiProduk.bahans.wholesalePrice', // Added wholesalePrice
                'estimasiProduk'
            ])->get();

        $categories = Produk::with('kategori')->get()->pluck('kategori')->unique();
        return view('pos.pos-home', compact('products', 'categories'));
    }

    // fungsi untuk menambahkan produk ke keranjang
    public function addToCart(Request $request)
    {
        $product = Produk::findOrFail($request->product_id);
        $quantity = $request->quantity;
        $specifications = $request->specifications;
        $specDetails = [];
        $totalSpecPrice = 0;
        $wholesalePrice = new WholesalePrice();

        foreach ($specifications as $specId => $value) {
            $spesifikasiProduk = SpesifikasiProduk::with(['spesifikasi', 'bahans'])->find($specId);

            if ($spesifikasiProduk->spesifikasi->tipe_input === 'select') {
                $bahan = Bahan::with('wholesalePrice')->find($value);
                if ($bahan) {
                    $finalPrice = $wholesalePrice->calculateFinalPrice($bahan->hpp, $quantity, $bahan->id);
                    $specPrice = $finalPrice * $quantity;

                    $specDetails[$specId] = [
                        'value' => $value,
                        'bahan_id' => $bahan->id,
                        'input_type' => 'select',
                        'price' => $specPrice,
                        'nama_spesifikasi' => $spesifikasiProduk->spesifikasi->nama_spesifikasi
                    ];
                }
            } else {
                $inputValue = (int)$value;
                $bahan = $spesifikasiProduk->bahans->first();
                if ($bahan) {
                    $pricePerUnit = $wholesalePrice->calculateFinalPrice($bahan->hpp, $inputValue, $bahan->id);
                    $specPrice = $pricePerUnit * $inputValue * $quantity;

                    $specDetails[$specId] = [
                        'value' => $inputValue,
                        'bahan_id' => $bahan->id,
                        'input_type' => 'number',
                        'price' => $specPrice,
                        'nama_spesifikasi' => $spesifikasiProduk->spesifikasi->nama_spesifikasi
                    ];
                }
            }
            $totalSpecPrice += $specPrice;
        }

        $cartItem = [
            'product_id' => $product->id,
            'product_name' => $product->nama_produk,
            'quantity' => $quantity,
            'specifications' => $specDetails,
            'total_price' => $totalSpecPrice,
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
            $totalPrice = 0;
            $quantity = $item['quantity'];

            foreach ($item['specifications'] as $specId => $spec) {
                $spesifikasiProduk = SpesifikasiProduk::with(['spesifikasi', 'bahans.wholesalePrice'])->find($specId);
                $bahan = Bahan::find($spec['bahan_id']);
                $wholesalePrice = new WholesalePrice();

                if (
                    $spec['input_type'] === 'select'
                ) {
                    $finalPrice = $wholesalePrice->calculateFinalPrice($bahan->hpp, $quantity, $bahan->id);
                    $specPrice = $finalPrice * $quantity;
                } else {
                    $inputValue = (int)$spec['value'];
                    $pricePerUnit = $wholesalePrice->calculateFinalPrice($bahan->hpp, $inputValue, $bahan->id);
                    $specPrice = $pricePerUnit * $inputValue * $quantity;
                }

                $totalPrice += $specPrice;
                $item['specifications'][$specId]['price'] = $specPrice;
            }

            $item['total_price'] = $totalPrice;
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

    public function checkPrice(Request $request)
    {
        $product = Produk::findOrFail($request->product_id);
        $quantity = $request->quantity ?? 1;
        $specifications = $request->specifications ?? [];
        $total = 0;
        $specificationDetails = [];

        foreach ($specifications as $specId => $value) {
            $spesifikasiProduk = SpesifikasiProduk::with(['spesifikasi', 'bahans.wholesalePrice'])->find($specId);
            $wholesalePrice = new WholesalePrice();

            if ($spesifikasiProduk->spesifikasi->tipe_input === 'select') {
                $bahan = Bahan::with('wholesalePrice')->find($value);
                if ($bahan) {
                    $finalPrice = $wholesalePrice->calculateFinalPrice($bahan->hpp, $quantity, $bahan->id);
                    $total += $finalPrice * $quantity;

                    $specificationDetails[] = [
                        'name' => $spesifikasiProduk->spesifikasi->nama_spesifikasi,
                        'value' => $bahan->nama_bahan,
                        'price' => $finalPrice
                    ];
                }
            } elseif ($spesifikasiProduk->spesifikasi->tipe_input === 'number') {
                $inputValue = (int)$value;
                $bahan = $spesifikasiProduk->bahans->first();
                if ($bahan) {
                    $pricePerUnit = $wholesalePrice->calculateFinalPrice($bahan->hpp, $inputValue, $bahan->id);
                    $materialCost = $pricePerUnit * $inputValue; // Multiply by input value
                    $total += $materialCost * $quantity;

                    $specificationDetails[] = [
                        'name' => $spesifikasiProduk->spesifikasi->nama_spesifikasi,
                        'value' => $inputValue . ' ' . $spesifikasiProduk->spesifikasi->satuan,
                        'price' => $materialCost
                    ];
                }
            }
        }

        return response()->json([
            'quantity' => $quantity,
            'specifications' => $specificationDetails,
            'totalPrice' => number_format($total, 0, ',', '.')
        ]);
    }
}
