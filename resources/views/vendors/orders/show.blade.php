@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="container-fluid px-4 py-4">
    {{-- BREADCRUMB & HEADER --}}
    <div class="mb-4 d-flex justify-content-between align-items-end">
        <div>
            <a href="{{ route('vendor.orders.index') }}" class="text-decoration-none text-muted small fw-bold">
                <i class="fa-solid fa-chevron-left me-1"></i> Kembali ke Daftar Pesanan
            </a>
            <h3 class="fw-bold text-dark mt-2 mb-0" style="letter-spacing: -1px;">
                Order <span class="text-primary">#{{ $order->order_number }}</span>
            </h3>
            <p class="text-muted small mb-0">Diterima pada: {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
        <button onclick="window.print()" class="btn btn-white shadow-sm rounded-pill px-4 border fw-bold small">
            <i class="fa fa-print me-2 text-primary"></i> Cetak Invoice
        </button>
    </div>

    <div class="row g-4">
        {{-- KIRI: DAFTAR PRODUK --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 28px;">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold mb-0 text-dark">Item Yang Dipesan</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="text-muted small text-uppercase fw-black" style="letter-spacing: 1px; font-size: 11px;">
                                <tr>
                                    <th class="border-0">Produk</th>
                                    <th class="text-center border-0">Harga Satuan</th>
                                    <th class="text-center border-0">Qty</th>
                                    <th class="text-end border-0">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="border-0 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="position-relative">
                                                <img src="{{ $item->product->image_url ?? 'https://placehold.co/100' }}" 
                                                     class="rounded-4 me-3 shadow-sm" width="60" height="60" style="object-fit: cover;">
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $item->product->name }}</div>
                                                <span class="badge bg-light text-muted fw-bold" style="font-size: 10px;">{{ $item->product->category->name ?? 'UMKM Product' }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center border-0 fw-medium">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td class="text-center border-0 text-muted fw-bold">{{ $item->quantity }}x</td>
                                    <td class="text-end border-0 fw-bold text-dark">
                                        Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="border-top">
                                <tr>
                                    <td colspan="3" class="text-end fw-bold py-4 text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1.5px;">Grand Total Pesanan:</td>
                                    <td class="text-end fw-extrabold py-4 fs-4 text-primary">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            {{-- TIMELINE PESANAN (OPSIONAL BIAR KEREN) --}}
            <div class="card border-0 shadow-sm" style="border-radius: 28px; background: #f8fafc;">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="p-3 bg-white rounded-circle shadow-sm me-3 text-primary">
                        <i class="fa fa-circle-info fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-1">Catatan Sistem</h6>
                        <p class="small text-muted mb-0 text-wrap">Pesanan ini dikelola secara otomatis melalui sistem Multi-Vendor Retail Pro. Pastikan stok fisik tersedia sebelum mengubah status ke "Proses".</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- KANAN: PELANGGAN & STATUS --}}
        <div class="col-lg-4">
            {{-- INFORMASI PELANGGAN --}}
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 28px;">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-4 text-dark text-uppercase small" style="letter-spacing: 1px;">Informasi Customer</h6>
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-indigo bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center me-3" style="width: 55px; height: 55px;">
                            <i class="fa-solid fa-user-tie fs-4"></i>
                        </div>
                        <div class="overflow-hidden">
                            <div class="fw-bold text-dark text-truncate">{{ $order->user->name ?? 'Pembeli Umum' }}</div>
                            <div class="small text-muted text-truncate">{{ $order->user->email ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="p-3 bg-light rounded-4">
                        <small class="text-muted d-block mb-1 text-uppercase fw-black" style="font-size: 9px; letter-spacing: 1px;">Alamat Kirim:</small>
                        <p class="small mb-0 text-dark fw-medium">{{ $order->shipping_address ?? 'Ambil di Toko / Sesuai Profil' }}</p>
                    </div>
                </div>
            </div>

            {{-- KONTROL STATUS (FITUR NO. 9) --}}
            <div class="card border-0 shadow-lg text-white" style="border-radius: 28px; background: #0f172a;">
                <div class="card-body p-4 text-center">
                    <h6 class="fw-bold mb-4 text-uppercase small opacity-75" style="letter-spacing: 1px;">Update Status Logistik</h6>
                    
                    {{-- Status Badge Dinamis --}}
                    <div class="mb-4">
                        @php
                            $badgeClass = match($order->status) {
                                'pending' => 'bg-warning text-dark',
                                'processing' => 'bg-info text-white',
                                'shipped' => 'bg-primary text-white',
                                'completed' => 'bg-success text-white',
                                'cancelled' => 'bg-danger text-white',
                                default => 'bg-secondary text-white'
                            };
                            $statusLabel = match($order->status) {
                                'pending' => 'MENUNGGU KONFIRMASI',
                                'processing' => 'SEDANG DIPROSES',
                                'shipped' => 'DALAM PENGIRIMAN',
                                'completed' => 'PESANAN SELESAI',
                                'cancelled' => 'DIBATALKAN',
                                default => strtoupper($order->status)
                            };
                        @endphp
                        <span class="badge {{ $badgeClass }} px-4 py-2 rounded-pill fw-black" style="font-size: 11px; letter-spacing: 1px;">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <form action="{{ route('vendor.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <div class="mb-3">
                            <select name="status" class="form-select border-0 rounded-4 py-3 px-4 fw-bold" style="background: rgba(255,255,255,0.1); color: white;">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }} class="text-dark">Menunggu Konfirmasi</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }} class="text-dark">Proses Pesanan</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }} class="text-dark">Kirim Barang</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }} class="text-dark">Selesai</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }} class="text-dark">Batalkan Pesanan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-4 py-3 fw-bold shadow-lg transition-all active:scale-95">
                            Update Progress Pesanan
                        </button>
                    </form>
                    
                    <div class="mt-4 pt-3 border-top border-white border-opacity-10">
                        <small class="opacity-50" style="font-size: 10px;">Developer: SUPRIANTO OPICK</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling khusus dropdown agar teks di dalam option tetap terbaca (hitam) */
    select option { background: white; color: black; }
    .bg-indigo { background-color: #6366f1; }
    @media print {
        .sidebar, .navbar, .btn, .breadcrumb, footer, .mobile-header { display: none !important; }
        .main-content { margin: 0 !important; padding: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #eee !important; }
    }
</style>
@endsection