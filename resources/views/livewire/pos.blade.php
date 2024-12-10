<div>
    <div class="flex h-screen">
        <!-- Products Section (Left Side) -->
        <div class="w-full lg:w-2/3 p-4 overflow-y-auto">
            <div class="mb-4">
                <input type="text" wire:model.debounce.300ms="search"
                    class="block w-full text-gray-900 border-gray-300 rounded-lg shadow-sm focus:border-primary-600 focus:ring-primary-600 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-400"
                    placeholder="Search products...">
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2">
                @foreach ($products as $product)
                    <div wire:click="selectProduct({{ $product->id }})"
                        class="bg-white dark:bg-gray-800 p-2 rounded-lg shadow cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                        @if ($product->gambar)
                            <img src="{{ asset('storage/' . (is_array($product->gambar) ? $product->gambar[0] : json_decode($product->gambar)[0])) }}"
                                class="w-full h-24 object-cover rounded-lg mb-1">
                        @endif
                        <h3 class="font-medium text-sm text-gray-900 dark:text-gray-100">{{ $product->nama_produk }}
                        </h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Rp
                            {{ number_format($product->harga, 0, ',', '.') }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Cart Section (Right Side) -->
        <div class="w-full lg:w-1/3 bg-white dark:bg-gray-800 p-4 shadow-sm border-l dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-4">Shopping Cart</h2>

            <div class="flex-1 overflow-y-auto mb-4 max-h-[40vh]">
                @foreach ($cart as $item)
                    <div class="flex items-center justify-between mb-2 p-2 border-b">
                        <div>
                            <h4 class="font-semibold">{{ $item['name'] }}</h4>
                            <p class="text-sm text-gray-600">
                                {{ $item['qty'] }} x Rp {{ number_format($item['total_price'], 0, ',', '.') }}
                                @if ($item['price'] != $item['total_price'])
                                    <span class="line-through text-gray-400">
                                        <s>Rp {{ number_format($item['price'], 0, ',', '.') }}</s>
                                    </span>
                                @endif
                            </p>
                            <p class="text-xs text-gray-500">
                                Min. Qty: {{ $item['minimal_qty'] }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button wire:click="decrementQty({{ $item['id'] }})" class="px-2 py-1 bg-red-500 rounded">
                                -
                            </button>
                            <span>{{ $item['qty'] }}</span>
                            <button wire:click="incrementQty({{ $item['id'] }})"
                                class="px-2 py-1 bg-green-500 rounded">
                                +
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Customer Information -->
            <div class="border-t dark:border-gray-700 pt-4 mb-4">
                <h3 class="font-bold text-gray-900 dark:text-gray-100 mb-2">Customer Information</h3>

                <!-- Customer Selection Toggle -->
                <div class="mb-4">
                    <button wire:click="$toggle('show_customer_form')"
                        class="text-sm text-primary-600 hover:text-primary-500">
                        {{ $show_customer_form ? 'Select Existing Customer' : 'Add New Customer' }}
                    </button>
                </div>

                @if (!$show_customer_form)
                    <!-- Existing Customer Selection -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium">Select Customer</label>
                        <select wire:model="selected_customer_id"
                            wire:change="selectExistingCustomer($event.target.value)"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400">
                            <option value="">Select a customer...</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">
                                    {{ $customer->nama }} - {{ $customer->email }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- New Customer Form -->
                <div class="{{ !$show_customer_form ? 'hidden' : '' }} space-y-3">
                    <div>
                        <label class="block text-sm font-medium">Name</label>
                        <input type="text" wire:model="customer_name"
                            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400">
                        @error('customer_name')
                            <span class="text-danger-600 text-xs">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium">Name</label>
                            <input type="text" wire:model="customer_name"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 shadow-sm focus:border-primary-600 focus:ring-primary-600">
                            @error('customer_name')
                                <span class="text-danger-600 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Email</label>
                            <input type="email" wire:model="customer_email"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 shadow-sm focus:border-primary-600 focus:ring-primary-600">
                            @error('customer_email')
                                <span class="text-danger-600 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Phone</label>
                            <input type="text" wire:model="customer_phone"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 shadow-sm focus:border-primary-600 focus:ring-primary-600">
                            @error('customer_phone')
                                <span class="text-danger-600 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Address</label>
                            <textarea wire:model="customer_address"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 shadow-sm focus:border-primary-600 focus:ring-primary-600"></textarea>
                            @error('customer_address')
                                <span class="text-danger-600 text-xs">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t dark:border-gray-700 pt-4">
                <div class="flex justify-between mb-2">
                    <span class="text-gray-900 dark:text-gray-100">Subtotal:</span>
                    <span class="text-gray-900 dark:text-gray-100">Rp
                        {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <select wire:model="payment_method"
                    class="block w-full mb-4 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 shadow-sm focus:border-primary-600 focus:ring-primary-600">
                    <option value="">Select Payment Method</option>
                    <option value="cash">Cash</option>
                    <option value="transfer">Transfer</option>
                    <option value="qris">QRIS</option>
                </select>

                <button wire:click="processTransaction"
                    class="w-full inline-flex items-center justify-center py-2 rounded-lg bg-primary-600 hover:bg-primary-500 focus:bg-primary-700 text-white transition"
                    @if (!$cart) disabled @endif>
                    Process Transaction
                </button>
            </div>
        </div>

        <!-- Backdrop overlay -->
        <div x-show="$wire.showInvoice" x-transition:enter="transition-all ease-in-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-all ease-in-out duration-300" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40"
            @click="$wire.closeInvoice()">
        </div>

        @if ($showInvoice && $transaction)
            <div x-show="$wire.showInvoice" x-transition:enter="transform transition-transform ease-in-out duration-300"
                x-transition:enter-start="-translate-y-full" x-transition:enter-end="translate-y-0"
                x-transition:leave="transform transition-transform ease-in-out duration-300"
                x-transition:leave-start="translate-y-0" x-transition:leave-end="-translate-y-full"
                class="fixed inset-x-0 top-0 w-full max-w-lg mx-auto bg-white dark:bg-gray-900 shadow-2xl overflow-y-auto z-50 rounded-b-2xl border-2 border-primary-600 backdrop-blur-lg"
                @keydown.escape.window="$wire.closeInvoice()">

                <!-- Header with close button -->
                <div class="p-4 border-b dark:border-gray-800 bg-gradient-to-r from-primary-600 to-primary-700">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold">Invoice Preview</h2>
                        <button @click="$wire.closeInvoice()"
                            class="p-1.5 hover:bg-white/10 rounded-full transition-colors duration-200">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="p-4">
                    @if ($transaction->pelanggan->first())
                        <div class="mb-4 bg-gray-50 dark:bg-gray-800 p-3 rounded-xl">
                            <h3 class="font-bold mb-2 text-base text-primary-600 dark:text-primary-400">Customer
                                Details
                            </h3>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 mb-0.5">Name</p>
                                    <p class="font-medium">{{ $transaction->pelanggan->first()->nama }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 mb-0.5">Email</p>
                                    <p class="font-medium">{{ $transaction->pelanggan->first()->email }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 mb-0.5">Phone</p>
                                    <p class="font-medium">{{ $transaction->pelanggan->first()->no_telp }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 dark:text-gray-400 mb-0.5">Address</p>
                                    <p class="font-medium">{{ $transaction->pelanggan->first()->alamat }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mb-4 bg-gray-50 dark:bg-gray-800 p-3 rounded-xl">
                        <h3 class="font-bold mb-2 text-base text-primary-600 dark:text-primary-400">Transaction Details
                        </h3>
                        <div class="grid grid-cols-2 gap-3 text-sm">
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 mb-0.5">Transaction Code</p>
                                <p class="font-medium">{{ $transaction->kode }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 mb-0.5">Date</p>
                                <p class="font-medium">{{ $transaction->created_at->format('d/m/Y H:i') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 dark:text-gray-400 mb-0.5">Payment Method</p>
                                <p class="font-medium">{{ $transaction->metode_pembayaran_label }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h3 class="font-bold mb-2 text-base text-primary-600 dark:text-primary-400">Products</h3>
                        <div class="overflow-x-auto bg-gray-50 dark:bg-gray-800 rounded-xl">
                            <table class="w-full min-w-[400px] table-auto">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="py-2 px-3 text-left text-xs font-semibold">Product</th>
                                        <th class="py-2 px-3 text-right text-xs font-semibold">Qty</th>
                                        <th class="py-2 px-3 text-right text-xs font-semibold">Price</th>
                                        <th class="py-2 px-3 text-right text-xs font-semibold">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transaction->produk as $product)
                                        <tr
                                            class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                            <td class="py-2 px-3 text-xs">{{ $product->nama_produk }}</td>
                                            <td class="py-2 px-3 text-right text-xs">{{ $product->pivot->quantity }}
                                            </td>
                                            <td class="py-2 px-3 text-right text-xs">Rp
                                                {{ number_format($product->total_harga, 0, ',', '.') }}</td>
                                            <td class="py-2 px-3 text-right text-xs">Rp
                                                {{ number_format($product->pivot->quantity * $product->total_harga, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-100 dark:bg-gray-700 font-semibold">
                                        <td colspan="3" class="py-2 px-3 text-right text-xs">Total:</td>
                                        <td class="py-2 px-3 text-right text-xs">Rp
                                            {{ number_format($transaction->total_harga, 0, ',', '.') }}</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="sticky bottom-0 bg-white dark:bg-gray-900 py-3 border-t dark:border-gray-800">
                        <div class="flex gap-2">
                            <a href="{{ route('transaksi.print', $transaction->id) }}" target="_blank"
                                class="flex-1 bg-primary-600 hover:bg-primary-700 text-white py-2 px-4 rounded-xl text-xs font-semibold transition-colors duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print Invoice
                            </a>
                            <button wire:click="closeAndReset"
                                class="flex-1 bg-gray-500 hover:bg-gray-600 py-2 px-4 rounded-xl text-xs font-semibold transition-colors duration-200 flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Close & Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
