<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Menampilkan daftar kategori milik vendor yang sedang login.
     */
    public function index(Request $request)
    {
        $vendorId = Auth::user()->vendor->id;
        
        // Query dasar: filter berdasarkan vendor_id
        $query = Category::where('vendor_id', $vendorId)->withCount('products');

        // Fitur Search (Opsional tapi berguna buat skripsi)
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Menyimpan kategori baru ke database (Private per Vendor).
     */
    public function store(Request $request)
    {
        $vendorId = Auth::user()->vendor->id;

        // Validasi: Nama wajib diisi, dan unik HANYA di dalam list vendor tersebut
        $request->validate([
            'name' => [
                'required',
                'max:255',
                // Logika: Boleh kembar dengan vendor lain, tapi gak boleh kembar di toko sendiri
                Rule::unique('categories')->where(function ($query) use ($vendorId) {
                    return $query->where('vendor_id', $vendorId);
                }),
            ],
        ]);

        Category::create([
            'vendor_id' => $vendorId,
            'name'      => $request->name,
            // Slug otomatis dibuat di Model Category yang kita edit tadi
        ]);

        return redirect()->back()->with('success', 'Kategori baru berhasil ditambahkan!');
    }

    /**
     * Menghapus kategori (Hanya jika milik sendiri dan kosong).
     */
    public function destroy($id)
    {
        $vendorId = Auth::user()->vendor->id;

        // Cari kategori yang ID-nya cocok DAN milik vendor ini (mencegah bypass URL)
        $category = Category::where('vendor_id', $vendorId)->findOrFail($id);

        // Cek apakah ada produk yang masih menggunakan kategori ini
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Gagal! Kategori tidak bisa dihapus karena masih ada produk di dalamnya.');
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus!');
    }
}