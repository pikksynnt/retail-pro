@extends('layouts.app')

@section('title', 'Tambah Produk Baru')

@section('content')
<style>
    .card { border: none; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); }
    .form-label { font-weight: 700; color: #475569; font-size: 0.8rem; margin-bottom: 8px; letter-spacing: 0.5px; }
    .form-control, .form-select {
        border-radius: 14px;
        padding: 12px 16px;
        border: 1.5px solid #e2e8f0;
        background-color: #f8fafc;
        transition: all 0.3s;
    }
    .form-control:focus, .form-select:focus {
        background-color: #fff;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
    .img-preview-container {
        width: 100%;
        height: 200px;
        border-radius: 20px;
        border: 2px dashed #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        background: #f8fafc;
        position: relative;
    }
    .img-preview-container img { width: 100%; height: 100%; object-fit: cover; }
    .btn-save {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        border: none;
        border-radius: 16px;
        padding: 16px;
        font-weight: 800;
        transition: 0.3s;
        letter-spacing: 1px;
    }
    .btn-save:hover { transform: translateY(-3px); box-shadow: 0 12px 24px rgba(99, 102, 241, 0.3); }
    .section-icon { width: 32px; height: 32px; background: #eef2ff; color: #6366f1; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; font-size: 0.9rem; }
</style>

<div class="container-fluid px-4 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-xl-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-extrabold text-dark mb-1">📦 Tambah Produk Baru</h2>
                    <p class="text-secondary mb-0">Publikasikan produk UMKM Anda ke ekosistem retail & marketplace.</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-white rounded-pill px-4 fw-bold border shadow-sm">
                    <i class="fa fa-times me-2"></i> Batal
                </a>
            </div>

            {{-- NOTIFIKASI ERROR (DEBUG) --}}
            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 16px;">
                    <div class="fw-bold mb-1"><i class="fa fa-exclamation-triangle me-2"></i>Gagal Menyimpan:</div>
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 16px;">
                    <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
                </div>
            @endif

            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card p-4 p-md-5 mb-4">
                            
                            {{-- BAGIAN PILIH VENDOR (HANYA UNTUK SUPER ADMIN) --}}
                            @if(Auth::user()->role == 'admin')
                            <div class="mb-5 p-4 bg-light rounded-4 border">
                                <h5 class="fw-bold text-dark mb-3"><i class="fa fa-user-shield me-2 text-primary"></i>Penempatan Produk</h5>
                                <label class="form-label text-uppercase">Pilih Mitra UMKM (Vendor)</label>
                                <select name="vendor_id" class="form-select @error('vendor_id') is-invalid @enderror" required>
                                    <option value="" selected disabled>-- Pilih Vendor Pemilik Produk --</option>
                                    @foreach($vendors as $v)
                                        <option value="{{ $v->id }}" {{ old('vendor_id') == $v->id ? 'selected' : '' }}>{{ $v->shop_name }} ({{ $v->user->name }})</option>
                                    @endforeach
                                </select>
                                @error('vendor_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            @endif

                            <div class="mb-5">
                                <h5 class="fw-bold text-dark mb-4"><span class="section-icon"><i class="fa fa-info"></i></span>Informasi Produk</h5>
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <label class="form-label text-uppercase">Barcode / SKU</label>
                                        <input type="text" name="barcode" class="form-control @error('barcode') is-invalid @enderror" placeholder="Scan atau ketik kode..." value="{{ old('barcode') }}" required autofocus>
                                        @error('barcode') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-uppercase">Nama Produk</label>
                                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Contoh: Keripik Tempe Pedas" value="{{ old('name') }}" required>
                                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-uppercase">Kategori</label>
                                        <input type="text" name="category_name" list="cat_list" class="form-control @error('category_name') is-invalid @enderror" placeholder="Ketik kategori produk..." required value="{{ old('category_name') }}">
                                        <datalist id="cat_list">
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->name }}">
                                            @endforeach
                                        </datalist>
                                        <small class="text-muted" style="font-size: 9px;">Ketik kategori baru atau pilih yang tersedia.</small>
                                        @error('category_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-uppercase">Satuan (Unit)</label>
                                        <input type="text" name="unit" class="form-control" placeholder="pcs, pack, kg..." value="{{ old('unit', 'pcs') }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-uppercase">Deskripsi Produk (Marketplace)</label>
                                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Jelaskan keunggulan produk Anda agar pembeli tertarik...">{{ old('description') }}</textarea>
                                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h5 class="fw-bold text-dark mb-4"><span class="section-icon"><i class="fa fa-tag"></i></span>Stok & Harga</h5>
                                <div class="row g-4">
                                    <div class="col-md-4">
                                        <label class="form-label text-uppercase">Stok Saat Ini</label>
                                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', 0) }}" min="0" required>
                                        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-uppercase">Stok Minimum</label>
                                        <input type="number" name="min_stock" class="form-control" value="{{ old('min_stock', 5) }}" min="1">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label text-uppercase text-primary">Harga Jual (Rp)</label>
                                        <div class="input-group">
                                            <span class="input-group-text border-0 bg-light">Rp</span>
                                            <input type="number" name="price_eceran" class="form-control @error('price_eceran') is-invalid @enderror" placeholder="0" value="{{ old('price_eceran') }}" required>
                                            @error('price_eceran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card p-4 h-100">
                            <h5 class="fw-bold text-dark mb-4"><span class="section-icon"><i class="fa fa-image"></i></span>Visual Produk</h5>
                            
                            <div class="img-preview-container mb-3" id="imagePreview">
                                <div class="text-center text-muted p-3">
                                    <i class="fa fa-cloud-upload-alt fs-1 mb-2"></i>
                                    <p class="small mb-0">Belum ada foto terpilih</p>
                                </div>
                            </div>

                           <label class="form-label text-uppercase">Upload Foto Utama</label>
                                <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" id="imageInput" accept="image/*">
                                @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror

                            <label class="form-label text-uppercase mt-4">URL Gambar Produk</label>
                                <input 
                                    type="text" 
                                    name="image_url" 
                                    class="form-control @error('image_url') is-invalid @enderror" 
                                    placeholder="https://i.ibb.co/contoh/gambar.jpg"
                                    value="{{ old('image_url') }}"
                                >

                                <small class="text-muted d-block mt-2">
                                    Untuk Vercel, pakai link gambar dari ImgBB/Postimages agar gambar muncul di landing page.
                                </small>
                                  @error('image_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            
                            <div class="mt-4 p-3 bg-light rounded-4">
                                <ul class="small text-muted mb-0 ps-3">
                                    <li>Format: JPG, PNG, WEBP</li>
                                    <li>Maximal ukuran: 2 MB</li>
                                    <li>Gunakan foto terang agar menarik</li>
                                </ul>
                            </div>

                            <div class="mt-auto pt-4">
                                <button type="submit" class="btn btn-primary btn-save w-100 text-white shadow">
                                    <i class="fa fa-save me-2"></i> SIMPAN PRODUK
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('imageInput').onchange = function (evt) {
        const [file] = this.files;
        if (file) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = `<img src="${URL.createObjectURL(file)}" alt="Preview" class="img-fluid rounded-4">`;
        }
    }
</script>
@endsection