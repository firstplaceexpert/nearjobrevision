{{-- Riwayat Lamaran Pelamar --}}
<div style="background: #f0f4f9; min-height: calc(100vh - 172px); padding: 16px 0 100px;">
    <div class="max-w-2xl mx-auto px-4">

        {{-- ===== HEADER ===== --}}
        <div class="flex items-center justify-between mb-8 pb-5 border-b border-slate-200/80">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Riwayat Lamaran</h1>
                <p class="text-slate-500 text-xs mt-1.5 font-semibold">Pantau status lamaran pekerjaan yang Anda kirimkan</p>
            </div>
            <a href="{{ route('applicant.map') }}"
               class="px-4 py-2.5 rounded-xl flex items-center gap-2 text-xs font-extrabold transition-all text-white shadow-md hover:opacity-95"
               style="background: #5680d8; box-shadow: 0 4px 16px rgba(86,128,216,.35);">
                <i class='bx bx-map-alt text-base'></i> Cari Lowongan
            </a>
        </div>

        @if($applications->isEmpty())
            <div class="text-center py-16 px-6 bg-white rounded-3xl my-6 border" style="border-color: #e8edf5; box-shadow: 0 4px 20px rgba(0,0,0,.03);">
                <div class="w-16 h-16 rounded-2xl mx-auto mb-4 flex items-center justify-center shadow-sm" style="background: #eef2fb; color: #5680d8;">
                    <i class='bx bx-notepad text-3xl'></i>
                </div>
                <h3 class="text-base font-extrabold text-slate-800">Belum Ada Lamaran Kiriman</h3>
                <p class="text-xs text-slate-400 mt-2 max-w-[260px] mx-auto leading-relaxed font-medium">Anda belum pernah melamar pekerjaan. Ayo temukan berbagai peluang kerja terdekat di sekitar lokasi Anda!</p>
                <a href="{{ route('applicant.map') }}"
                   class="inline-flex items-center gap-2 mt-6 px-6 py-3 text-white text-xs font-extrabold rounded-xl shadow-lg"
                   style="background: #5680d8; box-shadow: 0 4px 15px rgba(86,128,216,.35);">
                    <i class='bx bx-map-alt text-base'></i> Buka Peta Lowongan Pekerjaan
                </a>
            </div>
        @else
            <div style="display: flex; flex-direction: column; gap: 32px; margin-bottom: 48px;">
                @foreach($applications as $app)
                @php
                    $statusConfig = match($app->status) {
                        'menunggu'    => ['bg' => '#fffbeb', 'text' => '#d97706', 'border' => '#fde68a', 'label' => 'Menunggu Respons', 'icon' => 'bx-time-five'],
                        'dihubungi'   => ['bg' => '#eef2fb', 'text' => '#5680d8', 'border' => '#c7d6f5', 'label' => 'Dihubungi Pemberi Kerja', 'icon' => 'bxl-whatsapp'],
                        'interview'   => ['bg' => '#f5f0ff', 'text' => '#7c3aed', 'border' => '#ddd6fe', 'label' => 'Tahap Interview', 'icon' => 'bx-user-voice'],
                        'diterima'    => ['bg' => '#e6f8f6', 'text' => '#2a9d8f', 'border' => '#99f6e4', 'label' => 'Diterima Bekerja!', 'icon' => 'bx-check-circle'],
                        'tidak_lolos' => ['bg' => '#fff5f5', 'text' => '#ef4444', 'border' => '#fecaca', 'label' => 'Belum Sesuai', 'icon' => 'bx-x-circle'],
                        default       => ['bg' => '#f8faff', 'text' => '#5680d8', 'border' => '#e8edf5', 'label' => 'Diproses', 'icon' => 'bx-loader'],
                    };
                @endphp

                <div class="bg-white rounded-3xl overflow-hidden transition-all duration-200 border" style="border-color: #e2e8f0; box-shadow: 0 6px 20px rgba(0,0,0,.04);">
                    
                    {{-- Status Bar Top (Sangat Jelas & Berjarak) --}}
                    <div class="px-6 py-4 flex items-center justify-between gap-3"
                         style="background: {{ $statusConfig['bg'] }}; border-bottom: 1.5px solid {{ $statusConfig['border'] }};">
                        <div class="flex items-center gap-2.5">
                            <i class='bx {{ $statusConfig['icon'] }} text-lg' style="color: {{ $statusConfig['text'] }};"></i>
                            <span class="text-xs font-black tracking-wide" style="color: {{ $statusConfig['text'] }};">
                                {{ $statusConfig['label'] }}
                            </span>
                        </div>
                        <span class="text-xs font-bold text-slate-400">
                            {{ $app->application_date ? $app->application_date->format('d M Y') : $app->created_at->format('d M Y') }}
                        </span>
                    </div>

                    {{-- Card Body dengan Spasi Luas --}}
                    <div style="padding: 26px 26px 30px;">
                        {{-- Company & Position --}}
                        <div class="flex items-start gap-4" style="margin-bottom: 24px;">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl font-black shrink-0 border shadow-sm"
                                 style="background: #eef2fb; color: #5680d8; border-color: #c7d6f5;">
                                {{ substr($app->jobListing->company->company_name, 0, 1) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="font-black text-base text-slate-800 leading-snug mb-1.5 truncate">
                                    <a href="{{ route('applicant.job.detail', $app->job_listing_id) }}" class="hover:underline" style="color: #24427b;">
                                        {{ $app->jobListing->position }}
                                    </a>
                                </h3>
                                <p class="text-xs text-slate-600 font-extrabold mb-2 truncate">{{ $app->jobListing->company->company_name }}</p>
                                
                                {{-- Kota & Gaji Dipisah dengan Berjarak Nyaman --}}
                                <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-top: 6px;">
                                    <span style="display: inline-flex; align-items: center; gap: 6px; background: #f1f5f9; color: #334155; padding: 6px 14px; border-radius: 10px; font-size: 11.5px; font-weight: 700; border: 1px solid #e2e8f0;">
                                        📍 {{ $app->jobListing->company->city }}
                                    </span>
                                    <span style="display: inline-flex; align-items: center; gap: 6px; background: #eef2fb; color: #24427b; padding: 6px 14px; border-radius: 10px; font-size: 11.5px; font-weight: 800; border: 1px solid #c7d6f5;">
                                        💰 {{ $app->jobListing->salary_range }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Details Box (Wrap 1 - Spasial & Lega) --}}
                        <div class="grid grid-cols-2 gap-4 rounded-2xl text-xs" 
                             style="background: #f8faff; border: 1.5px solid #e8edf5; padding: 20px 22px; margin-bottom: 20px;">
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">JENIS KERJA</span>
                                <span class="font-black text-slate-800 text-xs sm:text-sm">{{ $app->jobListing->work_type_label }}</span>
                            </div>
                            <div>
                                <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1">KONTAK LAMAR</span>
                                <span class="font-extrabold text-xs sm:text-sm inline-flex items-center gap-1.5 {{ $app->contact_method === 'whatsapp' ? 'text-green-600' : 'text-blue-600' }}">
                                    {!! $app->contact_method === 'whatsapp' ? "<i class='bx bxl-whatsapp text-base'></i> WhatsApp" : "<i class='bx bx-envelope text-base'></i> Email" !!}
                                </span>
                            </div>
                        </div>

                        {{-- Status Message Banner (Wrap 2 - Berjarak Nyaman dari Wrap 1) --}}
                        @if($app->status === 'menunggu')
                            <div class="rounded-2xl text-center bg-slate-50 border border-slate-200" style="padding: 18px 22px;">
                                <p class="text-xs text-slate-500 font-bold italic">Menunggu respons & verifikasi dari pihak pemberi kerja...</p>
                            </div>
                        @elseif($app->status === 'dihubungi')
                            <div class="rounded-2xl flex items-center gap-3.5" style="background: #eef2fb; border: 1.5px solid #c7d6f5; padding: 18px 22px;">
                                <i class='bx bxl-whatsapp text-2xl shrink-0' style="color: #16a34a;"></i>
                                <span class="text-xs font-bold text-slate-700 leading-relaxed">Pemberi kerja akan segera menghubungi Anda via WhatsApp. Harap pastikan nomor HP aktif.</span>
                            </div>
                        @elseif($app->status === 'diterima')
                            <div class="rounded-2xl text-center" style="background: #e6f8f6; border: 1.5px solid #99f6e4; padding: 18px 22px;">
                                <p class="text-xs font-black" style="color: #2a9d8f;">🎉 Selamat! Anda telah diterima bekerja di tempat ini!</p>
                            </div>
                        @elseif($app->status === 'tidak_lolos')
                            <div class="rounded-2xl text-center" style="background: #fff5f5; border: 1.5px solid #fecaca; padding: 18px 22px;">
                                <p class="text-xs font-bold text-red-500">Tetap semangat! Masih banyak peluang lowongan lain di Near Job.</p>
                            </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
