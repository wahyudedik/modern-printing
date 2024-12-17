<?php

namespace App\Livewire;

use App\Models\Bahan;
use App\Models\Produk;
use Livewire\Component;
use App\Models\SpesifikasiProduk;

class Pos extends Component
{
    public $search = '';
    public $kategoriFilter = '';

    public function render()
    {
        $products = Produk::query()
            ->when($this->search, function ($query) {
                $query->where('nama_produk', 'like', '%' . $this->search . '%');
            })
            ->when($this->kategoriFilter, function ($query) {
                $query->where('kategori', $this->kategoriFilter);
            })
            ->with(['spesifikasiProduk', 'vendor'])
            ->get();

        return view('livewire.pos', [
            'products' => $products
        ]);
    }
}
