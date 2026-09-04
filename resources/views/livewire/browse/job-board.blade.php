<div class="max-w-7xl mx-auto px-4 py-8 space-y-8">
    
    <!-- Search & Filter Banner -->
    <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-sm space-y-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">Jelajahi Lowongan Kerja Lokal</h1>
            <p class="text-slate-500 text-sm mt-1">Cari lowongan aktif dari perusahaan dan UMKM di sekitar Anda</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Cari Kata Kunci</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Posisi atau nama perusahaan..." class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Kota / Lokasi</label>
                <select wire:model.live="city" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm">
                    <option value="">Semua Kota</option>
                    @foreach($cities as $c)
                        <option value="{{ $c->name }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Tipe Kerja</label>
                <select wire:model.live="work_type" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm">
                    <option value="">Semua Tipe</option>
                    @foreach($workTypes as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1">Kategori</label>
                <select wire:model.live="category" class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-indigo-500 text-sm">
                    <option value="">Semua Kategori</option>
                    @foreach($jobCategories as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Jobs Grid -->
    @if($jobs->isEmpty())
        <div class="text-center py-16 bg-white rounded-3xl border border-slate-200 text-slate-500 space-y-2">
            <div class="text-3xl">🔍</div>
            <p class="font-bold text-base">Tidak ada lowongan yang sesuai kriteria filter Anda.</p>
            <p class="text-xs text-slate-400">Coba ubah kata kunci atau bersihkan filter pencarian.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($jobs as $job)
                <div class="bg-white p-6 rounded-3xl border border-slate-200 hover:border-indigo-300 hover:shadow-lg transition-all flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between gap-2">
                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ $job->work_type_label }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium">📍 {{ $job->city }}</span>
                        </div>

                        <h3 class="text-xl font-extrabold text-slate-900 leading-snug">{{ $job->position }}</h3>
                        <p class="text-sm font-semibold text-slate-600">{{ $job->company->company_name }}</p>

                        <p class="text-xs text-slate-500 line-clamp-3 leading-relaxed">{{ $job->description }}</p>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-2">
                        <span class="text-xs font-semibold text-slate-500">Min. {{ \App\Models\ApplicantProfile::educationLevels()[$job->min_education] ?? '' }}</span>
                        <a href="{{ route('register.applicant') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs shadow-sm transition-all">
                            Daftar & Swipe →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="pt-4">
            {{ $jobs->links() }}
        </div>
    @endif
</div>
