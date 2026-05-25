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
                        'primary': '#6366f1',
                        'dark-slate': '#0f172a',
                    },
                    borderRadius: {
                        '3xl': '1.5rem',
                        '4xl': '2rem',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); }
        .product-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .product-card:hover { transform: translateY(-10px); }
        .gradient-text { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero-gradient { background: radial-gradient(circle at top right, rgba(99, 102, 241, 0.1), transparent), radial-gradient(circle at bottom left, rgba(168, 85, 247, 0.05), transparent); }
    </style>
</head>
<body class="antialiased">

    {{-- NAVBAR --}}
    <nav class="glass sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-6">
            <div class="flex justify-between h-20 items-center">
                <a href="{{ route('landing') }}" class="flex items-center gap-3 group">
                    <div class="w-11 h-11 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-200 group-hover:rotate-6 transition-transform">
                        <i class="fa fa-bolt-lightning text-lg"></i>
                    </div>
                    <span class="text-xl font-extrabold tracking-tight text-dark-slate uppercase">Retail<span class="text-primary">Pro</span></span>
                </a>

                <div class="flex items-center gap-4">
                            @auth

                                @if(Auth::user()->role == 'vendor' || Auth::user()->role == 'admin')

                                    <a href="{{ route('dashboard') }}" 
                                    class="flex items-center gap-2 bg-dark-slate text-white px-5 py-2.5 rounded-2xl font-bold text-sm hover:bg-opacity-90 transition-all shadow-xl shadow-slate-200">
                                        <i class="fa fa-layer-group opacity-70"></i>
                                        Dashboard
                                    </a>

                                @else

                                    <a href="{{ route('cart.index') }}" 
                                    class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-2xl font-bold text-sm hover:brightness-110 transition-all shadow-xl shadow-indigo-100">
                                        <i class="fa fa-shopping-cart opacity-70"></i>
                                        Keranjang Saya
                                    </a>

                                @endif

                                {{-- LOGOUT BUTTON --}}
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button 
                                        type="submit"
                                        class="flex items-center gap-2 bg-red-500 text-white px-5 py-2.5 rounded-2xl font-bold text-sm hover:bg-red-600 transition-all shadow-xl shadow-red-100 active:scale-95"
                                    >
                                        <i class="fa fa-sign-out-alt opacity-80"></i>
                                        Logout
                                    </button>
                                </form>

                            @else

                                <a href="{{ route('login') }}" 
                                class="text-slate-500 font-bold hover:text-primary transition-colors text-sm">
                                    Sign In
                                </a>

                                <a href="{{ route('register.customer') }}" 
                                class="text-primary font-bold hover:text-dark transition-colors text-sm">
                                    Daftar
                                </a>

                                <a href="{{ route('register') }}" 
                                class="bg-primary text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-lg shadow-indigo-100 hover:shadow-indigo-200 transition-all active:scale-95">
                                    Join Merchant
                                </a>

                            @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- HERO SECTION --}}
    <header class="hero-gradient pt-24 pb-16">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center">
                <span class="inline-block px-4 py-1.5 mb-6 text-[10px] font-black tracking-[0.2em] text-primary bg-indigo-50 rounded-full uppercase border border-indigo-100">
                    Premium UMKM Ecosystem
                </span>
                <h1 class="text-5xl md:text-6xl font-black text-dark-slate leading-tight mb-6">
                    Elevating Local <span class="gradient-text">Excellence.</span>
                </h1>
                <p class="text-slate-500 max-w-2xl mx-auto text-lg font-medium leading-relaxed">
                    Dukung pertumbuhan ekonomi kreatif dengan berbelanja langsung dari mitra UMKM pilihan. Kualitas premium, dampak sosial nyata.
                </p>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-6 py-12">
        <div class="flex flex-col lg:flex-row gap-12">
            
            {{-- ADVANCED FILTER SIDEBAR --}}
            <aside class="w-full lg:w-72 flex-shrink-0">
                <div class="bg-white rounded-4xl p-8 border border-slate-100 shadow-sm sticky top-28">
                    <form action="{{ route('landing') }}" method="GET" class="space-y-8">
                        @if(request('category'))
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Quick Search</label>
                            <div class="relative group">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Find products..." 
                                       class="w-full bg-slate-50 border-0 rounded-2xl py-4 pl-12 pr-4 text-sm focus:ring-2 focus:ring-primary outline-none transition-all">
                                <i class="fa fa-search absolute left-4 top-4.5 text-slate-300 group-focus-within:text-primary transition-colors"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Price Range</label>
                            <div class="space-y-3">
                                <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min (Rp)" 
                                       class="w-full bg-slate-50 border-0 rounded-2xl py-3.5 px-4 text-sm outline-none focus:ring-2 focus:ring-primary">
                                <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max (Rp)" 
                                       class="w-full bg-slate-50 border-0 rounded-2xl py-3.5 px-4 text-sm outline-none focus:ring-2 focus:ring-primary">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white py-4 rounded-2xl font-bold text-sm shadow-xl shadow-indigo-100 hover:brightness-110 transition-all active:scale-95">
                            Filter Results
                        </button>
                        
                        <a href="{{ route('landing') }}" class="block text-center text-xs font-bold text-slate-400 hover:text-red-500">
                            Clear Filters
                        </a>
                    </form>
                </div>
            </aside>

            {{-- PRODUCT GRID --}}
            <div class="flex-grow">
                {{-- CATEGORY TABS --}}
                <div class="flex gap-3 overflow-x-auto pb-8 scrollbar-hide">
                    <a href="{{ route('landing', request()->except('category')) }}" 
                       class="px-8 py-3 rounded-2xl text-[11px] font-bold uppercase tracking-wider transition-all {{ !request('category') ? 'bg-primary text-white shadow-xl shadow-indigo-100' : 'bg-white text-slate-400 border border-slate-100 hover:border-primary' }}">
                       All Products
                    </a>
                    @foreach($categories as $cat)
                        <a href="{{ route('landing', array_merge(request()->all(), ['category' => $cat->slug])) }}" 
                           class="px-8 py-3 rounded-2xl text-[11px] font-bold uppercase tracking-wider transition-all {{ request('category') == $cat->slug ? 'bg-primary text-white shadow-xl shadow-indigo-100' : 'bg-white text-slate-400 border border-slate-100 hover:border-primary' }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>

                {{-- GRID --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-8">
                    @forelse($products as $product)
                        <div class="product-card bg-white rounded-4xl border border-slate-50 overflow-hidden flex flex-col group shadow-sm hover:shadow-2xl hover:shadow-slate-200/50">
                            <div class="relative p-4">
                                <div class="relative overflow-hidden rounded-3xl aspect-[4/3] bg-slate-100">
                                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition duration-700 ease-in-out"
                                         onerror="this.src='https://placehold.co/600x400?text=Premium+Product';">
                                    <div class="absolute top-4 left-4">
                                        <span class="glass px-3 py-1.5 rounded-xl text-[9px] font-black uppercase text-primary border border-white">
                                            {{ $product->category->name ?? 'Curated' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="px-8 pb-8 flex-1 flex flex-col">
                                <div class="mb-5">
                                    <h3 class="text-base font-bold text-dark-slate mb-1 line-clamp-1 leading-tight">{{ $product->name }}</h3>
                                    <div class="flex items-center text-[11px] text-slate-400 font-bold uppercase tracking-wide">
                                        <i class="fa fa-store-alt mr-2 text-primary"></i>
                                        <span>{{ $product->vendor->shop_name ?? 'Official Store' }}</span>
                                    </div>
                                </div>
                                
                                <div class="mt-auto flex justify-between items-center pt-5 border-t border-slate-50">
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5">Price</p>
                                        <p class="text-lg font-black text-dark-slate">Rp {{ number_format($product->price ?? $product->price_eceran, 0, ',', '.') }}</p>
                                    </div>
                                    <a href="{{ route('product.detail', $product->id) }}" 
                                       class="bg-primary text-white w-12 h-12 rounded-2xl flex items-center justify-center transition-all shadow-lg shadow-indigo-100 hover:rotate-12 active:scale-95">
                                        <i class="fa fa-arrow-right-long"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full py-32 text-center">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                                <i class="fa fa-search text-slate-200 text-3xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-dark-slate">No matches found</h3>
                            <p class="text-slate-400 mt-2">Try adjusting your search or filters.</p>
                        </div>
                    @endforelse
                </div>

                <div class="mt-16">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </main>

    {{-- FOOTER --}}
    <footer class="bg-dark-slate pt-24 pb-12 mt-20 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary opacity-[0.03] blur-[100px] -mr-48 -mt-48"></div>
        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            <a href="#" class="text-2xl font-black text-white tracking-tighter uppercase">
                Retail<span class="text-primary">Pro</span>
            </a>
            <p class="text-slate-500 mt-6 max-w-md mx-auto leading-relaxed text-sm font-medium">
                Sistem Informasi Ekosistem Digital Marketplace UMKM Binaan. Menghubungkan inovasi lokal dengan pasar global.
            </p>
            
            <div class="mt-20 pt-10 border-t border-slate-800/50 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-slate-600 text-[10px] font-black uppercase tracking-[0.3em]">
                    &copy; 2026 Architectural Design. All Rights Reserved.
                </p>
                <div class="px-6 py-2 bg-slate-800/50 rounded-full border border-slate-700/50">
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-0">
                        Engineered by <span class="text-white">SUPRIANTO OPICK</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>