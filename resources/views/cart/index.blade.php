<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart — Retail Pro Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#6366f1',
                        'slate-dark': '#0f172a',
                    },
                    borderRadius: {
                        '4xl': '2rem',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .cart-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .cart-card:hover { transform: scale(1.01); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); }
        .glass-summary { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="antialiased text-slate-700">

    {{-- HEADER NAV --}}
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-6xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="group flex items-center gap-2 text-slate-500 font-bold text-sm transition-all hover:text-primary">
                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:-translate-x-1 transition-transform">
                    <i class="fa fa-arrow-left text-[10px]"></i>
                </div>
                Continue Shopping
            </a>
            <div class="text-center">
                <h1 class="text-lg font-black text-slate-dark tracking-tight">Shopping <span class="text-primary">Cart</span></h1>
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Retail Pro Ecosystem</p>
            </div>
            <div class="w-32 hidden md:block"></div>
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            
            {{-- CART ITEMS LIST --}}
            <div class="lg:col-span-8 space-y-4">
                <div class="flex items-center justify-between mb-6 px-2">
                    <h2 class="text-xl font-extrabold text-slate-dark tracking-tight">Your Selection</h2>
                    <span class="text-xs font-bold text-slate-400 bg-slate-100 px-3 py-1 rounded-full">{{ $cartItems->count() }} Items</span>
                </div>

                @forelse($cartItems as $item)
                    <div class="cart-card bg-white p-5 rounded-3xl border border-slate-50 flex items-center gap-6 group">
                        {{-- Product Image --}}
                        <div class="relative overflow-hidden rounded-2xl w-28 h-28 flex-shrink-0 bg-slate-50">
                            <img src="{{ asset($item->product->image_url) }}" 
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                                 onerror="this.src='https://placehold.co/400x400?text=Product';">
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <span class="text-[10px] font-black text-primary uppercase tracking-widest mb-1 block">{{ $item->product->vendor->shop_name ?? 'Verified Mitra' }}</span>
                            <h4 class="font-bold text-slate-dark text-lg truncate mb-1">{{ $item->product->name }}</h4>
                            <div class="flex items-center gap-3">
                                <p class="font-black text-slate-dark">Rp {{ number_format($item->product->price ?? $item->product->price_eceran, 0, ',', '.') }}</p>
                                <span class="text-[10px] text-slate-300 font-bold">/ Unit</span>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="flex flex-col items-end gap-5">
                            <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all active:scale-90 shadow-sm">
                                    <i class="fa-regular fa-trash-can text-sm"></i>
                                </button>
                            </form>
                            <div class="flex items-center bg-slate-50 rounded-xl p-1 border border-slate-100">
                                <span class="px-3 text-[10px] font-black text-slate-400 uppercase">Qty</span>
                                <span class="bg-white text-slate-dark px-4 py-1.5 rounded-lg font-black text-sm shadow-sm">{{ $item->quantity }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-24 bg-white rounded-4xl border border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                             <i class="fa fa-shopping-bag text-slate-200 text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-dark">Your cart is empty</h3>
                        <p class="text-slate-400 text-sm mt-1 mb-8">Looks like you haven't added anything yet.</p>
                        <a href="{{ route('landing') }}" class="inline-flex items-center gap-2 bg-primary text-white px-8 py-3 rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:brightness-110 transition-all">
                            Browse Collection
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- ORDER SUMMARY --}}
            <div class="lg:col-span-4">
                <div class="glass-summary p-8 rounded-4xl shadow-2xl shadow-slate-200/50 sticky top-32">
                    <h3 class="font-black text-xl text-slate-dark mb-8 tracking-tight">Order Summary</h3>
                    
                    <div class="space-y-4 mb-10">
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[10px]">Subtotal Items</span>
                            <span class="font-black text-slate-dark">{{ $cartItems->sum('quantity') }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="font-bold text-slate-400 uppercase tracking-widest text-[10px]">Tax Platform</span>
                            <span class="font-black text-emerald-500">Free</span>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Payment</p>
                                @php
                                    $total = $cartItems->sum(function($item) {
                                        return ($item->product->price ?? $item->product->price_eceran) * $item->quantity;
                                    });
                                @endphp
                                <p class="text-3xl font-black text-primary">Rp {{ number_format($total, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Checkout CTA --}}
                    @if($cartItems->isNotEmpty())
                        <form action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="group w-full py-5 bg-slate-dark text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:brightness-125 transition-all active:scale-95 flex items-center justify-center gap-3">
                                <span>Proceed to Checkout</span>
                                <i class="fa fa-arrow-right-long group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </form>
                    @else
                        <button disabled class="w-full py-5 bg-slate-100 text-slate-300 rounded-2xl font-black text-xs uppercase tracking-[0.2em] cursor-not-allowed">
                            Empty Cart
                        </button>
                    @endif
                    
                    {{-- SECURITY BRANDING --}}
                    <div class="mt-8 pt-6 border-t border-slate-50">
                        <div class="flex items-center justify-center gap-4 opacity-30 grayscale mb-6">
                            <i class="fa-brands fa-cc-visa text-2xl"></i>
                            <i class="fa-brands fa-cc-mastercard text-2xl"></i>
                            <i class="fa-solid fa-shield-check text-2xl"></i>
                        </div>
                        
                        {{-- HAK CIPTA SUPRIANTO OPICK --}}
                        <div class="text-center">
                            <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em]">
                                Architectural Integrity by
                            </p>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.1em] mt-1">
                                SUPRIANTO OPICK
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        {{-- Custom Professional Alerts --}}
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            background: '#0f172a',
            color: '#ffffff'
        });

        @if(session('success'))
            Toast.fire({ icon: 'success', title: "{{ session('success') }}" });
        @endif

        @if(session('error'))
            Swal.fire({ 
                icon: 'error', 
                title: 'Process Failed', 
                text: "{{ session('error') }}",
                confirmButtonColor: '#6366f1',
                borderRadius: '24px'
            });
        @endif
    </script>
</body>
</html>