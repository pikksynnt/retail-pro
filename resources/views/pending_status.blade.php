@extends('layouts.app')

@section('title', 'Verification in Progress — Retail Pro')

@section('content')
<div class="container-fluid d-flex align-items-center justify-content-center" style="min-height: 85vh; background-color: #f8fafc;">
    <div class="row justify-content-center w-100">
        <div class="col-md-6 col-lg-5">
            {{-- MAIN PREMIUM CARD --}}
            <div class="card border-0 shadow-lg p-5 text-center" style="border-radius: 40px; background: white; border: 1px solid #f1f5f9;">
                
                {{-- MODERN ANIMATED ICON --}}
                <div class="mb-5">
                    <div class="d-inline-flex align-items-center justify-content-center shadow-sm" 
                         style="width: 120px; height: 120px; background: #fffbeb; border-radius: 35px; border: 2px solid #fef3c7;">
                        <i class="fa-solid fa-hourglass-half fa-spin-pulse text-warning" style="font-size: 3.5rem;"></i>
                    </div>
                </div>

                {{-- STATUS INFORMATION --}}
                <h3 class="fw-black text-dark mb-2" style="letter-spacing: -1px;">Account Under Review 🛰️</h3>
                <p class="text-secondary mb-4 px-2" style="font-size: 0.95rem; line-height: 1.6;">
                    Hello, <span class="fw-bold text-dark">{{ Auth::user()->name }}</span>! Our Core Admin team is currently auditing your merchant credentials. This security protocol typically concludes within **24 hours**.
                </p>

                {{-- STATUS BADGE BOX --}}
                <div class="p-4 mb-4 border-0 bg-light" style="border-radius: 25px;">
                    <div class="d-flex align-items-center justify-content-center gap-2 mb-2">
                        <span class="badge bg-warning text-dark rounded-pill px-4 py-2 fw-black" style="font-size: 10px; letter-spacing: 1px;">
                            <i class="fa-solid fa-shield-halved me-1"></i> STATUS: PENDING VERIFICATION
                        </span>
                    </div>
                    <div class="text-muted fw-bold" style="font-size: 11px; letter-spacing: 0.5px;">
                        Submission Date: <span class="text-dark">{{ Auth::user()->created_at->format('M d, Y — H:i') }}</span>
                    </div>
                </div>

                {{-- EDUCATIONAL ALERT --}}
                <div class="alert border-0 d-flex align-items-center text-start mb-5" style="border-radius: 20px; background-color: #f0f7ff; color: #1e40af;">
                    <i class="fa-solid fa-circle-info fs-4 me-3 opacity-75"></i>
                    <div class="small fw-medium">
                        During the audit, access to the <strong>POS Terminal, Inventory,</strong> and <strong>Global Reports</strong> is temporarily restricted.
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="d-grid gap-3">
                    <button onclick="window.location.reload()" class="btn btn-dark rounded-pill py-3 fw-black shadow-lg" style="background: #0f172a; border: none; letter-spacing: 1px;">
                        <i class="fa-solid fa-rotate-right me-2"></i> REFRESH STATUS
                    </button>
                    
                    <button onclick="event.preventDefault(); document.getElementById('logout-form').submit();" 
                            class="btn btn-link text-danger text-decoration-none fw-bold small">
                        <i class="fa-solid fa-power-off me-2"></i> Terminate Session
                    </button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>
            
            {{-- FOOTER BRANDING --}}
            <div class="text-center mt-5">
                <p class="text-muted small fw-bold" style="letter-spacing: 2px; opacity: 0.6;">
                    ENGINEERED BY <span class="text-dark">SUPRIANTO OPICK</span>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- INDEPENDENT REFRESH SCRIPT --}}
<script>
    // Auto-check status every 60 seconds
    setTimeout(function(){
       window.location.reload(1);
    }, 60000);
</script>
@endsection