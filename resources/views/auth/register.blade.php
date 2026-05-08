<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Onboarding — Retail Pro Ecosystem</title>
    
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
            background-image: radial-gradient(circle at 100% 0%, rgba(99, 102, 241, 0.08) 0%, transparent 40%),
                              radial-gradient(circle at 0% 100%, rgba(99, 102, 241, 0.08) 0%, transparent 40%);
            min-height: 100vh; 
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--slate-dark);
            padding: 40px 0;
        }

        .register-container {
            max-width: 900px;
            margin: auto;
        }

        .register-card { 
            background: #ffffff;
            border-radius: 40px; 
            box-shadow: 0 40px 100px rgba(15, 23, 42, 0.06); 
            border: 1px solid #f1f5f9;
            overflow: hidden;
            animation: slideUp 0.7s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-section {
            padding: 40px 0;
            text-align: center;
        }

        .brand-logo {
            letter-spacing: -1.5px;
            font-size: 2rem;
            font-weight: 800;
            color: var(--slate-dark);
        }

        .brand-text { color: var(--primary-indigo); }

        .section-title {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 2px;
            color: var(--primary-indigo);
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .section-title::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #f1f5f9;
        }

        .form-label {
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 10px;
            margin-left: 5px;
            text-transform: uppercase;
        }

        .form-control { 
            border-radius: 18px; 
            padding: 14px 20px; 
            background: #f8fafc; 
            border: 2px solid #f1f5f9; 
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s;
        }

        .form-control:focus {
            background: #fff;
            border-color: var(--primary-indigo);
            box-shadow: 0 0 0 5px rgba(99, 102, 241, 0.05);
            outline: none;
        }

        .input-group-text {
            background: #f8fafc;
            border: 2px solid #f1f5f9;
            border-right: none;
            border-radius: 18px 0 0 18px;
            color: #94a3b8;
            padding-left: 20px;
        }

        .has-icon .form-control {
            border-left: none;
            border-radius: 0 18px 18px 0;
        }

        .btn-register { 
            background: var(--slate-dark); 
            color: white;
            border: none; 
            border-radius: 20px; 
            padding: 18px; 
            font-weight: 800; 
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
            letter-spacing: 2px;
            text-transform: uppercase;
            box-shadow: 0 15px 30px rgba(15, 23, 42, 0.15);
            margin-top: 20px;
        }

        .btn-register:hover { 
            background: var(--primary-indigo); 
            transform: translateY(-4px); 
            box-shadow: 0 20px 40px rgba(99, 102, 241, 0.2);
            color: white;
        }

        .footer-credit {
            margin-top: 50px;
            padding-bottom: 30px;
            text-align: center;
        }

        .dev-text {
            font-size: 8px;
            font-weight: 800;
            letter-spacing: 3px;
            color: #cbd5e1;
            text-transform: uppercase;
        }

        .dev-name { 
            color: #94a3b8;
            font-size: 10px;
            font-weight: 700;
        }

        .login-link {
            color: var(--primary-indigo);
            font-weight: 700;
            text-decoration: none;
        }

        .file-input-wrapper {
            position: relative;
            background: #f8fafc;
            border: 2px dashed #e2e8f0;
            border-radius: 18px;
            padding: 20px;
            text-align: center;
            transition: all 0.3s;
        }

        .file-input-wrapper:hover {
            border-color: var(--primary-indigo);
            background: #fff;
        }
    </style>
</head>
<body>

<div class="container register-container">
    <div class="brand-section">
        <h2 class="brand-logo mb-1">RETAIL <span class="brand-text">PRO</span></h2>
        <p class="text-muted small fw-bold opacity-50" style="letter-spacing: 1px;">MERCHANT ONBOARDING SYSTEM v2.0 (MODIFIED BY AI)</p>
    </div>

    <div class="card register-card">
        <div class="bg-light p-3 text-center border-bottom">
            <p class="mb-0 small fw-bold text-muted">Mau belanja aja? <a href="{{ route('register.customer') }}" class="text-primary">Daftar sebagai Pembeli</a></p>
        </div>
        <div class="card-body p-4 p-md-5">
            <form action="{{ route('register.process') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                {{-- SECTION 1: PERSONAL --}}
                <div class="section-title">
                    <i class="fa fa-user-circle"></i> Personal Information
                </div>
                
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required placeholder="e.g. Suprianto Opick">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Business Email</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" required placeholder="name@company.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Secure Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="••••••••">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Verify Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required placeholder="••••••••">
                    </div>
                </div>

                {{-- SECTION 2: LEGAL --}}
                <div class="section-title">
                    <i class="fa fa-briefcase"></i> Business Legitimacy
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Shop / UMKM Name</label>
                        <input type="text" name="shop_name" class="form-control" value="{{ old('shop_name') }}" required placeholder="Retail Pro Store">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">National Identity (NIK)</label>
                        <input type="text" name="identity_number" class="form-control" value="{{ old('identity_number') }}" required placeholder="16-digit ID Number">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Identity Document (KTP)</label>
                        <div class="file-input-wrapper">
                            <i class="fa fa-cloud-upload-alt text-primary mb-2 fs-4"></i>
                            <input type="file" name="identity_file" class="form-control" required accept="image/*" style="opacity: 0; position: absolute; inset: 0; cursor: pointer;">
                            <p class="mb-0 small fw-bold text-muted">Click to upload or drag and drop KTP image</p>
                            <p class="mb-0 text-muted" style="font-size: 9px;">Maximum size: 2MB (JPG, PNG)</p>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label">Operational Address</label>
                        <textarea name="address" class="form-control" rows="3" required placeholder="Detailed business location..."></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-register w-100 mb-4">
                    Launch My Merchant Account <i class="fa fa-rocket ms-2"></i>
                </button>
                
                <p class="text-center small text-muted fw-bold">
                    Already part of the ecosystem? <a href="{{ route('login') }}" class="login-link">Sign In</a>
                </p>
            </form>
        </div>
    </div>

    {{-- IDENTITAS SUPRIANTO OPICK --}}
    <div class="footer-credit">
        <p class="dev-text">System Architecture Integrity by</p>
        <p class="dev-name mb-0 text-uppercase">Suprianto Opick</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>