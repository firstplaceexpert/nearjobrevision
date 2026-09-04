<div class="min-h-screen flex items-center justify-center px-4 py-12" style="background: #f0f4f9;">
    <div class="w-full max-w-sm">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mx-auto mb-4" style="background: #5680d8; box-shadow: 0 8px 25px rgba(86,128,216,.35);">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg>
            </div>
            <h1 class="text-2xl font-extrabold text-slate-800">Masuk ke NEAR JOB</h1>
            <p class="text-slate-400 text-sm mt-1">Masukkan email dan kata sandi Anda</p>
        </div>

        <div class="bg-white rounded-3xl p-8" style="border: 1.5px solid #e8edf5; box-shadow: 0 4px 30px rgba(37,67,155,.08);">
            <form wire:submit="login" class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Email</label>
                    <input type="email" wire:model="email" id="login-email"
                        class="w-full px-4 py-3.5 rounded-xl text-sm transition-all"
                        style="border: 1.5px solid #e2e8f0; background: #f8faff;"
                        placeholder="email@contoh.com" autocomplete="email">
                    @error('email') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-2">Kata Sandi</label>
                    <input type="password" wire:model="password" id="login-password"
                        class="w-full px-4 py-3.5 rounded-xl text-sm transition-all"
                        style="border: 1.5px solid #e2e8f0; background: #f8faff;"
                        placeholder="••••••••" autocomplete="current-password">
                    @error('password') <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p> @enderror
                </div>
                <button type="submit"
                    class="w-full py-4 text-white font-bold rounded-xl text-sm transition-all shadow-md"
                    style="background: #5680d8; box-shadow: 0 4px 15px rgba(86,128,216,.3);">
                    <i class='bx bx-log-in mr-1.5'></i> Masuk
                </button>
            </form>

            <div style="margin-top: 28px; padding-top: 24px; border-top: 1.5px solid #e8edf5; display: flex; flex-direction: column; gap: 14px;">
                <p style="font-size: 13px; color: #94a3b8; text-align: center; font-weight: 600; margin: 0 0 4px;">Belum punya akun?</p>
                <a href="{{ route('register.applicant') }}"
                   class="flex items-center justify-center gap-2.5 w-full py-3.5 font-bold rounded-xl text-sm transition-all"
                   style="border: 2px solid #c7d6f5; color: #5680d8; background: #f8faff; text-decoration: none;">
                    <i class='bx bx-search'></i> Daftar sebagai Pencari Kerja
                </a>
                <a href="{{ route('register.company') }}"
                   class="flex items-center justify-center gap-2.5 w-full py-3.5 font-bold rounded-xl text-sm transition-all"
                   style="border: 2px solid #e2e8f0; color: #475569; background: #ffffff; text-decoration: none;">
                    <i class='bx bx-buildings'></i> Daftar sebagai Pemberi Kerja
                </a>
            </div>
        </div>
    </div>
</div>
