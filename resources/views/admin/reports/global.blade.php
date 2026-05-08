@extends('layouts.app')

@section('title', 'Analitik Global - Premium Insight')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #f8fafc; min-height: 100vh;">
    {{-- HEADER SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -1px;">Command Center Global 🛰️</h2>
            <p class="text-secondary mb-0 fw-medium">Real-time data monitoring dari seluruh ekosistem mitra UMKM.</p>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.location.reload()" class="btn btn-white shadow-sm rounded-pill px-3 border border-light">
                <i class="fa fa-sync-alt text-primary small"></i>
            </button>
            <button onclick="window.print()" class="btn btn-dark shadow-lg rounded-pill px-4 border-0" style="background: #0f172a;">
                <i class="fa fa-file-export me-2 small"></i> Export Laporan
            </button>
        </div>
    </div>

    {{-- TOP STATS CARDS --}}
    <div class="row g-4 mb-5">
        {{-- Card: Total Omset (GTV PLATFORM) --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-lg overflow-hidden h-100" 
                 style="border-radius: 30px; background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%); position: relative;">
                {{-- Decorative Glow --}}
                <div style="position: absolute; top: -20px; right: -20px; width: 120px; height: 120px; background: rgba(99, 102, 241, 0.25); filter: blur(50px); border-radius: 50%;"></div>
                
                <div class="card-body p-4 text-white position-relative" style="z-index: 2;">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="p-2 bg-white bg-opacity-10 rounded-3 border border-white border-opacity-10">
                            <i class="fa fa-wallet fs-5 text-info"></i>
                        </div>
                        
                        <span class="badge rounded-pill px-3 py-2" 
                              style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: #ffffff !important; font-size: 10px; letter-spacing: 1px; font-weight: 800; text-shadow: 0 1px 2px rgba(0,0,0,0.5);">
                            <i class="fa fa-circle text-success me-1 small animate-pulse"></i> GTV PLATFORM
                        </span>
                    </div>
                    
                    <p class="mb-1 text-white-50 small fw-bold text-uppercase" style="letter-spacing: 1.5px;">Global Revenue</p>
                    <h2 class="fw-extrabold mb-0" style="font-size: 2.2rem; color: #ffffff;">
                        <span class="fs-4 fw-normal text-white-50">Rp</span> {{ number_format($totalOmset, 0, ',', '.') }}
                    </h2>
                    
                    <div class="mt-4 pt-3 border-top border-white border-opacity-10">
                        <small class="text-white-50 fw-medium">Akumulasi pendapatan selesai otomatis</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Total Transaksi --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 30px; background: white; border: 1px solid #eef2f7;">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="p-3 bg-primary bg-opacity-10 rounded-4 text-primary">
                        <i class="fa fa-receipt fs-4"></i>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 fw-bold">Live Traffic</span>
                    </div>
                </div>
                <p class="text-muted fw-bold small text-uppercase mb-1" style="letter-spacing: 1px;">Total Transaksi</p>
                <h2 class="fw-extrabold text-dark mb-0" style="font-size: 2rem;">{{ number_format($totalTransactions) }} <span class="fs-6 fw-normal text-muted">Order</span></h2>
                <div class="mt-auto pt-3">
                    <div class="progress" style="height: 6px; border-radius: 10px; background: #f1f5f9;">
                        <div class="progress-bar bg-primary shadow-sm" style="width: 100%; border-radius: 10px;"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Vendor Aktif --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 30px; background: white; border: 1px solid #eef2f7;">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="p-3 bg-success bg-opacity-10 rounded-4 text-success">
                        <i class="fa fa-store fs-4"></i>
                    </div>
                </div>
                <p class="text-muted fw-bold small text-uppercase mb-1" style="letter-spacing: 1px;">Ekosistem Mitra</p>
                <h2 class="fw-extrabold text-dark mb-0" style="font-size: 2rem;">{{ $totalVendors }} <span class="fs-6 fw-normal text-muted">Unit Toko</span></h2>
                <p class="mb-0 mt-2 small text-success fw-bold">
                    <i class="fa fa-check-circle me-1"></i> Terverifikasi Sistem
                </p>
            </div>
        </div>
    </div>

    {{-- CHART SECTION --}}
    <div class="row g-4 mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 30px; border: 1px solid #eef2f7;">
                <div class="card-header bg-white border-0 p-4 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0 text-dark">Visualisasi Tren GTV (7 Hari Terakhir)</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-light rounded-pill px-3 fw-bold" type="button">
                                Weekly Insight
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div style="height: 380px; width: 100%; position: relative;">
                        <canvas id="globalSalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLES SECTION --}}
    <div class="row g-4">
        {{-- Performa Vendor --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 30px; border: 1px solid #eef2f7;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fa fa-crown me-2 text-warning"></i>Leaderboard Vendor</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="text-muted small">
                                <tr>
                                    <th class="border-0 ps-0 py-3" style="letter-spacing: 1px;">NAMA TOKO</th>
                                    <th class="border-0 text-end py-3" style="letter-spacing: 1px;">REVENUE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vendorPerformance as $perf)
                                <tr>
                                    <td class="border-0 ps-0">
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center me-3 shadow-sm" style="width: 42px; height: 42px;">
                                                <span class="fw-bold text-primary small">{{ strtoupper(substr($perf->shop_name, 0, 1)) }}</span>
                                            </div>
                                            <span class="fw-bold text-dark">{{ $perf->shop_name }}</span>
                                        </div>
                                    </td>
                                    <td class="border-0 text-end">
                                        <span class="fw-extrabold text-primary" style="font-size: 1.1rem;">Rp {{ number_format($perf->total_sales, 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Kategori Terlaris --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 30px; border: 1px solid #eef2f7;">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4 text-dark"><i class="fa fa-fire me-2 text-danger"></i>Hot Categories</h5>
                    <div class="list-group list-group-flush">
                        @forelse($topCategories as $cat)
                        <div class="list-group-item px-0 py-3 border-0 d-flex justify-content-between align-items-center bg-transparent">
                            <div class="d-flex align-items-center">
                                <div class="p-2 bg-warning bg-opacity-10 text-warning rounded-3 me-3">
                                    <i class="fa fa-layer-group small"></i>
                                </div>
                                <span class="fw-bold text-dark">{{ $cat->name }}</span>
                            </div>
                            <span class="badge rounded-pill bg-white text-dark border px-3 py-2 fw-bold shadow-sm">
                                {{ number_format($cat->total_qty) }} <small class="text-muted ms-1">Unit</small>
                            </span>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <p class="text-muted fw-medium">No sales data recorded yet.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT DIBAWAH SECTION UNTUK FIX GRAFIK --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('globalSalesChart').getContext('2d');
        const salesData = @json($salesTrend);

        console.log("Suprianto Opick Debug - Global Data:", salesData);

        if (salesData.length > 0) {
            let gradient = ctx.createLinearGradient(0, 0, 0, 350);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.4)');
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: salesData.map(item => item.date),
                    datasets: [{
                        label: 'Global Revenue',
                        data: salesData.map(item => item.total),
                        borderColor: '#6366f1',
                        borderWidth: 4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#6366f1',
                        pointBorderWidth: 3,
                        pointRadius: 6,
                        pointHoverRadius: 9,
                        fill: true,
                        backgroundColor: gradient,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            padding: 15,
                            cornerRadius: 12,
                            callbacks: {
                                label: function(context) {
                                    return ' Revenue: Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#64748b', font: { weight: '600' } } },
                        y: {
                            beginAtZero: true,
                            grid: { borderDash: [5, 5], color: '#e2e8f0', drawBorder: false },
                            ticks: {
                                color: '#64748b',
                                font: { weight: '600' },
                                callback: function(value) {
                                    if (value >= 1000000) return 'Rp ' + (value/1000000).toFixed(1) + 'jt';
                                    if (value >= 1000) return 'Rp ' + (value/1000).toFixed(0) + 'rb';
                                    return 'Rp ' + value;
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>

<style>
    .card { transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1); }
    .card:hover { transform: translateY(-8px); box-shadow: 0 25px 50px -12px rgba(0,0,0,0.08) !important; }
    .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }
</style>
@endsection