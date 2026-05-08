@extends('layouts.app')

@section('content')
<style>
    /* UI Elevating Styles */
    .card { border: none; border-radius: 24px; transition: all 0.3s ease; background: #ffffff; }
    .card-stats:hover { transform: translateY(-5px); box-shadow: 0 15px 30px -5px rgba(0,0,0,0.1) !important; }
    .stat-icon { width: 60px; height: 60px; border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 28px; }
    
    /* Table Styling */
    .table thead th { 
        background: #f8fafc; 
        color: #64748b; 
        font-size: 11px; 
        text-transform: uppercase; 
        letter-spacing: 1.5px; 
        border: none; 
        padding: 20px 15px;
    }
    .table tbody td { padding: 18px 15px; border-bottom: 1px solid #f1f5f9; }
    .invoice-badge { background: #f1f5f9; color: #475569; font-family: 'Monaco', 'Consolas', monospace; font-weight: 700; padding: 8px 14px; border-radius: 12px; font-size: 0.85rem; }
    
    /* Buttons */
    .btn-pill { border-radius: 14px; padding: 12px 24px; font-weight: 700; letter-spacing: 0.5px; transition: 0.3s; }
    .btn-filter { background: #1e293b; color: white; border: none; }
    .btn-filter:hover { background: #0f172a; color: white; transform: scale(1.02); }
    
    /* Print Customization */
    @media print {
        .no-print, .sidebar, .btn-pill, form, .btn-sm { display: none !important; }
        .container-fluid { width: 100% !important; padding: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #e2e8f0 !important; border-radius: 10px !important; }
        .main-content { margin-left: 0 !important; }
    }
</style>

<div class="container-fluid pb-5">
    <div class="row mb-5 align-items-center no-print">
        <div class="col-md-6">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3">
                    <span class="fs-2">📊</span>
                </div>
                <div>
                    <h2 class="fw-bold text-dark mb-1">Financial Report</h2>
                    <p class="text-secondary mb-0">Analisis pendapatan & performa retail Anda</p>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-md-end mt-3 mt-md-0">
            <button onclick="window.print()" class="btn btn-dark btn-pill shadow-lg border-0">
                <i class="fas fa-print me-2"></i> Eksport ke PDF / Cetak
            </button>
        </div>
    </div>

    <div class="card p-4 shadow-sm mb-5 no-print border-start border-primary border-5">
        <form action="{{ route('pos.report') }}" method="GET" class="row g-4 align-items-end">
            <div class="col-md-4">
                <label class="small fw-bold text-muted mb-2 tracking-wide">PERIODE AWAL</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="far fa-calendar-alt text-muted"></i></span>
                    <input type="date" name="start_date" class="form-control border-0 bg-light rounded-end-3" value="{{ $start }}">
                </div>
            </div>
            <div class="col-md-4">
                <label class="small fw-bold text-muted mb-2 tracking-wide">PERIODE AKHIR</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="far fa-calendar-check text-muted"></i></span>
                    <input type="date" name="end_date" class="form-control border-0 bg-light rounded-end-3" value="{{ $end }}">
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-filter btn-pill w-100 shadow">
                    🚀 Jalankan Analisis Data
                </button>
            </div>
        </form>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card card-stats p-4 shadow-sm border-0 h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary me-3">💵</div>
                    <span class="text-muted fw-bold small">TOTAL PENDAPATAN</span>
                </div>
                <h2 class="fw-bold mb-1 text-dark">Rp {{ number_format($total_omzet, 0, ',', '.') }}</h2>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stats p-4 shadow-sm border-0 h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-icon bg-success bg-opacity-10 text-success me-3">🧾</div>
                    <span class="text-muted fw-bold small">VOLUME TRANSAKSI</span>
                </div>
                <h2 class="fw-bold mb-1 text-dark">{{ $transactions->count() }} <small class="fs-6 fw-normal text-muted">Orders</small></h2>
                <p class="text-success small mb-0 mt-2"><i class="fas fa-arrow-up me-1"></i> Data Real-time</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-stats p-4 shadow-sm border-0 h-100">
                <div class="d-flex align-items-center mb-3">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning me-3">🏢</div>
                    <span class="text-muted fw-bold small">IDENTITAS VENDOR</span>
                </div>
                <h2 class="fw-bold mb-1 text-dark">{{ Auth::user()->vendor->shop_name ?? 'Mitra Retail' }}</h2>
                <p class="text-muted small mb-0 mt-2">Kasir: {{ Auth::user()->name }}</p>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0 overflow-hidden">
        <div class="card-header bg-white py-4 px-4 border-0 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark">Riwayat Penjualan Terperinci</h5>
            <span class="badge bg-light text-dark rounded-pill px-3 py-2 border">Total {{ $transactions->count() }} Data</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">🕒 WAKTU & TANGGAL</th>
                        <th>📄 NOMOR INVOICE</th>
                        <th>👤 PELANGGAN</th>
                        <th>💳 NOMINAL</th>
                        <th class="text-center no-print">🔍 DETAIL</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $t)
                    <tr>
                        <td class="ps-4">
                            <div class="fw-bold text-dark">{{ $t->created_at->format('H:i') }}</div>
                            <div class="text-muted" style="font-size: 0.75rem;">{{ $t->created_at->format('d M Y') }}</div>
                        </td>
                        <td><span class="invoice-badge">{{ $t->invoice_number }}</span></td>
                        <td>
                            @if($t->member)
                                <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2">
                                    💎 {{ $t->member->name }}
                                </span>
                            @else
                                <span class="text-muted small italic">Pelanggan Umum</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-bold text-primary">Rp {{ number_format($t->total_price, 0, ',', '.') }}</div>
                            <div class="text-muted" style="font-size: 0.7rem;">Tunai: Rp {{ number_format($t->cash, 0, ',', '.') }}</div>
                        </td>
                        <td class="text-center no-print">
                            <a href="{{ route('pos.print', $t->id) }}" target="_blank" class="btn btn-sm btn-light border rounded-pill px-3 fw-bold text-secondary">
                                <i class="fas fa-eye me-1"></i> Lihat Struk
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="py-4">
                                <img src="https://illustrations.popsy.co/amber/no-data.svg" style="height: 150px;" class="mb-4 opacity-75">
                                <h5 class="text-muted fw-bold">Belum ada transaksi ditemukan</h5>
                                <p class="text-muted small">Coba ubah filter tanggal untuk melihat data lainnya.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->count() > 0)
        <div class="card-footer bg-light border-0 py-3 px-4 no-print text-center">
            <small class="text-muted">Menampilkan semua transaksi dalam rentang tanggal yang dipilih.</small>
        </div>
        @endif
    </div>
</div>
@endsection