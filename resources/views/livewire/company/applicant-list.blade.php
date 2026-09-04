<div class="min-h-screen pb-36 pt-16" style="background: #f0f4f9;">
    <div class="max-w-xl mx-auto px-4 pt-5">

        {{-- ===== HEADER ===== --}}
        <div class="flex items-center gap-3 mb-5">
            <a href="{{ route('company.jobs') }}"
               class="w-10 h-10 bg-white rounded-xl flex items-center justify-center transition-all"
               style="border: 1px solid #e8edf5; color: #5680d8; box-shadow: 0 2px 8px rgba(0,0,0,.04);">
                <i class='bx bx-arrow-back'></i>
            </a>
            <div>
                <h1 class="text-xl font-extrabold text-slate-800">Daftar Pelamar</h1>
                <p class="text-slate-400 text-sm truncate max-w-[240px]">{{ $job->position }}</p>
            </div>
        </div>

        @if($applications->isEmpty())
            <div class="text-center py-16 bg-white rounded-2xl" style="border: 1px dashed #c7d6f5;">
                <i class='bx bx-group text-5xl mb-4' style="color: #c7d6f5;"></i>
                <h3 class="font-extrabold text-slate-600 mb-1">Belum ada pelamar</h3>
                <p class="text-sm text-slate-400 mt-1 max-w-[220px] mx-auto">Tetap bersabar menunggu kandidat yang tepat.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($applications as $app)
                @php $profile = $app->user->applicantProfile; @endphp

                <div class="bg-white rounded-3xl overflow-hidden" style="border: 1px solid #e8edf5; box-shadow: 0 2px 12px rgba(0,0,0,.04);">
                    {{-- Top Section --}}
                    <div class="p-4 pb-3">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                @if($profile?->photo_url)
                                    <img src="{{ $profile->photo_url }}" alt="{{ $app->user->name }}" class="w-11 h-11 rounded-full object-cover border border-slate-200 shadow-sm shrink-0">
                                @else
                                    <div class="w-11 h-11 rounded-full flex items-center justify-center text-sm font-black text-white shrink-0 shadow-sm" style="background: #5680d8;">
                                        {{ substr($app->user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-extrabold text-lg text-slate-800">{{ $app->user->name }}</h3>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        Usia: {{ $app->user->age }} thn &bull; Lulusan {{ strtoupper($profile?->education_level ?? '-') }}
                                    </p>
                                </div>
                            </div>

                            {{-- Status dropdown --}}
                            <select wire:change="updateStatus({{ $app->id }}, $event.target.value)"
                                class="text-[10px] font-bold uppercase tracking-wider rounded-xl border-2 appearance-none px-2.5 py-2 focus:ring-0 focus:outline-none cursor-pointer"
                                style="
                                    {{ $app->status === 'menunggu' ? 'border-color: #fde68a; background: #fffbeb; color: #d97706;' : '' }}
                                    {{ $app->status === 'dihubungi' ? 'border-color: #c7d6f5; background: #eef2fb; color: #5680d8;' : '' }}
                                    {{ $app->status === 'interview' ? 'border-color: #ddd6fe; background: #f5f0ff; color: #7c3aed;' : '' }}
                                    {{ $app->status === 'diterima' ? 'border-color: #99f6e4; background: #e6f8f6; color: #2a9d8f;' : '' }}
                                    {{ $app->status === 'tidak_lolos' ? 'border-color: #fecaca; background: #fff5f5; color: #ef4444;' : '' }}
                                ">
                                @foreach($statuses as $val => $label)
                                    <option value="{{ $val }}" {{ $app->status === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Profile Info --}}
                        <div class="grid grid-cols-2 gap-3 p-3 rounded-xl mb-3" style="background: #f8faff; border: 1px solid #e8edf5;">
                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Pengalaman</p>
                                <p class="text-xs font-semibold text-slate-700 line-clamp-2">{{ $profile?->work_experience ?: ($profile?->education_institution ?? '-') }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">Domisili</p>
                                <p class="text-xs font-semibold text-slate-700">{{ $profile?->city ?? '-' }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-[9px] font-bold uppercase tracking-wider text-slate-400 mb-1">Keahlian</p>
                                <div class="flex flex-wrap gap-1">
                                    @forelse($profile?->skills ?? [] as $skill)
                                        <span class="text-[9px] font-bold px-2 py-0.5 rounded-md" style="background: #eef2fb; color: #5680d8;">{{ $skill }}</span>
                                    @empty
                                        <span class="text-xs text-slate-400">-</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Contact Actions --}}
                    <div class="flex gap-2 px-4 pb-4 pt-0">
                        @if($app->contact_method === 'whatsapp')
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', preg_replace('/[^0-9]/', '', $app->user->whatsapp)) }}"
                               target="_blank"
                               class="flex-1 py-2.5 text-center text-xs font-bold rounded-xl transition-all"
                               style="background: #e6f8f6; color: #2a9d8f; border: 1px solid #99f6e4;">
                                <i class='bx bxl-whatsapp'></i> Hubungi WA
                            </a>
                        @else
                            <a href="mailto:{{ $app->user->email }}"
                               target="_blank"
                               class="flex-1 py-2.5 text-center text-xs font-bold rounded-xl transition-all"
                               style="background: #eef2fb; color: #5680d8; border: 1px solid #c7d6f5;">
                                <i class='bx bx-envelope'></i> Hubungi Email
                            </a>
                        @endif

                        @if($profile?->cv_generated)
                            <a href="#" class="flex-1 py-2.5 text-center text-xs font-bold rounded-xl transition-all"
                               style="background: #f8faff; color: #475569; border: 1px solid #e2e8f0;">
                                <i class='bx bx-file'></i> Lihat CV
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
