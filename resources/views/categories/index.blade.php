@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-1">🗂️ Manajemen Kategori</h2>
            <p class="text-secondary">Kelola pengelompokan produk retail Anda</p>
            
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-4 mb-0 mt-3">
                    ✅ {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-0 mt-3">
                    ❌ {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4">🆕 Tambah Kategori</h5>
                    
                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Nama Kategori</label>
                            <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                                   placeholder="Contoh: Minuman Dingin" style="border-radius: 12px; background-color: #f8fafc;" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" 
                                style="border-radius: 12px; background: linear-gradient(135deg, #6366f1, #4f46e5); border: none;">
                            💾 Simpan Kategori
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="alert bg-white border-0 shadow-sm p-4" style="border-radius: 20px;">
                <div class="d-flex">
                    <span class="fs-4 me-3">💡</span>
                    <small class="text-muted">Kategori membantu kamu mengelompokkan barang agar laporan penjualan lebih detail dan mudah dibaca.</small>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-0">
                    <div class="p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold text-dark mb-0">Daftar Kategori Terdaftar</h5>
                        <span class="badge bg-soft-primary text-primary px-3 py-2 rounded-pill" style="background: #eef2ff;">
                            Total: {{ $categories->count() }} Kategori
                        </span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 text-muted small fw-bold text-uppercase" style="width: 80px;">ID</th>
                                    <th class="py-3 text-muted small fw-bold text-uppercase">Nama Kategori</th>
                                    <th class="py-3 text-muted small fw-bold text-uppercase text-center">Jumlah Produk</th>
                                    <th class="pe-4 py-3 text-muted small fw-bold text-uppercase text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $cat)
                                <tr>
                                    <td class="ps-4 fw-bold text-secondary">#{{ $cat->id }}</td>
                                    <td>
                                        <div class="fw-bold text-dark">{{ $cat->name }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge rounded-pill bg-light text-dark border px-3">
                                            {{ $cat->products_count ?? 0 }} Item
                                        </span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Yakin mau hapus kategori ini, bro?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-3 shadow-sm border-0">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        Belum ada kategori. Silakan tambah di sebelah kiri.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection