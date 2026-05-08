<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * Tampilkan Halaman Login
     */
    public function showLogin() 
    {
        // Jika sudah login, langsung arahkan sesuai role
        if (Auth::check()) {
            return $this->redirectUserByRole();
        }
        return view('auth.login');
    }

    /**
     * Proses Login
     */
    public function login(Request $request) 
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            
            // Redirect cerdas sesuai role setelah login sukses
            return $this->redirectUserByRole()->with('success', 'Selamat datang kembali!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang kamu masukkan salah.',
        ])->onlyInput('email');
    }

    /**
     * Helper: Logika Redirect Berdasarkan Role
     * Mencegah user nyasar ke halaman 404 /home
     */
    protected function redirectUserByRole()
    {
        $role = Auth::user()->role;

        if ($role === 'admin') {
            return redirect()->intended('/dashboard');
        } elseif ($role === 'vendor') {
            return redirect()->intended('/dashboard'); 
        }

        // Default ke landing page jika role tidak dikenal
        return redirect()->intended('/');
    }

    /**
     * Tampilkan Halaman Registrasi Vendor/Mitra
     */
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole();
        }
        return view('auth.register');
    }

    /**
     * Tampilkan Halaman Registrasi Pembeli (Customer)
     */
    public function showRegisterCustomer()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole();
        }
        return view('auth.register-customer');
    }

    /**
     * Proses Registrasi Pembeli Baru
     */
    public function registerCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        Auth::login($user);

        return redirect()->route('landing')->with('success', 'Akun Pembeli berhasil dibuat! Selamat berbelanja.');
    }

    /**
     * Proses Registrasi Mitra Baru (Atomic & Aman)
     */
    public function register(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'shop_name' => 'required|string|max:255',
            'identity_number' => 'required|digits:16', 
            'identity_file' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', 
            'address' => 'required|string',
        ]);

        // 2. Mulai Database Transaction
        DB::beginTransaction();

        $ktpFilename = null;

        try {
            // 3. Buat Akun User (Role: Vendor)
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'vendor',
            ]);

            // 4. Proses Upload Foto KTP
            if ($request->hasFile('identity_file')) {
                $file = $request->file('identity_file');
                $ktpFilename = time() . '_ktp_' . Str::slug($request->shop_name) . '.' . $file->getClientOriginalExtension();
                
                // JANGAN UPLOAD KE PUBLIC DI VERCEL (Karena Read-Only)
                if (!env('VERCEL')) {
                    $path = public_path('uploads/identitas');
                    if (!File::isDirectory($path)) {
                        File::makeDirectory($path, 0755, true, true);
                    }
                    $file->move($path, $ktpFilename);
                }
            }

            // 5. Buat Profil Vendor
            $vendor = new Vendor();
            $vendor->user_id = $user->id;
            $vendor->shop_name = $request->shop_name;
            $vendor->slug = Str::slug($request->shop_name) . '-' . Str::random(5);
            $vendor->identity_number = $request->identity_number;
            $vendor->identity_file = $ktpFilename;
            $vendor->address = $request->address;
            $vendor->status = 'pending'; 
            
            if (!$vendor->save()) {
                throw new \Exception("Gagal menyimpan data profil toko ke database.");
            }

            DB::commit(); 

            return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Tunggu verifikasi admin sebelum mulai berjualan.');

        } catch (\Exception $e) {
            DB::rollback(); 
            
            if ($ktpFilename && File::exists(public_path('uploads/identitas/' . $ktpFilename))) {
                File::delete(public_path('uploads/identitas/' . $ktpFilename));
            }
            
            return back()->with('error', 'Gagal mendaftar: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Proses Logout
     */
    public function logout(Request $request) 
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
 
        return redirect('/login')->with('info', 'Anda telah keluar dari sistem.');
    }
}