<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Retail Pro') }} - @yield('title', 'System Management')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <style>
        :root {
            --primary-color: #6366f1;
            --sidebar-bg: #0f172a;
            --sidebar-hover: rgba(255, 255, 255, 0.04);
            --content-bg: #f8fafc;
        }

        body { 
            background-color: var(--content-bg); 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            overflow-x: hidden; 
            color: #334155;
        }
        
        .sidebar { 
            min-height: 100vh; 
            background: var(--sidebar-bg); 
            color: white; 
            position: fixed; 
            width: 280px; 
            z-index: 1000;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            border-right: 1px solid rgba(255,255,255,0.05);
        }
        
        .nav-link { 
            color: #94a3b8; 
            padding: 12px 20px; 
            transition: 0.2s; 
            border-radius: 12px;
            margin: 4px 18px;
            font-weight: 500;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            text-decoration: none;
        }
        
        .nav-link i { font-size: 1rem; width: 28px; opacity: 0.7; }
        .nav-link:hover { background: var(--sidebar-hover); color: #f1f5f9; }
        .nav-link.active { 
            background: rgba(99, 102, 241, 0.1); 
            color: #818cf8 !important; 
            font-weight: 700;
        }

        .main-content { 
            margin-left: 280px; 
            padding: 40px; 
            min-height: 100vh; 
            display: flex;
            flex-direction: column;
        }

        .mobile-header {
            display: none;
            background: white;
            padding: 15px 20px;
            border-bottom: 1px solid #e2e8f0;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .user-profile {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 20px;
            padding: 18px;
            margin: 20px;
        }

        .role-badge {
            font-size: 9px; font-weight: 700; letter-spacing: 0.5px;
            padding: 4px 8px; border-radius: 6px; text-transform: uppercase;
        }

        .menu-label {
            font-size: 10px; font-weight: 700; color: #475569;
            letter-spacing: 1.5px; margin: 25px 0 10px 38px; text-transform: uppercase;
            opacity: 0.8;
        }

        .footer-branding {
            margin-top: auto;
            padding: 30px 0;
            border-top: 1px solid #edf2f7;
            color: #94a3b8;
        }

        .copyright-text {
            font-size: 0.75rem;
            letter-spacing: 0.025em;
            font-weight: 500;
        }

        .dev-name {
            color: #64748b;
            font-weight: 700;
            text-transform: uppercase;
            margin-left: 4px;
        }

        @media (max-width: 991px) {
            .sidebar { margin-left: -280px; }
            .sidebar.show { margin-left: 0; }
            .main-content { margin-left: 0; padding: 20px; }
            .mobile-header { display: flex; align-items: center; justify-content: space-between; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <div class="mobile-header">
        <h6 class="fw-bold mb-0">RETAIL<span style="color:var(--primary-color)">PRO</span></h6>
        <button class="btn btn-sm" id="sidebarToggle"><i class="fa fa-bars text-dark"></i></button>
    </div>

    <div class="sidebar" id="mainSidebar">
        <div class="px-4 py-5 border-bottom border-white border-opacity-5">
            <h5 class="fw-bold mb-0 text-white text-center" style="letter-spacing: -0.5px;">RETAIL<span style="color: #818cf8;">PRO</span></h5>
        </div>

        {{-- NOTIFICATIONS --}}
        <div class="dropdown mx-4 mt-4 mb-3">
            <button class="btn w-100 text-start nav-link d-flex align-items-center position-relative border-0 py-2 px-3" 
                    type="button" data-bs-toggle="dropdown" style="background: rgba(255,255,255,0.03); border-radius: 10px;">
                <i class="fa-solid fa-bell me-2"></i>
                <span class="small">Notifications</span>
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge bg-indigo ms-auto rounded-circle p-1" style="width:6px; height:6px; background:#818cf8"></span>
                @endif
            </button>
            <ul class="dropdown-menu shadow-lg border-0 py-0" style="width: 300px; border-radius: 15px;">
                <li class="p-3 border-bottom bg-light fw-bold small">Recent Activity</li>
                <div style="max-height: 250px; overflow-y: auto;">
                    @forelse(auth()->user()->unreadNotifications as $notif)
                        <li><a class="dropdown-item p-3 small border-bottom text-wrap" href="{{ $notif->data['url'] ?? '#' }}">{{ $notif->data['messages'] }}</a></li>
                    @empty
                        <li class="p-4 text-center text-muted x-small">No new notifications</li>
                    @endforelse
                </div>
            </ul>
        </div>
        
        <nav class="nav flex-column">
            <div class="menu-label">Main Overview</div>
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="fa-solid fa-chart-line me-2"></i> <span>Insights</span>
            </a>
            
            @php 
                $checkRole = strtolower(Auth::user()->role);
                $isVerified = Auth::user()->vendor && Auth::user()->vendor->status === 'active';
            @endphp

            @if($checkRole == 'vendor' && $isVerified)
                <div class="menu-label">Merchant Services</div>
                <a class="nav-link {{ request()->routeIs('vendor.orders.*') ? 'active' : '' }}" href="{{ route('vendor.orders.index') }}">
                    <i class="fa-solid fa-bag-shopping me-2"></i> <span>Incoming Orders</span>
                </a>
                <a class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                    <i class="fa-solid fa-box me-2"></i> <span>Inventory List</span>
                </a>
                <a class="nav-link {{ request()->is('pos*') ? 'active' : '' }}" href="{{ route('pos.index') }}">
                    <i class="fa-solid fa-calculator me-2"></i> <span>Point of Sale</span>
                </a>
            @endif

            @if($checkRole == 'admin')
                <div class="menu-label">Administration</div>
                <a class="nav-link {{ request()->routeIs('admin.vendors.pending') ? 'active' : '' }}" href="{{ route('admin.vendors.pending') }}">
                    <i class="fa-solid fa-shield-halved me-2"></i> <span>Verifications</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.vendors.index') ? 'active' : '' }}" href="{{ route('admin.vendors.index') }}">
                    <i class="fa-solid fa-store me-2"></i> <span>Active Merchants</span>
                </a>
                <a class="nav-link {{ request()->routeIs('admin.reports.global') ? 'active' : '' }}" href="{{ route('admin.reports.global') }}">
                    <i class="fa-solid fa-chart-pie me-2"></i> <span>Global Analytics</span>
                </a>
            @endif
        </nav>

        <div class="user-profile mt-auto">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle bg-white bg-opacity-10 d-flex align-items-center justify-content-center fw-bold text-white shadow-sm" 
                     style="width: 40px; height: 40px; min-width: 40px; font-size: 14px;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="ms-3 overflow-hidden">
                    <p class="mb-0 fw-bold small text-white text-truncate">{{ Auth::user()->name }}</p>
                    <span class="role-badge bg-{{ $checkRole == 'admin' ? 'danger' : 'success' }} text-white">
                        {{ $checkRole == 'admin' ? 'Administrator' : 'Verified Merchant' }}
                    </span>
                </div>
            </div>
            <button class="btn btn-outline-light btn-sm w-100 border-0 rounded-3 text-start ps-3 py-2 mb-2" 
                    style="background: rgba(255,255,255,0.03); font-size: 12px;"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fa-solid fa-power-off me-2 text-danger"></i> Sign Out
            </button>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            
            <div class="text-center pt-2 mt-2" style="border-top: 1px solid rgba(255,255,255,0.03)">
                <span style="font-size: 8px; color: rgba(255,255,255,0.2); letter-spacing: 2px; font-weight: 700;">
                    ENGINEERED BY <span style="color:rgba(255,255,255,0.4)">SUPRIANTO OPICK</span>
                </span>
            </div>
        </div>
    </div>

    <div class="main-content">
        @yield('content')

        <footer class="footer-branding text-center">
            <div class="container">
                <p class="copyright-text mb-0">
                    &copy; {{ date('Y') }} Retail Pro Marketplace Architecture.
                    <span class="d-block d-md-inline ms-md-2 mt-2 mt-md-0 pt-2 pt-md-0">
                        Designed & Developed by <span class="dev-name">SUPRIANTO OPICK</span>
                    </span>
                </p>
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $('#sidebarToggle').click(function() { $('#mainSidebar').toggleClass('show'); });
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        @if(session('success'))
            Swal.fire({ icon: 'success', title: 'Action Successful', text: "{{ session('success') }}", timer: 1500, showConfirmButton: false, border: 'none' });
        @endif
    </script>
</body>
</html>