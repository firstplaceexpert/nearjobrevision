<div class="min-h-screen pb-36 pt-16" style="background: #f0f4f9;">
    <div class="max-w-md mx-auto px-4 pt-5">
        <h1 class="text-2xl font-extrabold text-slate-800 mb-1">Profil Usaha</h1>
        <p class="text-slate-400 text-sm mb-5">Kelola informasi perusahaan dan data kontak Anda.</p>

        <div class="bg-white rounded-2xl overflow-hidden" style="border: 1px solid #e8edf5; box-shadow: 0 2px 12px rgba(0,0,0,.04);">
            <form wire:submit="save" class="space-y-4 p-5">
                <h3 class="text-xs font-extrabold uppercase tracking-widest pb-2" style="color: #5680d8; border-bottom: 1px solid #e8edf5;">Data Pemilik</h3>
                
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Pemilik Usaha</label>
                    <input type="text" wire:model="owner_name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nomor WhatsApp</label>
                    <input type="tel" wire:model="whatsapp" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                </div>

                <h3 class="text-xs font-extrabold uppercase tracking-widest pb-2 mt-6" style="color: #5680d8; border-bottom: 1px solid #e8edf5;">Data Usaha</h3>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Nama Usaha</label>
                    <input type="text" wire:model="company_name" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Bidang Usaha</label>
                    <select wire:model="business_field" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                        @foreach($categories as $k => $v)
                            <option value="{{ $k }}">{{ $v }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">NIB (opsional)</label>
                    <input type="text" wire:model="nib" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Kota Lokasi Usaha</label>
                    <select wire:model="city" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                        @foreach($cities as $c)
                            <option value="{{ $c->name }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Alamat Lengkap</label>
                    <input type="text" wire:model="address" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 text-sm bg-slate-50 focus:bg-white">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Preferensi Kontak Utama</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" wire:click="$set('contact_method', 'whatsapp')"
                            class="py-3 rounded-xl border-2 text-sm font-bold transition-all {{ $contact_method === 'whatsapp' ? 'border-green-500 bg-green-50 text-green-700' : 'border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                            <i class='bx bxl-whatsapp'></i> WhatsApp
                        </button>
                        <button type="button" wire:click="$set('contact_method', 'email')"
                            class="py-3 rounded-xl border-2 text-sm font-bold transition-all {{ $contact_method === 'email' ? 'border-blue-500 bg-blue-50 text-blue-700' : 'border-slate-200 text-slate-500 hover:bg-slate-50' }}">
                            <i class='bx bx-envelope'></i> Email
                        </button>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full py-3.5 text-white font-bold rounded-xl text-sm transition-all" style="background: #5680d8; box-shadow: 0 4px 15px rgba(86,128,216,.3);">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
