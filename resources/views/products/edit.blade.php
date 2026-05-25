@extends('layouts.app')

@section('content')
<style>
    body { background-color: #f8fafc; padding-bottom: 50px; }
    .card { border: none; border-radius: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.04); }
    .form-label { font-weight: 700; color: #475569; font-size: 0.85rem; margin-bottom: 8px; }
    .form-control, .form-select {
        border-radius: 14px;
        padding: 12px 16px;
        border: 1.5px solid #e2e8f0;
        background-color: #f8fafc;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        background-color: #fff;
        border-color: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
    }
    .btn-update {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        border-radius: 14px;
        padding: 16px;
        font-weight: 800;
        color: white;
        transition: 0.3s;
        width: 100%;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }
    .btn-update:hover { transform: translateY(-2px); color: white; opacity: 0.9; }
    .image-preview-wrapper {
        border: 2px dashed #e2e8f0;
        border-radius: 16px;
        padding: 15px;
        background: #fff;
    }
    .current-img {
        width: 120px;
        height: 100px;
        border-radius: 12px;
        object-fit: cover;
    }
</style>

<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-8">
            
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">✏️ Edit Produk</h2>
                    <p class="text-secondary mb-0">Perbarui informasi barang secara permanen</p>
                </div>
                <a href="{{ route('products.index') }}" class="btn btn-light rounded-pill px-4 fw-bold border">
                    ✕ Batal
                </a>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 16px;">
                    <div class="fw-bold mb-1 small">Gagal Memperbarui:</div>
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="card p-4 p-md-5 mb-5">
                    <div class="row g-4 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-uppercase">Barcode / SKU</label>
                            <input type="text" name="barcode" class="form-control" value="{{ old('barcode', $product->barcode) }}" required>
                            <small class="text-muted" style="font-size: 10px;">Barcode harus unik dan tidak boleh sama dengan barang lain.</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-uppercase">Nama Barang</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
                        </div>
                        
                        <div class="col-md-12">
                            <label class="form-label text-uppercase">Kategori Produk</label>
                            <input type="text" name="category_name" class="form-control" 
                                   value="{{ old('category_name', $product->category->name ?? '') }}" 
                                   placeholder="Ketik kategori baru atau pilih yang tersedia..."
                                   list="categoryList" required>
                            <datalist id="categoryList">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->name }}">
                                @endforeach
                            </datalist>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-uppercase">Foto Produk</label>

                        <div class="image-preview-wrapper">
                            <div class="d-flex align-items-center gap-4">
                                <div class="text-center">
                                    <img 
                                        src="{{ old('image_url', $product->image_url) ?: ($product->image ? asset('storage/products/' . $product->image) : 'https://via.placeholder.com/100?text=No+Img') }}" 
                                        class="current-img border shadow-sm" 
                                        id="previewImage"
                                        onerror="this.src='https://via.placeholder.com/100?text=No+Img'"
                                    >
                                    <div class="mt-1 text-muted" style="font-size: 9px;">PREVIEW</div>
                                </div>

                                <div class="flex-grow-1">
                                    <input type="file" name="image" class="form-control" id="imageInput">
                                    <small class="text-muted">Abaikan jika tidak ingin mengganti foto produk.</small>
                                </div>
                            </div>

                            <div class="mt-4">
                                <label class="form-label text-uppercase">URL Gambar Produk</label>
                                <input 
                                    type="text"
                                    name="image_url"
                                    class="form-control"
                                    placeholder="https://i.ibb.co/contoh/gambar.jpg"
                                    value="{{ old('image_url', $product->image_url ?? '') }}"
                                    id="imageUrlInput"
                                >
                                <small class="text-muted">
                                    Untuk Vercel, tempel direct link gambar dari ImgBB/Postimages agar muncul di landing page.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-uppercase">Deskripsi Produk (Marketplace)</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan detail produkmu di sini..." required>{{ old('description', $product->description) }}</textarea>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-6">
                            <label class="form-label text-uppercase">Stok Barang</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $product->stock) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-uppercase">Harga Jual (Rp)</label>
                            <input type="number" name="price_eceran" class="form-control" value="{{ old('price_eceran', $product->price_eceran) }}" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-update shadow">
                        💾 SIMPAN PERUBAHAN KE DATABASE
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const imageInput = document.getElementById('imageInput');
    const imageUrlInput = document.getElementById('imageUrlInput');
    const previewImage = document.getElementById('previewImage');

    imageInput.addEventListener('change', function () {
        const [file] = this.files;
        if (file) {
            previewImage.src = URL.createObjectURL(file);
        }
    });

    imageUrlInput.addEventListener('input', function () {
        if (this.value.trim() !== '') {
            previewImage.src = this.value.trim();
        }
    });
</script>
@endsection