<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', Auth::id())->with('product')->get();
        return view('wishlist.index', compact('wishlists'));
    }

    public function store($productId)
    {
        // Cek kalau sudah ada, maka hapus (Toggle system)
        $exists = Wishlist::where('user_id', Auth::id())->where('product_id', $productId)->first();
        
        if ($exists) {
            $exists->delete();
            return back()->with('success', 'Produk dihapus dari wishlist.');
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $productId
        ]);

        return back()->with('success', 'Produk berhasil disimpan ke wishlist!');
    }
}