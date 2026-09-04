<!DOCTYPE html>
<html lang="id">
<head>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEAR JOB — Temukan Pekerjaan di Sekitar Anda</title>
    <meta name="description" content="Near Job membantu Anda menemukan peluang kerja di sekitar berdasarkan lokasi dan keahlian. Hubungi pemberi kerja langsung via WhatsApp atau Email.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --primary: #5680d8;
            --primary-dark: #24427b;
            --teal: #47bfae;
            --section-pad: 112px;
        }
        * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .hero-gradient { background: linear-gradient(160deg, var(--primary-dark) 0%, var(--primary) 60%, #6b93e8 100%); }

        /* Phone mockup styling with generous padding */
        .phone-frame {
            width: 275px; height: 560px;
            border-radius: 2.75rem; border: 6px solid #e8edf5;
            box-shadow: 0 24px 60px rgba(37,67,155,.22);
            overflow: hidden; position: relative;
            background: #fff; flex-shrink: 0;
        }
        .phone-notch {
            position: absolute; top: 0; left: 0; right: 0; width: 100%;
            height: 24px; display: flex; justify-content: center; z-index: 30;
        }
        .phone-notch-inner { width: 120px; height: 24px; background: #e8edf5; border-radius: 0 0 14px 14px; }
        .phone-statusbar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 8px 18px 4px; font-size: 9.5px; font-weight: 700; color: #475569;
            position: relative; z-index: 20;
        }
        .filter-chip {
            padding: 6px 14px; border-radius: 99px; font-size: 9.5px; font-weight: 700;
            white-space: nowrap; display: inline-flex; align-items: center; gap: 4px;
        }
        .job-card-mini {
            background: #fff; border-radius: 14px; padding: 12px 14px;
            border: 1px solid #e8edf5; margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,.03);
        }
        .apply-btn-mini {
            padding: 6px 14px; background: var(--primary); color: #fff;
            border-radius: 8px; font-size: 9.5px; font-weight: 800; border: none;
            cursor: pointer;
        }
        .map-cluster {
            border-radius: 50%; border: 2.5px solid #fff; color: #fff; font-weight: 800;
            display: flex; align-items: center; justify-content: center;
            background: var(--primary); box-shadow: 0 4px 12px rgba(86,128,216,.45);
        }
        .bottom-nav-mini {
            position: absolute; bottom: 0; left: 0; right: 0; width: 100%;
            height: 54px; background: #fff; border-top: 1px solid #e8edf5;
            display: flex; align-items: center; justify-content: space-around;
            z-index: 20; padding: 4px 16px 8px;
        }
        .nav-mini-item {
            display: flex; flex-direction: column; align-items: center;
            gap: 2px; font-size: 8px; font-weight: 700; min-width: 44px;
        }
        .nav-mini-item i { font-size: 19px; line-height: 1; }
        .nav-mini-item.active { color: var(--primary); }
        .nav-mini-item:not(.active) { color: #94a3b8; }

        /* Section Layout Spacing */
        .sec { padding: 112px 24px; }
        .sec-white { background: #ffffff; }
        .sec-light { background: #f5f7fb; }
        .sec-head { text-align: center; margin-bottom: 72px; }
        .sec-label {
            display: inline-block; font-size: 12px; font-weight: 900;
            text-transform: uppercase; letter-spacing: .14em;
            color: var(--primary); margin-bottom: 14px;
        }
        .sec-title {
            font-size: clamp(28px, 4vw, 42px); font-weight: 900;
            color: #1e293b; line-height: 1.25; margin-bottom: 16px;
        }
        .sec-desc {
            font-size: 15px; font-weight: 500; color: #64748b;
            max-width: 500px; margin: 0 auto; line-height: 1.8;
        }
    </style>
</head>
<body class="bg-white text-slate-800 antialiased">

{{-- ===== HEADER ===== --}}
<header style="position:fixed;top:0;left:0;right:0;z-index:9999;background:#ffffff;border-bottom:2px solid #e8edf5;box-shadow:0 2px 14px rgba(36,66,123,.08);">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-sm" style="background:var(--primary);">
                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            </div>
            <span class="font-black text-xl tracking-tight" style="color:var(--primary-dark);">NEAR JOB</span>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('login') }}" class="text-sm font-extrabold px-5 py-2.5 rounded-xl transition-colors hover:bg-slate-100" style="color:var(--primary-dark);text-decoration:none;">Masuk</a>
            <a href="{{ route('register.applicant') }}" class="text-sm font-extrabold text-white px-6 py-2.5 rounded-xl shadow-md transition-all hover:opacity-95" style="background:var(--primary);text-decoration:none;">Daftar Gratis</a>
        </div>
    </div>
</header>

{{-- ===== HERO ===== --}}
<section class="hero-gradient px-6 text-white relative overflow-hidden" style="padding-top:112px;padding-bottom:64px;">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full opacity-10" style="background:radial-gradient(circle,white,transparent);"></div>
        <div class="absolute top-1/2 -left-12 w-48 h-48 rounded-full opacity-5" style="background:radial-gradient(circle,white,transparent);"></div>
    </div>

    <div class="relative max-w-3xl mx-auto text-center" style="display:flex;flex-direction:column;gap:28px;align-items:center;">
        <div class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full text-xs font-black shadow-sm" style="background:rgba(255,255,255,.16);border:1px solid rgba(255,255,255,.3);">
            <i class='bx bx-map-pin text-yellow-300 text-sm'></i> Lebih dari 500+ lowongan pekerjaan di sekitar Anda
        </div>

        <h1 style="font-size:clamp(36px,6vw,62px);font-weight:900;line-height:1.15;letter-spacing:-.02em;margin:0;">
            Pekerjaan yang Tepat<br>
            <span style="color:#a8c7ff;">Mungkin Lebih Dekat</span><br>
            Dari yang Anda Kira.
        </h1>

        <p style="color:#bfdbfe;font-size:16px;max-width:560px;line-height:1.8;font-weight:600;margin:0;">
            Near Job membantu Anda menemukan berbagai peluang lowongan pekerjaan lokal terdekat berdasarkan lokasi presisi dan keahlian Anda. Hubungi pemberi kerja langsung via WhatsApp.
        </p>

        <div style="display:flex;flex-wrap:wrap;gap:16px;justify-content:center;margin-top:4px;">
            <a href="{{ route('register.applicant') }}" style="display:inline-flex;align-items:center;gap:10px;background:#fff;color:var(--primary-dark);font-weight:900;font-size:16px;padding:16px 36px;border-radius:18px;box-shadow:0 10px 30px rgba(0,0,0,.25);text-decoration:none;white-space:nowrap;">
                <i class='bx bx-search' style="font-size:20px;"></i> Cari Pekerjaan Sekarang
            </a>
            <a href="{{ route('register.company') }}" style="display:inline-flex;align-items:center;gap:10px;background:rgba(255,255,255,.12);color:#fff;font-weight:800;font-size:16px;padding:16px 36px;border-radius:18px;border:2px solid rgba(255,255,255,.35);text-decoration:none;white-space:nowrap;">
                <i class='bx bx-buildings' style="font-size:20px;"></i> Saya Butuh Karyawan
            </a>
        </div>
    </div>

    {{-- 4 PHONE MOCKUPS (PERFECTLY CENTERED & AMPLE WRAP SPACING) --}}
    <div class="relative mt-16" style="padding-top:16px;">
        <div class="absolute bottom-0 left-0 right-0 w-full" style="height:50%;background:var(--primary);z-index:0;"></div>
        <div class="relative z-10 w-full" style="overflow-x:auto;">
            <div style="display:flex;flex-wrap:nowrap;justify-content:center;align-items:flex-end;gap:28px;padding:8px 40px 0;min-width:max-content;margin:0 auto;">

                {{-- PHONE 1: Filter Panel --}}
                <div style="display:flex;flex-direction:column;align-items:center;gap:16px;">
                    <div style="padding:9px 20px;background:#fff;border-radius:99px;font-size:12px;font-weight:800;color:var(--primary-dark);box-shadow:0 4px 16px rgba(0,0,0,.12);display:flex;align-items:center;gap:6px;">
                        <i class='bx bx-filter-alt' style="color:var(--primary);"></i> 1. Filter Peta &amp; Jarak
                    </div>
                    <div class="phone-frame">
                        <div class="phone-notch"><div class="phone-notch-inner"></div></div>
                        <div class="phone-statusbar"><span>9:41</span><div class="flex gap-1"><i class='bx bx-signal-5'></i><i class='bx bx-wifi'></i><i class='bx bxs-battery-full'></i></div></div>

                        <div style="display:flex;justify-content:space-between;align-items:center;padding:14px 18px;border-bottom:1px solid #f1f5f9;">
                            <span style="width:20px"></span>
                            <span style="font-weight:800;color:#1e293b;font-size:13px;">Terapkan Filter</span>
                            <i class='bx bx-x' style="font-size:20px;color:#94a3b8;cursor:pointer;"></i>
                        </div>

                        <div style="padding:16px 18px;display:flex;flex-direction:column;gap:16px;">
                            <div>
                                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                                    <span style="font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;">Bidang Lowongan:</span>
                                    <span style="font-size:10px;font-weight:800;color:var(--primary);">Pilih Semua</span>
                                </div>
                                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:8px;">
                                    @foreach([['bx-restaurant','F&B',false],['bx-coffee','Barista',false],['bx-user-voice','Jasa',false],['bx-store','Retail',true],['bx-package','Logistik',false],['bx-cog','Produksi',false]] as $cat)
                                    @php $a=$cat[2]; @endphp
                                    <div style="display:flex;align-items:center;justify-content:center;gap:6px;padding:9px 8px;border-radius:10px;font-size:10px;font-weight:800;{{ $a?'background:var(--primary);color:#fff;border:1px solid transparent;':'background:#f8faff;color:#475569;border:1px solid #e2e8f0;' }}">
                                        <i class='bx {{ $cat[0] }}' style="font-size:14px;"></i> {{ $cat[1] }}
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div>
                                <span style="font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;display:block;margin-bottom:10px;text-align:left;">Jenis Pekerjaan:</span>
                                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:6px;">
                                    @foreach(['Part-time','Full-time','Keduanya'] as $i=>$t)
                                    <div style="text-align:center;padding:9px 4px;border-radius:10px;font-size:9.5px;font-weight:800;white-space:nowrap;{{ $i===2?'background:var(--primary);color:#fff;':'background:#f8faff;color:#475569;border:1px solid #e2e8f0;' }}">{{ $t }}</div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div style="position:absolute;bottom:0;left:0;right:0;width:100%;padding:14px 18px;background:#fff;border-top:1px solid #e8edf5;">
                            <div style="width:100%;padding:11px;border-radius:12px;color:#fff;font-size:12px;font-weight:800;text-align:center;background:var(--primary);box-shadow:0 4px 14px rgba(86,128,216,.3);">Terapkan Filter</div>
                        </div>
                    </div>
                </div>

                {{-- PHONE 2: Job List --}}
                <div style="display:flex;flex-direction:column;align-items:center;gap:16px;">
                    <div style="padding:9px 20px;background:#fff;border-radius:99px;font-size:12px;font-weight:800;color:var(--primary-dark);box-shadow:0 4px 16px rgba(0,0,0,.12);display:flex;align-items:center;gap:6px;">
                        <i class='bx bx-list-ul' style="color:var(--primary);"></i> 2. Kartu Lowongan Karusel
                    </div>
                    <div class="phone-frame" style="background:#f8faff;">
                        <div class="phone-notch"><div class="phone-notch-inner"></div></div>
                        <div class="phone-statusbar" style="background:#fff;"><span>9:41</span><div class="flex gap-1"><i class='bx bx-signal-5'></i><i class='bx bx-wifi'></i><i class='bx bxs-battery-full'></i></div></div>

                        <div style="background:#fff;padding:12px 14px 14px;box-shadow:0 2px 8px rgba(0,0,0,.03);">
                            <div style="display:flex;align-items:center;background:#f1f5f9;border-radius:12px;padding:9px 12px;margin-bottom:10px;">
                                <i class='bx bx-search' style="color:#94a3b8;font-size:15px;margin-right:8px;"></i>
                                <span style="font-size:11px;color:#334155;flex:1;font-weight:700;">Kota Yogyakarta</span>
                                <i class='bx bx-current-location' style="color:#94a3b8;font-size:15px;"></i>
                            </div>
                            <div style="display:flex;gap:8px;overflow-x:auto;" class="hide-scrollbar">
                                <div class="filter-chip" style="background:var(--teal);color:#fff;"><i class='bx bx-filter-alt'></i> Filter</div>
                                <div class="filter-chip" style="background:var(--teal);color:#fff;">Terdekat</div>
                                <div class="filter-chip" style="background:#fff;color:#475569;border:1px solid #e2e8f0;">Part-time</div>
                            </div>
                        </div>

                        <div style="padding:14px;overflow-y:auto;padding-bottom:70px;height:calc(100% - 118px);" class="hide-scrollbar">
                            @foreach([['Pramusaji','Warung Gudeg','2 km','2,5 jt','F&B'],['SPG Toko','Batik Sekar','3.1 km','1,8 jt','Retail'],['Resepsionis','Hotel Mutiara','4.5 km','3,2 jt','Jasa']] as [$pos,$emp,$dist,$sal,$cat])
                            <div class="job-card-mini text-left">
                                <div style="display:flex;gap:10px;margin-bottom:10px;align-items:center;">
                                    <div style="width:34px;height:34px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;flex-shrink:0;background:#eef2fb;color:var(--primary);">{{ substr($emp,0,1) }}</div>
                                    <div style="flex:1;min-width:0;">
                                        <h4 style="font-size:11px;font-weight:800;color:#1e293b;margin:0;line-height:1.3;">{{ $pos }}</h4>
                                        <p style="font-size:9px;color:#64748b;margin:2px 0 0;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $emp }} · {{ $dist }}</p>
                                    </div>
                                    <span style="font-size:10px;font-weight:800;color:#1e293b;flex-shrink:0;">{{ $sal }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <span style="font-size:9px;font-weight:800;padding:3px 8px;border-radius:6px;background:#eef2fb;color:var(--primary);">{{ $cat }}</span>
                                    <button class="apply-btn-mini">Lamar</button>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <div class="bottom-nav-mini">
                            <div class="nav-mini-item active"><i class='bx bxs-home'></i><span>Beranda</span></div>
                            <div class="nav-mini-item"><i class='bx bx-briefcase-alt-2'></i><span>Lamaran</span></div>
                            <div class="nav-mini-item"><i class='bx bx-user'></i><span>Profil</span></div>
                        </div>
                    </div>
                </div>

                {{-- PHONE 3: Map View --}}
                <div style="display:flex;flex-direction:column;align-items:center;gap:16px;">
                    <div style="padding:9px 20px;background:#fff;border-radius:99px;font-size:12px;font-weight:800;color:var(--primary-dark);box-shadow:0 4px 16px rgba(0,0,0,.12);display:flex;align-items:center;gap:6px;">
                        <i class='bx bx-map-alt' style="color:var(--primary);"></i> 3. Pin Lokasi Peta Presisi
                    </div>
                    <div class="phone-frame" style="background:#d4e1f0;">
                        <svg width="100%" height="100%" style="position:absolute;opacity:.4"><path d="M-30,120 Q80,140 260,80 M60,-20 Q100,220 140,580 M-20,280 Q80,320 260,430 M220,-10 Q190,200 260,560" fill="none" stroke="#fff" stroke-width="5"/></svg>

                        <div class="map-cluster" style="position:absolute;top:32%;left:22%;width:26px;height:26px;font-size:9px;">8</div>
                        <div class="map-cluster" style="position:absolute;top:44%;left:54%;width:34px;height:34px;font-size:11px;">20</div>
                        <div class="map-cluster" style="position:absolute;top:60%;left:32%;width:44px;height:44px;font-size:13px;box-shadow:0 6px 18px rgba(86,128,216,.5);">50</div>

                        <div style="position:absolute;top:0;left:0;right:0;width:100%;z-index:20;padding:8px 12px 12px;background:linear-gradient(160deg,#24427b,#5680d8);">
                            <div class="phone-notch" style="position:relative;height:20px;"><div class="phone-notch-inner" style="background:rgba(255,255,255,.2);"></div></div>
                            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;padding:0 4px;font-size:9.5px;font-weight:600;color:rgba(255,255,255,.85);">
                                <span>9:41</span><div class="flex gap-1"><i class='bx bx-signal-5'></i><i class='bx bx-wifi'></i><i class='bx bxs-battery-full'></i></div>
                            </div>
                            <div style="display:flex;align-items:center;border-radius:12px;padding:8px 12px;background:rgba(255,255,255,.2);backdrop-filter:blur(6px);">
                                <i class='bx bx-search' style="color:#fff;margin-right:8px;font-size:14px;"></i>
                                <span style="font-size:11px;color:#fff;font-weight:600;flex:1;">Yogyakarta, 25 km</span>
                            </div>
                        </div>

                        <div class="bottom-nav-mini">
                            <div class="nav-mini-item active"><i class='bx bxs-home'></i><span>Beranda</span></div>
                            <div class="nav-mini-item"><i class='bx bx-briefcase-alt-2'></i><span>Lamaran</span></div>
                            <div class="nav-mini-item"><i class='bx bx-user'></i><span>Profil</span></div>
                        </div>
                    </div>
                </div>

                {{-- PHONE 4: Detail Card --}}
                <div style="display:flex;flex-direction:column;align-items:center;gap:16px;">
                    <div style="padding:9px 20px;background:#fff;border-radius:99px;font-size:12px;font-weight:800;color:var(--primary-dark);box-shadow:0 4px 16px rgba(0,0,0,.12);display:flex;align-items:center;gap:6px;">
                        <i class='bx bx-detail' style="color:var(--primary);"></i> 4. Detail &amp; Kontak Langsung
                    </div>
                    <div class="phone-frame" style="background:#d4e1f0;">
                        <div class="map-cluster" style="position:absolute;top:26%;left:42%;width:42px;height:42px;font-size:13px;box-shadow:0 6px 18px rgba(86,128,216,.5);">50</div>

                        <div style="position:absolute;bottom:54px;left:0;right:0;width:100%;z-index:20;border-radius:22px 22px 0 0;padding:16px 14px;background:rgba(255,255,255,.98);backdrop-filter:blur(10px);box-shadow:0 -8px 25px rgba(0,0,0,.10);text-align:left;">
                            <div style="width:36px;height:4px;background:#cbd5e1;border-radius:99px;margin:0 auto 12px;"></div>

                            <div style="background:#fff;border-radius:14px;padding:12px 14px;border:2px solid var(--primary);box-shadow:0 4px 16px rgba(86,128,216,.15);">
                                <div style="display:flex;gap:10px;margin-bottom:10px;align-items:center;">
                                    <div style="width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:900;flex-shrink:0;background:#eef2fb;color:var(--primary);">W</div>
                                    <div style="flex:1;min-width:0;">
                                        <h4 style="font-size:11px;font-weight:800;color:#1e293b;margin:0;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Pramusaji</h4>
                                        <p style="font-size:9px;color:#64748b;margin:2px 0 0;font-weight:600;">Warung Gudeg Bu Yanti · 2 km</p>
                                    </div>
                                </div>
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <span style="font-size:9px;font-weight:800;padding:3px 8px;border-radius:6px;background:#eef2fb;color:var(--primary);">F&B</span>
                                    <button style="padding:6px 14px;background:var(--primary);color:#fff;border-radius:8px;font-size:9.5px;font-weight:800;border:none;">Lamar via WA</button>
                                </div>
                            </div>
                        </div>

                        <div class="bottom-nav-mini">
                            <div class="nav-mini-item active"><i class='bx bxs-home'></i><span>Beranda</span></div>
                            <div class="nav-mini-item"><i class='bx bx-briefcase-alt-2'></i><span>Lamaran</span></div>
                            <div class="nav-mini-item"><i class='bx bx-user'></i><span>Profil</span></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- Transition strip --}}
<div style="background:var(--primary);height:64px;"></div>

{{-- ===== CARA KERJA ===== --}}
<section class="sec sec-white">
    <div class="max-w-5xl mx-auto">
        <div class="sec-head">
            <span class="sec-label">Cara Kerja</span>
            <h2 class="sec-title">Tiga Langkah Mudah</h2>
            <p class="sec-desc">Proses pencarian dan pelamaran kerja lokal tercepat tanpa perantara</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach([
                ['01','bx-map-alt','Temukan Lowongan','Lihat posisi pekerjaan terdekat yang tersedia langsung dari peta presisi di sekitar Anda.'],
                ['02','bx-filter-alt','Filter & Pilih','Sesuaikan filter pencarian berdasarkan jarak km, bidang usaha, jenis kerja, dan ekspektasi gaji.'],
                ['03','bxl-whatsapp','Lamar Langsung','Kirimkan lamaran dan hubungi pemberi kerja langsung via WhatsApp atau Email tanpa birokrasi.'],
            ] as [$num,$ico,$title,$desc])
            <div style="background:#fafbff;border:1.5px solid #e8edf5;border-radius:24px;padding:36px 30px;text-align:center;box-shadow:0 4px 20px rgba(37,67,155,.03);transition:all .2s;" class="hover:shadow-lg">
                <div style="font-size:48px;font-weight:900;color:#e2e8f0;margin-bottom:8px;line-height:1;">{{ $num }}</div>
                <div style="width:64px;height:64px;border-radius:20px;background:var(--primary);color:#fff;display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 22px;box-shadow:0 4px 14px rgba(86,128,216,.3);">
                    <i class='bx {{ $ico }}'></i>
                </div>
                <h3 style="font-size:19px;font-weight:900;color:#1e293b;margin-bottom:12px;">{{ $title }}</h3>
                <p style="font-size:14px;color:#64748b;line-height:1.75;font-weight:500;margin:0;">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== KEUNGGULAN NEAR JOB ===== --}}
<section class="sec sec-light">
    <div class="max-w-5xl mx-auto">
        <div class="sec-head">
            <span class="sec-label">Keunggulan Utama</span>
            <h2 class="sec-title">Kenapa Harus Near Job?</h2>
            <p class="sec-desc">Dirancang khusus untuk ekosistem tenaga kerja dan pemilik usaha lokal Indonesia.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach([
                ['bx-map','Lokasi Presisi Sekitar','Temukan lowongan pekerjaan berdasarkan radius km dari lokasi Anda sekarang. Tidak perlu scroll ribuan lowongan luar kota.'],
                ['bx-user-plus','Tanpa Perlu Relasi','Semua pencari kerja memiliki kesempatan yang sama tanpa perlu orang dalam atau jaringan khusus.'],
                ['bx-gift','3 Kredit Lamaran Gratis','Setiap akun baru pelamar langsung mendapatkan 3 kredit kesempatan melamar pekerjaan secara gratis.'],
                ['bxl-whatsapp','Kontak Langsung WA','Kirimkan lamaran dan profil langsung ke nomor WhatsApp resmi pemberi kerja.'],
                ['bx-file','CV ATS Otomatis','Buat dokumen CV ATS standar profesional secara otomatis dari data profil Anda kapan saja.'],
                ['bx-store','Untuk Usaha Lokal','Sangat cocok untuk warung, toko, kafe, restoran, hotel, pabrik, dan usaha UMKM lokal.'],
            ] as [$ico,$title,$desc])
            <div style="background:#fff;border-radius:24px;padding:32px 28px;border:1.5px solid #e8edf5;display:flex;flex-direction:column;gap:18px;box-shadow:0 4px 16px rgba(37,67,155,.04);transition:all .2s;" class="hover:shadow-md">
                <div style="width:54px;height:54px;border-radius:16px;background:#eef2fb;color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:26px;flex-shrink:0;">
                    <i class='bx {{ $ico }}'></i>
                </div>
                <div>
                    <h4 style="font-size:17px;font-weight:900;color:#1e293b;margin-bottom:10px;line-height:1.3;">{{ $title }}</h4>
                    <p style="font-size:14px;color:#64748b;line-height:1.75;font-weight:500;margin:0;">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== LOWONGAN TERBARU ===== --}}
<section class="sec sec-white">
    <div class="max-w-5xl mx-auto">
        <div class="sec-head">
            <span class="sec-label">Tersedia Sekarang</span>
            <h2 class="sec-title">Lowongan Pekerjaan Terbaru</h2>
            <p class="sec-desc">Temukan lowongan aktif yang siap dilamar hari ini</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8" style="margin-bottom:56px;">
            @foreach([
                ['bx-restaurant','Kitchen Helper','Warung Makan ABC','1.4 km','Rp 2,5–3 juta','Full-time','whatsapp'],
                ['bx-store','Kasir Toko','Toko Makmur','2.1 km','Rp 2,3–2,8 juta','Full-time','whatsapp'],
                ['bx-cog','Operator Produksi','CV Maju Bersama','3.7 km','Rp 3–3,5 juta','Kontrak','email'],
                ['bx-brush','Cleaning Service','Hotel Banyuwangi','4.2 km','Rp 2,5–3 juta','Full-time','whatsapp'],
                ['bx-package','Helper Gudang','PT Logistik Jaya','5.1 km','Rp 2,8–3,4 juta','Harian','email'],
                ['bx-car','Driver Pengiriman','CV Jasa Antar','3.2 km','Rp 3–4 juta','Full-time','whatsapp'],
            ] as [$ico,$pos,$emp,$dist,$gaji,$type,$contact])
            <div style="background:#f8faff;border-radius:24px;padding:28px 24px;border:1.5px solid #e8edf5;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 2px 10px rgba(0,0,0,.02);transition:all .2s;" class="hover:shadow-md">
                <div>
                    <div style="display:flex;align-items:center;gap:14px;margin-bottom:18px;">
                        <div style="width:48px;height:48px;border-radius:15px;background:#eef2fb;color:var(--primary);display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;">
                            <i class='bx {{ $ico }}'></i>
                        </div>
                        <div style="min-width:0;">
                            <h4 style="font-weight:900;color:#1e293b;font-size:16px;margin-bottom:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $pos }}</h4>
                            <p style="font-size:13px;color:#64748b;font-weight:700;margin:0;">{{ $emp }} <span style="color:var(--teal);">✓</span></p>
                        </div>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:10px;margin:20px 0;padding:14px 16px;background:#ffffff;border-radius:14px;border:1px solid #eef2fb;">
                        <div style="display:flex;align-items:center;gap:10px;font-size:13px;font-weight:600;color:#475569;"><i class='bx bx-map' style="color:var(--primary);font-size:17px;"></i> {{ $dist }} dari lokasi Anda</div>
                        <div style="display:flex;align-items:center;gap:10px;font-size:13px;font-weight:600;color:#475569;"><i class='bx bx-money' style="color:var(--primary);font-size:17px;"></i> {{ $gaji }} / bulan</div>
                        <div style="display:flex;align-items:center;gap:10px;font-size:13px;font-weight:600;color:#475569;"><i class='bx bx-time-five' style="color:var(--primary);font-size:17px;"></i> {{ $type }}</div>
                    </div>
                </div>

                <div style="padding-top:4px;">
                    <span style="display:inline-flex;align-items:center;gap:8px;font-size:12.5px;font-weight:800;padding:10px 18px;border-radius:12px;{{ $contact==='whatsapp'?'background:#dcfce7;color:#15803d;':'background:#dbeafe;color:#1d4ed8;' }}">
                        {!! $contact==='whatsapp' ? "<i class='bx bxl-whatsapp' style='font-size:16px;'></i> Lamar via WhatsApp" : "<i class='bx bx-envelope' style='font-size:16px;'></i> Lamar via Email" !!}
                    </span>
                </div>
            </div>
            @endforeach
        </div>

        <div style="text-align:center;">
            <a href="{{ route('register.applicant') }}" style="display:inline-flex;align-items:center;gap:10px;background:var(--primary);color:#fff;font-weight:900;font-size:16px;padding:18px 44px;border-radius:18px;box-shadow:0 6px 25px rgba(86,128,216,.35);text-decoration:none;">
                <i class='bx bx-search' style="font-size:20px;"></i> Buka Peta &amp; Lihat Semua Lowongan
            </a>
        </div>
    </div>
</section>

{{-- ===== PROFILE PREVIEW ===== --}}
<section class="sec sec-light">
    <div class="max-w-5xl mx-auto">
        <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:72px;">

            {{-- Phone Mockup --}}
            <div style="flex-shrink:0;">
                <div style="width:280px;height:550px;border-radius:2.75rem;border:6px solid #e8edf5;box-shadow:0 24px 60px rgba(37,67,155,.20);overflow:hidden;position:relative;background:#fff;">
                    <div style="display:flex;justify-content:space-between;align-items:center;padding:10px 18px 6px;font-size:9.5px;font-weight:700;color:#475569;">
                        <span>9:41</span>
                        <div style="display:flex;gap:4px;"><i class='bx bx-signal-5'></i><i class='bx bx-wifi'></i><i class='bx bxs-battery-full'></i></div>
                    </div>

                    <div style="background:linear-gradient(160deg,#24427b,#5680d8);padding:24px 20px;text-align:center;">
                        <div style="width:66px;height:66px;border-radius:50%;margin:0 auto 10px;border:3px solid rgba(255,255,255,.8);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:900;background:rgba(255,255,255,.2);color:white;box-shadow:0 4px 14px rgba(0,0,0,.15);">B</div>
                        <p style="font-weight:900;font-size:15px;color:white;margin:0 0 3px;">Budi Santoso</p>
                        <p style="font-size:10.5px;color:rgba(255,255,255,.85);font-weight:600;margin:0 0 12px;">Kota Yogyakarta</p>
                        <div style="display:inline-flex;align-items:center;gap:5px;background:var(--teal);color:white;padding:5px 16px;border-radius:99px;font-size:9.5px;font-weight:800;box-shadow:0 2px 8px rgba(0,0,0,.12);">✓ Aktif Cari Kerja</div>
                    </div>

                    <div style="padding:16px 20px;">
                        <p style="font-size:11px;font-weight:900;color:#1e293b;margin:0 0 12px;text-transform:uppercase;letter-spacing:.06em;">Kelengkapan Profil Anda</p>
                        @foreach(['Data Kontak & Kota','Pendidikan Terakhir','Foto Profil & Banner','Pengalaman Kerja'] as $lbl)
                        <div style="display:flex;align-items:center;justify-content:space-between;padding:11px 0;border-bottom:1px solid #f1f5f9;">
                            <span style="font-size:11px;color:#475569;font-weight:600;">{{ $lbl }}</span>
                            <div style="width:20px;height:20px;border-radius:50%;background:var(--teal);display:flex;align-items:center;justify-content:center;">
                                <i class='bx bx-check' style="color:white;font-size:12px;"></i>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="bottom-nav-mini">
                        <div class="nav-mini-item"><i class='bx bx-home'></i><span>Beranda</span></div>
                        <div class="nav-mini-item"><i class='bx bx-briefcase-alt-2'></i><span>Lamaran</span></div>
                        <div class="nav-mini-item active"><i class='bx bxs-user'></i><span>Profil</span></div>
                    </div>
                </div>
            </div>

            {{-- Right Content --}}
            <div style="max-width:480px;display:flex;flex-direction:column;gap:28px;">
                <div>
                    <span class="sec-label" style="color:var(--teal);">Kemudahan Pelamar</span>
                    <h2 class="sec-title" style="margin-bottom:0;">Profil Anda<br>Adalah CV Anda</h2>
                </div>

                <p style="font-size:15px;color:#64748b;line-height:1.8;font-weight:500;margin:0;">
                    Profil pelamar dirancang sebagai CV digital lengkap. Pemberi kerja dapat langsung melihat kualifikasi, keahlian, dan riwayat pekerjaan Anda dalam satu tampilan profesional.
                </p>

                <ul style="display:flex;flex-direction:column;gap:16px;list-style:none;padding:0;margin:0;">
                    @foreach([
                        'Informasi pribadi dan nomor kontak WhatsApp terverifikasi',
                        'Keahlian (skills) dan riwayat pengalaman kerja lengkap',
                        'Dokumen CV ATS profesional yang dapat diunduh otomatis',
                        'Pantau riwayat status lamaran secara gratis dan akurat',
                    ] as $item)
                    <li style="display:flex;align-items:flex-start;gap:14px;font-size:14.5px;font-weight:600;color:#334155;line-height:1.6;">
                        <div style="width:26px;height:26px;border-radius:50%;background:#eef2fb;color:var(--teal);display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px;">
                            <i class='bx bx-check' style="font-size:15px;"></i>
                        </div>
                        <span>{{ $item }}</span>
                    </li>
                    @endforeach
                </ul>

                <div>
                    <a href="{{ route('register.applicant') }}" style="display:inline-flex;align-items:center;gap:10px;background:var(--primary);color:#fff;font-weight:800;font-size:15px;padding:18px 36px;border-radius:18px;box-shadow:0 4px 20px rgba(86,128,216,.35);text-decoration:none;white-space:nowrap;">
                        <i class='bx bx-user-plus' style="font-size:19px;"></i> Buat Akun Profil Gratis
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ===== CTA EMPLOYER ===== --}}
<section style="padding:112px 24px;background:linear-gradient(135deg,#1a2f5a 0%,var(--primary-dark) 100%);">
    <div style="max-width:620px;margin:0 auto;text-align:center;display:flex;flex-direction:column;gap:28px;align-items:center;">
        <div style="width:72px;height:72px;border-radius:22px;background:rgba(255,255,255,.16);display:flex;align-items:center;justify-content:center;font-size:32px;color:#fff;box-shadow:0 6px 20px rgba(0,0,0,.15);">
            <i class='bx bx-buildings'></i>
        </div>

        <h2 style="font-size:clamp(28px,4vw,42px);font-weight:900;color:#fff;line-height:1.25;margin:0;">Butuh Tenaga Kerja<br>untuk Usaha Anda?</h2>

        <p style="font-size:15px;color:#bfdbfe;line-height:1.8;font-weight:500;max-width:480px;margin:0;">
            Pasang lowongan pekerjaan Anda sekarang dan dapatkan calon karyawan lokal yang tinggal tepat di sekitar tempat usaha Anda.
        </p>

        <a href="{{ route('register.company') }}" style="display:inline-flex;align-items:center;gap:10px;background:#fff;color:var(--primary-dark);font-weight:900;font-size:16px;padding:18px 44px;border-radius:18px;box-shadow:0 8px 30px rgba(0,0,0,.3);text-decoration:none;">
            <i class='bx bx-rocket' style="font-size:22px;"></i> Pasang Lowongan — Gratis
        </a>
    </div>
</section>

{{-- ===== FOOTER ===== --}}
<footer style="background:#0f172a;padding:72px 24px 48px;">
    <div style="max-w-5xl mx-auto" style="max-width:1024px;margin:0 auto;">
        <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:24px;padding-bottom:36px;border-bottom:1px solid #1e293b;margin-bottom:36px;">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:38px;height:38px;border-radius:11px;background:var(--primary);display:flex;align-items:center;justify-content:center;">
                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
                </div>
                <span style="font-weight:900;color:#fff;font-size:20px;letter-spacing:-.02em;">NEAR JOB</span>
            </div>
            <div style="display:flex;gap:32px;">
                <a href="{{ route('login') }}" style="font-size:14px;font-weight:600;color:#94a3b8;text-decoration:none;" class="hover:text-white transition-colors">Masuk</a>
                <a href="{{ route('register.applicant') }}" style="font-size:14px;font-weight:600;color:#94a3b8;text-decoration:none;" class="hover:text-white transition-colors">Daftar Pelamar</a>
                <a href="{{ route('register.company') }}" style="font-size:14px;font-weight:600;color:#94a3b8;text-decoration:none;" class="hover:text-white transition-colors">Daftar Pemberi Kerja</a>
            </div>
        </div>

        <div style="display:flex;flex-wrap:wrap;justify-content:space-between;align-items:center;gap:16px;">
            <p style="font-size:13px;font-weight:500;color:#64748b;margin:0;">Platform pencari kerja lokal berbasis peta dan koordinat lokasi presisi.</p>
            <p style="font-size:13px;font-weight:500;color:#64748b;margin:0;">© 2026 Near Job. All Rights Reserved.</p>
        </div>
    </div>
</footer>

</body>
</html>