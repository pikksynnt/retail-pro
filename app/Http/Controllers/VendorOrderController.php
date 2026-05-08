<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorOrderController extends Controller
{
    /**
     * Tampilkan semua pesanan yang masuk ke toko vendor ini
     */
    public function index(Request $request)
    {
        // Ambil data vendor dari user yang sedang login
        $vendor = Auth::user()->vendor;

        // Validasi jika user tidak memiliki data vendor
        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak. Anda bukan vendor aktif.');
        }

        // Query pesanan khusus milik vendor ini
        $query = Order::where('vendor_id', $vendor->id)->with('user');

        // Fitur Filter Status (misal: hanya tampilkan yang 'pending')
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Pagination 10 data per halaman
        $orders = $query->latest()->paginate(10);

        // RETURN VIEW: Mengarah ke resources/views/vendors/orders/index.blade.php
        return view('vendors.orders.index', compact('orders'));
    }

    /**
     * Lihat detail item di dalam satu pesanan
     */
    public function show($id)
    {
        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        // Security Check: Pastikan vendor hanya bisa melihat detail pesanannya sendiri
        $order = Order::where('vendor_id', $vendor->id)
                      ->with(['items.product', 'user'])
                      ->findOrFail($id);

        // RETURN VIEW: Mengarah ke resources/views/vendors/orders/show.blade.php
        return view('vendors.orders.show', compact('order'));
    }

    /**
     * Update Status Pesanan (Diproses, Dikirim, Selesai, Batal)
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi input status
        $request->validate([
            'status' => 'required|in:processing,shipped,completed,cancelled'
        ]);

        $vendor = Auth::user()->vendor;

        if (!$vendor) {
            return back()->with('error', 'Aksi tidak diizinkan.');
        }

        // Cari order dan pastikan milik vendor yang login
        $order = Order::where('vendor_id', $vendor->id)->findOrFail($id);

        // Update status di database
        $order->update([
            'status' => $request->status
        ]);

        // Mapping nama status untuk notifikasi yang lebih user-friendly
        $statusLabel = [
            'processing' => 'Diproses',
            'shipped'    => 'Dikirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan'
        ];

        return back()->with('success', 'Status pesanan berhasil diperbarui menjadi: ' . $statusLabel[$request->status]);
    }
}