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

    <div class="card-body p-4">
        {{-- Cart Details --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold text-black mb-0">Cart Details</h3>
            <a href="{{ route('pos.index', ['tenant' => request()->route('tenant')]) }}"
                class="btn btn-light rounded-pill px-4">
                <i class="fas fa-arrow-left me-2"></i>Back to Products
            </a>
        </div>
        <div id="cartItems" class="space-y-4">
            @foreach ($cartItems as $index => $item)
                <div class="card shadow-sm border-0 rounded-4 mb-3">
                    <div class="card-body p-4">
                        <!-- Product Header -->
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold text-dark mb-0">{{ $item['product_name'] }}</h5>
                            <button class="btn btn-light btn-sm rounded-circle" onclick="removeItem({{ $index }})">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <!-- Quantity -->
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <span class="text-muted">Quantity</span>
                            <span class="fw-medium">
                                {{ $item['quantity'] }} pcs
                            </span>
                        </div>

                        <div class="specifications-container">
                            <!-- Base Price -->
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Harga Dasar</span>
                                <span class="fw-medium">
                                    Rp {{ number_format($item['base_price'], 0, ',', '.') }}
                                </span>
                            </div>

                            <!-- Specifications -->
                            @foreach ($item['specifications'] as $specId => $spec)
                                @php
                                    $spesifikasiProduk = \App\Models\SpesifikasiProduk::with([
                                        'spesifikasi',
                                        'bahans',
                                    ])->find($specId);
                                    $bahan = $spec['bahan_id'] ? \App\Models\Bahan::find($spec['bahan_id']) : null;
                                    $hargaPerSatuan = $bahan ? $bahan->harga_per_satuan : 0;
                                    $specPrice = $spesifikasiProduk->calculatePrice(
                                        $spec['value'],
                                        $spec['bahan_id'],
                                        $item['quantity'],
                                    );
                                @endphp
                                <div class="d-flex justify-content-between border-bottom py-2">
                                    <span class="text-muted">
                                        {{ $spesifikasiProduk->spesifikasi->nama_spesifikasi }}
                                    </span>
                                    <span class="fw-medium">
                                        {{ $spec['value'] }} {{ $spesifikasiProduk->spesifikasi->satuan }}
                                        @if ($bahan)
                                            (Rp {{ number_format($specPrice, 0, ',', '.') }})
                                        @endif
                                    </span>
                                </div>
                            @endforeach

                            <!-- Production Time -->
                            @php
                                $estimasiProduk = \App\Models\EstimasiProduk::where(
                                    'produk_id',
                                    $item['product_id'],
                                )->first();
                                $estimatedTime = $estimasiProduk
                                    ? $estimasiProduk->calculateTotalProductionTime($item['quantity'])
                                    : 0;
                            @endphp
                            <div class="d-flex justify-content-between border-bottom py-2">
                                <span class="text-muted">Estimasi Waktu Produksi</span>
                                <span class="fw-medium">{{ $estimatedTime }} menit</span>
                            </div>

                            <!-- Total Price -->
                            <div class="d-flex justify-content-between pt-3">
                                <span class="fw-bold">Total Item</span>
                                <span class="fw-bold text-primary">
                                    Rp {{ number_format($item['total_price'], 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Cart Summary -->
        <div class="mt-4 border-t pt-4">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="fw-bold text-dark">Total</h4>
                <h4 class="fw-bold text-primary">
                    Rp
                    {{ number_format(
                        collect($cartItems)->sum(function ($item) {
                            $itemTotal = $item['base_price'];
                            foreach ($item['specifications'] as $specId => $spec) {
                                $spesifikasiProduk = \App\Models\SpesifikasiProduk::with(['spesifikasi', 'bahans'])->find($specId);
                    
                                $hargaPerSatuan = $spec['harga_per_satuan'] ?? 0;
                                $quantity = $item['quantity'] ?? 1;
                    
                                if ($spesifikasiProduk->spesifikasi->nama_spesifikasi === 'Jumlah Halaman') {
                                    $specPrice = $spec['value'] * $hargaPerSatuan; // Price per page
                                } elseif ($spesifikasiProduk->spesifikasi->nama_spesifikasi === 'Cover') {
                                    $specPrice = $hargaPerSatuan; // Fixed cover price
                                } else {
                                    $specPrice = $spec['value'] * $hargaPerSatuan;
                                }
                    
                                $itemTotal += $specPrice;
                            }
                            return $itemTotal * $quantity;
                        }),
                        0,
                        ',',
                        '.',
                    ) }}
                </h4>
            </div>

            <!-- Action Buttons -->
            <div class="mt-4 d-flex justify-content-end gap-2">
                <button class="btn btn-light rounded-pill px-4" onclick="clearCart()">
                    <i class="fas fa-trash me-2"></i>Clear Cart
                </button>
                <button class="btn btn-primary rounded-pill px-4" onclick="proceedToCheckout()">
                    <i class="fas fa-shopping-cart me-2"></i>Proceed to Checkout
                </button>
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
        function removeItem(index) {
            const currentTenant = window.location.pathname.split('/')[2];
            window.location.href = `/app/${currentTenant}/pos/cart/remove/${index}`;
        }

        function clearCart() {
            const currentTenant = window.location.pathname.split('/')[2];
            window.location.href = `/app/${currentTenant}/pos/cart/clear`;
        }

        function proceedToCheckout() {
            const currentTenant = window.location.pathname.split('/')[2];
            window.location.href = `/app/${currentTenant}/pos/checkout`;
        }
    </script>
@endsection
