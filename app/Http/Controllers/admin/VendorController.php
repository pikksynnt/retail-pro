<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * FITUR 1: DAFTAR MITRA AKTIF
     * Menampilkan UMKM yang sudah diverifikasi.
     */
    public function index(Request $request)
    {
        $query = Vendor::with('user')->where('status', 'active');

        if ($request->filled('search')) {
            $query->where('shop_name', 'like', '%' . $request->search . '%')
                  ->orWhere('owner_name', 'like', '%' . $request->search . '%');
        }

        $vendors = $query->latest()->paginate(10);
        
        // Sesuaikan path view (tanpa 'admin.' karena folder vendors sudah di luar)
        return view('vendors.index', compact('vendors'));
    }

    /**
     * FITUR 2: ANTRIAN VERIFIKASI (PENDING)
     * Menampilkan UMKM yang baru daftar dan butuh ACC Admin.
     */
    public function pending(Request $request)
    {
        $query = Vendor::with('user')->where('status', 'pending');

        if ($request->filled('search')) {
            $query->where('shop_name', 'like', '%' . $request->search . '%');
        }

        $vendors = $query->latest()->paginate(10);
        
        return view('vendors.pending_list', compact('vendors'));
    }

    /**
     * FITUR 3: DETAIL VENDOR
     * Melihat profil lengkap UMKM sebelum di-ACC.
     */
    public function show($id)
    {
        $vendor = Vendor::with('user')->findOrFail($id);
        return view('vendors.show', compact('vendor'));
    }

    /**
     * ACTION: VERIFIKASI MITRA (APPROVE)
     */
    public function approve($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->update(['status' => 'active']);

        return redirect()->route('admin.vendors.index')
            ->with('success', 'Mitra UMKM berhasil diverifikasi dan sekarang aktif di katalog!');
    }

    /**
     * ACTION: TOLAK / HAPUS MITRA
     * Berguna jika data UMKM palsu atau tidak layak.
     */
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);
        
        // Optional: Hapus user terkait juga jika ingin bersih total
        // $vendor->user()->delete(); 
        
        $vendor->delete();

        return back()->with('success', 'Data mitra telah dihapus dari sistem.');
    }
}