<div class="p-4 bg-slate-50 min-h-screen pb-36 pt-16">
    <div class="max-w-md mx-auto">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('company.jobs') }}" class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-100 transition-colors border border-slate-200">
                ←
            </a>
            <div>
                <h1 class="text-xl font-extrabold text-slate-800">{{ $isEdit ? 'Edit Lowongan' : 'Pasang Lowongan' }}</h1>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6">
            <form wire:submit="save" class="space-y-4">
                
                <h3 class="text-sm font-extrabold text-blue-600 uppercase tracking-widest border-b border-slate-100 pb-2">Informasi Dasar</h3>
                
                @if($isEdit)
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Status Lowongan</label>
                    <select wire:model="status" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm font-bold {{ $status === 'active' ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700' }}">
                        <option value="active">Aktif (Menerima Pelamar)</option>
                        <option value="filled">Terisi (Karyawan sudah dapat)</option>
                        <option value="closed">Ditutup (Batal/Selesai)</option>
                    </select>
                </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Posisi / Jabatan Pekerjaan <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="position" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Contoh: Kasir, Kitchen Helper, dll">
                    @error('position') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">
                        Kuota Lowongan (Jumlah Kebutuhan) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="number" min="1" max="999" wire:model="quota" 
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white font-bold" 
                               placeholder="Contoh: 2 atau 5">
                        <span class="absolute right-4 top-3 text-xs font-bold text-slate-400">Orang</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1 font-medium">Angka kuota ini akan muncul langsung di pin peta calon pelamar.</p>
                    @error('quota') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Bidang <span class="text-red-500">*</span></label>
                        <select wire:model="job_category" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                            @foreach($categories as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Sistem Kerja <span class="text-red-500">*</span></label>
                        <select wire:model="work_type" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                            @foreach($workTypes as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Min. Pendidikan <span class="text-red-500">*</span></label>
                        <select wire:model="min_education" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                            @foreach($educationLevels as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kota Penempatan <span class="text-red-500">*</span></label>
                        <select wire:model="city" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                            @foreach($cities as $c)
                                <option value="{{ $c->name }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <h3 class="text-sm font-extrabold text-blue-600 uppercase tracking-widest border-b border-slate-100 pb-2 mt-6">Gaji & Waktu</h3>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Gaji Minimum (Rp)</label>
                        <input type="number" wire:model="salary_min" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Kosongkan jika nego">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Gaji Maksimum (Rp)</label>
                        <input type="number" wire:model="salary_max" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Opsional">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Durasi Kerja</label>
                        <input type="text" wire:model="work_duration" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Cth: Tetap, Kontrak 6 bulan">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Jam Kerja</label>
                        <input type="text" wire:model="work_hours" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Cth: 08.00 - 17.00">
                    </div>
                </div>

                <h3 class="text-sm font-extrabold text-blue-600 uppercase tracking-widest border-b border-slate-100 pb-2 mt-6">Detail Pekerjaan</h3>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Pekerjaan <span class="text-red-500">*</span></label>
                    <textarea wire:model="description" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white resize-none" placeholder="Jelaskan apa saja tugas dan tanggung jawab pekerjaannya..."></textarea>
                    @error('description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kualifikasi / Persyaratan <span class="text-red-500">*</span></label>
                    <textarea wire:model="qualifications" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white resize-none" placeholder="Jelaskan syarat kandidat (contoh: Pria/Wanita max 30 tahun, jujur, rajin)..."></textarea>
                    @error('qualifications') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Keahlian (Skills) yang Dicari</label>
                    <div class="flex gap-2 mb-2">
                        <input type="text" wire:model="newSkill" wire:keydown.enter.prevent="addSkill" class="flex-grow px-4 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Contoh: Mengemudi, Memasak...">
                        <button type="button" wire:click="addSkill" class="px-4 py-2.5 bg-blue-600 text-white font-bold rounded-xl">+</button>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($required_skills as $i => $sk)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-blue-50 text-blue-700 text-xs font-semibold rounded-lg border border-blue-100">
                                {{ $sk }} <button type="button" wire:click="removeSkill({{ $i }})" class="hover:text-red-500 font-bold">×</button>
                            </span>
                        @endforeach
                    </div>
                </div>

                <h3 class="text-sm font-extrabold text-blue-600 uppercase tracking-widest border-b border-slate-100 pb-2 mt-6">Kontak Lamaran</h3>
                
                <p class="text-xs text-slate-500 mb-2">Pilih bagaimana cara pelamar menghubungi Anda.</p>

                <div>
                    <div class="grid grid-cols-2 gap-2 mb-3">
                        <button type="button" wire:click="$set('contact_method', 'whatsapp')"
                            class="py-3 rounded-xl border-2 text-sm font-bold transition-all {{ $contact_method === 'whatsapp' ? 'border-green-500 bg-green-50 text-green-700' : 'border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                            <i class='bx bxl-whatsapp'></i> WhatsApp
                        </button>
                        <button type="button" wire:click="$set('contact_method', 'email')"
                            class="py-3 rounded-xl border-2 text-sm font-bold transition-all {{ $contact_method === 'email' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                            <i class='bx bx-envelope'></i> Email
                        </button>
                    </div>

                    @if($contact_method === 'whatsapp')
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp Penerima Lamaran</label>
                            <input type="tel" wire:model="contact_whatsapp" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="08xxxxxxxxxx">
                        </div>
                    @else
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1">Email Penerima Lamaran</label>
                            <input type="email" wire:model="contact_email" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="hrd@usaha.com">
                        </div>
                    @endif
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-blue-600/20 transition-colors">
                        {{ $isEdit ? 'Simpan Perubahan' : 'Pasang Lowongan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
