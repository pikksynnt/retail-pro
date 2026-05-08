<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration — Retail Pro</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { --primary-indigo: #6366f1; --slate-dark: #0f172a; }
        body { background-color: #f8fafc; font-family: 'Plus Jakarta Sans', sans-serif; padding: 40px 0; }
        .register-card { background: #fff; border-radius: 35px; box-shadow: 0 40px 100px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; max-width: 450px; margin: auto; padding: 40px; }
        .form-control { border-radius: 18px; padding: 14px 20px; border: 2px solid #f1f5f9; background: #f8fafc; font-weight: 600; }
        .btn-register { background: var(--primary-indigo); color: #fff; border: none; border-radius: 18px; padding: 16px; font-weight: 800; letter-spacing: 1px; transition: 0.3s; box-shadow: 0 10px 20px rgba(99, 102, 241, 0.2); }
        .btn-register:hover { transform: translateY(-3px); box-shadow: 0 15px 30px rgba(99, 102, 241, 0.3); color: #fff; }
    </style>
</head>
<body>

<div class="container text-center mb-4">
    <h2 class="fw-black text-dark tracking-tighter uppercase">RETAIL <span style="color: var(--primary-indigo)">PRO</span></h2>
    <p class="small fw-bold text-muted opacity-50 uppercase tracking-widest">Customer Portal Registration</p>
</div>

<div class="register-card">
    <div class="text-center mb-4">
        <div class="w-16 h-16 bg-indigo-50 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3 fs-3">
            <i class="fa fa-shopping-bag"></i>
        </div>
        <h4 class="fw-bold">Ayo Belanja!</h4>
        <p class="text-muted small">Buat akun pembeli untuk mulai berbelanja.</p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-4 small mb-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('register.customer.process') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label small fw-bold text-muted text-uppercase ms-2">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" placeholder="Masukkan nama Anda" required value="{{ old('name') }}">
        </div>
        <div class="mb-3">
            <label class="form-label small fw-bold text-muted text-uppercase ms-2">Email Address</label>
            <input type="email" name="email" class="form-control" placeholder="user@gmail.com" required value="{{ old('email') }}">
        </div>
        <div class="mb-3">
            <label class="form-label small fw-bold text-muted text-uppercase ms-2">Password</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>
        <div class="mb-4">
            <label class="form-label small fw-bold text-muted text-uppercase ms-2">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-register w-100">
            DAFTAR SEBAGAI PEMBELI <i class="fa fa-arrow-right ms-2"></i>
        </button>

        <div class="text-center mt-4 pt-3 border-top">
            <p class="small text-muted mb-0">Udah punya akun? <a href="{{ route('login') }}" class="text-primary fw-bold">Login</a></p>
            <p class="small text-muted mt-2">Mau jualan? <a href="{{ route('register') }}" class="text-dark fw-bold">Daftar sebagai Penjual</a></p>
        </div>
    </form>
</div>

</body>
</html>
