<div>
    {{-- Data embed via hidden script tags --}}
    <script id="njob-map-data" type="application/json">
        {!! json_encode([
            'userLat'  => $userLat,
            'userLon'  => $userLon,
            'credits'  => $credits,
            'jobs'     => $jobsMapDataArray ?? [],
            'jobCards' => $jobs->map(fn($j) => [
                'id'          => $j->id,
                'position'    => $j->position,
                'company'     => $j->company->company_name,
                'initial'     => substr($j->company->company_name, 0, 1),
                'city'        => $j->company->city,
                'salary'      => $j->salary_range,
                'quota'       => (int) ($j->quota ?: 1),
                'distance'    => $j->distance,
                'method'      => $j->contact_method,
                'education'   => strtoupper($j->min_education),
                'workType'    => $j->work_type_label,
                'applyRoute'  => route('applicant.job.detail', $j->id),
                'profileRoute'=> route('applicant.profile'),
            ])->values()->all(),
        ]) !!}
    </script>

    {{-- ── SEARCH & FILTER BAR (TOP FLOATING) ── --}}
    <div style="position:fixed;top:56px;left:0;right:0;z-index:400;background:linear-gradient(160deg,#24427b 0%,#5680d8 100%);padding:10px 14px;box-shadow:0 4px 20px rgba(37,67,155,.25);">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
            <div style="flex:1;display:flex;align-items:center;background:white;border-radius:12px;padding:6px 12px;gap:8px;box-shadow:0 2px 8px rgba(0,0,0,.1);">
                <i class='bx bx-search' style="color:#5680d8;font-size:18px;"></i>
                <input type="text" wire:model.live.debounce.300ms="searchQuery" 
                       placeholder="Cari lowongan, posisi, atau kota..." 
                       style="border:none;outline:none;font-size:12px;font-weight:700;color:#1e293b;width:100%;background:transparent;">
                @if($searchQuery)
                    <button wire:click="$set('searchQuery', '')" style="border:none;background:none;color:#94a3b8;cursor:pointer;"><i class='bx bx-x-circle' style="font-size:16px;"></i></button>
                @endif
                <button onclick="njobPromptGPS()" title="Aktifkan Lokasi GPS" 
                        style="border:none;background:#eef2fb;color:#5680d8;padding:4px 8px;border-radius:8px;font-size:11px;font-weight:800;cursor:pointer;display:flex;align-items:center;gap:4px;white-space:nowrap;">
                    <i class='bx bx-target-lock' style="font-size:15px;color:#2a9d8f;"></i> GPS
                </button>
            </div>
        </div>
        <div style="display:flex;gap:6px;overflow-x:auto;scrollbar-width:none;padding-bottom:2px;">
            <button onclick="njobToggleFilter()" style="flex-shrink:0;display:flex;align-items:center;gap:5px;background:rgba(255,255,255,.2);color:white;border:1.5px solid rgba(255,255,255,.3);border-radius:20px;padding:5px 12px;font-size:11px;font-weight:700;cursor:pointer;">
                <i class='bx bx-filter-alt'></i> Filter
                @if($filterCategory || $filterWorkType)<span style="width:6px;height:6px;background:#fbbf24;border-radius:50%;"></span>@endif
            </button>
            <button onclick="njobSetWT('');njobSetCat('')" style="flex-shrink:0;background:{{ !$filterWorkType && !$filterCategory ? '#47bfae' : 'rgba(255,255,255,.15)' }};color:white;border:1.5px solid rgba(255,255,255,.25);border-radius:20px;padding:5px 12px;font-size:11px;font-weight:700;cursor:pointer;">Untuk Anda</button>
            <button onclick="njobSetWT('part_time')" style="flex-shrink:0;background:{{ $filterWorkType === 'part_time' ? '#47bfae' : 'rgba(255,255,255,.15)' }};color:white;border:1.5px solid rgba(255,255,255,.25);border-radius:20px;padding:5px 12px;font-size:11px;font-weight:700;cursor:pointer;">Part Time</button>
            <button onclick="njobSetWT('full_time')" style="flex-shrink:0;background:{{ $filterWorkType === 'full_time' ? '#47bfae' : 'rgba(255,255,255,.15)' }};color:white;border:1.5px solid rgba(255,255,255,.25);border-radius:20px;padding:5px 12px;font-size:11px;font-weight:700;cursor:pointer;">Full Time</button>
            <button onclick="njobSetRadius(5)" style="flex-shrink:0;background:{{ $filterRadius <= 5 ? '#47bfae' : 'rgba(255,255,255,.15)' }};color:white;border:1.5px solid rgba(255,255,255,.25);border-radius:20px;padding:5px 12px;font-size:11px;font-weight:700;cursor:pointer;">Terdekat</button>
        </div>
    </div>

    {{-- ── FULLSCREEN LEAFLET MAP (WIRE:IGNORE TO PREVENT MAP DISAPPEARING ON FILTER) ── --}}
    <div id="njob-map-container" wire:ignore style="position:fixed;top:56px;bottom:64px;left:0;right:0;z-index:100;">
        <div id="njob-map" style="width:100%;height:100%;"></div>
    </div>

    {{-- ── FLOATING BUTTON: "LIHAT DAFTAR LOWONGAN" (FIXED DI ATAS MENU NAV) ── --}}
    <div id="njob-btn-wrapper" style="position:fixed;bottom:78px;left:0;right:0;z-index:450;display:flex;justify-content:center;pointer-events:none;">
        <button id="njob-btn-toggle-list" onclick="njobToggleHorizontalCards(true)"
            style="pointer-events:auto;background:#24427b;color:white;border:none;padding:12px 24px;border-radius:30px;font-size:13px;font-weight:800;cursor:pointer;box-shadow:0 8px 24px rgba(37,67,155,.5);display:flex;align-items:center;gap:8px;white-space:nowrap;transition:all .25s;">
            <i class='bx bx-list-ul' style="font-size:18px;"></i> Lihat Daftar Lowongan ({{ $jobs->count() }})
        </button>
    </div>

    {{-- ── HORIZONTAL CARDS POPUP (HANYA MUNCUL SAAT DIKLIK, DERET KESAMPING) ── --}}
    <div id="njob-horizontal-panel" style="position:fixed;bottom:72px;left:0;right:0;z-index:480;display:none;flex-direction:column;pointer-events:none;transform:translateY(120%);opacity:0;transition:all .35s cubic-bezier(.34,1.1,.64,1);">
        
        {{-- Tombol Tutup Kecil di Atas Carousel (Centering) --}}
        <div style="max-width:960px;margin:0 auto;width:100%;padding:0 16px 8px;display:flex;justify-content:center;pointer-events:auto;">
            <button onclick="njobToggleHorizontalCards(false)" style="background:#1e293b;color:white;border:none;padding:7px 20px;border-radius:24px;font-size:12px;font-weight:800;cursor:pointer;box-shadow:0 4px 16px rgba(0,0,0,.35);display:flex;align-items:center;gap:6px;">
                <i class='bx bx-x' style="font-size:18px;"></i> Tutup Daftar Lowongan
            </button>
        </div>

        {{-- Container Carousel Scroll Kesamping --}}
        <div id="njob-cards-carousel" style="display:flex;gap:12px;overflow-x:auto;padding:4px 16px 12px;scrollbar-width:none;-webkit-overflow-scrolling:touch;scroll-snap-type:x mandatory;pointer-events:auto;">
            @forelse($jobs as $job)
            <div class="njob-card-h"
                 data-job-id="{{ $job->id }}"
                 data-lat="{{ $job->latitude ?? 0 }}"
                 data-lon="{{ $job->longitude ?? 0 }}"
                 onclick="njobFocusCard(this)"
                 style="flex:0 0 300px;scroll-snap-align:center;background:white;border-radius:18px;padding:15px;border:2px solid #e8edf5;box-shadow:0 8px 30px rgba(0,0,0,.18);cursor:pointer;transition:all .2s;position:relative;">

                {{-- Row 1: Logo/Initial + Judul + Gaji --}}
                <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:10px;">
                    <div style="width:46px;height:46px;flex-shrink:0;background:#eef2fb;color:#5680d8;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:900;border:1.5px solid #c7d6f5;">
                        {{ substr($job->company->company_name, 0, 1) }}
                    </div>
                    <div style="flex:1;min-width:0;">
                        <h3 style="font-size:13px;font-weight:800;color:#1e293b;line-height:1.3;margin:0 0 2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $job->position }}
                        </h3>
                        <div style="font-size:11px;color:#5680d8;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ $job->company->company_name }}
                        </div>
                        <div style="font-size:12px;font-weight:800;color:#1e293b;margin-top:2px;">
                            {{ $job->salary_range }}
                        </div>
                    </div>
                </div>

                {{-- Row 2: Info Jarak, Kuota & Pendidikan --}}
                <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:#64748b;font-weight:600;margin-bottom:10px;background:#f8faff;padding:6px 10px;border-radius:10px;flex-wrap:wrap;">
                    <span>📍 <b>{{ $job->distance }} km</b></span>
                    <span>•</span>
                    <span style="color:#2563eb;font-weight:800;background:#eff6ff;padding:1px 6px;border-radius:6px;border:1px solid #bfdbfe;">🎯 <b>{{ $job->quota ?? 1 }} Kuota</b></span>
                    <span>•</span>
                    <span style="color:#ef6c00;">🎓 Min. {{ strtoupper($job->min_education) }}</span>
                </div>

                {{-- Row 3: Tag WA/Email + Tombol Lamar --}}
                <div style="display:flex;align-items:center;justify-content:space-between;gap:8px;">
                    <div>
                        @if($job->contact_method === 'whatsapp')
                            <span style="font-size:10px;font-weight:700;background:#dcfce7;color:#16a34a;padding:4px 9px;border-radius:8px;display:inline-flex;align-items:center;gap:3px;">
                                <i class='bx bxl-whatsapp'></i> WA
                            </span>
                        @else
                            <span style="font-size:10px;font-weight:700;background:#eef2fb;color:#5680d8;padding:4px 9px;border-radius:8px;display:inline-flex;align-items:center;gap:3px;">
                                <i class='bx bx-envelope'></i> Email
                            </span>
                        @endif
                    </div>

                    <button onclick="event.stopPropagation(); njobOpenSheet(this.closest('.njob-card-h').dataset.jobId)"
                        style="background:#5680d8;color:white;border:none;padding:8px 16px;border-radius:10px;font-size:11px;font-weight:800;cursor:pointer;box-shadow:0 4px 12px rgba(86,128,216,.35);">
                        Lamar Sekarang
                    </button>
                </div>
            </div>
            @empty
            <div style="background:white;border-radius:16px;padding:20px;text-align:center;width:100%;">
                <div style="font-size:13px;font-weight:700;color:#64748b;">Belum ada lowongan di area ini</div>
            </div>
            @endforelse
        </div>
    </div>

    {{-- JOB DETAIL MODAL (FULL DETAILS) --}}
    <div id="njob-sheet" style="position:fixed;bottom:64px;left:0;right:0;z-index:700;background:white;border-radius:24px 24px 0 0;box-shadow:0 -8px 40px rgba(0,0,0,.25);transform:translateY(100%);display:none;transition:transform .3s cubic-bezier(.34,1.1,.64,1);padding:0 20px 24px;max-height:65vh;overflow-y:auto;">
        <div style="width:40px;height:4px;background:#e2e8f0;border-radius:2px;margin:14px auto;"></div>
        <button onclick="njobCloseSheet()" style="position:absolute;top:12px;right:14px;background:#f1f5f9;border:none;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:18px;color:#64748b;display:flex;align-items:center;justify-content:center;font-weight:700;">×</button>
        <div id="njob-sheet-body"></div>
    </div>
    <div id="njob-overlay" onclick="njobCloseSheet()" style="display:none;position:fixed;inset:0;z-index:699;background:rgba(0,0,0,.4);"></div>

    {{-- ===== GPS LOCATION NOTICE MODAL ===== --}}
    <div id="njob-gps-modal" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,.6);align-items:center;justify-content:center;padding:16px;">
        <div style="background:white;border-radius:24px;max-width:380px;width:100%;padding:24px;text-align:center;box-shadow:0 10px 40px rgba(0,0,0,.3);">
            <div style="width:64px;height:64px;background:#e6f8f6;color:#2a9d8f;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:32px;border:3px solid #99f6e4;">
                <i class='bx bx-current-location'></i>
            </div>
            <h3 style="font-size:16px;font-weight:900;color:#1e293b;margin:0 0 8px;">Aktifkan Lokasi / GPS Anda</h3>
            <p style="font-size:12px;color:#64748b;line-height:1.6;margin:0 0 20px;font-weight:600;">
                Aplikasi <b>NEAR JOB</b> memerlukan akses lokasi (GPS) untuk mendeteksi posisi Anda dan secara otomatis menampilkan lowongan pekerjaan terdekat di sekitar tempat tinggal Anda.
            </p>
            <button onclick="njobRequestGPSLocation()" style="width:100%;padding:14px;background:#5680d8;color:white;border:none;border-radius:14px;font-size:13px;font-weight:800;cursor:pointer;box-shadow:0 4px 16px rgba(86,128,216,.4);display:flex;align-items:center;justify-content:center;gap:8px;">
                <i class='bx bx-target-lock' style="font-size:18px;"></i> Izinkan Akses GPS Lokasi Saya
            </button>
            <button onclick="document.getElementById('njob-gps-modal').style.display='none'" style="margin-top:12px;background:none;border:none;color:#94a3b8;font-size:11px;font-weight:700;cursor:pointer;">Nanti Saja</button>
        </div>
    </div>

    {{-- FILTER MODAL --}}
    <div id="njob-filter" onclick="if(event.target===this)njobToggleFilter()"
         style="display:none;position:fixed;inset:0;z-index:900;background:rgba(0,0,0,.5);align-items:flex-end;">
        <div style="background:white;border-radius:24px 24px 0 0;width:100%;max-height:80vh;overflow-y:auto;padding:20px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;padding-bottom:12px;border-bottom:1px solid #f1f5f9;">
                <span style="font-size:15px;font-weight:800;color:#1e293b;">Filter Lowongan</span>
                <button onclick="njobToggleFilter()" style="background:#f1f5f9;border:none;width:32px;height:32px;border-radius:50%;cursor:pointer;font-size:18px;color:#64748b;font-weight:700;">×</button>
            </div>
            <div style="margin-bottom:18px;">
                <div style="font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">Bidang Pekerjaan</div>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;">
                    @foreach($categories as $k => $v)
                    <button onclick="njobSetCat('{{ $k }}')"
                        style="padding:10px 6px;border-radius:10px;font-size:11px;font-weight:700;border:1.5px solid {{ $filterCategory === $k ? '#5680d8' : '#e2e8f0' }};background:{{ $filterCategory === $k ? '#5680d8' : 'white' }};color:{{ $filterCategory === $k ? 'white' : '#64748b' }};cursor:pointer;">
                        {{ $v }}
                    </button>
                    @endforeach
                </div>
            </div>
            <div style="margin-bottom:18px;">
                <div style="font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">Jenis Pekerjaan</div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach($workTypes as $k => $v)
                    <button onclick="njobSetWT('{{ $k }}')"
                        style="padding:10px 16px;border-radius:10px;font-size:11px;font-weight:700;border:1.5px solid {{ $filterWorkType === $k ? '#5680d8' : '#e2e8f0' }};background:{{ $filterWorkType === $k ? '#5680d8' : 'white' }};color:{{ $filterWorkType === $k ? 'white' : '#64748b' }};cursor:pointer;">
                        {{ $v }}
                    </button>
                    @endforeach
                </div>
            </div>
            <div style="margin-bottom:20px;">
                <div style="font-size:10px;font-weight:800;color:#64748b;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">Jarak Maksimal</div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach([3 => '&lt; 3 km', 5 => '&lt; 5 km', 10 => '&lt; 10 km', 25 => '&lt; 25 km', 50 => 'Semua'] as $km => $label)
                    <button onclick="njobSetRadius({{ $km }})"
                        style="padding:10px 16px;border-radius:10px;font-size:11px;font-weight:700;border:1.5px solid {{ $filterRadius == $km ? '#5680d8' : '#e2e8f0' }};background:{{ $filterRadius == $km ? '#5680d8' : 'white' }};color:{{ $filterRadius == $km ? 'white' : '#64748b' }};cursor:pointer;">
                        {!! $label !!}
                    </button>
                    @endforeach
                </div>
            </div>
            <div style="display:flex;gap:10px;">
                <button onclick="njobSetWT('');njobSetCat('')" style="flex:1;padding:14px;border-radius:14px;border:1.5px solid #e2e8f0;background:white;color:#94a3b8;font-size:13px;font-weight:700;cursor:pointer;">Hapus Filter</button>
                <button onclick="njobToggleFilter()" style="flex:2;padding:14px;border-radius:14px;border:none;background:#5680d8;color:white;font-size:13px;font-weight:700;cursor:pointer;">Terapkan</button>
            </div>
        </div>
    </div>

    <style>
    .njob-card-h:hover { box-shadow:0 12px 36px rgba(86,128,216,.25)!important; border-color:#5680d8!important; transform:translateY(-2px); }
    #njob-cards-carousel::-webkit-scrollbar { display: none; }
    </style>

    @script
    <script>
    (function(){
        const D     = JSON.parse(document.getElementById('njob-map-data').textContent);
        const ULAT  = D.userLat, ULON = D.userLon;
        let JOBS = D.jobs, CARDS = D.jobCards, CRED = D.credits;
        let map = null, markers = {}, selId = null, panelOpen = false;

        function boot(){
            if(typeof L==='undefined'){ setTimeout(boot,200); return; }
            const el = document.getElementById('njob-map');
            if(!el||map) return;
            map = L.map(el,{center:[ULAT,ULON],zoom:13,zoomControl:false});
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png',{maxZoom:19,attribution:'© OSM'}).addTo(map);
            L.control.zoom({position:'bottomright'}).addTo(map);
            const ui = L.divIcon({html:'<div style="width:14px;height:14px;background:#5680d8;border:3px solid white;border-radius:50%;box-shadow:0 0 0 6px rgba(86,128,216,.22),0 2px 8px rgba(86,128,216,.5);"></div>',className:'',iconSize:[14,14],iconAnchor:[7,7]});
            L.marker([ULAT,ULON],{icon:ui,zIndexOffset:2000}).addTo(map);
            drawPins();
            setTimeout(()=>map.invalidateSize(),300);
        }

        function drawPins(){
            if(!map) return;
            Object.values(markers).forEach(m=>map.removeLayer(m));
            markers={};
            JOBS.forEach(j=>{
                if(!j.latitude||!j.longitude) return;
                const sel=selId===j.id, sz=sel?44:36, col=sel?'#24427b':'#5680d8';
                const quotaVal = (typeof j.quota !== 'undefined' && j.quota !== null) ? j.quota : 1;
                const ico=L.divIcon({html:'<div style="display:flex;flex-direction:column;align-items:center;"><div style="width:'+sz+'px;height:'+sz+'px;background:'+col+';border:3px solid white;border-radius:50% 50% 50% 0;transform:rotate(-45deg);display:flex;align-items:center;justify-content:center;box-shadow:0 4px 14px rgba(36,66,123,.4);"><span style="transform:rotate(45deg);color:white;font-size:'+(sel?13:11)+'px;font-weight:800;">'+quotaVal+'</span></div></div>',className:'',iconSize:[sz,sz+6],iconAnchor:[sz/2,sz+6]});
                const m=L.marker([j.latitude,j.longitude],{icon:ico}).on('click',()=>{ 
                    selId=j.id; 
                    drawPins(); 
                    map.panTo([j.latitude,j.longitude],{animate:true,duration:.4}); 
                    highlightCard(j.id);
                    njobOpenSheet(j.id);
                }).addTo(map);
                markers[j.id]=m;
            });
        }

        function highlightCard(id){
            document.querySelectorAll('.njob-card-h').forEach(c=>{
                const a=Number(c.dataset.jobId)===Number(id);
                c.style.borderColor=a?'#5680d8':'#e8edf5';
                c.style.borderWidth=a?'2px':'1.5px';
                c.style.boxShadow=a?'0 12px 30px rgba(86,128,216,.25)':'0 8px 30px rgba(0,0,0,.15)';
                if(a) c.scrollIntoView({behavior:'smooth',block:'nearest',inline:'center'});
            });
        }

        window.njobFocusCard=function(el){
            const id=Number(el.dataset.jobId), lat=parseFloat(el.dataset.lat), lon=parseFloat(el.dataset.lon);
            selId=id; drawPins(); 
            if(map&&lat&&lon) map.panTo([lat,lon],{animate:true,duration:.4}); 
            highlightCard(id);
        };

        window.njobToggleHorizontalCards=function(forceState){
            if(typeof forceState === 'boolean') panelOpen = forceState;
            else panelOpen = !panelOpen;

            const panel = document.getElementById('njob-horizontal-panel');
            const btn = document.getElementById('njob-btn-wrapper');

            if(panelOpen){
                panel.style.display = 'flex';
                requestAnimationFrame(() => {
                    panel.style.transform = 'translateY(0)';
                    panel.style.opacity = '1';
                });
                btn.style.opacity = '0';
                btn.style.pointerEvents = 'none';
            } else {
                panel.style.transform = 'translateY(120%)';
                panel.style.opacity = '0';
                setTimeout(() => {
                    if(!panelOpen) panel.style.display = 'none';
                }, 350);
                btn.style.opacity = '1';
                btn.style.pointerEvents = 'auto';
            }
        };

        window.njobOpenSheet=function(jobId){
            const j=CARDS.find(c=>String(c.id)===String(jobId)); if(!j) return;
            const html='<div style="display:flex;align-items:center;gap:14px;margin-bottom:16px;">'
                +'<div style="width:56px;height:56px;background:#eef2fb;color:#5680d8;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:900;border:1.5px solid #c7d6f5;flex-shrink:0;">'+j.initial+'</div>'
                +'<div><div style="font-size:16px;font-weight:800;color:#1e293b;line-height:1.3;">'+j.position+'</div><div style="font-size:12px;color:#5680d8;font-weight:700;margin-top:3px;">'+j.company+' <span style=\"color:#47bfae;\">✓</span></div></div>'
                +'</div>'
                +'<div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;background:#f8faff;border-radius:14px;padding:14px;margin-bottom:16px;border:1px solid #e8edf5;">'
                +'<div style="font-size:12px;color:#475569;font-weight:600;">📍 '+j.distance+' km dari Anda</div>'
                +'<div style="font-size:12px;color:#475569;font-weight:600;">💰 '+j.salary+'</div>'
                +'<div style="font-size:12px;color:#475569;font-weight:600;">🕐 '+j.workType+'</div>'
                +'<div style="font-size:12px;color:#475569;font-weight:600;">🎓 Min. '+j.education+'</div>'
                +'<div style="font-size:12px;color:#2563eb;font-weight:800;grid-column:span 2;background:#eff6ff;padding:8px 12px;border-radius:10px;border:1px solid #bfdbfe;">🎯 Kuota: '+(j.quota||1)+' Lowongan Dibutuhkan</div>'
                +'</div>'
                +'<div style="display:flex;gap:10px;">'
                +'<a href="'+j.applyRoute+'" style="flex:1;padding:14px;text-align:center;font-size:13px;font-weight:700;color:#475569;background:white;border:2px solid #e2e8f0;border-radius:14px;text-decoration:none;">Detail</a>'
                +(CRED>0
                    ? '<button onclick="njobApply('+j.id+')" style="flex:2;padding:14px;font-size:13px;font-weight:800;color:white;background:#5680d8;border:none;border-radius:14px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 20px rgba(86,128,216,.4);">Lamar Sekarang <span style="background:rgba(255,255,255,.25);padding:2px 8px;border-radius:20px;font-size:10px;">-1 Kredit</span></button>'
                    : '<button style="flex:2;padding:14px;font-size:13px;font-weight:800;background:#cbd5e1;color:white;border:none;border-radius:14px;cursor:not-allowed;display:flex;align-items:center;justify-content:center;">Kredit Habis</button>'
                )
                +'</div>'
                +(CRED<=0?'<div style="margin-top:12px;padding:12px;background:#fff5f5;border:1px solid #fecaca;border-radius:12px;text-align:center;font-size:12px;font-weight:700;color:#dc2626;">Kredit habis · <a href="'+j.profileRoute+'" style="color:#5680d8;">Beli 1 Kredit — Rp5.999</a></div>':'');
            document.getElementById('njob-sheet-body').innerHTML=html;
            const sheet = document.getElementById('njob-sheet');
            sheet.style.display = 'block';
            requestAnimationFrame(() => { sheet.style.transform='translateY(0)'; });
            document.getElementById('njob-overlay').style.display='block';
        };

        window.njobCloseSheet=function(){ 
            const sheet = document.getElementById('njob-sheet');
            sheet.style.transform='translateY(100%)'; 
            document.getElementById('njob-overlay').style.display='none';
            setTimeout(() => { sheet.style.display = 'none'; }, 300);
        };

        window.njobApply=function(jobId){
            const wid=document.querySelector('[wire\\:id]')?.getAttribute('wire:id');
            if(wid) Livewire.find(wid).call('selectJob',jobId).then(()=>Livewire.find(wid).call('applyForJob'));
        };

        window.njobSetWT=function(w){ @this.set('filterWorkType',w); };
        window.njobSetCat=function(c){ @this.set('filterCategory',c); };
        window.njobSetRadius=function(r){ @this.set('filterRadius',r); };
        window.njobToggleFilter=function(){ const m=document.getElementById('njob-filter'); m.style.display=m.style.display==='flex'?'none':'flex'; };

        window.njobPromptGPS = function(){
            document.getElementById('njob-gps-modal').style.display = 'flex';
        };

        window.njobRequestGPSLocation = function(){
            if(navigator.geolocation){
                navigator.geolocation.getCurrentPosition(
                    function(pos){
                        const lat = pos.coords.latitude;
                        const lon = pos.coords.longitude;
                        document.getElementById('njob-gps-modal').style.display = 'none';
                        if(map){
                            map.setView([lat, lon], 14, {animate:true});
                            L.marker([lat, lon], {
                                icon: L.divIcon({
                                    html: '<div style="width:16px;height:16px;background:#2a9d8f;border:3px solid white;border-radius:50%;box-shadow:0 0 0 8px rgba(42,157,143,.3);"></div>',
                                    className: '', iconSize: [16,16], iconAnchor: [8,8]
                                })
                            }).addTo(map);
                        }
                    },
                    function(err){
                        alert('Silakan aktifkan akses GPS lokasi pada browser/HP Anda.');
                    }
                );
            } else {
                alert('Browser Anda belum mendukung fitur GPS.');
            }
        };

        if(!localStorage.getItem('njob_gps_notified')){
            setTimeout(window.njobPromptGPS, 1000);
            localStorage.setItem('njob_gps_notified', 'true');
        }

        function refreshDataAndPins(){
            try {
                const updatedData = JSON.parse(document.getElementById('njob-map-data').textContent);
                JOBS = updatedData.jobs || [];
                CARDS = updatedData.jobCards || [];
                CRED = updatedData.credits || 0;
            } catch(e){}
            drawPins();
        }

        if(document.readyState==='loading') document.addEventListener('DOMContentLoaded',boot);
        else setTimeout(boot,80);
        if(window.Livewire) Livewire.hook('morph.updated',()=>setTimeout(refreshDataAndPins,100));
    })();
    </script>
    @endscript
</div>
