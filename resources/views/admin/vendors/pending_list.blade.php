@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 py-4">
    <h3 class="fw-bold mb-4">⏳ Antrian Verifikasi Mitra</h3>
    
    <div class="card border-0 shadow-sm p-4" style="border-radius: 20px;">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Nama Toko</th>
                    <th>Pemilik</th>
                    <th>Tanggal Daftar</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendors as $v)
                <tr>
                    <td>{{ $v->shop_name }}</td>
                    <td>{{ $v->user->name }}</td>
                    <td>{{ $v->created_at->format('d/m/Y') }}</td>
                    <td class="text-end">
                        <form action="{{ route('admin.vendors.approve', $v->id) }}" method="POST">
                            @csrf @method('PATCH')
                            <button class="btn btn-success btn-sm rounded-pill px-3">Verifikasi Mitra</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection