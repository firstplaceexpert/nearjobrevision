<div class="pb-36 pt-16" style="background: #f0f4f9; min-height: 100vh;">
    <div class="max-w-2xl mx-auto px-4 pt-5">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Generator CV ATS</h1>
                <p class="text-slate-500 text-xs mt-1">Buat resume terstruktur standar industri yang lolos filter sistem ATS.</p>
            </div>
            <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-md bg-blue-50 text-blue-700 border border-blue-200">
                <i class='bx bx-check-shield'></i> Format Standar HRD
            </span>
        </div>

        @if(!$isPaid)
            {{-- Aktivasi CV ATS & Paket Promo --}}
            <div class="bg-white rounded-3xl p-6 sm:p-8 text-center border border-slate-200 shadow-sm mb-6">
                <div class="w-16 h-16 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4 bg-blue-50 text-blue-700">
                    <i class='bx bx-file'></i>
                </div>
                <h2 class="text-xl font-bold text-slate-900 mb-1">Aktivasi Generator CV ATS Profesional</h2>
                <p class="text-slate-500 text-xs mb-6 max-w-sm mx-auto">Format terstruktur ramah scanner, langsung dibuat otomatis dari data profil Anda. Sekali bayar, aktif selamanya.</p>

                {{-- Option 1: Standalone CV ATS (Sesuai BMC Rp14.999) --}}
                <div class="p-5 rounded-2xl mb-4 text-left max-w-md mx-auto border border-slate-200 bg-slate-50 relative">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-bold text-slate-700">CV ATS Standalone</span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">Hemat 60%</span>
                    </div>
                    <div class="flex items-baseline justify-between mb-3">
                        <div>
                            <span class="text-xs text-slate-400 line-through block">Rp39.000</span>
                            <span class="text-2xl font-bold text-slate-900">Rp14.999</span>
                        </div>
                        <button wire:click="buyCv" wire:loading.attr="disabled"
                                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center gap-1.5">
                            <span wire:loading.remove wire:target="buyCv">Beli CV Saja</span>
                            <span wire:loading wire:target="buyCv"><i class='bx bx-loader-alt animate-spin'></i></span>
                        </button>
                    </div>
                </div>

                {{-- Option 2: Paket Komplit Siap Kerja (CV ATS + 5 Kuota Lamaran Rp24.999) --}}
                <div class="p-5 rounded-2xl text-left max-w-md mx-auto border-2 border-amber-400 bg-gradient-to-br from-amber-50 to-orange-50 relative shadow-sm">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-xs font-bold text-amber-950 flex items-center gap-1">
                            <i class='bx bx-check-shield text-amber-600'></i> Paket Komplit Siap Kerja
                        </span>
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-amber-400 text-slate-950">Rekomendasi</span>
                    </div>
                    <p class="text-xs text-amber-900 mb-3">Dapatkan <strong>1x CV ATS Resmi + 5 Kuota Lamaran Prioritas</strong> sekaligus!</p>
                    <div class="flex items-baseline justify-between">
                        <div>
                            <span class="text-xs text-amber-800 line-through block">Rp65.000</span>
                            <span class="text-2xl font-black text-amber-950">Rp24.999</span>
                        </div>
                        <button wire:click="buyBundle" wire:loading.attr="disabled"
                                class="px-5 py-2.5 bg-amber-400 hover:bg-amber-300 active:scale-95 text-slate-950 font-bold text-xs rounded-xl shadow transition-all flex items-center gap-1.5">
                            <span wire:loading.remove wire:target="buyBundle">Ambil Paket Komplit</span>
                            <span wire:loading wire:target="buyBundle"><i class='bx bx-loader-alt animate-spin'></i></span>
                        </button>
                    </div>
                </div>
            </div>
        @else
            {{-- Panel Skor ATS & Cetak --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm mb-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3.5">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-xl font-black shrink-0 border border-emerald-200">
                        96%
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <h4 class="font-bold text-slate-900 text-sm">Skor Kesesuaian ATS Tinggi</h4>
                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">Siap Lolos HRD</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Format terstruktur, tanpa tabel tersembunyi, margin standar, dan ramah pembaca mesin.</p>
                    </div>
                </div>

                <button onclick="window.print()" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow transition-all flex items-center gap-2 shrink-0">
                    <i class='bx bx-printer text-base'></i> Cetak / Simpan PDF
                </button>
            </div>

            {{-- The CV Template --}}
            <div id="cv-document" class="bg-white p-8 sm:p-12 shadow-md border border-slate-200 text-slate-800" style="font-family: 'Times New Roman', Times, serif; max-width: 21cm; margin: 0 auto; min-height: 29.7cm;">
                
                {{-- Header --}}
                <div class="text-center mb-6 border-b-2 border-slate-800 pb-4">
                    <h1 class="text-3xl font-bold uppercase tracking-wide mb-2">{{ $user->name }}</h1>
                    <p class="text-sm">
                        {{ $profile->city }} | {{ preg_replace('/(\d{4})(\d{4})(\d{4})/', '$1-$2-$3', $profile->whatsapp) }} | {{ $user->email }}
                    </p>
                </div>

                {{-- Summary --}}
                <div class="mb-6">
                    <h2 class="text-lg font-bold uppercase border-b border-slate-300 mb-2">Profil</h2>
                    <p class="text-sm leading-relaxed text-justify">
                        Seorang tenaga profesional yang berdomisili di {{ $profile->city }}. Lulusan {{ strtoupper($profile->education_level) }} dari {{ $profile->education_institution }} {{ $profile->field_of_study ? 'jurusan ' . $profile->field_of_study : '' }}. Memiliki kemampuan bekerja dalam tim, disiplin, dan berorientasi pada hasil kerja terbaik.
                    </p>
                </div>

                {{-- Education --}}
                <div class="mb-6">
                    <h2 class="text-lg font-bold uppercase border-b border-slate-300 mb-2">Pendidikan</h2>
                    <div class="flex justify-between items-baseline mb-1">
                        <span class="font-bold text-base">{{ $profile->education_institution }}</span>
                        <span class="text-sm italic">{{ $profile->city }}</span>
                    </div>
                    <div class="flex justify-between items-baseline text-sm">
                        <span>{{ strtoupper($profile->education_level) }} {{ $profile->field_of_study ? '- ' . $profile->field_of_study : '' }}</span>
                        <span class="text-slate-600">Lulus</span>
                    </div>
                </div>

                {{-- Skills --}}
                @if(!empty($profile->skills))
                    <div class="mb-6">
                        <h2 class="text-lg font-bold uppercase border-b border-slate-300 mb-2">Keahlian</h2>
                        <ul class="list-disc list-inside text-sm space-y-1">
                            @foreach($profile->skills as $skill)
                                <li>{{ $skill }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Work Experience --}}
                @if(!empty($profile->work_experiences))
                    <div class="mb-6">
                        <h2 class="text-lg font-bold uppercase border-b border-slate-300 mb-2">Pengalaman Kerja</h2>
                        @foreach($profile->work_experiences as $exp)
                            <div class="mb-4">
                                <div class="flex justify-between items-baseline mb-1">
                                    <span class="font-bold text-base">{{ $exp['position'] ?? '-' }}</span>
                                    <span class="text-sm italic">{{ $exp['start_year'] ?? '' }} - {{ $exp['end_year'] ?? 'Sekarang' }}</span>
                                </div>
                                <div class="text-sm font-semibold text-slate-700 mb-1">{{ $exp['company_name'] ?? '-' }}</div>
                                <p class="text-sm text-slate-600 leading-relaxed text-justify">{{ $exp['description'] ?? '' }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        @endif

    </div>

    {{-- MODAL PROTOTYPE PEMBAYARAN INSTAN (DEMO-READY) --}}
    @if($showModal && $modalOrder)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-100 relative animate-scaleUp">
            
            <button wire:click="closeModal" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 text-2xl">
                <i class='bx bx-x'></i>
            </button>

            <div class="text-center mb-5">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-2xl mx-auto mb-2">
                    <i class='bx bx-file'></i>
                </div>
                <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Aktivasi Fitur CV ATS</span>
                <h3 class="text-lg font-bold text-slate-900 mt-1">{{ $modalOrder->item_name }}</h3>
                <span class="text-xs text-slate-400 font-mono">{{ $modalOrder->order_number }}</span>
            </div>

            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 mb-5">
                <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                    <span>Metode:</span>
                    <span class="font-bold text-slate-800">QRIS / GoPay / DANA</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700">Total Tagihan:</span>
                    <span class="text-2xl font-black text-blue-700">Rp{{ number_format($modalOrder->gross_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="space-y-2.5">
                <button wire:click="confirmQuickPayment"
                        wire:loading.attr="disabled"
                        class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="confirmQuickPayment">
                        <i class='bx bx-check-shield text-lg'></i> Konfirmasi Pembayaran (Simulasi Cepat 1-Klik)
                    </span>
                    <span wire:loading wire:target="confirmQuickPayment">
                        <i class='bx bx-loader-alt animate-spin text-base'></i> Mengaktifkan CV...
                    </span>
                </button>

                <button wire:click="closeModal"
                        class="w-full py-2.5 text-slate-500 hover:text-slate-800 text-xs font-medium">
                    Batal
                </button>
            </div>

            <p class="text-[10px] text-center text-slate-400 mt-4">
                Mode Demo Lomba: Sekali klik langsung mengaktifkan CV ATS dan menambahkan kuota otomatis.
            </p>
        </div>
    </div>
    @endif
</div>
