<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    /**
     * Tampilkan Daftar Member (Multi-Vendor Friendly)
     */
    public function index(Request $request)
    {
        // Ambil vendor dari user yang login
        $vendor = Auth::user()->vendor;

        // Proteksi: Jika user belum punya profil vendor atau belum diverifikasi
        if (!$vendor) {
            return redirect()->route('dashboard')->with('error', 'Profil UMKM tidak ditemukan. Silahkan lengkapi profil Anda.');
        }

        // AMBIL DATA: Pastikan hanya mengambil member milik vendor_id ini
        $query = Member::where('vendor_id', $vendor->id);

        // Fitur Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('member_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Tampilkan yang terbaru daftar di paling atas
        $members = $query->latest()->paginate(10)->withQueryString();

        return view('members.index', compact('members'));
    }

    /**
     * Simpan Member Baru (Private Code per Vendor)
     */
    public function store(Request $request)
    {
        $vendor = Auth::user()->vendor;
        $vendorId = $vendor->id;

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'nullable', 'string', 'max:20',
                // Unik hanya di dalam lingkup vendor ini saja
                Rule::unique('members')->where(fn ($q) => $q->where('vendor_id', $vendorId)),
            ],
            'discount_percent' => 'required|numeric|min:0|max:100',
        ]);

        // LOGIKA AUTO-CODE: Kita hitung total member vendor ini untuk generate kode urut
        $count = Member::where('vendor_id', $vendorId)->count();
        $increment = $count + 1;
        $newCode = 'MBR-' . str_pad($increment, 4, '0', STR_PAD_LEFT);

        // Proteksi Global: Cek apakah kode ini sudah ada di seluruh sistem (karena kolom unik)
        while (Member::where('member_code', $newCode)->exists()) {
            $increment++;
            $newCode = 'MBR-' . str_pad($increment, 4, '0', STR_PAD_LEFT);
        }

        try {
            Member::create([
                'vendor_id'        => $vendorId,
                'member_code'      => $newCode,
                'name'             => $request->name,
                'phone'            => $request->phone,
                'discount_percent' => $request->discount_percent,
            ]);

            return redirect()->route('members.index')->with('success', "Member {$request->name} berhasil disimpan.");

        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Terjadi kesalahan: Kode Member sudah ada.');
        }
    }

    /**
     * Update Data Member
     */
    public function update(Request $request, $id)
    {
        $vendorId = Auth::user()->vendor->id;
        
        // FindOrFail dengan filter vendor_id agar vendor A tidak bisa edit member vendor B
        $member = Member::where('vendor_id', $vendorId)->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => [
                'nullable', 'string', 'max:20',
                Rule::unique('members')->where(fn ($q) => $q->where('vendor_id', $vendorId))->ignore($member->id),
            ],
            'discount_percent' => 'required|numeric|min:0|max:100',
        ]);

        $member->update($request->only(['name', 'phone', 'discount_percent']));

        return redirect()->route('members.index')->with('success', 'Data member berhasil diperbarui.');
    }

    /**
     * Hapus Member
     */
    public function destroy($id)
    {
        $vendorId = Auth::user()->vendor->id;
        
        // Pastikan hanya bisa menghapus milik sendiri
        $member = Member::where('vendor_id', $vendorId)->findOrFail($id);
        $member->delete();

        return redirect()->route('members.index')->with('success', 'Member berhasil dihapus.');
    }
}