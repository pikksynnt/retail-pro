@extends('layouts.app')

@section('content')
<style>
    /* Premium Table Styling */
    .card { border: none; border-radius: 28px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); overflow: hidden; background: #fff; }
    .table thead th { 
        background-color: #f8fafc; 
        color: #475569; 
        text-transform: uppercase; 
        font-size: 0.75rem; 
        letter-spacing: 1.2px; 
        border: none;
        padding: 22px 15px;
        font-weight: 800;
    }
    .table tbody td { padding: 20px 15px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .product-name { font-weight: 800; color: #0f172a; font-size: 1rem; margin-bottom: 2px; }
    .barcode-badge { background: #f1f5f9; color: #64748b; font-family: 'Monaco', monospace; font-weight: 700; padding: 4px 10px; border-radius: 8px; font-size: 0.7rem; }
    
    /* Action Buttons */
    .btn-action { width: 40px; height: 40px; display: inline-flex; align-items: center; justify-content: center; border-radius: 14px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); text-decoration: none; border: none; }
    .btn-edit { background-color: #eef2ff; color: #4f46e5; }
    .btn-delete { background-color: #fff1f2; color: #e11d48; }
    .btn-edit:hover { background-color: #4f46e5; color: white; transform: translateY(-3px) rotate(8deg); }
    .btn-delete:hover { background-color: #e11d48; color: white; transform: translateY(-3px) rotate(-8deg); }
    
    /* Image Handling */
    .img-product-table { width: 60px; height: 60px; border-radius: 18px; object-fit: cover; box-shadow: 0 8px 15px rgba(0,0,0,0.08); border: 2px solid #fff; }
    .no-img-table { width: 60px; height: 60px; border-radius: 18px; background: #f8fafc; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-size: 1.5rem; border: 1px dashed #e2e8f0; }
    
    /* Stock Indicators */
    .stock-pill { padding: 6px 14px; border-radius: 12px; font-weight: 800; font-size: 0.8rem; display: inline-flex; align-items: center; }
    .stock-dot { width: 8px; height: 8px; border-radius: 50%; margin-right: 8px; animation: pulse 2s infinite; }

    @keyframes pulse {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 0, 0, 0.2); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(0, 0, 0, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(0, 0, 0, 0); }
    }
</style>

<div class="container-fluid px-4 py-4">
    {{-- HEADER & SEARCH --}}
    <div class="row mb-5 align-items-center">
        <div class="col-lg-6">
            <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -1px;">Katalog Produk 📦</h2>
            <p class="text-secondary mb-0 fw-medium">Manajemen inventori global sistem <span class="text-primary fw-bold">Retail Pro</span>.</p>
        </div>
        <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
            <div class="d-flex gap-3 justify-content-lg-end">
                <div class="input-group shadow-sm border-0 rounded-pill px-3 bg-white" style="max-width: 300px;">
                    <span class="input-group-text bg-transparent border-0"><i class="fa fa-search text-muted"></i></span>
                    <input type="text" class="form-control border-0 bg-transparent py-2" placeholder="Cari produk..." id="productSearch">
                </div>
                <a href="{{ route('products.create') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-lg border-0 d-flex align-items-center" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                    <i class="fa fa-plus-circle me-2 fs-5"></i> Tambah Produk
                </a>
            </div>
        </div>
    </div>

    {{-- ALERT BERHASIL --}}
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm alert-dismissible fade show mb-4 py-3" role="alert" style="border-radius: 20px; background-color: #f0fdf4; color: #166534;">
            <div class="d-flex align-items-center">
                <div class="p-2 bg-success bg-opacity-10 rounded-circle me-3">
                    <i class="fa-solid fa-check"></i>
                </div>
                <div>
                    <strong class="d-block">Beres Bro!</strong>
                    <span class="small">{{ session('success') }}</span>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle" id="productTable">
                <thead>
                    <tr>
                        <th class="ps-4 text-center">Preview</th>
                        <th>Info Produk</th>
                        @if(Auth::user()->role == 'admin')
                            <th>Pemilik (Mitra)</th>
                        @endif
                        <th>Kategori</th>
                        <th>Stok Saat Ini</th>
                        <th>Harga Eceran</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="ps-4 text-center">
                            @if($product->image_url)
                                <img src="{{ $product->image_url }}" 
                                     class="img-product-table" 
                                     alt="{{ $product->name }}"
                                     onerror="this.onerror=null;this.src='https://placehold.co/200x200?text=No+Image';">
                            @else
                                <div class="no-img-table"><i class="fa-solid fa-image opacity-50"></i></div>
                            @endif
                        </td>
                        <td>
                            <div class="product-name">{{ $product->name }}</div>
                            <span class="barcode-badge"><i class="fa fa-barcode me-1"></i>{{ $product->barcode }}</span>
                        </td>
                        @if(Auth::user()->role == 'admin')
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="p-2 bg-indigo bg-opacity-10 text-indigo rounded-circle me-2" style="font-size: 0.7rem;">
                                        <i class="fa fa-store"></i>
                                    </div>
                                    <span class="small fw-bold text-dark">{{ $product->vendor->shop_name ?? 'Tanpa Nama' }}</span>
                                </div>
                            </td>
                        @endif
                        <td>
                            <span class="badge rounded-pill px-3 py-2 fw-bold" style="background-color: #f8fafc; color: #64748b; border: 1px solid #e2e8f0;">
                                <i class="fa fa-tag me-1 text-muted"></i> {{ $product->category->name ?? 'Umum' }}
                            </span>
                        </td>
                        <td>
                            @php 
                                $min = $product->min_stock ?? 5; 
                                if($product->stock <= $min) {
                                    $color = 'danger'; $label = 'Kritis'; $bg = '#fef2f2';
                                } elseif($product->stock <= ($min + 10)) {
                                    $color = 'warning'; $label = 'Menipis'; $bg = '#fffbeb';
                                } else {
                                    $color = 'success'; $label = 'Aman'; $bg = '#f0fdf4';
                                }
                            @endphp
                            <div class="stock-pill" style="background-color: {{ $bg }}; color: var(--bs-{{ $color }});">
                                <span class="stock-dot bg-{{ $color }}"></span>
                                {{ $product->stock }} {{ $product->unit ?? 'pcs' }}
                            </div>
                            <div class="mt-1">
                                <small class="text-muted fw-bold" style="font-size: 9px; text-transform: uppercase;">{{ $label }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="fw-extrabold text-dark fs-6">Rp {{ number_format($product->price_eceran, 0, ',', '.') }}</div>
                            <small class="text-muted" style="font-size: 10px;">Harga per unit</small>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('products.edit', $product->id) }}" class="btn-action btn-edit" title="Edit Produk">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-action btn-delete btn-confirm-delete" title="Hapus Produk">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->role == 'admin' ? '7' : '6' }}" class="text-center py-5">
                            <div class="py-5">
                                <img src="https://illustrations.popsy.co/amber/box.svg" style="height: 200px;" class="mb-4 opacity-75">
                                <h4 class="text-dark fw-bold">Belum Ada Produk Nih...</h4>
                                <p class="text-secondary mb-4 mx-auto" style="max-width: 400px;">Ayo mulai bangun katalog tokomu biar makin banyak pembeli yang mampir ke Marketplace.</p>
                                <a href="{{ route('products.create') }}" class="btn btn-primary rounded-pill px-5 py-3 fw-bold shadow-lg border-0" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
                                    Tambah Produk Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // FITUR: Search Bar Realtime
    $(document).ready(function(){
        $("#productSearch").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#productTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });

        // SweetAlert2 Konfirmasi Hapus
        $('.btn-confirm-delete').on('click', function(e) {
            let form = $(this).closest('form');
            Swal.fire({
                title: 'Hapus Produk?',
                text: "Data produk yang dihapus nggak bisa balik lagi lho!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Saja!',
                cancelButtonText: 'Batal',
                border: 'none',
                borderRadius: '20px'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            })
        });
    });
</script>
@endpush
@endsection