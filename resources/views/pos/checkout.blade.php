@extends('pos.index')

@section('content')
    @php
        $vendor = \App\Models\Vendor::where('slug', request()->route('tenant'))->firstOrFail();
    @endphp

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

    <div class="col-md-12 mt-4">
        <div class="card shadow-sm border-0 rounded-4">
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                    {{ session('success') }}
                </div>
                <script>
                    setTimeout(function() {
                        document.getElementById('successAlert').remove();
                    }, 3000);
                </script>
            @endif

            <div class="card-body p-4">
                <form id="checkoutForm"
                    action="{{ route('pos.checkout.process', ['tenant' => request()->route('tenant')]) }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="mb-4">Customer Information</h4>
                            <div class="mb-3">
                                <label class="form-label">Customer</label>
                                <div class="d-flex gap-2">
                                    <select name="pelanggan_id" class="form-select" required id="customerSelect">
                                        <option value="">Select Customer</option>
                                        @foreach ($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->nama }}</option>
                                        @endforeach
                                    </select>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#newCustomerModal">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" class="form-select" required>
                                    <option value="cash">Cash</option>
                                    <option value="transfer">Bank Transfer</option>
                                    <option value="qris">QRIS</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Notes</label>
                                <textarea name="catatan" class="form-control" rows="3"
                                    placeholder="Add any special instructions or notes here..."></textarea>
                            </div>

                        </div>
                        <div class="col-md-6">
                            <h4 class="mb-4">Order Summary</h4>
                            @foreach ($cartItems as $index => $item)
                                <div class="card shadow-sm border-0 rounded-4 mb-3">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-3">{{ $item['product_name'] }} (x{{ $item['quantity'] }})</h5>

                                        <!-- Specifications -->
                                        @foreach ($item['specifications'] as $specId => $spec)
                                            @php
                                                $spesifikasiProduk = \App\Models\SpesifikasiProduk::with([
                                                    'spesifikasi',
                                                    'bahans',
                                                ])->find($specId);
                                                $bahan = \App\Models\Bahan::with('wholesalePrice')->find(
                                                    $spec['bahan_id'],
                                                );
                                                $wholesalePrice = new \App\Models\WholesalePrice();

                                                // Calculate price based on input type
                                                if ($spec['input_type'] === 'select') {
                                                    $pricePerUnit = $wholesalePrice->calculateFinalPrice(
                                                        $bahan->hpp,
                                                        $item['quantity'],
                                                        $bahan->id,
                                                    );
                                                } else {
                                                    $pricePerUnit = $wholesalePrice->calculateFinalPrice(
                                                        $bahan->hpp,
                                                        $spec['value'],
                                                        $bahan->id,
                                                    );
                                                }
                                            @endphp
                                            <div class="d-flex justify-content-between border-bottom py-2">
                                                <span class="text-muted">{{ $spec['nama_spesifikasi'] }}</span>
                                                <span class="fw-medium">
                                                    @if ($spec['input_type'] === 'select')
                                                        {{ $bahan->nama_bahan }}: {{ $item['quantity'] }} x Rp
                                                        {{ number_format($pricePerUnit, 0, ',', '.') }} = Rp
                                                        {{ number_format($spec['price'], 0, ',', '.') }}
                                                    @else
                                                        {{ $spec['value'] }} {{ $spesifikasiProduk->spesifikasi->satuan }}
                                                        x Rp {{ number_format($pricePerUnit, 0, ',', '.') }} = Rp
                                                        {{ number_format($spec['price'], 0, ',', '.') }}
                                                    @endif
                                                </span>
                                            </div>
                                        @endforeach

                                        <!-- Production Details -->
                                        @php
                                            $product = \App\Models\Produk::with('estimasiProduk.alat')->find(
                                                $item['product_id'],
                                            );
                                            $estimatedTime = $product->getEstimatedProductionTime($item['quantity']);
                                        @endphp
                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span class="text-muted">Estimasi Waktu</span>
                                            <span class="fw-medium">{{ $estimatedTime }} menit</span>
                                        </div>

                                        <div class="d-flex justify-content-between border-bottom py-2">
                                            <span class="text-muted">Alat Produksi</span>
                                            <span class="fw-medium">
                                                {{ $product->estimasiProduk->pluck('alat.nama_alat')->implode(', ') }}
                                            </span>
                                        </div>

                                        <!-- Item Total -->
                                        <div class="d-flex justify-content-between pt-3">
                                            <span class="fw-bold">Subtotal</span>
                                            <span class="fw-bold text-primary">
                                                Rp {{ number_format($item['total_price'], 0, ',', '.') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden inputs for form submission -->
                                <input type="hidden" name="items[{{ $index }}][product_id]"
                                    value="{{ $item['product_id'] }}">
                                <input type="hidden" name="items[{{ $index }}][quantity]"
                                    value="{{ $item['quantity'] }}">
                                @foreach ($item['specifications'] as $specId => $spec)
                                    <input type="hidden"
                                        name="items[{{ $index }}][specifications][{{ $specId }}][bahan_id]"
                                        value="{{ $spec['bahan_id'] }}">
                                    <input type="hidden"
                                        name="items[{{ $index }}][specifications][{{ $specId }}][value]"
                                        value="{{ $spec['value'] }}">
                                    <input type="hidden"
                                        name="items[{{ $index }}][specifications][{{ $specId }}][input_type]"
                                        value="{{ $spec['input_type'] }}">
                                    <input type="hidden"
                                        name="items[{{ $index }}][specifications][{{ $specId }}][price]"
                                        value="{{ $spec['price'] }}">
                                @endforeach
                            @endforeach

                            <!-- Order Totals -->
                            <div class="card shadow-sm border-0 rounded-4">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between border-bottom py-2">
                                        <span class="text-muted">Total Items</span>
                                        <span class="fw-medium">{{ count($cartItems) }} items</span>
                                    </div>
                                    <div class="d-flex justify-content-between border-bottom py-2">
                                        <span class="text-muted">Total Quantity</span>
                                        <span class="fw-medium">{{ collect($cartItems)->sum('quantity') }} pcs</span>
                                    </div>
                                    <div class="d-flex justify-content-between border-bottom py-2">
                                        <span class="text-muted">Total Production Time</span>
                                        <span class="fw-medium">{{ $totalTime }} minutes</span>
                                    </div>
                                    <div class="d-flex justify-content-between pt-3">
                                        <h4 class="fw-bold mb-0">Grand Total</h4>
                                        <h4 class="fw-bold text-primary mb-0">
                                            Rp {{ number_format($totalAmount, 0, ',', '.') }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('pos.cart', ['tenant' => request()->route('tenant')]) }}"
                                class="btn btn-light rounded-pill px-4">
                                <i class="fas fa-arrow-left me-2"></i>Back to Cart
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-4">
                                <i class="fas fa-check me-2"></i>Complete Order
                            </button>
                        </div>
                </form>
            </div>
        </div>
    </div>

    <!-- New Customer Modal -->
    <div class="modal fade" id="newCustomerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Customer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('pos.customer.create', ['tenant' => request()->route('tenant')]) }}"
                    method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No Telp</label>
                            <input type="tel" name="no_telp" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
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
        function createCustomer() {
            const nama = document.getElementById('nama').value;
            const alamat = document.getElementById('alamat').value;
            const no_telp = document.getElementById('no_telp').value;
            const email = document.getElementById('email').value;

            fetch(`/app/${window.location.pathname.split('/')[2]}/pos/customer/create`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        nama,
                        alamat,
                        no_telp,
                        email
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add the new customer to the select dropdown
                        const select = document.getElementById('pelanggan_id');
                        const option = new Option(data.customer.nama, data.customer.id);
                        select.add(option);
                        select.value = data.customer.id;

                        // Close the modal
                        bootstrap.Modal.getInstance(document.getElementById('newCustomerModal')).hide();
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        document.getElementById('checkoutForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            submitButton.disabled = true;

            try {
                const formData = new FormData(this);

                const response = await fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Open web view in new tab
                    window.open(data.invoiceUrl, '_blank');

                    // Download PDF version
                    fetch(data.downloadUrl)
                        .then(response => response.blob())
                        .then(blob => {
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = "invoice.pdf";
                            document.body.appendChild(a);
                            a.click();
                            window.URL.revokeObjectURL(url);
                        });

                    // Redirect main window
                    setTimeout(() => {
                        window.location.href = data.redirectUrl;
                    }, 1500);
                }
            } catch (error) {
                alert(error.message);
            } finally {
                // Reset button state
                submitButton.innerHTML = '<i class="fas fa-check me-2"></i>Complete Order';
                submitButton.disabled = false;
            }
        });
    </script>
@endsection
