<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Notifications\OrderNotification; // IMPORT UNTUK FITUR NO. 9

class CartController extends Controller
{
    /**
     * Tampilkan isi keranjang belanja user
     */
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())
                         ->with(['product.vendor'])
                         ->get();
                         
        return view('cart.index', compact('cartItems'));
    }

    /**
     * Tambah barang ke keranjang
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = Cart::where('user_id', Auth::id())
                    ->where('product_id', $request->product_id)
                    ->first();

        if ($cart) {
            $cart->update(['quantity' => $cart->quantity + $request->quantity]);
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambah ke keranjang!');
    }

    /**
     * Update jumlah barang di keranjang
     */
    public function update(Request $request, $id)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);
        
        $cart = Cart::where('user_id', Auth::id())->findOrFail($id);
        $cart->update(['quantity' => $request->quantity]);

        return back()->with('success', 'Jumlah berhasil diupdate.');
    }

    /**
     * PROSES CHECKOUT (BANTAI POIN 4 & 9)
     * Memisahkan pesanan per vendor & Kirim notifikasi ke vendor
     */
    public function checkout(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $userId)->with('product.vendor.user')->get();

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Keranjang belanja Anda kosong.');
        }

        try {
            DB::beginTransaction();

            // LOGIC POIN 4: Kelompokkan item berdasarkan vendor_id
            $groupedByVendor = $cartItems->groupBy(function($item) {
                return $item->product->vendor_id;
            });

            foreach ($groupedByVendor as $vendorId => $items) {
                $totalPrice = $items->sum(function($item) {
                    $price = $item->product->price ?? $item->product->price_eceran;
                    return $price * $item->quantity;
                });

                // 1. Buat Header Order per Vendor
                $order = Order::create([
                    'order_number' => 'INV-' . date('Ymd') . strtoupper(bin2hex(random_bytes(3))),
                    'user_id' => $userId,
                    'vendor_id' => $vendorId,
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                    'shipping_address' => $request->address ?? 'Ambil di Toko',
                ]);

                // 2. Simpan Detail Barang
                foreach ($items as $item) {
                    $unitPrice = $item->product->price ?? $item->product->price_eceran;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'price' => $unitPrice,
                    ]);

                    // 3. Potong Stok Produk
                    $product = $item->product;
                    if ($product->stock >= $item->quantity) {
                        $product->decrement('stock', $item->quantity);
                    } else {
                        throw new \Exception("Stok produk {$product->name} tidak mencukupi.");
                    }
                }

                // LOGIC POIN 9: Kirim Notifikasi ke Vendor bahwa ada pesanan baru
                $vendor = $items->first()->product->vendor;
                if ($vendor && $vendor->user) {
                    $vendor->user->notify(new OrderNotification([
                        'title' => 'Pesanan Baru Masuk! 🛒',
                        'messages' => 'Ada pesanan baru dengan nomor ' . $order->order_number,
                        'url' => route('vendor.orders.show', $order->id),
                        'icon' => 'fa-shopping-bag text-success'
                    ]));
                }
            }

            // 4. Bersihkan Keranjang
            Cart::where('user_id', $userId)->delete();

            DB::commit();

            return redirect()->route('customer.orders')->with('success', 'Checkout berhasil! Pesanan Anda telah dikirim ke para mitra UMKM.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->route('cart.index')->with('error', 'Gagal checkout: ' . $e->getMessage());
        }
    }

    /**
     * Hapus satu item dari keranjang
     */
    public function destroy($id)
    {
        Cart::where('user_id', Auth::id())->findOrFail($id)->delete();
        return back()->with('success', 'Item berhasil dihapus.');
    }

    /**
     * Tampilkan riwayat pesanan milik customer
     */
    public function myOrders()
    {
        $orders = Order::where('user_id', Auth::id())
                       ->with(['vendor', 'items.product'])
                       ->latest()
                       ->get();

        return view('cart.my_orders', compact('orders'));
    }
}