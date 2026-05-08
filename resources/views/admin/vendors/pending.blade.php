@extends('layouts.app')

@section('title', 'Antrian Verifikasi Mitra UMKM')

@section('content')
<div class="container-fluid px-4 py-4" style="background-color: #f8fafc; min-height: 100vh;">
    {{-- TOP SECTION --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h2 class="fw-bold text-slate-800 mb-1">Antrian Verifikasi 🛡️</h2>
            <p class="text-muted mb-0">Otoritas verifikasi pendaftaran mitra UMKM baru dalam ekosistem <strong>Retail Pro</strong>.</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-4 shadow-sm border d-flex align-items-center gap-3">
            <div class="p-2 bg-warning bg-opacity-10 text-warning rounded-circle">
                <i class="fa fa-user-shield"></i>
            </div>
            <div>
                <small class="text-muted d-block small" style="font-size: 10px;">TOTAL ANTRIAN</small>
                <span class="fw-bold text-dark">{{ $vendors->count() }} Pendaftar</span>
            </div>
        </div>
    </div>

    {{-- ALERT NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 d-flex align-items-center animate-pulse">
            <i class="fa fa-check-circle me-3 fs-4"></i>
            <div>
                <span class="fw-bold d-block">Berhasil!</span>
                <small>{{ session('success') }}</small>
            </div>
        </div>
    @endif

    {{-- TABLE SECTION --}}
    <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 28px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-muted small uppercase" style="letter-spacing: 1px;">
                        <th class="ps-4 py-4">Tgl Daftar</th>
                        <th class="py-4">Info Toko & Dokumen</th>
                        <th class="py-4">Pemilik</th>
                        <th class="py-4">Kontak</th>
                        <th class="text-center py-4 pe-4">Aksi Strategis</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vendors as $v)
                    <tr class="transition-all">
                        <td class="ps-4">
                            <div class="bg-light rounded-3 p-2 text-center" style="width: fit-content;">
                                <span class="fw-bold text-dark d-block" style="font-size: 12px;">{{ $v->created_at->format('d') }}</span>
                                <small class="text-uppercase text-muted" style="font-size: 10px;">{{ $v->created_at->format('M Y') }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="p-3 bg-blue-50 text-blue-600 rounded-4 me-3 shadow-sm">
                                    <i class="fa fa-store fs-5"></i>
                                </div>
                                <div>
                                    <span class="fw-bold text-slate-800 d-block mb-1">{{ $v->shop_name }}</span>
                                    <a href="{{ asset('uploads/identitas/' . $v->identity_file) }}" target="_blank" class="text-decoration-none small fw-bold text-primary">
                                        <i class="fa fa-id-card me-1"></i> Lihat Dokumen KTP
                                    </a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name={{ $v->user->name }}&background=random" class="rounded-circle me-2" width="25">
                                <span class="text-slate-700 font-medium">{{ $v->user->name }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="p-1 bg-light rounded text-muted">
                                    <i class="fa fa-envelope" style="font-size: 10px;"></i>
                                </div>
                                <span class="text-slate-600 small">{{ $v->user->email }}</span>
                            </div>
                        </td>
                        <td class="text-center pe-4">
                            <div class="d-flex justify-content-center gap-2">
                                {{-- Tombol Reject --}}
                                <form action="{{ route('admin.vendors.destroy', $v->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 border-0" onclick="return confirm('Tolak pendaftaran ini?')">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </form>

                                {{-- Tombol Approve --}}
                                <form action="{{ route('admin.vendors.verify', $v->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-4 fw-bold shadow-sm" onclick="return confirm('Terima Mitra ini?')">
                                        <i class="fa fa-check-circle me-1"></i> Terima Mitra
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="py-5">
                                <img src="https://cdn-icons-png.flaticon.com/512/5038/5038590.png" width="100" class="opacity-25 mb-4" style="filter: grayscale(1);">
                                <h5 class="fw-bold text-slate-400">Tidak Ada Pendaftaran Baru</h5>
                                <p class="text-muted small">Semua antrian pendaftaran telah diproses.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .text-slate-800 { color: #1e293b; }
    .text-slate-700 { color: #334155; }
    .text-slate-600 { color: #475569; }
    .bg-blue-50 { background-color: #eff6ff; }
    .animate-pulse { animation: pulse 2s infinite; }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .7; }
    }
    .table tbody tr { transition: all 0.3s; }
    .table tbody tr:hover { background-color: #f8fafc; transform: scale(1.002); }
    .btn-success { background-color: #10b981; border: none; }
    .btn-success:hover { background-color: #059669; transform: translateY(-1px); }
</style>
@endsection