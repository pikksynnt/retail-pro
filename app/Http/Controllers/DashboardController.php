<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $lastSevenDays = Carbon::now()->subDays(6); // Ambil 7 hari terakhir termasuk hari ini

        // =========================================================================
        // 1. DASHBOARD SUPER ADMIN
        // =========================================================================
        if ($user->role === 'admin') {
            $totalOmset = Order::where('status', 'completed')->sum('total_price');
            $totalTransactions = Order::count();
            $totalVendors = Vendor::where('status', 'active')->count();
            $totalProducts = Product::count();

            $stats = [
                'pending_mitra' => Vendor::where('status', 'pending')->count(),
                'active_mitra' => $totalVendors,
                'total_users' => User::count(),
                'sales_today' => Order::whereDate('created_at', $today)->where('status', 'completed')->sum('total_price') ?? 0,
            ];

            $pendingList = Vendor::with('user')->where('status', 'pending')->latest()->take(5)->get();
            
            // FIX GRAFIK ADMIN: Pastikan tanggal berurutan dan format string tanggal benar
            $salesTrend = Order::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total_price) as total')
                )
                ->where('status', 'completed')
                ->where('created_at', '>=', $lastSevenDays)
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();

            $vendorPerformance = Vendor::withCount(['orders' => function($q) {
                    $q->where('status', 'completed');
                }])
                ->addSelect(['total_sales' => Order::selectRaw('sum(total_price)')
                    ->whereColumn('vendor_id', 'vendors.id')
                    ->where('status', 'completed')
                ])
                ->orderByDesc('total_sales')
                ->take(5)
                ->get();

            $topCategories = Category::select('categories.name', 
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

            $topProducts = Product::with(['vendor'])
                ->withCount(['orderItems as total_sold' => function($q) {
                    $q->select(DB::raw('sum(quantity)'))
                      ->join('orders', 'order_items.order_id', '=', 'orders.id')
                      ->where('orders.status', 'completed');
                }])
                ->orderByDesc('total_sold')
                ->limit(5)
                ->get();

            return view('admin.dashboard', compact(
                'totalOmset', 'totalTransactions', 'totalVendors', 'totalProducts',
                'stats', 'salesTrend', 'pendingList', 'vendorPerformance', 
                'topCategories', 'topProducts'
            ));
        }

        // =========================================================================
        // 2. DASHBOARD VENDOR
        // =========================================================================
        if ($user->role === 'vendor') {
            if (!$user->vendor || $user->vendor->status !== 'active') {
                return view('pending_status'); 
            }

            $vendorId = $user->vendor->id;
            
            $stats = [
                'total_products' => Product::where('vendor_id', $vendorId)->count(),
                'sales_today' => Order::where('vendor_id', $vendorId)->whereDate('created_at', $today)->sum('total_price') ?? 0,
                'transactions_count' => Order::where('vendor_id', $vendorId)->whereDate('created_at', $today)->count(),
                'new_orders' => Order::where('vendor_id', $vendorId)->where('status', 'pending')->count(),
                'low_stock' => Product::where('vendor_id', $vendorId)->whereColumn('stock', '<=', 'min_stock')->count(),
            ];

            // FIX GRAFIK VENDOR: Tambahkan orderBy dan status filter agar sinkron
            $salesTrend = Order::where('vendor_id', $vendorId)
                ->select(
                    DB::raw('DATE(created_at) as date'), 
                    DB::raw('SUM(total_price) as total')
                )
                ->where('created_at', '>=', $lastSevenDays)
                ->groupBy('date')
                ->orderBy('date', 'asc') // WAJIB ADA BIAR GARISNYA BENER
                ->get();

            $lowStockProducts = Product::where('vendor_id', $vendorId)
                ->with('category')
                ->whereColumn('stock', '<=', 'min_stock')
                ->orderBy('stock', 'asc')
                ->take(5)
                ->get();

            return view('dashboard', compact('stats', 'salesTrend', 'lowStockProducts'));
        }

        return redirect()->route('landing');
    }
}