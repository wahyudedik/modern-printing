@extends('pos.index')

@section('content')
    {{-- header --}}
    <div class="col-md-12 mt-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-white border-0 py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0 fw-bold text-primary">Point of Sale</h2>
                        <p class="text-muted mb-0 mt-2"><i class="fas fa-calendar-alt me-2"></i>{{ date('Y-m-d') }}</p>
                    </div>
                    <div class="text-end">
                        <h5 class="mb-2 text-dark"><i class="fas fa-user-circle me-2"></i>{{ Auth::user()->name }}</h5>
                        <p class="mb-0 text-muted"><i
                                class="fas fa-store-alt me-2"></i>{{ $products->first() ? $products->first()->vendor->name : 'No Vendor' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- navigation kategori --}}
    <div class="col-md-12 mt-3">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="row align-items-center g-3">

                    {{-- category --}}
                    <div class="col-12 col-lg-5">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('pos.index', ['tenant' => request()->route('tenant')]) }}"
                                class="btn {{ request()->routeIs('pos.index') ? 'btn-primary' : 'btn-light' }} rounded-pill px-4 py-2">
                                <i class="fas fa-th-large me-2"></i>All Products
                            </a>
                            @foreach ($categories as $category)
                                <a href="{{ route('pos.category', ['tenant' => request()->route('tenant'), 'slug' => $category->slug]) }}"
                                    class="btn {{ request()->is('*/pos/category/' . $category->slug) ? 'btn-primary' : 'btn-light' }} rounded-pill px-4 py-2">
                                    <i class="fas fa-tag me-2"></i>{{ $category->nama_kategori }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- search --}}
                    <div class="col-12 col-lg-5">
                        <form action="{{ route('pos.search', ['tenant' => request()->route('tenant')]) }}" method="GET"
                            class="d-flex gap-2">
                            <div class="input-group input-group-merge shadow-sm">
                                <span class="input-group-text border-0 bg-transparent">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" name="search" class="form-control form-control-lg border-0 ps-2"
                                    value="{{ request('search') }}" placeholder="Search products..." autocomplete="off"
                                    style="border-radius: 20px;">
                                @if (request('search'))
                                    <span class="input-group-text border-0 bg-transparent">
                                        <a href="{{ route('pos.index', ['tenant' => request()->route('tenant')]) }}"
                                            class="text-muted hover-danger" style="text-decoration: none">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- cart --}}
                    <div class="col-12 col-lg-2">
                        <a href="{{ route('pos.cart', ['tenant' => request()->route('tenant')]) }}"
                            class="btn btn-primary rounded-pill px-4 py-2 w-100">
                            <i class="fas fa-shopping-cart me-2"></i>Cart
                            <span class="badge bg-light text-primary ms-2">{{ count(session('cart', [])) }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- produk --}}
    <div class="col-md-12 mt-3">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="row g-4">
                    @foreach ($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="card h-100 border-0 shadow-hover rounded-4">
                                <!-- Smaller image with fixed size -->
                                <img src="{{ asset('storage/' . $product->gambar[0]) }}" class="card-img-top"
                                    style="height: 100px; object-fit: cover; cursor: pointer" data-bs-toggle="modal"
                                    data-bs-target="#productModal{{ $product->id }}">

                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-2">{{ $product->nama_produk }}</h5>
                                    <p class="text-muted mb-3">{!! Str::limit($product->deskripsi, 50) !!}</p>

                                    {{-- <div class="alert alert-info mb-3">
                                        <strong>Harga Dasar:</strong> Rp
                                        <span id="totalPrice{{ $product->id }}">{{ number_format($product->harga_dasar, 0, ',', '.') }}</span>
                                    </div> --}}

                                    <button class="btn btn-outline-primary w-100 mb-3" data-bs-toggle="modal"
                                        data-bs-target="#productModal{{ $product->id }}">
                                        <i class="fas fa-shopping-cart me-2"></i>Order Now
                                    </button>
                                </div>
                            </div>

                            <!-- Bootstrap Modal -->
                            <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $product->nama_produk }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form
                                            action="{{ route('pos.addToCart', ['tenant' => request()->route('tenant')]) }}"
                                            method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                                <div class="mb-3">
                                                    <label class="form-label">Quantity</label>
                                                    <input type="number" name="quantity" class="form-control"
                                                        value="1" min="1">
                                                </div>

                                                @foreach ($product->spesifikasiProduk as $spec)
                                                    <div class="mb-3">
                                                        <label class="form-label">
                                                            {{ $spec->spesifikasi->nama_spesifikasi }}
                                                            @if ($spec->wajib_diisi)
                                                                <span class="text-danger">*</span>
                                                            @endif
                                                        </label>

                                                        @if ($spec->spesifikasi->tipe_input === 'select')
                                                            <select name="specifications[{{ $spec->id }}]"
                                                                class="form-select"
                                                                {{ $spec->wajib_diisi ? 'required' : '' }}>
                                                                @foreach ($spec->bahans as $bahan)
                                                                    <option value="{{ $bahan->id }}">
                                                                        {{ $bahan->nama_bahan }} -
                                                                        @if ($bahan->wholesalePrice->count() > 0)
                                                                            @foreach ($bahan->wholesalePrice as $price)
                                                                                {{ $price->min_quantity }}-{{ $price->max_quantity }}
                                                                                pcs: Rp {{ number_format($price->harga) }}
                                                                            @endforeach
                                                                        @else
                                                                            Rp {{ number_format($bahan->hpp) }}
                                                                        @endif
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        @else
                                                            <div class="input-group">
                                                                <input type="{{ $spec->spesifikasi->tipe_input }}"
                                                                    name="specifications[{{ $spec->id }}]"
                                                                    class="form-control"
                                                                    {{ $spec->wajib_diisi ? 'required' : '' }}>
                                                                @if ($spec->spesifikasi->satuan)
                                                                    <span
                                                                        class="input-group-text">{{ $spec->spesifikasi->satuan }}</span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach

                                                {{-- rincian harga produk --}}
                                                <div class="mb-3">
                                                    <div id="priceDetails{{ $product->id }}"></div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Close</button>
                                                <button type="button" class="btn btn-info"
                                                    id="cekHarga{{ $product->id }}">
                                                    <i class="fas fa-calculator me-2"></i>Cek Harga
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- footer --}}
    <div class="col-md-12 mt-4 mb-4">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-footer bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-0 text-muted">© {{ date('Y') }} Modern Printing. All rights reserved.</p>
                    </div>
                    <div>
                        <p class="mb-0 text-muted"><i class="fas fa-code me-2"></i>Version 1.0</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('[id^="cekHarga"]').forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                const productId = this.id.replace('cekHarga', '');
                const formData = new FormData(form);
                const data = {
                    product_id: formData.get('product_id'),
                    quantity: formData.get('quantity'),
                    specifications: {}
                };

                formData.forEach((value, key) => {
                    if (key.startsWith('specifications')) {
                        const matches = key.match(/\[(.*?)\]/);
                        if (matches) {
                            data.specifications[matches[1]] = value;
                        }
                    }
                });

                fetch(`{{ route('pos.checkPrice', ['tenant' => request()->route('tenant')]) }}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        const priceDetails = document.querySelector(`#priceDetails${productId}`);
                        priceDetails.innerHTML = `
                    <div class="alert alert-info">
                        <h6 class="mb-2"><strong>Rincian Harga:</strong></h6>
                        <p class="mb-1">Quantity: ${data.quantity}</p>
                        ${data.specifications.map(spec => `
                                    <p class="mb-1">${spec.name}: Rp ${spec.price}</p>
                                `).join('')}
                        <hr>
                        <p class="mb-0"><strong>Total Harga:</strong> Rp ${data.totalPrice}</p>
                    </div>
                `;
                    });
            });
        });
    </script>
@endsection
