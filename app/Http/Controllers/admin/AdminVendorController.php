<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Import Notification agar fitur No. 9 jalan
use App\Notifications\OrderNotification; 

class AdminVendorController extends Controller
{
    /**
     * 1. DAFTAR MITRA AKTIF (Fitur No. 1)
     * Menampilkan UMKM yang sudah diverifikasi (Status: active)
     */
    public function index()
    {
        $vendors = Vendor::with('user')
            ->where('status', 'active')
            ->latest()
            ->get();
        
        return view('admin.vendors.index', compact('vendors'));
    }

    /**
     * 2. ANTRIAN VERIFIKASI (Fitur No. 1 & 8)
     * Menampilkan pendaftar baru yang statusnya 'pending'
     */
    public function pending()
    {
        $vendors = Vendor::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();
        
        return view('admin.vendors.pending', compact('vendors'));
    }

    /**
     * 3. VERIFIKASI VENDOR (APPROVE) + NOTIFIKASI (Fitur No. 9)
     */
    public function approve($id)
    {
        $vendor = Vendor::with('user')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Update status vendor jadi aktif
            $vendor->update([
                'status' => 'active'
            ]);

            // POIN 9: KIRIM NOTIFIKASI REAL-TIME (DATABASE)
            // Memberitahu vendor bahwa toko mereka sudah disetujui admin
            if ($vendor->user) {
                $vendor->user->notify(new OrderNotification($vendor));
            }

            DB::commit();
            return redirect()->route('admin.vendors.index')
                ->with('success', 'Mantap! Toko ' . $vendor->shop_name . ' resmi aktif. Notifikasi telah dikirim ke Vendor.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal verifikasi: ' . $e->getMessage());
        }
    }

    /**
     * 4. DETAIL VENDOR (Fitur No. 8)
     */
    public function show($id)
    {
        $vendor = Vendor::with(['user', 'products'])->findOrFail($id);
        return view('admin.vendors.show', compact('vendor'));
    }

    /**
     * 5. HAPUS / REJECT VENDOR (Atomic Transaction)
     */
    public function destroy($id)
    {
        $vendor = Vendor::findOrFail($id);

        DB::beginTransaction();
        try {
            $user = User::find($vendor->user_id);

            // Hapus data vendor
            $vendor->delete();

            // Hapus akun user agar email bisa digunakan daftar lagi jika ingin
            if ($user) {
                $user->delete();
            }

            DB::commit();
            return back()->with('success', 'Data vendor dan akun terkait berhasil dihapus permanen.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}