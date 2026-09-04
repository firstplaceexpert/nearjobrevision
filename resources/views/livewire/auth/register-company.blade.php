<div class="min-h-screen bg-slate-50 flex flex-col">
    <div class="px-4 py-8 max-w-md mx-auto w-full">
        <div class="mb-6 text-center sm:text-left">
            <div class="text-3xl mb-2 text-blue-600"><i class='bx bx-buildings'></i></div>
            <h1 class="text-2xl font-extrabold text-slate-800">Daftar sebagai Pemberi Kerja</h1>
            <p class="text-slate-500 text-sm mt-1">Posting lowongan dan temukan tenaga kerja di sekitar usaha Anda.</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-7 sm:p-8 space-y-5">

            <div class="pb-3 border-b border-slate-50">
                <p class="text-xs font-extrabold text-blue-600 tracking-widest uppercase mb-3">Data Pemilik</p>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pemilik Usaha *</label>
                        <input type="text" wire:model="owner_name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Nama lengkap Anda">
                        @error('owner_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">NIK *</label>
                        <input type="text" wire:model="nik" maxlength="16" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white font-mono" placeholder="16 digit NIK">
                        @error('nik') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                        @if($nikError) <p class="text-xs text-red-500 mt-1">{{ $nikError }}</p> @endif
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp *</label>
                        <input type="tel" wire:model="whatsapp" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="08xxxxxxxxxx">
                        @error('whatsapp') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Email *</label>
                        <input type="email" wire:model="email" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="email@usaha.com">
                        @error('email') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kata Sandi *</label>
                        <input type="password" wire:model="password" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Minimal 6 karakter">
                        @error('password') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div>
                <p class="text-xs font-extrabold text-blue-600 tracking-widest uppercase mb-3">Data Usaha</p>
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Usaha *</label>
                        <input type="text" wire:model="company_name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Warung Makan ABC">
                        @error('company_name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Bidang Usaha *</label>
                        <select wire:model="business_field" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                            @foreach($categories as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">NIB (opsional)</label>
                        <input type="text" wire:model="nib" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Nomor Induk Berusaha">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kota Lokasi Usaha *</label>
                        <select wire:model="city" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                            <option value="">-- Pilih Kota --</option>
                            @foreach($cities as $c)
                                <option value="{{ $c->name }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                        @error('city') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                        <input type="text" wire:model="address" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white" placeholder="Jl. Merdeka No. 1">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Cara Pelamar Menghubungi Anda *</label>
                        <div class="grid grid-cols-2 gap-2">
                            <button type="button" wire:click="$set('contact_method', 'whatsapp')"
                                class="py-3 rounded-xl border-2 text-sm font-bold transition-all {{ $contact_method === 'whatsapp' ? 'border-green-500 bg-green-50 text-green-700' : 'border-slate-200 text-slate-500' }}">
                                <i class='bx bxl-whatsapp'></i> WhatsApp
                            </button>
                            <button type="button" wire:click="$set('contact_method', 'email')"
                                class="py-3 rounded-xl border-2 text-sm font-bold transition-all {{ $contact_method === 'email' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-500' }}">
                                <i class='bx bx-envelope'></i> Email
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-xs text-blue-700">
                ✓ <strong>Profil Pemberi Kerja Terdaftar</strong> akan ditampilkan setelah Anda menyelesaikan pendaftaran. Pelamar kerja dapat melihat bahwa identitas Anda sudah terdaftar.
            </div>

            <button type="button" wire:click="register" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-blue-600/20">
                <i class='bx bx-rocket'></i> Mulai Posting Lowongan — Gratis
            </button>
        </div>

        <p class="text-center text-xs text-slate-400 mt-4">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-600 font-semibold">Masuk di sini</a>
        </p>
    </div>
</div>
