@extends('layouts.app')

@section('title', 'Pesanan Masuk')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">📦 Pesanan Masuk</h3>
            <p class="text-muted small">Kelola pesanan dari pelanggan UMKM Anda.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 24px;">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr class="small text-uppercase">
                        <th>No. Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="fw-bold text-primary">{{ $order->order_number }}</td>
                        <td>{{ $order->user->name }}</td>
                        <td class="fw-bold">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                        <td>
                            @php
                                $statusBadge = [
                                    'pending' => 'bg-warning text-dark',
                                    'processing' => 'bg-info text-white',
                                    'shipped' => 'bg-primary text-white',
                                    'completed' => 'bg-success text-white',
                                    'cancelled' => 'bg-danger text-white'
                                ];
                            @endphp
                            <span class="badge {{ $statusBadge[$order->status] ?? 'bg-secondary' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('vendor.orders.show', $order->id) }}" class="btn btn-sm btn-light rounded-pill px-3 text-primary fw-bold">Detail</a>
                                
                                @if($order->status == 'pending')
                                <form action="{{ route('vendor.orders.updateStatus', $order->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="processing">
                                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3">Proses</button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-cart-flatbed fa-3x mb-3 opacity-25"></i>
                            <p class="mb-0">Belum ada pesanan masuk.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    </div>
</div>
@endsection