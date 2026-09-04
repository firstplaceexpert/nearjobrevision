<!DOCTYPE html>
<html lang="id">
<head>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($title) ? $title . ' — NEAR JOB' : 'NEAR JOB' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #5680d8;
            --primary-dark: #24427b;
            --primary-light: #eef2fb;
            --teal: #47bfae;
            --bg: #f0f4f9;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: var(--bg); }

        /* ── Bottom Nav ── */
        .bottom-nav { background: #fff; border-top: 1px solid #e8edf5; box-shadow: 0 -4px 20px rgba(37,67,155,.06); }
        .nav-item {
            display: flex; flex-direction: column; align-items: center;
            gap: 3px; padding: 8px 12px; font-size: 10px; font-weight: 700;
            color: #94a3b8; text-decoration: none; transition: color .2s; flex: 1;
            position: relative;
        }
        .nav-item i { font-size: 22px; line-height: 1; }
        .nav-item.active { color: var(--primary); }
        .nav-item.active::before {
            content: '';
            position: absolute; top: 0; left: 50%; transform: translateX(-50%);
            width: 28px; height: 3px;
            background: var(--primary); border-radius: 0 0 4px 4px;
        }
        .nav-badge {
            position: absolute; top: 5px; right: calc(50% - 16px);
            background: #ef4444; color: white; font-size: 8px; font-weight: 800;
            width: 14px; height: 14px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            border: 2px solid white;
        }

        /* ── Header ── */
        .app-header {
            background: #fff;
            border-bottom: 1px solid #e8edf5;
            box-shadow: 0 2px 12px rgba(37,67,155,.05);
        }

        /* ── Cards & UI ── */
        .card { background: #fff; border-radius: 1.25rem; border: 1px solid #e8edf5; }
        .card-lg { background: #fff; border-radius: 1.5rem; border: 1px solid #e8edf5; }
        .btn-primary {
            background: var(--primary); color: #fff; font-weight: 700;
            border-radius: .875rem; transition: all .2s;
            box-shadow: 0 4px 15px rgba(86,128,216,.3);
        }
        .btn-primary:hover { background: var(--primary-dark); transform: translateY(-1px); }
        .badge-primary { background: var(--primary-light); color: var(--primary); font-weight: 700; }
        .badge-teal { background: #e6f8f6; color: #2a9d8f; font-weight: 700; }

        /* ── Scrollbar hide ── */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* ── Leaflet ── */
        .leaflet-container { font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .leaflet-control-attribution { font-size: 9px !important; }

        /* ── Transitions ── */
        .slide-up { animation: slideUp .3s ease; }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
    @livewireStyles

    {{-- Midtrans Snap JS --}}
    @php
        $snapJsUrl = config('services.midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
        $clientKey = config('services.midtrans.client_key') ?: env('MIDTRANS_CLIENT_KEY', '');
    @endphp
    <script src="{{ $snapJsUrl }}" data-client-key="{{ $clientKey }}"></script>
</head>
<body class="min-h-screen flex flex-col" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    {{-- ===== TOP HEADER ===== --}}
    <header class="app-header px-4 h-14 flex items-center justify-between fixed top-0 left-0 right-0 z-50">
        <a href="{{ auth()->user()?->isCompany() ? route('company.dashboard') : route('applicant.map') }}" class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: var(--primary);">
                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            </div>
            <span class="font-extrabold tracking-tight text-lg" style="color: var(--primary-dark);">NEAR JOB</span>
        </a>

        <div class="flex items-center gap-2">
            @auth
            @if(auth()->user()->isApplicant())
                @php $credits = auth()->user()->applicantProfile?->fresh()->application_credits ?? 0; @endphp
                <a href="{{ route('applicant.topup') }}"
                   x-data="{ credits: {{ $credits }} }"
                   @credits-updated.window="credits = ($event.detail && typeof $event.detail.credits !== 'undefined') ? $event.detail.credits : ($event.detail ?? credits)"
                   class="flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full border hover:shadow-sm hover:scale-105 transition-all text-decoration-none"
                   style="background: var(--primary-light); color: var(--primary); border-color: #c7d6f5;" title="Isi ulang kuota lamaran">
                    <i class='bx bx-coin-stack text-sm'></i> <span x-text="`${credits} kuota`">{{ $credits }} kuota</span>
                    <span class="w-4 h-4 rounded-full bg-blue-600 text-white flex items-center justify-center text-[10px] font-black leading-none">+</span>
                </a>
            @endif
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-xs font-semibold text-slate-400 hover:text-red-500 transition-colors px-2 py-1 rounded-lg hover:bg-red-50">
                    <i class='bx bx-log-out-circle text-lg'></i>
                </button>
            </form>
            @endauth
        </div>
    </header>

    {{-- ===== MAIN CONTENT (GUARANTEED CLEARANCE FROM FIXED HEADER 56px & BOTTOM NAV 64px) ===== --}}
    <main class="flex-grow" style="padding-top: 76px; padding-bottom: 96px;">
        {{ $slot }}
    </main>

    {{-- ===== BOTTOM NAVIGATION ===== --}}
    @auth
    <nav class="bottom-nav fixed bottom-0 left-0 right-0 z-50 flex items-center" style="padding-bottom: env(safe-area-inset-bottom);">
        @if(auth()->user()->isApplicant())
            <a href="{{ route('applicant.map') }}" class="nav-item {{ request()->routeIs('applicant.map') ? 'active' : '' }}">
                <i class='bx {{ request()->routeIs('applicant.map') ? "bxs-home" : "bx-home" }}'></i>
                <span>Beranda</span>
            </a>
            <a href="{{ route('applicant.applications') }}" class="nav-item {{ request()->routeIs('applicant.applications') ? 'active' : '' }}">
                <i class='bx {{ request()->routeIs('applicant.applications') ? "bxs-briefcase-alt-2" : "bx-briefcase-alt-2" }}'></i>
                <span>Lamaran</span>
            </a>
            <a href="{{ route('applicant.profile') }}" class="nav-item {{ request()->routeIs('applicant.profile') ? 'active' : '' }}">
                <i class='bx {{ request()->routeIs('applicant.profile') ? "bxs-user" : "bx-user" }}'></i>
                <span>Profil</span>
            </a>
        @else
            <a href="{{ route('company.dashboard') }}" class="nav-item {{ request()->routeIs('company.dashboard') ? 'active' : '' }}">
                <i class='bx {{ request()->routeIs('company.dashboard') ? "bxs-home" : "bx-home" }}'></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('company.jobs') }}" class="nav-item {{ request()->routeIs('company.jobs*') ? 'active' : '' }}">
                <i class='bx {{ request()->routeIs('company.jobs*') ? "bxs-briefcase" : "bx-briefcase" }}'></i>
                <span>Lowongan</span>
            </a>
            <a href="{{ route('company.profile') }}" class="nav-item {{ request()->routeIs('company.profile') ? 'active' : '' }}">
                <i class='bx {{ request()->routeIs('company.profile') ? "bxs-user" : "bx-user" }}'></i>
                <span>Profil</span>
            </a>
        @endif
    </nav>
    @endauth

    @livewireScripts

    {{-- Toast notifications --}}
    <div id="toast-container" class="fixed bottom-24 left-0 right-0 flex flex-col items-center gap-2 z-[200] pointer-events-none px-4"></div>
    <script>
    window.addEventListener('notify', e => {
        const msg = e.detail.message || e.detail[0]?.message || e.detail;
        const type = e.detail.type || 'info';
        const t = document.createElement('div');
        const colors = { success: '#059669', error: '#dc2626', info: '#1e293b' };
        t.style.cssText = `background:${colors[type]||colors.info};color:white;padding:12px 20px;border-radius:12px;box-shadow:0 8px 30px rgba(0,0,0,.2);font-size:13px;font-weight:600;pointer-events:auto;transform:translateY(16px);opacity:0;transition:all .3s;max-width:320px;text-align:center;`;
        t.textContent = msg;
        document.getElementById('toast-container').appendChild(t);
        requestAnimationFrame(() => { t.style.transform = ''; t.style.opacity = '1'; });
        setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateY(1rem)'; setTimeout(() => t.remove(), 300); }, 3500);
    });
    </script>
</body>
</html>
