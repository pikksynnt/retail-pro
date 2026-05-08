@extends('layouts.app')

@section('title', 'Manajemen Mitra UMKM')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark mb-1">Daftar Mitra UMKM 👋</h2>
            <p class="text-secondary mb-0">Kelola dan verifikasi identitas merchant untuk menjaga keamanan platform.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <span class="badge bg-white text-primary border px-3 py-2 rounded-pill shadow-sm">
                <i class="fa fa-users me-1"></i> Total: {{ $vendors->count() }} Mitra
            </span>
        </div>
    </div>

    {{-- ALERT NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <i class="fa fa-exclamation-triangle me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 24px;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th class="ps-3 border-0">Toko & Pemilik</th>
                            <th class="border-0">Kontak & NIK</th>
                            <th class="border-0 text-center">Dokumen KTP</th>
                            <th class="border-0">Status</th>
                            <th class="text-end pe-3 border-0">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vendors as $vendor)
                        <tr>
                            <td class="ps-3">
                                <div class="d-flex align-items-center">
                                    <div class="p-2 bg-primary bg-opacity-10 text-primary rounded-3 me-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                        <i class="fa fa-store fs-5"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark text-capitalize">{{ $vendor->shop_name }}</div>
                                        <small class="text-muted">{{ $vendor->user->name ?? 'User Tidak Ditemukan' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-bold text-dark">{{ $vendor->user->email ?? '-' }}</div>
                                <div class="small text-secondary"><i class="fa fa-id-card me-1 small"></i>{{ $vendor->identity_number }}</div>
                            </td>
                            <td class="text-center">
                                @if($vendor->identity_file)
                                    <a href="{{ asset('uploads/identitas/' . $vendor->identity_file) }}" target="_blank" class="text-decoration-none">
                                        <div class="d-inline-flex align-items-center p-2 px-3 rounded-pill border bg-light hover-shadow transition-all" style="cursor: pointer;">
                                            <i class="fa fa-file-image text-danger me-2"></i>
                                            <small class="fw-bold text-dark">Buka KTP</small>
                                        </div>
                                    </a>
                                @else
                                    <span class="text-danger small"><i class="fa fa-times-circle me-1"></i>Kosong</span>
                                @endif
                            </td>
                            <td>
                                @if($vendor->status == 'active')
                                    <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;">
                                        <i class="fa fa-check-circle me-1"></i>Terverifikasi
                                    </span>
                                @elseif($vendor->status == 'rejected')
                                    <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;">
                                        <i class="fa fa-times-circle me-1"></i>Ditolak
                                    </span>
                                @else
                                    <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;">
                                        <i class="fa fa-clock me-1"></i>Pending
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-3">
                                <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                    @if($vendor->status == 'pending')
                                    {{-- FIX: Form Verifikasi dengan method PATCH --}}
                                    <form action="{{ route('admin.vendors.verify', $vendor->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PATCH') {{-- WAJIB ADA karena Route lu pake PATCH --}}
                                        <button type="submit" class="btn btn-sm btn-primary px-3 py-2 border-0" title="Verifikasi Mitra">
                                            <i class="fa fa-check me-1"></i>Lolos
                                        </button>
                                    </form>
                                    @endif
                                    
                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('admin.vendors.destroy', $vendor->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus mitra ini? Akun user terkait juga akan dihapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger px-3 py-2 border-0" title="Hapus Data">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="opacity-50 mb-3">
                                    <i class="fa fa-folder-open fa-3x text-muted"></i>
                                </div>
                                <p class="text-muted mb-0">Belum ada pengajuan mitra UMKM baru.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-shadow:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background-color: #fff !important;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
</style>
@endsection