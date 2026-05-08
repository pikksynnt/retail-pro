<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PosController extends Controller
{
    // 1. Menampilkan Halaman Kasir
    public function index()
    {
        // Pastikan user punya vendor sebelum buka kasir
        if (!Auth::user()->vendor) {
            return redirect()->route('dashboard')->with('error', 'Akun Anda belum terhubung dengan Vendor/Mitra.');
        }
        return view('pos.index');
    }

    // 2. API Cari Produk berdasarkan Barcode (Khusus Milik Vendor yang Login)
    public function findProduct($barcode)
    {
        $vendorId = Auth::user()->vendor->id;

        $product = Product::where('barcode', $barcode)
                          ->where('vendor_id', $vendorId) // Pengaman agar tidak nyasar ke vendor lain
                          ->first();

        if ($product) {
            if ($product->stock <= 0) {
                return response()->json(['success' => false, 'message' => 'Stok produk habis!'], 400);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price_eceran, 
                    'stock' => $product->stock,
                    'image' => $product->image_url // Tambahkan image biar UI makin cakep
                ]
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Barang tidak terdaftar di toko Anda!'], 404);
    }

    // 3. API Cari Member berdasarkan Kode
    public function findMember($code)
    {
        $member = Member::where('member_code', $code)->first();

        if ($member) {
            return response()->json([
                'success' => true,
                'data' => $member
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Member tidak ditemukan!'], 404);
    }

    // 4. Simpan Transaksi
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'items' => 'required|array',
            'cash' => 'required|numeric',
            'total_price' => 'required|numeric',
        ]);

        $vendorId = Auth::user()->vendor->id;

        DB::beginTransaction();
        try {
            // A. Simpan data utama transaksi
            $transaction = Transaction::create([
                'vendor_id'      => $vendorId, // WAJIB ada vendor_id
                'invoice_number' => 'INV-' . Carbon::now()->format('YmdHis') . '-' . Auth::id(),
                'user_id'        => Auth::id(),
                'member_id'      => $request->member_id,
                'total_price'    => $request->total_price,
                'cash'           => $request->cash,
                'change'         => $request->cash - $request->total_price,
            ]);

            // B. Simpan Detail Belanja & Potong Stok
            foreach ($request->items as $item) {
                $product = Product::where('id', $item['id'])
                                  ->where('vendor_id', $vendorId)
                                  ->firstOrFail();

                if ($product->stock < $item['qty']) {
                    throw new \Exception("Stok {$product->name} tidak mencukupi!");
                }

                // Masuk ke TransactionDetail (Pastikan relasinya sudah ada di model Transaction)
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $product->id,
                    'qty'            => $item['qty'],
                    'price'          => $item['price'],
                    'subtotal'       => $item['qty'] * $item['price'],
                ]);

                // POTONG STOK
                $product->decrement('stock', $item['qty']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi Berhasil!',
                'transaction_id' => $transaction->id
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    // 5. Cetak Struk
    public function printReceipt($id)
    {
        $transaction = Transaction::with(['details.product', 'user', 'member'])
                                  ->where('vendor_id', Auth::user()->vendor->id)
                                  ->findOrFail($id);

        return view('pos.receipt', compact('transaction'));
    }

    // 6. Laporan Penjualan (Filter Vendor & Tanggal)
    public function report(Request $request)
    {
        $vendorId = Auth::user()->vendor->id;
        $start = $request->get('start_date', date('Y-m-d'));
        $end = $request->get('end_date', date('Y-m-d'));

        $transactions = Transaction::with(['user', 'member'])
            ->where('vendor_id', $vendorId)
            ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->orderBy('created_at', 'desc')
            ->get();

        $total_omzet = $transactions->sum('total_price');

        return view('pos.report', compact('transactions', 'total_omzet', 'start', 'end'));
    }
}