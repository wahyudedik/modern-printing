<div class="flex flex-col h-screen">
    <!-- Search and Filter Section -->
    <div class="p-4 bg-white shadow">
        <div class="flex gap-4">
            <input wire:model.live="search" type="search" placeholder="Search products..."
                class="w-full rounded-lg border-gray-300">
            <select wire:model.live="kategoriFilter" class="rounded-lg border-gray-300">
                <option value="">All Categories</option>
                <option value="Banner">Banner</option>
                <option value="Sticker">Sticker</option>
                <!-- Add other categories -->
            </select>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="flex-1 p-6 overflow-y-auto">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow">
                    @if ($product->gambar)
                        <img src="{{ Storage::url($product->gambar[0]) }}" alt="{{ $product->nama_produk }}"
                            class="w-full h-32 object-cover rounded-t-lg">
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-lg">{{ $product->nama_produk }}</h3>
                        <p class="text-sm text-gray-600">{{ $product->vendor->name }}</p>
                        <span class="inline-block px-2 py-1 mt-2 text-sm bg-gray-100 rounded">
                            {{ $product->kategori }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
