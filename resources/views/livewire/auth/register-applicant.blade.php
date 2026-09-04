<div class="min-h-screen flex flex-col" style="background: #f0f4f9;">
    {{-- Progress Bar --}}
    <div class="bg-white px-4 py-3" style="border-bottom: 1px solid #e8edf5;">
        <div class="max-w-sm mx-auto">
            <div class="flex items-center gap-2 mb-2">
                <div class="flex-1 h-1.5 rounded-full overflow-hidden" style="background: #e8edf5;">
                    <div class="h-full rounded-full transition-all duration-500" style="width: {{ $step === 1 ? '50%' : '100%' }}; background: #5680d8;"></div>
                </div>
                <span class="text-xs font-bold" style="color: #5680d8;">{{ $step }}/2</span>
            </div>
            <p class="text-xs text-slate-400">{{ $step === 1 ? 'Data diri Anda' : 'Profil kerja Anda' }}</p>
        </div>
    </div>

    <div class="flex-grow px-4 py-8 max-w-md mx-auto w-full">
        <div class="mb-6 text-center sm:text-left">
            <h1 class="text-2xl font-extrabold text-slate-800">
                {!! $step === 1 ? "<i class='bx bx-hand'></i> Halo! Siapa Anda?" : "<i class='bx bx-briefcase'></i> Profil Kerja Anda" !!}
            </h1>
            <p class="text-slate-500 text-sm mt-1">
                {{ $step === 1 ? 'Isi data diri untuk membuat akun Near Job.' : 'Informasi ini membantu pemberi kerja mengenal Anda.' }}
            </p>
        </div>

        @if($step === 1)
        {{-- ===== STEP 1: Identitas ===== --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 sm:p-8 space-y-5">
            
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                <input type="text" wire:model="name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Nama sesuai KTP">
                @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">
                    NIK (Nomor Induk Kependudukan) <span class="text-red-500">*</span>
                </label>
                <input type="text" wire:model="nik" maxlength="16" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white font-mono" placeholder="16 digit NIK Anda">
                @error('nik') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @if($nikError) <p class="text-xs text-red-500 mt-1">{{ $nikError }}</p> @endif
                <p class="text-xs text-slate-400 mt-1">NIK digunakan hanya sebagai pengidentifikasi akun unik. Tidak ditampilkan secara publik.</p>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nomor WhatsApp <span class="text-red-500">*</span></label>
                <input type="tel" wire:model="whatsapp" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="08xxxxxxxxxx">
                @error('whatsapp') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                <input type="date" wire:model="date_of_birth" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                @error('date_of_birth') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                @if($ageError) <div class="mt-2 bg-red-50 border border-red-100 rounded-xl p-3 text-xs text-red-600 font-medium">{{ $ageError }}</div> @endif
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                <input type="email" wire:model="email" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="email@contoh.com">
                @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kata Sandi <span class="text-red-500">*</span></label>
                <input type="password" wire:model="password" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Minimal 6 karakter">
                @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="bg-slate-50 rounded-xl p-3 text-xs text-slate-500 leading-relaxed">
                <i class='bx bx-lock-alt'></i> <strong>Privasi:</strong> Data pribadi Anda dikumpulkan semata-mata untuk mengoperasikan layanan Near Job. NIK Anda tidak akan pernah ditampilkan kepada pemberi kerja.
            </div>

            <button type="button" wire:click="nextStep"
                class="w-full py-3.5 text-white font-bold rounded-xl text-sm transition-all"
                style="background: #5680d8; box-shadow: 0 4px 15px rgba(86,128,216,.3);">
                Lanjut →
            </button>
        </div>

        @else
        {{-- ===== STEP 2: Profil Kerja ===== --}}
        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 sm:p-8 space-y-5">

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Kota Domisili <span class="text-red-500">*</span></label>
                <select wire:model="city" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                    <option value="">-- Pilih Kota --</option>
                    @foreach($cities as $c)
                        <option value="{{ $c->name }}">{{ $c->name }} ({{ $c->province }})</option>
                    @endforeach
                </select>
                @error('city') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pendidikan Terakhir <span class="text-red-500">*</span></label>
                <select wire:model="education_level" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                    @foreach($educationLevels as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Sekolah / Universitas <span class="text-red-500">*</span></label>
                <input type="text" wire:model="education_institution" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="SMAN 1 Banyuwangi">
                @error('education_institution') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Jurusan (opsional)</label>
                <input type="text" wire:model="field_of_study" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Teknik Informatika, Tata Boga, dll.">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Keahlian / Skills</label>
                <div class="flex gap-2 mb-2">
                    <input type="text" wire:model="newSkill" wire:keydown.enter.prevent="addSkill"
                        class="flex-grow px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white"
                        placeholder="Memasak, kasir, MS Excel...">
                    <button type="button" wire:click="addSkill" class="px-4 py-2.5 bg-blue-600 text-white font-bold text-sm rounded-xl hover:bg-blue-700 shrink-0">+</button>
                </div>
                <div class="flex flex-wrap gap-2">
                    @foreach($skills as $i => $sk)
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg border border-blue-100">
                            {{ $sk }}<button type="button" wire:click="removeSkill({{ $i }})" class="hover:text-red-500 font-bold">×</button>
                        </span>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Pengalaman Kerja (opsional)</label>
                <textarea wire:model="work_experience" rows="3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white resize-none" placeholder="Pernah bekerja sebagai apa, di mana, berapa lama..."></textarea>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1.5">Ekspektasi Gaji (opsional)</label>
                <input type="text" wire:model="salary_expectation" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Rp2.500.000 – Rp3.000.000">
            </div>

            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-3 text-xs text-emerald-700">
                <i class='bx bx-gift'></i> Akun baru mendapatkan <strong>3 kesempatan melamar gratis!</strong>
            </div>

            <div class="flex gap-3">
                <button type="button" wire:click="$set('step', 1)" class="flex-1 py-3 font-bold rounded-xl text-sm transition-all" style="border: 1.5px solid #e2e8f0; color: #64748b;">← Kembali</button>
                <button type="button" wire:click="register" class="flex-1 py-3 text-white font-bold rounded-xl text-sm transition-all" style="background: #5680d8; box-shadow: 0 4px 15px rgba(86,128,216,.3);">Daftar Sekarang</button>
            </div>
        </div>
        @endif

        <p class="text-center text-xs text-slate-400 mt-4">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-semibold">Masuk di sini</a>
        </p>
    </div>
</div>
