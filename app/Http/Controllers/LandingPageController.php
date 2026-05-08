<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Menampilkan Katalog Produk Marketplace UMKM (Katalog Lanjutan)
     */
    public function index(Request $request)
    {
        // 1. Ambil semua kategori untuk navigasi
        $categories = Category::select('id', 'name', 'slug')->get();
        
        // 2. Inisialisasi Query dengan Proteksi Status Vendor (Hanya yang Active)
        $query = Product::with(['category', 'vendor'])
            ->whereHas('vendor', function($q) {
                $q->where('status', 'active');
            });

        // 3. Fitur Pencarian Pintar
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('vendor', function($v) use ($searchTerm) {
                      $v->where('shop_name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // 4. Filter Kategori
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category)
                  ->orWhere('id', $request->category);
            });
        }

        // 5. Filter Rentang Harga (KOREKSI: Menggunakan price_eceran sesuai DB lu)
        if ($request->filled('min_price')) {
            $query->where('price_eceran', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price_eceran', '<=', $request->max_price);
        }

        // 6. Fitur Sorting (Urutkan)
        switch ($request->sort) {
            case 'price_low':
                $query->orderBy('price_eceran', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price_eceran', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
                break;
        }

        // 7. Eksekusi Query dengan Pagination & withQueryString agar filter tetap nempel
        $products = $query->paginate(12)->withQueryString();

        return view('welcome', compact('products', 'categories'));
    }

    /**
     * Menampilkan Halaman Detail Produk
     */
    public function showProduct($id)
    {
        // 1. Ambil data produk utama (Pastikan vendor aktif)
        $product = Product::with(['vendor', 'category'])
            ->whereHas('vendor', function($q) {
                $q->where('status', 'active');
            })
            ->findOrFail($id);
        
        // 2. Logika Produk Terkait (Kategori sama, beda produk)
        $relatedProducts = Product::with(['vendor'])
            ->whereHas('vendor', function($q) {
                $q->where('status', 'active');
            })
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->inRandomOrder() 
            ->limit(4)
            ->get();

        // 3. Ambil Koleksi Lain dari Vendor yang Sama
        $vendorProducts = Product::where('vendor_id', $product->vendor_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts', 'vendorProducts'));
    }
}