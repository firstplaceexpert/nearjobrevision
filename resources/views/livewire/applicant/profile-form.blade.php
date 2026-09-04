{{-- Profil Pelamar NEAR JOB --}}
<div style="background: #f0f4f9; min-height: calc(100vh - 172px); padding: 16px 0 140px;">

    <div class="max-w-2xl mx-auto px-4" style="display: flex; flex-direction: column; gap: 32px;">

        {{-- ===== PROFILE BANNER HEADER (CLEAN & NO OVERLAP) ===== --}}
        <div class="rounded-3xl overflow-hidden shadow-md text-white text-center relative" 
             style="background: linear-gradient(160deg, #1e3a8a 0%, #3b82f6 100%); padding: 36px 28px 32px;">
            
            {{-- Background Pattern / Cover Photo Overlay --}}
            @if($cover_picture)
                <div class="absolute inset-0 bg-cover bg-center pointer-events-none transition-all duration-300"
                     style="background-image: url('{{ $cover_picture }}');">
                    <div class="absolute inset-0" style="background: linear-gradient(180deg, rgba(15,23,42,0.45) 0%, rgba(15,23,42,0.78) 100%);"></div>
                </div>
            @else
                <div class="absolute inset-0 opacity-25 bg-cover bg-center pointer-events-none"
                     style="background-image: url('https://images.unsplash.com/photo-1579546929518-9e396f3cc809?auto=format&fit=crop&w=1200&q=80');"></div>
            @endif

            {{-- Cover / Background Picker Trigger --}}
            <button onclick="document.getElementById('cover-picker-modal').style.display='flex'" 
                    class="absolute top-5 right-5 bg-black/40 hover:bg-black/60 text-white backdrop-blur-md px-3.5 py-2 rounded-xl text-xs font-bold transition-all flex items-center gap-1.5 border border-white/20 z-20 shadow-sm cursor-pointer">
                <i class='bx bx-camera text-sm'></i> Ubah Sampul
            </button>

            {{-- Avatar & User Information --}}
            <div class="relative z-10 pt-2">
                {{-- Avatar Circle --}}
                <div class="relative inline-block mb-3.5">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full mx-auto flex items-center justify-center text-3xl sm:text-4xl font-black border-4 border-white/90 shadow-xl overflow-hidden bg-white/20 text-white backdrop-blur-md relative">
                        @if($profile_picture)
                            <img src="{{ $profile_picture }}" alt="Foto Profil" class="w-full h-full object-cover">
                        @else
                            {{ substr(auth()->user()->name, 0, 1) }}
                        @endif

                        {{-- Loading spinner saat upload file --}}
                        <div wire:loading wire:target="avatarFile" class="absolute inset-0 bg-black/60 rounded-full flex flex-col items-center justify-center text-white text-[10px] font-bold">
                            <i class='bx bx-loader-alt bx-spin text-2xl mb-1'></i>
                            <span>Upload...</span>
                        </div>
                    </div>
                    <button onclick="document.getElementById('avatar-picker-modal').style.display='flex'" 
                            class="absolute bottom-0 right-0 w-9 h-9 rounded-full border-2 border-white flex items-center justify-center text-white shadow-lg transition-transform hover:scale-110 cursor-pointer" 
                            style="background: #5680d8;" title="Ubah Foto Profil">
                        <i class='bx bx-camera text-base'></i>
                    </button>
                </div>

                {{-- Name & City --}}
                <h1 class="text-xl sm:text-2xl font-black text-white leading-tight mb-1.5 drop-shadow-sm">{{ auth()->user()->name }}</h1>
                <p class="text-white/90 text-xs sm:text-sm font-semibold mb-3.5 flex items-center justify-center gap-1.5">
                    <i class='bx bx-map-pin text-sm text-blue-200'></i>
                    {{ auth()->user()->applicantProfile?->city ?? 'Lokasi belum diatur' }}
                </p>

                {{-- Status Badge --}}
                <div class="inline-flex items-center gap-2 px-5 py-2 rounded-full text-xs font-extrabold text-white shadow-sm mb-6" style="background: #2a9d8f;">
                    <i class='bx bx-check-circle text-sm'></i> Status: Aktif Cari Kerja
                </div>

                {{-- Action Buttons (Inside Banner, Fully Clear) --}}
                <div class="flex justify-center items-center gap-3.5 pt-1 flex-wrap">
                    <a href="{{ route('applicant.applications') }}"
                       class="px-5 py-3 rounded-xl text-xs sm:text-sm font-extrabold border border-white/30 transition-all hover:bg-white/10 flex items-center gap-2 shadow-sm"
                       style="background: rgba(255,255,255,.18); color: white;">
                        <i class='bx bx-briefcase-alt-2 text-base'></i> Riwayat Lamaran
                    </a>
                    <button onclick="document.getElementById('profile-form-section').scrollIntoView({behavior:'smooth'})"
                       class="px-5 py-3 rounded-xl text-xs sm:text-sm font-extrabold transition-all hover:bg-slate-100 flex items-center gap-2 shadow-md"
                       style="background: white; color: #1e3a8a;">
                        <i class='bx bx-edit-alt text-base'></i> Edit Data Profil
                    </button>
                </div>
            </div>
        </div>

        {{-- ===== KREDIT & CV CARDS GRID ===== --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px;">
            {{-- Credits Card --}}
            <div class="rounded-3xl text-white relative overflow-hidden flex flex-col justify-between" 
                 style="background: linear-gradient(135deg, #24427b, #5680d8); box-shadow: 0 4px 20px rgba(37,67,155,.25); padding: 28px 24px;">
                <div class="absolute -right-4 -bottom-4 text-7xl opacity-10">🎫</div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-blue-200 block mb-1.5">KESEMPATAN MELAMAR</span>
                    <p class="text-3xl font-black mb-2">{{ $credits }} <span class="text-xs font-bold text-blue-200">Kredit Tersisa</span></p>
                    <p class="text-xs text-blue-100/90 font-medium leading-relaxed">1 kredit digunakan per lamaran yang dikirim.</p>
                </div>
                <button onclick="document.getElementById('buy-credits-section').scrollIntoView({behavior:'smooth'})"
                   class="mt-6 text-xs sm:text-sm font-extrabold px-4 py-3.5 rounded-xl w-full text-center transition-all shadow-sm flex items-center justify-center gap-2 hover:opacity-95"
                   style="background: white; color: #24427b;">
                    <i class='bx bx-plus-circle text-base'></i> Beli Kredit Lamaran
                </button>
            </div>

            {{-- CV Card --}}
            <div class="bg-white rounded-3xl border flex flex-col justify-between shadow-sm" style="border-color: #e2e8f0; padding: 28px 24px;">
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-400 block mb-1.5">DOKUMEN CV ATS</span>
                    @if($cv_generated)
                        <div class="flex items-center gap-2 font-black text-sm" style="color: #2a9d8f;">
                            <i class='bx bx-check-circle text-xl'></i> CV Tersedia
                        </div>
                        <p class="text-xs text-slate-500 font-medium mt-1 leading-relaxed">CV standar profesional siap dikirim ke pemberi kerja.</p>
                    @else
                        <div class="flex items-center gap-2 text-orange-500 font-black text-sm">
                            <i class='bx bx-time-five text-xl'></i> CV Belum Dibuat
                        </div>
                        <p class="text-xs text-slate-400 font-medium mt-1 leading-relaxed">Buat CV profesional otomatis dalam 1 kali klik.</p>
                    @endif
                </div>
                <a href="https://cvatsplygrown.vercel.app" target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center justify-center gap-2 w-full text-center text-xs sm:text-sm font-extrabold px-4 py-3.5 rounded-xl mt-6 transition-all shadow-sm hover:opacity-95"
                   style="background: linear-gradient(135deg, #1e3a8a, #3b82f6); color: white; text-decoration: none;">
                    <i class='bx bx-file-blank text-base'></i>
                    Buat CV ATS di CV ATS PLYGROWN <i class='bx bx-link-external text-xs'></i>
                </a>
            </div>
        </div>

        {{-- ===== PROFILE COMPLETENESS CHECKLIST ===== --}}
        <div class="bg-white rounded-3xl overflow-hidden border shadow-sm" style="border-color: #e2e8f0;">
            <div style="padding: 22px 26px; border-bottom: 1.5px solid #f1f5f9; background: #fafcff;">
                <h3 class="font-black text-slate-800 text-sm">Kelengkapan Profil Anda</h3>
                <p class="text-xs text-slate-500 mt-1 font-medium">Profil yang lengkap meningkatkan prioritas lamaran Anda</p>
            </div>
            @php
                $profile = auth()->user()->applicantProfile;
                $checks = [
                    ['Informasi Pribadi & Kontak', !empty($this->whatsapp) && !empty($this->city)],
                    ['Pendidikan Terakhir', !empty($this->education_institution)],
                    ['Pengalaman Kerja & Keahlian', !empty($this->work_experience) || count($this->skills) > 0],
                    ['Ekspektasi Gaji', !empty($this->salary_expectation)],
                ];
            @endphp
            <div class="divide-y divide-slate-100">
                @foreach($checks as [$label, $done])
                <div class="flex items-center justify-between" style="padding: 18px 26px;">
                    <span class="text-xs sm:text-sm {{ $done ? 'text-slate-800 font-extrabold' : 'text-slate-400 font-medium' }}">{{ $label }}</span>
                    @if($done)
                        <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0" style="background: #2a9d8f;">
                            <i class='bx bx-check text-white font-black text-sm'></i>
                        </div>
                    @else
                        <div class="w-6 h-6 rounded-full flex items-center justify-center shrink-0 bg-slate-100">
                            <i class='bx bx-minus text-slate-400 font-bold text-xs'></i>
                        </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>

        {{-- ===== BUY CREDITS SECTION ===== --}}
        <div id="buy-credits-section" class="bg-white rounded-3xl overflow-hidden border shadow-sm" style="border-color: #e2e8f0;">
            <div class="flex items-center justify-between" style="padding: 22px 26px; border-bottom: 1.5px solid #f1f5f9; background: #fafcff;">
                <div>
                    <h3 class="font-black text-slate-800 text-sm">Tambah Kredit Lamaran</h3>
                    <p class="text-xs text-slate-500 mt-1 font-medium">Melamar lowongan pekerjaan lokal tanpa batas</p>
                </div>
                <span class="text-xs font-black px-3.5 py-1.5 rounded-full" style="background: #eef2fb; color: #5680d8;">
                    {{ $credits }}/3 Kredit
                </span>
            </div>
            
            <div style="padding: 26px 26px 30px;">
                <div class="flex items-center justify-between" style="padding: 20px 22px; margin-bottom: 22px; border-radius: 18px; background: #f8faff; border: 1.5px solid #e8edf5;">
                    <div>
                        <span class="text-xs sm:text-sm font-extrabold text-slate-800 block mb-1">Paket 1 Kredit Melamar</span>
                        <span class="text-xs text-slate-400 font-medium">Bisa langsung digunakan melamar ke WhatsApp/Email</span>
                    </div>
                    <span class="text-xl font-black" style="color: #5680d8;">Rp5.999</span>
                </div>

                <button wire:click="buyCredit"
                    class="w-full py-4 text-white font-black rounded-2xl text-xs sm:text-sm flex items-center justify-center gap-2 transition-all shadow-md hover:opacity-95"
                    style="background: #5680d8; box-shadow: 0 4px 16px rgba(86,128,216,.35);">
                    <i class='bx bx-credit-card text-base'></i>
                    Beli 1 Kredit Sekarang — Rp5.999
                </button>
            </div>
        </div>

        {{-- ===== EDIT PROFILE FORM ===== --}}
        <div id="profile-form-section" class="bg-white rounded-3xl overflow-hidden border shadow-sm" style="border-color: #e2e8f0;">
            <div style="padding: 22px 26px; border-bottom: 1.5px solid #f1f5f9; background: #fafcff;">
                <h3 class="font-black text-slate-800 text-sm">Formulir Data Diri & Profil</h3>
                <p class="text-xs text-slate-500 mt-1 font-medium">Lengkapi data agar pemberi kerja dapat menghubungi Anda</p>
            </div>
            
            <div style="padding: 32px 26px 36px;">
                <form wire:submit="save">

                    {{-- SECTION 1: DATA PRIBADI --}}
                    <div style="margin-bottom: 32px;">
                        <div style="padding-bottom: 12px; border-bottom: 1.5px solid #f1f5f9; margin-bottom: 22px;">
                            <h4 class="text-xs font-black uppercase tracking-widest" style="color: #5680d8;">1. Informasi Kontak & Domisili</h4>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <div>
                                <label class="block text-xs sm:text-sm font-extrabold text-slate-700 mb-2">Nama Lengkap</label>
                                <input type="text" wire:model="name"
                                    class="w-full text-xs sm:text-sm transition-all focus:outline-none focus:ring-2"
                                    style="padding: 14px 18px; border-radius: 14px; border: 1.5px solid #e2e8f0; background: #f8faff;"
                                    placeholder="Masukkan nama lengkap Anda">
                                @error('name') <span class="text-xs text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-extrabold text-slate-700 mb-2">Alamat Email (Akun)</label>
                                <input type="email" wire:model="email" disabled
                                    class="w-full text-xs sm:text-sm cursor-not-allowed"
                                    style="padding: 14px 18px; border-radius: 14px; border: 1.5px solid #e2e8f0; background: #f1f5f9; color: #94a3b8;">
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-extrabold text-slate-700 mb-2">Nomor WhatsApp (Aktif)</label>
                                <input type="tel" wire:model="whatsapp"
                                    class="w-full text-xs sm:text-sm transition-all focus:outline-none focus:ring-2"
                                    style="padding: 14px 18px; border-radius: 14px; border: 1.5px solid #e2e8f0; background: #f8faff;"
                                    placeholder="Contoh: 08123456789">
                                @error('whatsapp') <span class="text-xs text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-extrabold text-slate-700 mb-2">Kota Domisili</label>
                                <select wire:model="city"
                                    class="w-full text-xs sm:text-sm font-semibold"
                                    style="padding: 14px 18px; border-radius: 14px; border: 1.5px solid #e2e8f0; background: #f8faff;">
                                    <option value="">Pilih Kota Domisili</option>
                                    @foreach($cities as $c)
                                        <option value="{{ $c->name }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                @error('city') <span class="text-xs text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 2: PENDIDIKAN --}}
                    <div style="margin-bottom: 32px;">
                        <div style="padding-bottom: 12px; border-bottom: 1.5px solid #f1f5f9; margin-bottom: 22px;">
                            <h4 class="text-xs font-black uppercase tracking-widest" style="color: #5680d8;">2. Pendidikan & Keahlian</h4>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <div>
                                <label class="block text-xs sm:text-sm font-extrabold text-slate-700 mb-2">Pendidikan Terakhir</label>
                                <select wire:model="education_level"
                                    class="w-full text-xs sm:text-sm font-semibold"
                                    style="padding: 14px 18px; border-radius: 14px; border: 1.5px solid #e2e8f0; background: #f8faff;">
                                    @foreach($educationLevels as $k => $v)
                                        <option value="{{ $k }}">{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-extrabold text-slate-700 mb-2">Nama Sekolah / Tempat Pendidikan</label>
                                <input type="text" wire:model="education_institution"
                                    class="w-full text-xs sm:text-sm transition-all"
                                    style="padding: 14px 18px; border-radius: 14px; border: 1.5px solid #e2e8f0; background: #f8faff;"
                                    placeholder="Contoh: SMA Negeri 1 Yogyakarta">
                                @error('education_institution') <span class="text-xs text-red-500 mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-extrabold text-slate-700 mb-2">Jurusan (Opsional)</label>
                                <input type="text" wire:model="field_of_study"
                                    class="w-full text-xs sm:text-sm transition-all"
                                    style="padding: 14px 18px; border-radius: 14px; border: 1.5px solid #e2e8f0; background: #f8faff;"
                                    placeholder="Contoh: IPA / IPS / Tata Boga">
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-extrabold text-slate-700 mb-2">Keahlian (Skills)</label>
                                <div style="display: flex; gap: 12px; margin-bottom: 16px;">
                                    <input type="text" wire:model="newSkill" wire:keydown.enter.prevent="addSkill"
                                        class="flex-grow text-xs sm:text-sm"
                                        style="padding: 13px 18px; border-radius: 14px; border: 1.5px solid #e2e8f0; background: #f8faff;"
                                        placeholder="Ketik keahlian lalu tekan Tambah...">
                                    <button type="button" wire:click="addSkill"
                                        class="text-white font-extrabold text-xs shrink-0 hover:opacity-95"
                                        style="padding: 13px 22px; border-radius: 14px; background: #5680d8;">Tambah</button>
                                </div>
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    @foreach($skills as $i => $sk)
                                        <span class="inline-flex items-center gap-1.5 text-xs font-bold"
                                              style="padding: 8px 14px; border-radius: 12px; background: #eef2fb; color: #5680d8; border: 1px solid #c7d6f5;">
                                            {{ $sk }}
                                            <button type="button" wire:click="removeSkill({{ $i }})" class="hover:text-red-500 font-bold ml-1 text-sm">×</button>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- SECTION 3: PENGALAMAN KERJA --}}
                    <div style="margin-bottom: 32px;">
                        <div style="padding-bottom: 12px; border-bottom: 1.5px solid #f1f5f9; margin-bottom: 22px;">
                            <h4 class="text-xs font-black uppercase tracking-widest" style="color: #5680d8;">3. Pengalaman Kerja & Ekspektasi</h4>
                        </div>

                        <div style="display: flex; flex-direction: column; gap: 20px;">
                            <div>
                                <label class="block text-xs sm:text-sm font-extrabold text-slate-700 mb-2">Pengalaman Kerja Sebelumnya</label>
                                <textarea wire:model="work_experience" rows="4"
                                    class="w-full text-xs sm:text-sm leading-relaxed resize-none"
                                    style="padding: 16px 18px; border-radius: 14px; border: 1.5px solid #e2e8f0; background: #f8faff;"
                                    placeholder="Ceritakan riwayat pekerjaan yang pernah Anda jalani sebelumnya..."></textarea>
                            </div>

                            <div>
                                <label class="block text-xs sm:text-sm font-extrabold text-slate-700 mb-2">Ekspektasi Gaji per Bulan (Opsional)</label>
                                <input type="text" wire:model="salary_expectation"
                                    class="w-full text-xs sm:text-sm transition-all"
                                    style="padding: 14px 18px; border-radius: 14px; border: 1.5px solid #e2e8f0; background: #f8faff;"
                                    placeholder="Contoh: Rp 2.000.000 - Rp 2.500.000">
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full text-white font-black text-sm sm:text-base transition-all shadow-md hover:opacity-95"
                        style="padding: 18px 28px; border-radius: 18px; background: #5680d8; box-shadow: 0 4px 16px rgba(86,128,216,.35); margin-top: 32px;">
                        Simpan Semua Perubahan Profil
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- ===== MODAL PICKER COVER BACKGROUND ===== --}}
    <div id="cover-picker-modal" onclick="if(event.target===this)this.style.display='none'" 
         style="display:none;position:fixed;inset:0;z-index:900;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:16px;">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 overflow-hidden shadow-2xl border border-slate-100">
            
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="font-black text-base text-slate-800">Ubah Foto Sampul (Background)</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Upload dari galeri HP / komputer atau pilih gambar sampul</p>
                </div>
                <button type="button" onclick="document.getElementById('cover-picker-modal').style.display='none'" class="text-slate-400 hover:text-slate-600 font-black text-2xl w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 cursor-pointer">×</button>
            </div>

            {{-- 1. UPLOAD DARI GALERI --}}
            <div class="mb-5">
                <label for="cover-file-input" 
                       class="border-2 border-dashed rounded-2xl p-5 flex flex-col items-center justify-center cursor-pointer transition-all text-center group"
                       style="border-color: #93c5fd; background: #f0f7ff;">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl mb-2.5 shadow-sm group-hover:scale-105 transition-transform"
                         style="background: #5680d8; color: white;">
                        <i class='bx bx-cloud-upload'></i>
                    </div>
                    <span class="text-sm font-black text-slate-800">Upload Foto dari Galeri / File</span>
                    <span class="text-xs text-slate-500 font-medium mt-1">Mendukung format JPG, PNG, WEBP (Maks. 8MB)</span>
                </label>
                <input type="file" id="cover-file-input" wire:model="coverFile" accept="image/*" class="hidden" onchange="document.getElementById('cover-picker-modal').style.display='none';">
                
                <div wire:loading wire:target="coverFile" class="text-center py-2 text-xs font-bold text-blue-600">
                    <i class='bx bx-loader-alt bx-spin text-sm mr-1'></i> Mengunggah foto sampul dari galeri...
                </div>
                @error('coverFile')
                    <span class="text-xs text-red-500 font-bold block mt-1 text-center">{{ $message }}</span>
                @enderror
            </div>

            {{-- PEMBATAS --}}
            <div class="relative flex items-center justify-center mb-4">
                <div class="border-t border-slate-200 w-full"></div>
                <span class="bg-white px-3 text-xs font-bold text-slate-400 uppercase tracking-wider shrink-0">atau pilih rekomendasi</span>
                <div class="border-t border-slate-200 w-full"></div>
            </div>

            {{-- 2. PILIHAN GAMBAR REKOMENDASI --}}
            <div class="grid grid-cols-2 gap-3 mb-5">
                @php
                    $covers = [
                        ['name' => 'Pemandangan Alam', 'url' => 'https://images.unsplash.com/photo-1579546929518-9e396f3cc809?auto=format&fit=crop&w=800&q=80'],
                        ['name' => 'Kopi & Suasana', 'url' => 'https://images.unsplash.com/photo-1447933601403-0c6688de566e?auto=format&fit=crop&w=800&q=80'],
                        ['name' => 'Kantor & Kerja', 'url' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&w=800&q=80'],
                        ['name' => 'Gradient Biru', 'url' => 'https://images.unsplash.com/photo-1557683316-973673baf926?auto=format&fit=crop&w=800&q=80'],
                    ];
                @endphp
                @foreach($covers as $cov)
                <div wire:click="selectCover('{{ $cov['url'] }}')" 
                     onclick="document.getElementById('cover-picker-modal').style.display='none';" 
                     class="h-20 rounded-xl overflow-hidden relative cursor-pointer group border-2 transition-all shadow-sm {{ $cover_picture === $cov['url'] ? 'border-blue-600 ring-2 ring-blue-300' : 'border-transparent hover:border-blue-400' }}">
                    <img src="{{ $cov['url'] }}" alt="{{ $cov['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                    <span class="absolute bottom-1 left-1.5 text-[10px] font-extrabold text-white bg-black/60 px-2 py-0.5 rounded-md backdrop-blur-sm">{{ $cov['name'] }}</span>
                    @if($cover_picture === $cov['url'])
                        <span class="absolute top-1 right-1.5 w-5 h-5 rounded-full bg-blue-600 text-white flex items-center justify-center text-xs font-bold">✓</span>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- 3. TOMBOL FOOTER --}}
            <div class="flex items-center gap-3">
                @if($cover_picture)
                <button type="button" wire:click="removeCover" onclick="document.getElementById('cover-picker-modal').style.display='none';" 
                        class="flex-1 py-3 px-4 rounded-xl text-xs font-extrabold text-red-600 bg-red-50 hover:bg-red-100 transition-colors border border-red-200 cursor-pointer">
                    <i class='bx bx-trash mr-1'></i> Reset Sampul Default
                </button>
                @endif
                <button type="button" onclick="document.getElementById('cover-picker-modal').style.display='none'" 
                        class="flex-1 py-3 px-4 rounded-xl text-xs font-extrabold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MODAL PICKER AVATAR PROFILE ===== --}}
    <div id="avatar-picker-modal" onclick="if(event.target===this)this.style.display='none'" 
         style="display:none;position:fixed;inset:0;z-index:900;background:rgba(0,0,0,.65);backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:16px;">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-7 overflow-hidden shadow-2xl border border-slate-100">
            
            <div class="flex items-center justify-between pb-3.5 border-b border-slate-100 mb-5">
                <div>
                    <h3 class="font-black text-base text-slate-800">Ubah Foto Profil (Avatar)</h3>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">Upload foto diri Anda dari galeri atau pilih foto avatar</p>
                </div>
                <button type="button" onclick="document.getElementById('avatar-picker-modal').style.display='none'" class="text-slate-400 hover:text-slate-600 font-black text-2xl w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 cursor-pointer">×</button>
            </div>

            {{-- 1. UPLOAD DARI GALERI --}}
            <div class="mb-5">
                <label for="avatar-file-input" 
                       class="border-2 border-dashed rounded-2xl p-5 flex flex-col items-center justify-center cursor-pointer transition-all text-center group"
                       style="border-color: #93c5fd; background: #f0f7ff;">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl mb-2.5 shadow-sm group-hover:scale-105 transition-transform"
                         style="background: #5680d8; color: white;">
                        <i class='bx bx-camera'></i>
                    </div>
                    <span class="text-sm font-black text-slate-800">Upload Foto dari Galeri / Kamera</span>
                    <span class="text-xs text-slate-500 font-medium mt-1">Mendukung format JPG, PNG, WEBP (Maks. 5MB)</span>
                </label>
                <input type="file" id="avatar-file-input" wire:model="avatarFile" accept="image/*" class="hidden" onchange="document.getElementById('avatar-picker-modal').style.display='none';">
                
                <div wire:loading wire:target="avatarFile" class="text-center py-2 text-xs font-bold text-blue-600">
                    <i class='bx bx-loader-alt bx-spin text-sm mr-1'></i> Mengunggah foto profil dari galeri...
                </div>
                @error('avatarFile')
                    <span class="text-xs text-red-500 font-bold block mt-1 text-center">{{ $message }}</span>
                @enderror
            </div>

            {{-- PEMBATAS --}}
            <div class="relative flex items-center justify-center mb-4">
                <div class="border-t border-slate-200 w-full"></div>
                <span class="bg-white px-3 text-xs font-bold text-slate-400 uppercase tracking-wider shrink-0">atau pilih avatar rekomendasi</span>
                <div class="border-t border-slate-200 w-full"></div>
            </div>

            {{-- 2. PILIHAN AVATAR REKOMENDASI --}}
            <div class="grid grid-cols-3 sm:grid-cols-6 gap-3 mb-5">
                @php
                    $avatars = [
                        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=300&q=80',
                        'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=300&q=80',
                        'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=300&q=80',
                        'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=300&q=80',
                        'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?auto=format&fit=crop&w=300&q=80',
                        'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=300&q=80',
                    ];
                @endphp
                @foreach($avatars as $av)
                <div wire:click="selectAvatar('{{ $av }}')" 
                     onclick="document.getElementById('avatar-picker-modal').style.display='none';" 
                     class="w-14 h-14 sm:w-16 sm:h-16 rounded-full overflow-hidden mx-auto cursor-pointer group border-2 transition-all shadow-sm relative {{ $profile_picture === $av ? 'border-blue-600 ring-2 ring-blue-300' : 'border-transparent hover:border-blue-400' }}">
                    <img src="{{ $av }}" alt="Avatar" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                    @if($profile_picture === $av)
                        <span class="absolute inset-0 bg-blue-600/40 flex items-center justify-center text-white font-bold text-sm">✓</span>
                    @endif
                </div>
                @endforeach
            </div>

            {{-- 3. TOMBOL FOOTER --}}
            <div class="flex items-center gap-3">
                @if($profile_picture)
                <button type="button" wire:click="removeAvatar" onclick="document.getElementById('avatar-picker-modal').style.display='none';" 
                        class="flex-1 py-3 px-4 rounded-xl text-xs font-extrabold text-red-600 bg-red-50 hover:bg-red-100 transition-colors border border-red-200 cursor-pointer">
                    <i class='bx bx-user-x mr-1'></i> Gunakan Inisial Saja
                </button>
                @endif
                <button type="button" onclick="document.getElementById('avatar-picker-modal').style.display='none'" 
                        class="flex-1 py-3 px-4 rounded-xl text-xs font-extrabold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>

</div>
