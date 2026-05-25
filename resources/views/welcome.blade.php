<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retail Pro - Premium Marketplace UMKM</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        'dark-slate': '#0f172a',
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
        .glass { background: rgba(255,255,255,.86); backdrop-filter: blur(12px); }
        .gradient-text {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .product-card { transition: all .25s ease; }
        .product-card:hover { transform: translateY(-4px); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>

<body class="antialiased text-slate-900">

    {{-- NAVBAR --}}
    <nav class="glass sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-5">
            <div class="flex justify-between h-16 items-center">
                <a href="{{ route('landing') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <i class="fa fa-bolt-lightning text-base"></i>
                    </div>
                    <span class="text-lg font-extrabold tracking-tight text-dark-slate uppercase">
                        Retail<span class="text-primary">Pro</span>
                    </span>
                </a>

                <div class="flex items-center gap-3">
                    @auth
                        @if(Auth::user()->role == 'vendor' || Auth::user()->role == 'admin')
                            <a href="{{ route('dashboard') }}"
                               class="flex items-center gap-2 bg-dark-slate text-white px-4 py-2 rounded-2xl font-bold text-xs hover:bg-opacity-90 transition-all shadow-lg shadow-slate-200">
                                <i class="fa fa-layer-group opacity-70"></i>
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('cart.index') }}"
                               class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-2xl font-bold text-xs hover:brightness-110 transition-all shadow-lg shadow-indigo-100">
                                <i class="fa fa-shopping-cart opacity-70"></i>
                                Keranjang Saya
                            </a>
                        @endif

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="flex items-center gap-2 bg-red-500 text-white px-4 py-2 rounded-2xl font-bold text-xs hover:bg-red-600 transition-all shadow-lg shadow-red-100 active:scale-95">
                                <i class="fa fa-sign-out-alt opacity-80"></i>
                                Logout
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-500 font-bold hover:text-primary transition-colors text-sm">Sign In</a>
                        <a href="{{ route('register.customer') }}" class="text-primary font-bold hover:text-dark-slate transition-colors text-sm">Daftar</a>
                        <a href="{{ route('register') }}" class="bg-primary text-white px-5 py-2.5 rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:shadow-indigo-200 transition-all active:scale-95">
                            Join Merchant
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <header class="bg-gradient-to-b from-indigo-50/80 to-slate-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-5 py-14 md:py-16">
            <div class="text-center">
                <span class="inline-block px-4 py-1.5 mb-5 text-[9px] font-black tracking-[0.22em] text-primary bg-white rounded-full uppercase border border-indigo-100 shadow-sm">
                    Premium UMKM Ecosystem
                </span>

                <h1 class="text-3xl md:text-5xl font-black text-dark-slate leading-tight mb-4">
                    Elevating Local <span class="gradient-text">Excellence.</span>
                </h1>

                <p class="text-slate-500 max-w-xl mx-auto text-sm md:text-base font-medium leading-relaxed">
                    Dukung pertumbuhan ekonomi kreatif dengan berbelanja langsung dari mitra UMKM pilihan.
                </p>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-5 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-[260px_1fr] gap-8">

            {{-- FILTER SIDEBAR --}}
            <aside class="w-full">
                <div class="bg-white rounded-4xl p-6 border border-slate-100 shadow-sm lg:sticky lg:top-24">
                    <form action="{{ route('landing') }}" method="GET" class="space-y-6">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Quick Search</label>
                            <div class="relative group">
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Find products..."
                                       class="w-full bg-slate-50 border-0 rounded-2xl py-3.5 pl-11 pr-4 text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
                                <i class="fa fa-search absolute left-4 top-4 text-slate-300 group-focus-within:text-primary transition-colors"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Price Range</label>
                            <div class="space-y-3">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min (Rp)"
                                       class="w-full bg-slate-50 border-0 rounded-2xl py-3.5 px-4 text-sm outline-none focus:ring-2 focus:ring-primary">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max (Rp)"
                                       class="w-full bg-slate-50 border-0 rounded-2xl py-3.5 px-4 text-sm outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white py-3.5 rounded-2xl font-bold text-sm shadow-xl shadow-indigo-100 hover:brightness-110 transition-all active:scale-95">
                            Filter Results
                        </button>

                        <a href="{{ route('landing') }}" class="block text-center text-xs font-bold text-slate-400 hover:text-red-500">
                            Clear Filters
                        </a>
                    </form>
                </div>
            </aside>

            {{-- PRODUCT AREA --}}
            <section class="min-w-0">
                {{-- CATEGORY TABS --}}
                <div class="flex gap-3 overflow-x-auto pb-6 hide-scrollbar">
                    <a href="{{ route('landing', request()->except('category')) }}"
                       class="shrink-0 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-wider transition-all {{ !request('category') ? 'bg-primary text-white shadow-lg shadow-indigo-100' : 'bg-white text-slate-400 border border-slate-100 hover:border-primary' }}">
                        All Products
                    </a>

                    @foreach($categories as $cat)
                        <a href="{{ route('landing', array_merge(request()->all(), ['category' => $cat->slug])) }}"
                           class="shrink-0 px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-wider transition-all {{ request('category') == $cat->slug ? 'bg-primary text-white shadow-lg shadow-indigo-100' : 'bg-white text-slate-400 border border-slate-100 hover:border-primary' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>

                {{-- GRID --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @forelse($products as $product)
                        <div class="product-card bg-white rounded-4xl border border-slate-100 overflow-hidden flex flex-col shadow-sm hover:shadow-xl hover:shadow-slate-200/60">

                            <div class="p-4">
                                <div class="relative overflow-hidden rounded-3xl bg-slate-50 h-56 flex items-center justify-center">
                                    <img src="{{ $product->image_url }}"
                                         alt="{{ $product->name }}"
                                         class="w-full h-full object-contain p-2 transition duration-500 ease-in-out"
                                         onerror="this.src='https://placehold.co/600x400?text=Premium+Product';">

                                    <div class="absolute top-3 left-3">
                                        <span class="glass px-3 py-1.5 rounded-xl text-[8px] font-black uppercase text-primary border border-white shadow-sm">
                                            {{ $product->category->name ?? 'Curated' }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="px-6 pb-6 flex-1 flex flex-col">
                                <div class="mb-4">
                                    <h3 class="text-base font-extrabold text-dark-slate mb-1 line-clamp-1 leading-tight">
                                        {{ $product->name }}
                                    </h3>

                                    <div class="flex items-center text-[10px] text-slate-400 font-bold uppercase tracking-wide">
                                        <i class="fa fa-store-alt mr-2 text-primary"></i>
                                        <span class="line-clamp-1">{{ $product->vendor->shop_name ?? 'Official Store' }}</span>
                                    </div>
                                </div>

                                <div class="mt-auto flex justify-between items-center pt-4 border-t border-slate-100">
                                    <div>
                                        <p class="text-[9px] text-slate-400 font-black uppercase mb-1">Price</p>
                                        <p class="text-lg font-black text-dark-slate">
                                            Rp {{ number_format($product->price ?? $product->price_eceran, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <a href="{{ route('product.detail', $product->id) }}"
                                       class="bg-primary text-white w-11 h-11 rounded-2xl flex items-center justify-center transition-all shadow-lg shadow-indigo-100 hover:rotate-6 active:scale-95">
                                        <i class="fa fa-arrow-right-long"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-28 text-center">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fa fa-search text-slate-200 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-dark-slate">No matches found</h3>
                            <p class="text-slate-400 mt-2">Try adjusting your search or filters.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            </section>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-dark-slate pt-14 pb-8 mt-14 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-5 relative z-10 text-center">
            <a href="#" class="text-xl font-black text-white tracking-tighter uppercase">
                Retail<span class="text-primary">Pro</span>
            </a>

            <p class="text-slate-500 mt-4 max-w-md mx-auto leading-relaxed text-sm font-medium">
                Sistem Informasi Ekosistem Digital Marketplace UMKM Binaan.
            </p>

            <div class="mt-10 pt-7 border-t border-slate-800/50 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-slate-600 text-[10px] font-black uppercase tracking-[0.25em]">
                    &copy; 2026 Retail Pro Marketplace.
                </p>
                <div class="px-5 py-2 bg-slate-800/50 rounded-full border border-slate-700/50">
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.18em] mb-0">
                        Engineered by <span class="text-white">SUPRIANTO OPICK</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>