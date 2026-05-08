<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Access — Retail Pro Ecosystem</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --slate-dark: #0f172a;
            --primary-indigo: #6366f1;
            --content-bg: #f8fafc;
        }

        body { 
            background-color: var(--content-bg);
            background-image: radial-gradient(circle at 0% 0%, rgba(99, 102, 241, 0.05) 0%, transparent 50%),
                              radial-gradient(circle at 100% 100%, rgba(99, 102, 241, 0.05) 0%, transparent 50%);
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
        }

        .login-card { 
            background: #ffffff;
            border-radius: 40px; 
            box-shadow: 0 40px 100px rgba(15, 23, 42, 0.08); 
            max-width: 440px; 
            width: 90%; 
            padding: 50px 45px; 
            border: 1px solid #f1f5f9;
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-logo {
            letter-spacing: -1.5px;
            font-size: 2.2rem;
            color: var(--slate-dark);
            font-weight: 800;
        }

        .brand-text { color: var(--primary-indigo); }

        .btn-login { 
            background: var(--slate-dark); 
            color: white;
            border: none; 
            border-radius: 18px; 
            padding: 16px; 
            font-weight: 800; 
            font-size: 0.85rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            letter-spacing: 1.5px;
            text-transform: uppercase;
            box-shadow: 0 10px 20px rgba(15, 23, 42, 0.15);
        }

        .btn-login:hover { 
            background: var(--primary-indigo); 
            transform: translateY(-3px); 
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.25);
            color: white;
        }

        .form-label {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.2px;
            color: #94a3b8;
            margin-bottom: 8px;
            margin-left: 5px;
            text-transform: uppercase;
        }

        .form-control { 
            border-radius: 18px; 
            padding: 15px 20px; 
            background: #f8fafc; 
            border: 2px solid #f1f5f9; 
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--slate-dark);
            transition: all 0.2s;
        }

        .form-control:focus {
            background: #fff;
            border-color: var(--primary-indigo);
            box-shadow: 0 0 0 5px rgba(99, 102, 241, 0.05);
            outline: none;
        }

        .alert-custom {
            border-radius: 18px;
            background: #fff1f2;
            color: #e11d48;
            border: none;
            font-size: 0.85rem;
            font-weight: 600;
            padding: 15px;
        }

        .footer-credit {
            margin-top: 40px;
            padding-top: 25px;
            border-top: 1px solid #f1f5f9;
        }

        .dev-text {
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 3px;
            color: #cbd5e1;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .dev-name { 
            color: #94a3b8;
            font-size: 10px;
            font-weight: 700;
        }

        .trouble-link {
            color: var(--primary-indigo);
            font-weight: 700;
            text-decoration: none;
            transition: opacity 0.2s;
        }

        .trouble-link:hover { opacity: 0.8; }
    </style>
</head>
<body>

    <div class="login-card text-center">
        <div class="mb-5">
            <h2 class="brand-logo mb-1">RETAIL <span class="brand-text">PRO</span></h2>
            <p class="text-muted small fw-bold opacity-50" style="letter-spacing: 1px;">CORE MANAGEMENT SYSTEM</p>
        </div>

        @if($errors->any())
            <div class="alert alert-custom mb-4 animate__animated animate__shakeX">
                <i class="fa fa-triangle-exclamation me-2"></i> {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.process') }}" method="POST" autocomplete="off">
            @csrf
            <div class="mb-4 text-start">
                <label class="form-label">Email Directory</label>
                <input type="email" name="email" class="form-control" placeholder="admin@retailpro.id" value="{{ old('email') }}" required autofocus>
            </div>
            
            <div class="mb-5 text-start">
                <label class="form-label">Security Key</label>
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-login w-100 mb-4">
                Verify & Access <i class="fa fa-chevron-right ms-2" style="font-size: 10px;"></i>
            </button>
        </form>
        
        <p class="text-muted small mb-0 fw-medium">Authentication Issues? <a href="#" class="trouble-link">Contact Sys-Admin</a></p>

        {{-- IDENTITAS SUPRIANTO OPICK --}}
        <div class="footer-credit">
            <p class="dev-text">Software Architecture by</p>
            <p class="dev-name mb-0">SUPRIANTO OPICK</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>