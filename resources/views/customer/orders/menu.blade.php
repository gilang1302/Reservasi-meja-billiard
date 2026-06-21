@extends('layouts.app')

@section('title', 'Pesan Menu F&B')

@section('content')
<div class="space-y-8" x-data="cartManager()">
    <!-- Header -->
    <div>
        <a href="{{ route('booking.show', $booking->id) }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5 mb-2 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Reservasi
        </a>
        <h2 class="text-2xl font-bold text-slate-100 flex items-center gap-2">
            <i class="fa-solid fa-burger text-orange-500"></i> Menu F&B Kantin CueMaster
        </h2>
        <p class="text-sm text-slate-400 mt-1">Sesi Bermain di Meja #{{ $booking->table->table_number }} &bull; Pesanan akan diantarkan langsung ke meja Anda.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left: Menu Items Catalog (2/3 width) -->
        <div class="lg:col-span-2 space-y-8">
            @foreach($groupedMenu as $category => $items)
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-slate-200 border-l-4 border-[#6C3BFF] pl-3">
                        @if($category === 'Food') Makanan Berat @elseif($category === 'Snack') Cemilan @else Minuman @endif
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($items as $item)
                            <div class="p-4 rounded-2xl glass-card flex justify-between gap-4">
                                <div class="space-y-1.5">
                                    <h4 class="text-sm font-bold text-slate-200">{{ $item->name }}</h4>
                                    <span class="block text-xs font-extrabold text-[#FF6B35]">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                                    <span class="inline-block text-[10px] text-slate-500 font-semibold uppercase">Stok: {{ $item->stock }} pcs</span>
                                </div>

                                <div class="flex items-center gap-2.5 h-fit self-center">
                                    <button type="button" @click="decreaseQty('{{ $item->id }}')" class="w-8 h-8 rounded-lg bg-slate-800 hover:bg-slate-700 flex items-center justify-center text-slate-300 font-bold transition-colors">
                                        -
                                    </button>
                                    <span class="text-sm font-extrabold text-slate-200 w-4 text-center" x-text="getItemQty('{{ $item->id }}')">0</span>
                                    <button type="button" @click="increaseQty('{{ $item->id }}', '{{ $item->name }}', {{ $item->price }}, {{ $item->stock }})" class="w-8 h-8 rounded-lg bg-[#6C3BFF] hover:bg-indigo-600 flex items-center justify-center text-white font-bold transition-colors">
                                        +
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Right: Basket & Checkout (1/3 width) -->
        <div class="glass-panel rounded-3xl p-6 border border-slate-800/80 flex flex-col justify-between h-fit">
            <div class="space-y-6">
                <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 border-b border-slate-800 pb-3">Keranjang Belanja</h3>
                
                <!-- Cart Items List -->
                <template x-if="items.length === 0">
                    <div class="text-center py-12 text-slate-500 space-y-3">
                        <i class="fa-solid fa-shopping-basket text-4xl block text-slate-650"></i>
                        <p class="text-xs">Keranjang Anda masih kosong. Silakan tambah makanan atau minuman dari menu catalog.</p>
                    </div>
                </template>

                <template x-if="items.length > 0">
                    <div class="space-y-3 max-h-60 overflow-y-auto divide-y divide-slate-800/30 pr-1">
                        <template x-for="cartItem in items" :key="cartItem.id">
                            <div class="flex items-center justify-between text-xs py-2.5 first:pt-0">
                                <div>
                                    <span class="font-bold text-slate-300" x-text="cartItem.name"></span>
                                    <span class="block text-[10px] text-slate-500" x-text="cartItem.qty + 'x @ Rp ' + formatPrice(cartItem.price)"></span>
                                </div>
                                <span class="font-extrabold text-slate-200" x-text="'Rp ' + formatPrice(cartItem.price * cartItem.qty)"></span>
                            </div>
                        </template>
                    </div>
                </template>

                <!-- Pricing Summary -->
                <div class="border-t border-slate-800 pt-4 space-y-2 text-xs" x-show="items.length > 0">
                    <div class="flex justify-between font-bold text-sm text-slate-200">
                        <span>Total Bayar</span>
                        <span class="text-[#FF6B35]" x-text="'Rp ' + formatPrice(getTotal())"></span>
                    </div>
                    <p class="text-[10px] text-slate-500 mt-1 italic"><i class="fa-solid fa-circle-info mr-1"></i>Pembayaran pesanan F&B akan digabungkan ke total invoice saat checkout akhir bermain.</p>
                </div>
            </div>

            <!-- Submit Order Form -->
            <div class="pt-6 border-t border-slate-850 mt-6" x-show="items.length > 0">
                <form method="POST" action="{{ route('order.store', $booking->id) }}" id="order-submit-form">
                    @csrf
                    <!-- Dynamic inputs injected by Alpine -->
                    <template x-for="(cartItem, index) in items" :key="cartItem.id">
                        <div>
                            <input type="hidden" :name="'items['+index+'][id]'" :value="cartItem.id">
                            <input type="hidden" :name="'items['+index+'][qty]'" :value="cartItem.qty">
                        </div>
                    </template>

                    <button type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-orange-600 to-[#FF6B35] hover:from-[#FF6B35] hover:to-orange-600 text-white text-sm font-bold shadow-lg shadow-orange-950/40 flex items-center justify-center gap-2 transition-all duration-300">
                        <i class="fa-solid fa-check"></i> Kirim Pesanan ke Kasir
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script>
    function cartManager() {
        return {
            items: [],
            
            getItemQty(id) {
                const found = this.items.find(item => item.id === id);
                return found ? found.qty : 0;
            },

            increaseQty(id, name, price, maxStock) {
                const found = this.items.find(item => item.id === id);
                if (found) {
                    if (found.qty < maxStock) {
                        found.qty++;
                    }
                } else {
                    this.items.push({ id, name, price, qty: 1 });
                }
            },

            decreaseQty(id) {
                const foundIndex = this.items.findIndex(item => item.id === id);
                if (foundIndex !== -1) {
                    const found = this.items[foundIndex];
                    if (found.qty > 1) {
                        found.qty--;
                    } else {
                        this.items.splice(foundIndex, 1);
                    }
                }
            },

            getTotal() {
                return this.items.reduce((sum, item) => sum + (item.price * item.qty), 0);
            },

            formatPrice(price) {
                return new Intl.NumberFormat('id-ID').format(price);
            }
        }
    }
</script>
@endsection
