@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    {{-- BARIS HEADER & NOTIFIKASI --}}
    <div class="row mb-3">
        <div class="col-12">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm rounded-pill px-4">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm rounded-pill px-4">
                    <i class="fa fa-exclamation-circle me-2"></i> {{ session('error') }}
                </div>
            @endif
        </div>
    </div>

    <div class="row g-4">
        {{-- FORM TAMBAH MEMBER --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 24px;">
                <h5 class="fw-bold mb-4">➕ Tambah Member Baru</h5>
                <form action="{{ route('members.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control rounded-pill @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="Contoh: Budi Sudarsono">
                        @error('name') <div class="invalid-feedback ps-3">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Nomor HP</label>
                        <input type="text" name="phone" class="form-control rounded-pill @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="0812xxxx">
                        @error('phone') <div class="invalid-feedback ps-3">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold mb-1">Diskon Member (%)</label>
                        <input type="number" name="discount_percent" class="form-control rounded-pill" value="5" min="0" max="100" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 mt-2 shadow-sm">
                        Simpan Member
                    </button>
                </form>
            </div>
        </div>

        {{-- TABEL DAFTAR MEMBER --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 24px;">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">👥 Daftar Pelanggan Setia</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3 py-2">
                        Total: {{ $members->total() }}
                    </span>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr class="small text-uppercase text-secondary">
                                <th class="ps-3">Kode</th>
                                <th>Nama</th>
                                <th>HP</th>
                                <th>Diskon</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($members as $m)
                            <tr>
                                <td class="ps-3">
                                    <span class="badge bg-dark rounded-pill fw-normal">{{ $m->member_code }}</span>
                                </td>
                                <td class="fw-bold text-dark">{{ $m->name }}</td>
                                <td>{{ $m->phone ?? '-' }}</td>
                                <td>
                                    <span class="text-success fw-bold">{{ $m->discount_percent }}%</span>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('members.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus member ini?')">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light text-danger rounded-circle shadow-sm" style="width: 32px; height: 32px;">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <i class="fa fa-users fa-3x mb-3 opacity-25"></i>
                                    <p class="mb-0">Belum ada member yang terdaftar.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="mt-4">
                    {{ $members->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 