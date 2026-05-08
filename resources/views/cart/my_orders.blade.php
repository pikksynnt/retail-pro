<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase History — Retail Pro Ecosystem</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#6366f1',
                        'slate-dark': '#0f172a',
                    },
                    borderRadius: {
                        '4xl': '2.5rem',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        .order-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .order-card:hover { transform: translateY(-4px); box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); }
        .star-rating i { cursor: pointer; transition: all 0.2s; color: #e2e8f0; }
        .star-rating i.active { color: #fbbf24; transform: scale(1.1); }
        .glass-modal { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); }
    </style>
</head>
<body class="antialiased text-slate-700">

    {{-- NAVIGATION --}}
    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-5xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="{{ route('landing') }}" class="group flex items-center gap-2 text-slate-500 font-bold text-sm transition-all hover:text-primary">
                <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center group-hover:-translate-x-1 transition-transform">
                    <i class="fa fa-chevron-left text-[10px]"></i>
                </div>
                Back to Market
            </a>
            <div class="text-center">
                <h1 class="text-lg font-black text-slate-dark tracking-tight">Purchase <span class="text-primary">History</span></h1>
                <p class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Order Management System</p>
            </div>
            <div class="w-32 hidden md:block"></div>
        </div>
    </nav>

    <main class="max-w-4xl mx-auto px-6 py-12">
        {{-- ALERTS --}}
        @if(session('success'))
            <script>Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", borderRadius: '24px', confirmButtonColor: '#6366f1' })</script>
        @endif

        <div class="space-y-8">
            @forelse($orders as $order)
                <div class="order-card bg-white rounded-4xl border border-slate-50 overflow-hidden shadow-sm">
                    {{-- Header Order --}}
                    <div class="p-6 border-b border-slate-50 flex flex-wrap justify-between items-center bg-slate-50/30 gap-4">
                        <div class="flex items-center gap-4">
                            <div class="bg-white p-3 rounded-2xl shadow-sm border border-slate-100 text-primary">
                                <i class="fa fa-file-invoice text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Order Index</p>
                                <h3 class="font-extrabold text-slate-dark tracking-tight">#{{ $order->order_number }}</h3>
                            </div>
                        </div>
                        <div>
                            @php
                                $statusMap = [
                                    'pending' => ['bg-amber-50', 'text-amber-600', 'Menunggu'],
                                    'processing' => ['bg-blue-50', 'text-blue-600', 'Diproses'],
                                    'shipped' => ['bg-indigo-50', 'text-indigo-600', 'Dikirim'],
                                    'completed' => ['bg-emerald-50', 'text-emerald-600', 'Selesai'],
                                    'cancelled' => ['bg-rose-50', 'text-rose-600', 'Batal']
                                ];
                                $st = $statusMap[$order->status] ?? ['bg-slate-100', 'text-slate-500', 'Unknown'];
                            @endphp
                            <span class="{{ $st[0] }} {{ $st[1] }} px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-wider border border-current border-opacity-10">
                                {{ $st[2] }}
                            </span>
                        </div>
                    </div>

                    {{-- Isi Order --}}
                    <div class="p-8">
                        <div class="flex items-center gap-2 mb-6 opacity-60">
                            <i class="fa fa-store text-primary text-[10px]"></i>
                            <span class="font-black text-[11px] uppercase tracking-widest text-slate-400">Sold by: {{ $order->vendor->shop_name }}</span>
                        </div>
                        
                        <div class="space-y-6">
                            @foreach($order->items as $item)
                                <div class="flex justify-between items-center group">
                                    <div class="flex items-center gap-5">
                                        <div class="relative overflow-hidden rounded-2xl w-16 h-16 border border-slate-100 shadow-sm">
                                            <img src="{{ asset($item->product->image_url) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110 duration-500">
                                        </div>
                                        <div>
                                            <p class="text-sm font-extrabold text-slate-dark">{{ $item->product->name }}</p>
                                            <p class="text-xs text-slate-400 font-bold">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                    
                                    {{-- REVIEW ACTION --}}
                                    @if($order->status == 'completed')
                                        @php
                                            $hasReview = \App\Models\Review::where('order_id', $order->id)
                                                                         ->where('product_id', $item->product->id)
                                                                         ->exists();
                                        @endphp

                                        @if($hasReview)
                                            <div class="flex items-center gap-2 text-emerald-500 bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-100">
                                                <i class="fa fa-circle-check text-[10px]"></i>
                                                <span class="text-[9px] font-black uppercase tracking-widest">Reviewed</span>
                                            </div>
                                        @else
                                            <button onclick="openReviewModal('{{ $order->id }}', '{{ $item->product->id }}', '{{ $item->product->name }}')" 
                                                    class="text-[10px] font-black uppercase tracking-widest text-primary bg-indigo-50 px-5 py-2.5 rounded-xl hover:bg-primary hover:text-white transition-all shadow-sm">
                                                Review Now
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8 pt-8 border-t border-slate-50 flex justify-between items-end">
                            <div>
                                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">Settlement Date</p>
                                <p class="text-xs font-bold text-slate-500">{{ $order->created_at->format('d F Y') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest mb-1">Total Bill</p>
                                <p class="text-2xl font-black text-primary tracking-tighter">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-32 bg-white rounded-4xl border border-dashed border-slate-200">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200">
                        <i class="fa fa-box-open text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-dark">No orders found</h3>
                    <p class="text-slate-400 text-sm mt-2 mb-10">You haven't made any purchases yet.</p>
                    <a href="{{ route('landing') }}" class="bg-primary text-white px-8 py-3.5 rounded-2xl font-bold text-sm shadow-xl shadow-indigo-100 hover:brightness-110 transition-all">Start Exploring</a>
                </div>
            @endforelse
        </div>

        {{-- FOOTER BRANDING --}}
        <div class="mt-20 text-center opacity-30">
            <p class="text-[9px] font-black uppercase tracking-[0.4em] mb-1">Transaction Architecture Integrity</p>
            <p class="text-[10px] font-black text-slate-900 uppercase tracking-[0.1em]">Engineered by SUPRIANTO OPICK</p>
        </div>
    </main>

    {{-- MODAL REVIEW --}}
    <div id="reviewModal" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-[100] hidden items-center justify-center p-6">
        <div class="glass-modal bg-white rounded-4xl w-full max-w-md p-10 shadow-2xl transition-all transform scale-95 duration-300 border border-white">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-indigo-50 text-primary rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                    <i class="fa fa-star-half-stroke text-2xl"></i>
                </div>
                <h2 class="text-2xl font-black text-slate-dark tracking-tight">Share Experience</h2>
                <p id="modalProductName" class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-2"></p>
            </div>

            <form action="{{ route('review.store') }}" method="POST">
                @csrf
                <input type="hidden" name="order_id" id="modalOrderId">
                <input type="hidden" name="product_id" id="modalProductId">
                <input type="hidden" name="rating" id="ratingValue" value="5">

                {{-- Bintang Input --}}
                <div class="star-rating flex gap-4 text-3xl mb-10 justify-center">
                    <i class="fa fa-star active" data-index="1" onclick="setRating(1)"></i>
                    <i class="fa fa-star active" data-index="2" onclick="setRating(2)"></i>
                    <i class="fa fa-star active" data-index="3" onclick="setRating(3)"></i>
                    <i class="fa fa-star active" data-index="4" onclick="setRating(4)"></i>
                    <i class="fa fa-star active" data-index="5" onclick="setRating(5)"></i>
                </div>

                <div class="relative mb-8">
                    <textarea name="comment" rows="4" required
                              class="w-full bg-slate-50 border-0 rounded-3xl p-6 text-sm focus:ring-2 focus:ring-primary outline-none transition-all placeholder-slate-300 shadow-inner"
                              placeholder="Tell us what you think about this product..."></textarea>
                </div>

                <div class="flex gap-4 items-center">
                    <button type="button" onclick="closeModal()" class="flex-1 py-4 text-slate-400 font-bold text-xs uppercase tracking-widest hover:text-slate-600 transition">Cancel</button>
                    <button type="submit" class="flex-1 bg-slate-dark text-white py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-xl hover:brightness-125 transition-all active:scale-95">
                        Submit Review
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openReviewModal(orderId, productId, productName) {
            document.getElementById('modalOrderId').value = orderId;
            document.getElementById('modalProductId').value = productId;
            document.getElementById('modalProductName').innerText = productName;
            document.getElementById('reviewModal').classList.remove('hidden');
            document.getElementById('reviewModal').classList.add('flex');
            // Reset rating to 5 on open
            setRating(5);
        }

        function closeModal() {
            document.getElementById('reviewModal').classList.add('hidden');
            document.getElementById('reviewModal').classList.remove('flex');
        }

        function setRating(val) {
            document.getElementById('ratingValue').value = val;
            const stars = document.querySelectorAll('.star-rating i');
            stars.forEach((s, index) => {
                if(index < val) s.classList.add('active');
                else s.classList.remove('active');
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            let modal = document.getElementById('reviewModal');
            if (event.target == modal) closeModal();
        }
    </script>
</body>
</html>