<div class="min-h-screen pb-36 pt-16" style="background: #f0f4f9;">
    <div class="max-w-xl mx-auto px-4 pt-5">

        {{-- ===== WELCOME HEADER ===== --}}
        <div class="bg-white rounded-2xl p-5 mb-4 flex items-center gap-4" style="border: 1px solid #e8edf5; box-shadow: 0 2px 12px rgba(37,67,155,.06);">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl font-black shrink-0" style="background: linear-gradient(135deg, #24427b, #5680d8); color: white;">
                {{ substr($company->company_name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-lg font-extrabold text-slate-800">{{ $company->company_name }}</h1>
                <p class="text-xs text-slate-400 mt-0.5">{{ $company->owner_name }}</p>
                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full mt-1" style="background: #e6f8f6; color: #2a9d8f;">
                    <i class='bx bx-check-circle'></i> Pemberi Kerja Terdaftar
                </span>
            </div>
        </div>

        {{-- ===== STATS ===== --}}
        <div class="grid grid-cols-2 gap-3 mb-4">
            <div class="rounded-2xl p-5 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #5680d8, #3a5bbf); box-shadow: 0 4px 20px rgba(86,128,216,.3);">
                <div class="absolute -right-4 -top-4 text-6xl opacity-10"><i class='bx bx-briefcase'></i></div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-blue-200 mb-1">Total Lowongan</p>
                <p class="text-4xl font-black">{{ $totalJobs }}</p>
            </div>
            <div class="rounded-2xl p-5 text-white relative overflow-hidden" style="background: linear-gradient(135deg, #47bfae, #2a9d8f); box-shadow: 0 4px 20px rgba(71,191,174,.3);">
                <div class="absolute -right-4 -top-4 text-6xl opacity-10"><i class='bx bx-group'></i></div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-teal-100 mb-1">Total Pelamar</p>
                <p class="text-4xl font-black">{{ $totalApplicants }}</p>
            </div>
        </div>

        {{-- ===== QUICK ACTIONS ===== --}}
        <div class="bg-white rounded-2xl p-4 mb-4 flex gap-3" style="border: 1px solid #e8edf5;">
            <a href="{{ route('company.jobs.create') }}"
               class="flex-1 py-3 text-white font-extrabold rounded-xl text-center text-sm transition-all flex items-center justify-center gap-1.5"
               style="background: #5680d8; box-shadow: 0 3px 12px rgba(86,128,216,.3);">
                <i class='bx bx-plus-circle'></i> Pasang Lowongan
            </a>
            <a href="{{ route('company.jobs') }}"
               class="flex-1 py-3 font-bold rounded-xl text-center text-sm transition-all"
               style="border: 1.5px solid #e2e8f0; color: #475569; background: white;">
                Semua Lowongan
            </a>
        </div>

        {{-- ===== RECENT JOBS ===== --}}
        <div class="flex items-center justify-between mb-3">
            <h2 class="font-extrabold text-slate-800">Lowongan Terbaru</h2>
            <a href="{{ route('company.jobs') }}" class="text-xs font-bold" style="color: #5680d8;">Lihat Semua →</a>
        </div>

        @if($recentJobs->isEmpty())
            <div class="text-center py-14 bg-white rounded-2xl" style="border: 1px dashed #c7d6f5;">
                <i class='bx bx-mail-send text-5xl mb-3' style="color: #c7d6f5;"></i>
                <h3 class="font-extrabold text-slate-600 mb-1">Belum ada lowongan</h3>
                <p class="text-sm text-slate-400 mb-5">Mulai cari karyawan untuk usaha Anda sekarang</p>
                <a href="{{ route('company.jobs.create') }}"
                   class="inline-flex items-center gap-2 text-white text-sm font-bold px-6 py-3 rounded-xl"
                   style="background: #5680d8;">
                    <i class='bx bx-plus'></i> Pasang Lowongan Pertama
                </a>
            </div>
        @else
            <div class="space-y-3">
                @foreach($recentJobs as $job)
                <div class="bg-white rounded-2xl p-4" style="border: 1px solid #e8edf5; box-shadow: 0 2px 10px rgba(0,0,0,.04);">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-extrabold text-slate-800">{{ $job->position }}</h3>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $job->created_at->diffForHumans() }}</p>
                        </div>
                        @php
                            $statusColor = $job->status === 'active' ? '#47bfae' : ($job->status === 'filled' ? '#5680d8' : '#94a3b8');
                            $statusBg = $job->status === 'active' ? '#e6f8f6' : ($job->status === 'filled' ? '#eef2fb' : '#f1f5f9');
                            $statusLabel = $job->status === 'active' ? 'Aktif' : ($job->status === 'filled' ? 'Terisi' : 'Ditutup');
                        @endphp
                        <span class="text-[10px] font-bold px-2.5 py-1 rounded-full uppercase tracking-wider"
                              style="background: {{ $statusBg }}; color: {{ $statusColor }};">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="flex items-center gap-4 text-xs font-semibold text-slate-500 mb-4 p-2.5 rounded-xl" style="background: #f8faff; border: 1px solid #e8edf5;">
                        <span class="flex items-center gap-1.5"><i class='bx bx-group' style="color:#5680d8"></i> {{ $job->applications_count }} Pelamar</span>
                        <span class="flex items-center gap-1.5"><i class='bx bx-map' style="color:#5680d8"></i> {{ $job->city }}</span>
                        <span class="flex items-center gap-1.5"><i class='bx bx-category' style="color:#5680d8"></i> {{ \App\Models\JobListing::jobCategories()[$job->job_category] ?? '-' }}</span>
                    </div>

                    <a href="{{ route('company.jobs.applicants', $job->id) }}"
                       class="block w-full py-2.5 font-bold rounded-xl text-center text-sm transition-all"
                       style="background: #eef2fb; color: #5680d8; border: 1px solid #c7d6f5;">
                        Kelola {{ $job->applications_count }} Pelamar →
                    </a>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
