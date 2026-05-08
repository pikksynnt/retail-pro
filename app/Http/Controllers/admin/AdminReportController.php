<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Vendor;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    /**
     * Menampilkan Laporan Statistik Global Marketplace UMKM
     */
    public function globalReport()
    {
        // 1. Statistik Dasar (Gunakan prefix tabel agar tidak ambigu)
        $totalOmset = Order::where('orders.status', 'completed')->sum('total_price');
        $totalTransactions = Order::count();
        $totalVendors = Vendor::where('vendors.status', 'active')->count();
        $totalProducts = Product::count();

        // 2. Data Tren Penjualan 7 Hari Terakhir
        $salesTrend = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_price) as total')
            )
            ->where('orders.status', 'completed')
            ->where('orders.created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 3. Performa Penjualan per Vendor (Top Omzet)
        $vendorPerformance = Vendor::withCount(['orders' => function($q) {
                $q->where('orders.status', 'completed');
            }])
            ->addSelect(['total_sales' => Order::selectRaw('IFNULL(sum(total_price), 0)')
                ->whereColumn('vendor_id', 'vendors.id')
                ->where('orders.status', 'completed')
            ])
            ->orderByDesc('total_sales')
            ->get();

        // 4. Kategori Paling Laris (Top 5)
        // Tambahkan prefix 'categories.name' untuk menghindari error ambiguitas
        $topCategories = Category::select(
                'categories.name', 
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->join('products', 'categories.id', '=', 'products.category_id')
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        // 5. Produk Paling Laris (Top 5)
        $topProducts = Product::with(['vendor'])
            ->withCount(['orderItems as total_sold' => function($q) {
                $q->select(DB::raw('IFNULL(sum(quantity), 0)'))
                  ->join('orders', 'order_items.order_id', '=', 'orders.id')
                  ->where('orders.status', 'completed');
            }])
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        // Mengembalikan View dengan data yang sudah diproses
        return view('admin.reports.global', compact(
            'totalOmset', 
            'salesTrend', 
            'vendorPerformance', 
            'topCategories',
            'topProducts',
            'totalTransactions',
            'totalVendors',
            'totalProducts'
        ));
    }
}