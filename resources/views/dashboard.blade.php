@extends('layouts.app')

@section('title', 'Dashboard Toko')

@section('content')
<div class="container-fluid px-4">
    {{-- HEADER SECTION --}}
    <div class="row mb-5 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1">Halo, {{ Auth::user()->name }}! 👋</h2>
            <p class="text-secondary mb-0">Pantau performa <span class="text-primary fw-semibold">{{ Auth::user()->vendor->shop_name ?? 'Toko Anda' }}</span> hari ini.</p>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <a href="{{ route('pos.index') }}" class="btn btn-primary shadow-sm px-4 py-2" style="border-radius: 12px; font-weight: 600;">
                <i class="fa fa-cash-register me-2"></i> Transaksi Baru
            </a>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="row g-4 mb-5">
        {{-- OMZET --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 24px;">
                <div class="d-flex align-items-center mb-3">
                    <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-4 me-3">💰</div>
                    <small class="text-muted fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Omzet Hari Ini</small>
                </div>
                <h3 class="fw-bold mb-0 text-dark">Rp {{ number_format($stats['sales_today'] ?? 0, 0, ',', '.') }}</h3>
                <small class="text-muted mt-2">Total pendapatan kotor</small>
            </div>
        </div>

        {{-- TRANSAKSI --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 24px;">
                <div class="d-flex align-items-center mb-3">
                    <div class="p-3 bg-success bg-opacity-10 text-success rounded-4 me-3">🛒</div>
                    <small class="text-muted fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Total Transaksi</small>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $stats['transactions_count'] ?? 0 }} <span class="fs-6 fw-normal text-muted">Struk</span></h3>
                <small class="text-muted mt-2">Pesanan berhasil hari ini</small>
            </div>
        </div>

        {{-- PRODUK --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 24px;">
                <div class="d-flex align-items-center mb-3">
                    <div class="p-3 bg-info bg-opacity-10 text-info rounded-4 me-3">📦</div>
                    <small class="text-muted fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Produk Aktif</small>
                </div>
                <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_products'] ?? 0 }} <span class="fs-6 fw-normal text-muted">SKU</span></h3>
                <small class="text-muted mt-2">Total koleksi barang jualan</small>
            </div>
        </div>

        {{-- STOK KRITIS --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-4 h-100 border-start border-danger border-4" style="border-radius: 24px;">
                <div class="d-flex align-items-center mb-3">
                    <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-4 me-3">⚠️</div>
                    <small class="text-muted fw-bold small text-uppercase" style="letter-spacing: 0.5px;">Stok Kritis</small>
                </div>
                <h3 class="fw-bold mb-0 text-danger">{{ $stats['low_stock'] ?? 0 }} <span class="fs-6 fw-normal text-muted">Item</span></h3>
                <small class="text-muted mt-2">Perlu segera restock!</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- GRAFIK TREN PENJUALAN TOKO --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 24px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0 text-dark">📈 Grafik Penjualan (7 Hari Terakhir)</h5>
                    <span class="badge bg-light text-primary rounded-pill px-3 py-2 border">Omzet Toko</span>
                </div>
                <div style="height: 350px;">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- SIDEBAR: DAFTAR RESTOCK --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 d-flex flex-column" style="border-radius: 24px; min-height: 440px;">
                <div class="mb-4">
                    <h5 class="fw-bold text-dark mb-1">🚨 Perlu Restock</h5>
                    <p class="text-muted small">Daftar produk dengan stok di bawah batas minimum.</p>
                </div>

                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover align-middle">
                        <thead class="small text-muted text-uppercase">
                            <tr>
                                <th>Produk</th>
                                <th class="text-end">Sisa Stok</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts ?? [] as $item)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark small">{{ $item->name }}</div>
                                    <small class="text-muted text-capitalize">{{ $item->category->name ?? 'Tanpa Kategori' }}</small>
                                </td>
                                <td class="text-end">
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold">
                                        {{ $item->stock }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center py-5">
                                    <div class="fs-2 mb-2">✅</div>
                                    <p class="text-muted small mb-0">Semua stok aman terkendali.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary w-100 rounded-pill fw-bold py-2">
                        <i class="fa fa-boxes me-2"></i> Manajemen Stok
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT CHART JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Ambil data dari variabel salesTrend yang dikirim Controller
        const trendData = @json($salesTrend ?? []);
        const labels = trendData.map(item => item.date);
        const totals = trendData.map(item => item.total);

        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.3)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Omzet Penjualan',
                    data: totals,
                    borderColor: '#6366f1',
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: gradient,
                    pointRadius: 4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold' },
                        callbacks: {
                            label: function(context) {
                                return ' Omzet: Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [5, 5], color: '#f1f5f9', drawBorder: false },
                        ticks: {
                            color: '#94a3b8',
                            font: { size: 11 },
                            callback: function(value) {
                                if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'jt';
                                if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + 'rb';
                                return 'Rp ' + value;
                            }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8', font: { size: 11 } }
                    }
                }
            }
        });
    });
</script>
@endsection