<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Menyimpan ulasan dari pembeli.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id'
        ]);

        // 2. Proteksi Logic (Bahan Sidang): 
        // Pastikan order tersebut milik user yang login dan statusnya sudah 'completed'
        $order = Order::where('id', $request->order_id)
                      ->where('user_id', Auth::id())
                      ->first();

        if (!$order || $order->status !== 'completed') {
            return back()->with('error', 'Anda hanya bisa memberikan ulasan pada pesanan yang sudah selesai.');
        }

        // 3. Cek apakah user sudah pernah kasih ulasan untuk produk di order ini
        // Biar nggak spam rating
        $existingReview = Review::where('order_id', $request->order_id)
                                ->where('product_id', $request->product_id)
                                ->first();

        if ($existingReview) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        // 4. Eksekusi Simpan
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'Terima kasih! Ulasan Anda sangat berarti bagi UMKM ini.');
    }
}