<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} — Retail Pro Marketplace</title>
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
                        '5xl': '3rem',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .glass { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); }
        .product-image-container { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
        .product-image-container:hover { transform: scale(1.02); }
        .gradient-text { background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>
</head>
<body class="antialiased">

    {{-- NAVIGATION --}}
    <nav class="glass sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="group flex items-center gap-2 text-slate-500 font-bold text-sm transition-all hover:text-primary">
                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:-translate-x-1 transition-transform">
                    <i class="fa fa-chevron-left text-[10px]"></i>
                </div>
                Back to Gallery
            </a>
            
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary rounded-xl flex items-center justify-center text-white shadow-lg shadow-indigo-100">
                    <i class="fa fa-bolt-lightning text-sm"></i>
                </div>
                <span class="text-xl font-extrabold tracking-tighter text-slate-dark uppercase">Retail<span class="text-primary">Pro</span></span>
            </div>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('cart.index') }}" class="relative w-11 h-11 flex items-center justify-center bg-white rounded-2xl border border-slate-100 text-slate-600 hover:text-primary transition-colors shadow-sm">
                        <i class="fa-solid fa-bag-shopping text-lg"></i>
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-12 lg:py-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 lg:gap-24 items-start">
            
            {{-- LEFT: PRODUCT VISUAL --}}
            <div class="product-image-container sticky top-32">
                <div class="rounded-5xl overflow-hidden bg-white shadow-2xl shadow-indigo-100/50 border border-white">
                    <img src="{{ asset($product->image_url) }}" alt="{{ $product->name }}" 
                         class="w-full aspect-square object-cover"
                         onerror="this.src='https://placehold.co/800x800?text=Premium+Product';">
                </div>
                {{-- Floating Badge --}}
                <div class="absolute -bottom-6 -right-6 glass p-6 rounded-4xl border border-white shadow-xl flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-500 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-emerald-100">
                        <i class="fa fa-check-double text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Stock Status</p>
                        <p class="text-sm font-black text-slate-dark">Ready to Dispatch</p>
                    </div>
                </div>
            </div>

            {{-- RIGHT: PRODUCT SPECIFICATIONS --}}
            <div class="flex flex-col">
                <div class="mb-10">
                    <div class="flex items-center gap-2 mb-6">
                        <span class="bg-indigo-50 text-primary px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] border border-indigo-100">
                            {{ $product->category->name ?? 'Curated Selection' }}
                        </span>
                        <div class="h-px w-8 bg-slate-200"></div>
                    </div>
                    
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-dark mb-6 leading-[1.1] tracking-tight">
                        {{ $product->name }}
                    </h1>
                    
                    {{-- MERCHANT BRANDING (POIN NO. 1) --}}
                    <div class="group flex items-center gap-5 p-5 bg-white rounded-4xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow mb-10">
                        <div class="w-14 h-14 bg-slate-dark rounded-2xl flex items-center justify-center text-white shadow-lg transition-transform group-hover:rotate-6">
                            <i class="fa fa-store-alt text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Official Merchant</p>
                            <h4 class="font-extrabold text-slate-dark text-lg">{{ $product->vendor->shop_name }}</h4>
                        </div>
                        <div class="ml-auto hidden sm:block">
                            <span class="flex items-center gap-2 bg-emerald-50 text-emerald-600 px-4 py-2 rounded-2xl text-[10px] font-black uppercase border border-emerald-100">
                                <i class="fa fa-shield-check"></i> Verified
                            </span>
                        </div>
                    </div>

                    <div class="flex items-baseline gap-3 mb-12">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] self-center mb-1">Investment</p>
                        <h3 class="text-5xl font-black text-slate-dark tracking-tighter">
                            <span class="text-2xl font-bold text-primary mr-1">Rp</span>{{ number_format($product->price ?? $product->price_eceran, 0, ',', '.') }}
                        </h3>
                    </div>

                    <div class="space-y-4 mb-12">
                        <h5 class="text-slate-900 font-black text-xs uppercase tracking-widest flex items-center gap-2">
                            <i class="fa fa-align-left text-primary"></i> Information Detail
                        </h5>
                        <p class="text-slate-500 leading-relaxed font-medium">
                            {{ $product->description ?? 'No specific technical description provided for this curated item.' }}
                        </p>
                    </div>

                    {{-- PURCHASE ACTION --}}
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-32">
                                <input type="number" name="quantity" value="1" min="1" 
                                       class="w-full h-16 bg-white border border-slate-100 rounded-2xl text-center font-black text-lg focus:ring-4 focus:ring-indigo-50 outline-none shadow-sm">
                            </div>
                            <div class="flex-grow">
                                <button type="submit" class="w-full h-16 bg-slate-dark text-white rounded-2xl font-black uppercase tracking-[0.2em] shadow-2xl shadow-slate-200 hover:brightness-125 transition-all flex items-center justify-center gap-4 active:scale-95">
                                    <i class="fa-solid fa-cart-plus text-lg"></i> Add to Collection
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- RELATED PRODUCTS --}}
        @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        <section class="mt-32 pt-20 border-t border-slate-100">
            <div class="flex justify-between items-end mb-16">
                <div>
                    <h2 class="text-3xl font-black text-slate-dark tracking-tight mb-2">Related Showcase</h2>
                    <p class="text-slate-400 font-medium">Curated selections from our local premium partners.</p>
                </div>
                <a href="{{ route('landing') }}" class="group text-primary font-black text-xs uppercase tracking-widest flex items-center gap-2">
                    Browse All <i class="fa fa-arrow-right-long group-hover:translate-x-2 transition-transform"></i>
                </a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($relatedProducts as $related)
                    <div class="group bg-white rounded-4xl p-4 shadow-sm border border-slate-50 hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-500">
                        <a href="{{ route('product.detail', $related->id) }}" class="block overflow-hidden rounded-3xl aspect-square mb-6">
                            <img src="{{ asset($related->image_url) }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                        </a>
                        <div class="px-2 pb-2">
                            <h4 class="font-bold text-slate-dark text-sm mb-2 line-clamp-1 group-hover:text-primary transition-colors">{{ $related->name }}</h4>
                            <p class="text-primary font-black text-base">Rp {{ number_format($related->price ?? $related->price_eceran, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
        @endif
    </main>

    {{-- PREMIUM FOOTER --}}
    <footer class="bg-slate-dark pt-20 pb-12 mt-20 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 relative z-10 text-center">
            <h4 class="text-white font-black text-xl tracking-tighter mb-6 uppercase">Retail<span class="text-primary">Pro</span></h4>
            <p class="text-slate-500 text-xs font-medium max-w-lg mx-auto leading-loose mb-16">
                Platform ekosistem digital terintegrasi untuk akselerasi pertumbuhan pasar UMKM lokal menuju standar pasar global.
            </p>
            
            <div class="pt-10 border-t border-slate-800/50 flex flex-col md:flex-row justify-between items-center gap-6">
                <p class="text-slate-600 text-[9px] font-black uppercase tracking-[0.3em]">
                    &copy; 2026 Architectural System Integration. All Rights Reserved.
                </p>
                {{-- IDENTITAS SUPRIANTO OPICK --}}
                <div class="px-6 py-2 bg-white/5 rounded-full border border-white/5">
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-0">
                        Engineered by <span class="text-white">SUPRIANTO OPICK</span>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Cart Updated',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 1500,
                background: '#0f172a',
                color: '#ffffff',
                borderRadius: '24px'
            });
        @endif
    </script>
</body>
</html>