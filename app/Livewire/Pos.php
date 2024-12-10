<?php

namespace App\Livewire;

use App\Models\Produk;
use Livewire\Component;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Auth;

class Pos extends Component
{
    public $search = '';
    public $customerSearch = '';
    public $cart = [];
    public $payment_method = '';
    public $showInvoice = false;
    public $transaction;
    public $subtotal = 0;
    public $customer_name;
    public $customer_email;
    public $customer_phone;
    public $customer_address;

    protected $rules = [
        'customer_name' => 'required',
        'customer_email' => 'required|email',
        'customer_phone' => 'required',
        'customer_address' => 'required',
        'payment_method' => 'required|in:cash,transfer,qris',
    ];

    // Add these new properties
    public $selected_customer_id = null;
    public $show_customer_form = false;

    // Add method to handle customer selection
    public function selectExistingCustomer($customerId)
    {
        $customer = Pelanggan::find($customerId);
        if ($customer) {
            $this->customer_name = $customer->nama;
            $this->customer_email = $customer->email;
            $this->customer_phone = $customer->no_telp;
            $this->customer_address = $customer->alamat;
            $this->selected_customer_id = $customer->id;
            $this->show_customer_form = false;
        }
    }

    public function closeAndReset()
    {
        // Close the invoice modal
        $this->showInvoice = false;

        // Reset the component state
        $this->reset([
            'cart',
            'customer_name',
            'customer_email',
            'customer_phone',
            'customer_address',
            'selected_customer_id',
            'payment_method',
            'transaction',
            'subtotal'
        ]);
    }


    public function render()
    {
        return view('livewire.pos', [
            'products' => Produk::where('vendor_id', Filament::getTenant()->id)
                ->where('nama_produk', 'like', "%{$this->search}%")
                ->get(),
            'customers' => Pelanggan::where('vendor_id', Filament::getTenant()->id)
                ->where(function ($query) {
                    $query->where('nama', 'like', "%{$this->customerSearch}%")
                        ->orWhere('email', 'like', "%{$this->customerSearch}%")
                        ->orWhere('no_telp', 'like', "%{$this->customerSearch}%");
                })
                ->get()
        ]);
    }

    public function selectProduct($productId)
    {
        $product = Produk::find($productId);

        // Check if product already exists in cart
        if (isset($this->cart[$productId])) {
            $this->incrementQty($productId);
            return;
        }

        // Add new product to cart
        $this->cart[$productId] = [
            'id' => $product->id,
            'name' => $product->nama_produk,
            'price' => $product->harga,
            'total_price' => $product->total_harga, // Add total_harga
            'qty' => 1,
            'minimal_qty' => $product->minimal_qty
        ];

        $this->calculateSubtotal();
    }

    public function incrementQty($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']++;
            $this->calculateSubtotal();
        }
    }

    public function decrementQty($productId)
    {
        if (isset($this->cart[$productId])) {
            $this->cart[$productId]['qty']--;

            if ($this->cart[$productId]['qty'] <= 0) {
                unset($this->cart[$productId]);
            }

            $this->calculateSubtotal();
        }
    }

    public function calculateSubtotal()
    {
        $this->subtotal = collect($this->cart)->sum(function ($item) {
            return $item['total_price'] * $item['qty'];
        });
    }

    public function processTransaction()
    {
        $this->validate();

        // Create customer first
        if ($this->selected_customer_id) {
            $customer = Pelanggan::find($this->selected_customer_id);
            $customer->update([
                'transaksi_terakhir' => now(),
            ]);
        } else {
            $customer = Pelanggan::create([
                'vendor_id' => Filament::getTenant()->id,
                'kode' => 'PLG-' . now()->format('YmdHis') . '-' . Auth::id(),
                'nama' => $this->customer_name,
                'email' => $this->customer_email,
                'no_telp' => $this->customer_phone,
                'alamat' => $this->customer_address,
                'transaksi_terakhir' => now(),
            ]);
        }

        // Create transaction
        $this->transaction = Transaksi::create([
            'vendor_id' => Filament::getTenant()->id,
            'kode' => 'TRX-' . now()->format('YmdHis') . '-' . Auth::id(),
            'total_qty' => collect($this->cart)->sum('qty'),
            'total_harga' => $this->subtotal,
            'metode_pembayaran' => $this->payment_method,
            'status' => 'pending'
        ]);

        // Attach customer to transaction
        $this->transaction->pelanggan()->attach($customer->id);

        // Attach products
        foreach ($this->cart as $item) {
            $this->transaction->produk()->attach($item['id'], [
                'quantity' => $item['qty']  // Add quantity here
            ]);
        }

        // Load relationships before showing invoice
        $this->transaction->load(['pelanggan', 'produk']);

        $this->showInvoice = true;
    }

    private function resetCustomerForm()
    {
        $this->customer_name = '';
        $this->customer_email = '';
        $this->customer_phone = '';
        $this->customer_address = '';
        $this->cart = [];
        $this->subtotal = 0;
        $this->payment_method = '';
    }


    public function printInvoice()
    {
        return redirect()->route('transaksi.print', ['record' => $this->transaction->id]);
    }

    public function closeInvoice()
    {
        $this->showInvoice = false;
        $this->transaction = null;
    }
}
