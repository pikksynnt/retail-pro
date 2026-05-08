@extends('layouts.app')

@section('title', 'Global Market Analysis | Super Admin')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #f8fafc; min-height: 100vh;">
    {{-- TOP BAR --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-slate-800 mb-1" style="letter-spacing: -1px;">Global Report Analysis 📊</h2>
            <p class="text-muted mb-0">Analisis performa real-time ekosistem <strong>Retail Pro</strong>.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-white shadow-sm border rounded-pill px-4 py-2 fw-bold small" onclick="window.location.reload()">
                <i class="fa fa-sync-alt me-2 text-primary"></i> Refresh Data
            </button>
            <div class="bg-white shadow-sm border rounded-pill px-4 py-2 d-flex align-items-center">
                <div class="p-1 bg-success rounded-circle me-2 animate-pulse" style="width: 8px; height: 8px;"></div>
                <small class="fw-bold text-dark" style="font-size: 12px;">Live Updates Enabled</small>
            </div>
        </div>
    </div>

    {{-- KARTU STATISTIK UTAMA --}}
    <div class="row g-4 mb-5">
        {{-- Card: Total Omset (PREMIUM DARK DESIGN) --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-lg overflow-hidden h-100" 
                 style="border-radius: 24px; background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%); position: relative;">
                
                <div style="position: absolute; top: -20px; right: -20px; width: 100px; height: 100px; background: rgba(59, 130, 246, 0.2); filter: blur(40px); border-radius: 50%;"></div>

                <div class="card-body p-4 text-white position-relative" style="z-index: 2;">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div class="p-2 bg-white bg-opacity-10 rounded-3 border border-white border-opacity-10">
                            <i class="fa fa-wallet fs-5 text-info"></i>
                        </div>
                        
                        <span class="badge rounded-pill px-3 py-2" 
                              style="background: rgba(255, 255, 255, 0.15); border: 1px solid rgba(255, 255, 255, 0.3); color: #ffffff !important; font-size: 10px; letter-spacing: 0.8px; font-weight: 800; text-shadow: 0px 1px 2px rgba(0,0,0,0.5);">
                            <i class="fa fa-circle text-success me-1 animate-pulse" style="font-size: 7px;"></i> GTV PLATFORM
                        </span>
                    </div>
                    
                    <p class="mb-1 text-white-50 small fw-bold text-uppercase" style="letter-spacing: 1.2px;">Global Revenue</p>
                    <h3 class="fw-bold mb-0 text-white" style="font-size: 1.8rem;">
                        <span class="fs-5 fw-normal opacity-50">Rp</span> {{ number_format($totalOmset, 0, ',', '.') }}
                    </h3>
                    
                    <div class="mt-4 pt-2 border-top border-white border-opacity-10">
                        <small class="text-white-50 small fw-medium">Real-time Platform Revenue</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card: Total Transaksi --}}
        <div class="col-md-3">
            <a href="{{ route('admin.reports.global') }}" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm p-4 h-100 border-bottom-hover-primary bg-white" style="border-radius: 24px; transition: all 0.3s ease;">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-4">
                            <i class="fa fa-shopping-cart fs-4"></i>
                        </div>
                        <span class="text-muted small fw-bold">Active Orders</span>
                    </div>
                    <small class="text-muted fw-bold small text-uppercase" style="letter-spacing: 1px;">Total Transaksi</small>
                    <h3 class="fw-bold mb-0 text-dark">{{ number_format($totalTransactions) }} <span class="fs-6 fw-normal text-muted">Items</span></h3>
                    <p class="small text-primary mt-3 mb-0 fw-bold">Lihat Analisis →</p>
                </div>
            </a>
        </div>

        {{-- Card: Antrian Verifikasi --}}
        <div class="col-md-3">
            <a href="{{ route('admin.vendors.pending') }}" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm p-4 h-100 border-bottom-hover-warning bg-white" style="border-radius: 24px; transition: all 0.3s ease;">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-4">
                            <i class="fa fa-user-clock fs-4"></i>
                        </div>
                        @if($stats['pending_mitra'] > 0)
                            <span class="badge bg-danger rounded-pill pulse-red">{{ $stats['pending_mitra'] }} New</span>
                        @endif
                    </div>
                    <small class="text-muted fw-bold small text-uppercase" style="letter-spacing: 1px;">Antrian Mitra</small>
                    <h3 class="fw-bold mb-0 text-dark">{{ $stats['pending_mitra'] }} <span class="fs-6 fw-normal text-muted">Pendaftar</span></h3>
                    <p class="small text-warning mt-3 mb-0 fw-bold">Proses Verifikasi →</p>
                </div>
            </a>
        </div>

        {{-- Card: Toko Aktif --}}
        <div class="col-md-3">
            <a href="{{ route('admin.vendors.index') }}" class="text-decoration-none h-100 d-block">
                <div class="card border-0 shadow-sm p-4 h-100 border-bottom-hover-indigo bg-white" style="border-radius: 24px; transition: all 0.3s ease;">
                    <div class="d-flex justify-content-between mb-3">
                        <div class="p-3 bg-indigo bg-opacity-10 rounded-4 text-indigo">
                            <i class="fa fa-store fs-4"></i>
                        </div>
                    </div>
                    <small class="text-muted fw-bold small text-uppercase" style="letter-spacing: 1px;">Ekosistem UMKM</small>
                    <h3 class="fw-bold mb-0 text-dark">{{ $totalVendors }} <span class="fs-6 fw-normal text-muted">Toko Aktif</span></h3>
                    <p class="small text-indigo mt-3 mb-0 fw-bold">Kelola Toko →</p>
                </div>
            </a>
        </div>
    </div>

    {{-- CHART AREA --}}
    <div class="row g-4 mb-5">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm p-4 bg-white" style="border-radius: 28px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold text-slate-800 mb-0">Tren GTV Global (7 Hari Terakhir)</h5>
                    <button class="btn btn-sm btn-light border rounded-pill px-3 shadow-sm" onclick="window.print()">
                        <i class="fa fa-file-export me-1 text-muted"></i> Export Report
                    </button>
                </div>
                {{-- KONTRAINER GRAFIK --}}
                <div style="height: 380px; width: 100%; position: relative;">
                    <canvas id="adminSalesChart"></canvas>
                </div>
            </div>
        </div>

        {{-- TOP VENDORS --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm p-4 h-100 bg-white" style="border-radius: 28px;">
                <h5 class="fw-bold mb-4 small text-uppercase" style="letter-spacing: 1px;">Top Performance Mitra</h5>
                <div class="list-group list-group-flush">
                    @forelse($vendorPerformance as $v)
                    <div class="list-group-item px-0 border-0 mb-3 bg-transparent">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($v->shop_name) }}&background=random&color=fff" class="rounded-circle shadow-sm" width="45">
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 text-dark text-capitalize small">{{ $v->shop_name }}</h6>
                                <p class="text-muted mb-0" style="font-size: 11px;">Rp {{ number_format($v->total_sales, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill small px-3">{{ $v->orders_count }} Trans.</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5">
                        <i class="fa fa-chart-line text-muted opacity-25 mb-3 fs-1"></i>
                        <p class="text-muted mb-0">Data performa belum tersedia.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-slate-800 { color: #1e293b; }
    .text-indigo { color: #6366f1; }
    .btn-white { background: #fff; }
    .card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .card:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.05) !important; }
    
    .animate-pulse { animation: pulse 2s infinite; }
    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: .4; } 100% { opacity: 1; } }
    
    .pulse-red { animation: pulse-red 2s infinite; }
    @keyframes pulse-red { 
        0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
</style>

{{-- SCRIPT DIPINDAH KE SINI AGAR TIDAK TERGANTUNG LAYOUT --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const canvas = document.getElementById('adminSalesChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const rawData = @json($salesTrend);
        
        // Debug di console (F12)
        console.log("Suprianto Opick Debug - Data:", rawData);

        if (rawData.length === 0) {
            console.warn("Data GTV Kosong!");
            return;
        }

        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: rawData.map(item => item.date),
                datasets: [{
                    label: 'Revenue Platform',
                    data: rawData.map(item => item.total),
                    borderColor: '#3b82f6',
                    borderWidth: 4,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: gradient,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
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
                    y: { 
                        beginAtZero: true, 
                        grid: { borderDash: [5, 5], color: '#e2e8f0', drawBorder: false },
                        ticks: { 
                            color: '#64748b', 
                            font: { size: 11, weight: '600' },
                            callback: function(value) { return 'Rp ' + value.toLocaleString(); }
                        }
                    },
                    x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 11, weight: '600' } } }
                }
            }
        });
    });
</script>
@endsection