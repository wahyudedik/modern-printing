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
                    <div class="col-12 col-lg-5">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('pos.index', ['tenant' => request()->route('tenant')]) }}"
                                class="btn {{ request()->routeIs('pos.index') ? 'btn-primary' : 'btn-light' }} rounded-pill px-4 py-2">
                                <i class="fas fa-th-large me-2"></i>All Products
                            </a>
                            @foreach ($categories as $category)
                                <a href="{{ route('pos.category', ['tenant' => request()->route('tenant'), 'slug' => $category->slug]) }}"
                                    class="btn {{ request()->is('pos/category/' . $category->slug) ? 'btn-primary' : 'btn-light' }} rounded-pill px-4 py-2">
                                    <i class="fas fa-tag me-2"></i>{{ $category->nama_kategori }}
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12 col-lg-5">
                        <form action="{{ route('pos.search', ['tenant' => request()->route('tenant')]) }}" method="GET"
                            class="d-flex gap-2">
                            <input type="text" name="search" class="form-control form-control-lg rounded-pill"
                                value="{{ request('search') }}" placeholder="Search products...">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill px-4">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </div>

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

    {{-- Notification --}}
    @if (session('success'))
        <div class="col-md-12 mt-3">
            <div class="alert alert-success alert-dismissible fade show rounded-4" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    {{-- produk --}}
    <div class="col-md-12 mt-3">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <div class="row g-4">
                    @foreach ($products as $product)
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="card h-100 border-0 shadow-hover rounded-4">
                                <img src="{{ asset('storage/' . $product->gambar[0]) }}" class="card-img-top">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-2">{{ $product->nama_produk }}</h5>
                                    <p class="text-muted mb-3">{!! Str::limit($product->deskripsi, 50) !!}</p>

                                    <div class="alert alert-info mb-3">
                                        <strong>Harga Dasar:</strong> Rp
                                        {{ number_format($product->harga_dasar, 0, ',', '.') }}
                                    </div>

                                    <div x-data="{ showForm: false }">
                                        <button @click="showForm = !showForm" class="btn btn-outline-primary w-100 mb-3">
                                            <span x-text="showForm ? 'Hide Details' : 'Show Details'"></span>
                                            <i class="fas" :class="showForm ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                                        </button>

                                        <div x-show="showForm" x-transition:enter="transition ease-out duration-300"
                                            x-transition:enter-start="opacity-0 transform scale-90"
                                            x-transition:enter-end="opacity-100 transform scale-100">
                                            <form
                                                action="{{ route('pos.addToCart', ['tenant' => request()->route('tenant')]) }}"
                                                method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">

                                                <div class="mb-3">
                                                    <label class="form-label">Quantity</label>
                                                    <input type="number" name="quantity" class="form-control"
                                                        value="1" min="1">
                                                </div>

                                                <div x-data="{ showPreview: false, previewImage: '' }">
                                                    @foreach ($product->spesifikasiProduk as $spec)
                                                        @if ($spec->spesifikasi->tipe_input === 'file')
                                                            <div class="mb-3">
                                                                <input type="file"
                                                                    @change="previewImage = URL.createObjectURL($event.target.files[0])"
                                                                    class="form-control">
                                                                <button @click="showPreview = true"
                                                                    class="btn btn-secondary mt-2">
                                                                    Preview Design
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @endforeach

                                                    <div x-show="showPreview" class="preview-modal">
                                                        <img :src="previewImage" class="preview-image">
                                                    </div>
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
                                                                        {{ $bahan->nama_bahan }} - Rp
                                                                        {{ number_format($bahan->harga_per_satuan) }}
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

                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="card-body p-4">
                                    <h5 class="fw-bold mb-2">{{ $product->nama_produk }}</h5>
                                    <p class="text-muted mb-3">{!! Str::limit($product->deskripsi, 50) !!}</p>
                                    <!-- Tambahkan Harga Dasar -->
                                    <div class="alert alert-info mb-3">
                                        <strong>Harga Dasar:</strong> Rp
                                        {{ number_format($product->harga_dasar, 0, ',', '.') }}
                                    </div>
                                    <form action="{{ route('pos.addToCart', ['tenant' => request()->route('tenant')]) }}"
                                        method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                                        <div class="mb-3">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" name="quantity" class="form-control" value="1"
                                                min="1">
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
                                                        class="form-select" {{ $spec->wajib_diisi ? 'required' : '' }}>
                                                        @foreach ($spec->bahans as $bahan)
                                                            <option value="{{ $bahan->id }}">
                                                                {{ $bahan->nama_bahan }} - Rp
                                                                {{ number_format($bahan->harga_per_satuan) }}
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

                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="fas fa-cart-plus me-2"></i>Add to Cart
                                        </button>
                                    </form>
                                </div> --}}
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
@endsection
