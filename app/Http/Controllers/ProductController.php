<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = Product::with(['category', 'vendor'])->latest();

        if ($user->role !== 'admin') {
            $vendor = $user->vendor;

            if (!$vendor || $vendor->status !== 'active') {
                return redirect()->route('dashboard')->with('error', 'Akun Anda belum aktif.');
            }

            $query->where('vendor_id', $vendor->id);
        }

        $products = $query->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && (!$user->vendor || $user->vendor->status !== 'active')) {
            return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }

        $categories = Category::where('vendor_id', $user->vendor->id)
            ->orWhereNull('vendor_id')
            ->get();

        $vendors = ($user->role === 'admin') ? Vendor::all() : null;

        return view('products.create', compact('categories', 'vendors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'barcode'       => 'required|string',
            'name'          => 'required|string|max:255',
            'category_name' => 'required|string|max:255',
            'price_eceran'  => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url'     => 'nullable|url',
            'description'   => 'required|string',
        ]);

        $user = Auth::user();
        $vendor = ($user->role === 'admin') ? Vendor::find($request->vendor_id) : $user->vendor;

        if (!$vendor) {
            return back()->withInput()->with('error', 'Gagal: Data Vendor tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            $category = Category::firstOrCreate(
                [
                    'name' => $request->category_name,
                    'vendor_id' => $vendor->id
                ],
                [
                    'slug' => Str::slug($request->category_name) . '-' . Str::random(5)
                ]
            );

            $imageName = null;

            if ($request->hasFile('image')) {
                $imageName = $this->handleFileUpload($request->file('image'), $request->name);
            }

            Product::create([
                'vendor_id'    => $vendor->id,
                'category_id'  => $category->id,
                'barcode'      => $request->barcode,
                'name'         => $request->name,
                'description'  => $request->description,
                'image'        => $imageName,
                'image_url'    => $request->image_url,
                'stock'        => $request->stock,
                'price_eceran' => $request->price_eceran,
                'unit'         => $request->unit ?? 'pcs',
                'min_stock'    => $request->min_stock ?? 5,
            ]);

            DB::commit();

            return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            DB::rollback();

            return back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin' && $product->vendor_id !== $user->vendor->id) {
            abort(403);
        }

        $categories = Category::where('vendor_id', $user->vendor->id)->get();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'barcode'       => 'required|string',
            'name'          => 'required|string|max:255',
            'category_name' => 'required|string|max:255',
            'price_eceran'  => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image_url'     => 'nullable|url',
        ]);

        try {
            $user = Auth::user();
            $vendorId = ($user->role === 'admin') ? $product->vendor_id : $user->vendor->id;

            $category = Category::firstOrCreate(
                [
                    'name' => $request->category_name,
                    'vendor_id' => $vendorId
                ],
                [
                    'slug' => Str::slug($request->category_name) . '-' . Str::random(5)
                ]
            );

            $data = $request->only([
                'name',
                'barcode',
                'stock',
                'price_eceran',
                'unit',
                'min_stock',
                'description',
                'image_url',
            ]);

            $data['category_id'] = $category->id;

            if ($request->hasFile('image')) {
                $this->deleteOldFile($product->image);
                $data['image'] = $this->handleFileUpload($request->file('image'), $request->name);
            }

            $product->update($data);

            return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate.');
        } catch (\Exception $e) {
            return back()->with('error', 'Update gagal: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user();

        if ($user->role !== 'admin' && $product->vendor_id !== $user->vendor->id) {
            return back();
        }

        $this->deleteOldFile($product->image);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produk dihapus.');
    }

    private function handleFileUpload($file, $name)
    {
        $filename = time() . "_" . Str::slug($name) . '.' . $file->getClientOriginalExtension();
        $path = public_path('storage/products');

        // JANGAN UPLOAD KE PUBLIC DI VERCEL (Karena Read-Only)
        if (!env('VERCEL')) {
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0755, true, true);
            }

            $file->move($path, $filename);
        }

        return $filename;
    }

    private function deleteOldFile($filename)
{
    if (!env('VERCEL')) {
        if ($filename && File::exists(public_path('storage/products/' . $filename))) {
            File::delete(public_path('storage/products/' . $filename));
        }
    }
 }
}