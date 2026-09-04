<div class="max-w-3xl mx-auto px-4 py-6">

    {{-- Header & Info Saldo --}}
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm mb-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-md bg-slate-100 text-slate-700 text-xs font-semibold mb-2">
                    <i class='bx bx-wallet text-sm'></i> Saldo & Kuota Lamaran
                </div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Isi Ulang Kuota Lamaran</h1>
                <p class="text-slate-500 text-sm mt-1">Pilih paket kuota sesuai kebutuhan pencarian kerja Anda.</p>
            </div>
            <div class="bg-slate-900 text-white rounded-xl p-4 sm:p-5 text-center sm:text-right shrink-0">
                <span class="text-xs text-slate-400 font-medium block">Sisa Kuota Aktif</span>
                <span class="text-3xl font-black">{{ $profile->application_credits ?? 0 }}</span>
                <span class="text-xs text-slate-400 block mt-0.5">Kesempatan Melamar</span>
            </div>
        </div>

        {{-- Status CV ATS --}}
        <div class="mt-5 pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-2">
                <span class="text-slate-500">Status CV ATS:</span>
                @if($profile->cv_generated)
                    <span class="inline-flex items-center gap-1 font-semibold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md border border-emerald-200">
                        <i class='bx bx-check-circle'></i> Aktif & Siap Digunakan
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 font-semibold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-md border border-amber-200">
                        <i class='bx bx-time-five'></i> Belum Aktif
                    </span>
                @endif
            </div>
            <span class="text-slate-400">Didukung oleh Midtrans Payment Gateway</span>
        </div>
    </div>

    {{-- PAKET SIAP KERJA ALL-IN-ONE (FEATURED) --}}
    @php $bundle = $packages['bundle_komplit']; @endphp
    <div class="bg-gradient-to-br from-blue-700 to-indigo-800 rounded-2xl p-6 sm:p-7 text-white mb-8 shadow-md relative overflow-hidden">
        <div class="flex flex-wrap items-center justify-between gap-2 mb-3">
            <span class="px-3 py-1 rounded-md text-xs font-bold uppercase tracking-wider bg-amber-400 text-slate-950">
                Paket Komplit Rekomendasi
            </span>
            <span class="text-xs font-semibold text-blue-200">Hemat Rp40.000</span>
        </div>

        <h2 class="text-xl font-bold mb-2">{{ $bundle['name'] }}</h2>
        <p class="text-blue-100 text-xs sm:text-sm max-w-xl mb-5 leading-relaxed">
            Paket lengkap untuk pelamar kerja. Dapatkan akses penuh pembuatan <strong>CV ATS Profesional</strong> beserta <strong>5 Kuota Lamaran Prioritas</strong> langsung ke kontak perusahaan.
        </p>

        <div class="grid sm:grid-cols-3 gap-3 mb-6">
            <div class="bg-white/10 rounded-xl p-3 border border-white/10 flex items-center gap-2.5">
                <i class='bx bx-file-blank text-lg text-blue-200'></i>
                <span class="text-xs font-medium">1x CV ATS Otomatis</span>
            </div>
            <div class="bg-white/10 rounded-xl p-3 border border-white/10 flex items-center gap-2.5">
                <i class='bx bx-briefcase-alt text-lg text-blue-200'></i>
                <span class="text-xs font-medium">5x Kuota Melamar Langsung</span>
            </div>
            <div class="bg-white/10 rounded-xl p-3 border border-white/10 flex items-center gap-2.5">
                <i class='bx bx-message-rounded-dots text-lg text-blue-200'></i>
                <span class="text-xs font-medium">Template Pesan Pengantar HRD</span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pt-4 border-t border-white/15">
            <div>
                <div class="flex items-baseline gap-2">
                    <span class="text-xs text-blue-200 line-through">Rp{{ number_format($bundle['original_price'], 0, ',', '.') }}</span>
                    <span class="text-2xl sm:text-3xl font-black text-amber-300">Rp{{ number_format($bundle['price'], 0, ',', '.') }}</span>
                </div>
                <span class="text-[11px] text-blue-200">Sekali pembayaran, langsung aktif.</span>
            </div>

            <button wire:click="buyPackage('{{ $bundle['key'] }}')"
                    wire:loading.attr="disabled"
                    class="px-6 py-3 bg-amber-400 hover:bg-amber-300 active:scale-95 text-slate-950 font-bold text-xs rounded-xl shadow transition-all flex items-center justify-center gap-2 shrink-0">
                <span wire:loading.remove wire:target="buyPackage('{{ $bundle['key'] }}')">
                    Pilih Paket Komplit
                </span>
                <span wire:loading wire:target="buyPackage('{{ $bundle['key'] }}')">
                    <i class='bx bx-loader-alt animate-spin text-base'></i> Memproses...
                </span>
            </button>
        </div>
    </div>

    {{-- PILIHAN PAKET KUOTA LAMARAN (PAY-PER-APPLY) --}}
    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base font-bold text-slate-900">Paket Kuota Lamaran Saja</h3>
                <p class="text-slate-500 text-xs">Pilihan kuota fleksibel untuk pengiriman lamaran kerja.</p>
            </div>
            <span class="text-xs font-medium text-slate-500">Standar BMC Near Job</span>
        </div>

        <div class="grid md:grid-cols-3 gap-4 items-stretch">
            {{-- Starter (1 Lamaran) --}}
            @php $starter = $packages['starter']; @endphp
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-100 text-slate-600">
                            Hemat 60%
                        </span>
                        <span class="text-xs font-semibold text-slate-400">1 Kuota</span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm mb-1">{{ $starter['name'] }}</h4>
                    <p class="text-xs text-slate-500 mb-4 leading-relaxed">{{ $starter['description'] }}</p>
                </div>

                <div>
                    <div class="mb-4 pt-3 border-t border-slate-100">
                        <span class="text-xs text-slate-400 line-through block">Rp{{ number_format($starter['original_price'], 0, ',', '.') }}</span>
                        <span class="text-xl font-bold text-slate-900">Rp{{ number_format($starter['price'], 0, ',', '.') }}</span>
                    </div>

                    <button wire:click="buyPackage('{{ $starter['key'] }}')"
                            wire:loading.attr="disabled"
                            class="w-full py-2.5 rounded-xl border border-slate-300 hover:border-slate-800 hover:bg-slate-50 text-slate-800 font-semibold text-xs transition-all flex items-center justify-center gap-1.5">
                        <span wire:loading.remove wire:target="buyPackage('{{ $starter['key'] }}')">Beli 1 Kuota</span>
                        <span wire:loading wire:target="buyPackage('{{ $starter['key'] }}')"><i class='bx bx-loader-alt animate-spin'></i></span>
                    </button>
                </div>
            </div>

            {{-- Popular (5 Lamaran - Paling Diminati) --}}
            @php $popular = $packages['popular']; @endphp
            <div class="bg-white rounded-2xl p-5 border-2 border-blue-600 shadow-sm flex flex-col justify-between relative">
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-blue-50 text-blue-700">
                            Paling Populer
                        </span>
                        <span class="text-xs font-bold text-blue-700">5 Kuota</span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm mb-1">{{ $popular['name'] }}</h4>
                    <p class="text-xs text-slate-500 mb-4 leading-relaxed">{{ $popular['description'] }}</p>
                </div>

                <div>
                    <div class="mb-4 pt-3 border-t border-slate-100">
                        <span class="text-xs text-slate-400 line-through block">Rp{{ number_format($popular['original_price'], 0, ',', '.') }}</span>
                        <span class="text-xl font-bold text-blue-700">Rp{{ number_format($popular['price'], 0, ',', '.') }}</span>
                        <span class="text-[11px] text-emerald-600 font-medium block">Rp3.999 per lamaran</span>
                    </div>

                    <button wire:click="buyPackage('{{ $popular['key'] }}')"
                            wire:loading.attr="disabled"
                            class="w-full py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold text-xs shadow-sm transition-all flex items-center justify-center gap-1.5">
                        <span wire:loading.remove wire:target="buyPackage('{{ $popular['key'] }}')">Pilih Paket 5 Kuota</span>
                        <span wire:loading wire:target="buyPackage('{{ $popular['key'] }}')"><i class='bx bx-loader-alt animate-spin'></i></span>
                    </button>
                </div>
            </div>

            {{-- Intensive (12 Lamaran) --}}
            @php $intensive = $packages['intensive']; @endphp
            <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded bg-slate-100 text-slate-600">
                            Hemat Maksimal
                        </span>
                        <span class="text-xs font-semibold text-slate-400">12 Kuota</span>
                    </div>
                    <h4 class="font-bold text-slate-900 text-sm mb-1">{{ $intensive['name'] }}</h4>
                    <p class="text-xs text-slate-500 mb-4 leading-relaxed">{{ $intensive['description'] }}</p>
                </div>

                <div>
                    <div class="mb-4 pt-3 border-t border-slate-100">
                        <span class="text-xs text-slate-400 line-through block">Rp{{ number_format($intensive['original_price'], 0, ',', '.') }}</span>
                        <span class="text-xl font-bold text-slate-900">Rp{{ number_format($intensive['price'], 0, ',', '.') }}</span>
                        <span class="text-[11px] text-emerald-600 font-medium block">Rp3.333 per lamaran</span>
                    </div>

                    <button wire:click="buyPackage('{{ $intensive['key'] }}')"
                            wire:loading.attr="disabled"
                            class="w-full py-2.5 rounded-xl border border-slate-300 hover:border-slate-800 hover:bg-slate-50 text-slate-800 font-semibold text-xs transition-all flex items-center justify-center gap-1.5">
                        <span wire:loading.remove wire:target="buyPackage('{{ $intensive['key'] }}')">Beli Paket 12 Kuota</span>
                        <span wire:loading wire:target="buyPackage('{{ $intensive['key'] }}')"><i class='bx bx-loader-alt animate-spin'></i></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- RIWAYAT TRANSAKSI TERAKHIR --}}
    @if($recentOrders->count() > 0)
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm mb-6">
        <h3 class="text-sm font-bold text-slate-900 mb-3 flex items-center gap-2">
            <i class='bx bx-history text-base text-slate-400'></i> Riwayat Transaksi
        </h3>

        <div class="divide-y divide-slate-100 text-xs">
            @foreach($recentOrders as $ord)
                <div class="py-3 flex items-center justify-between gap-3">
                    <div>
                        <span class="font-semibold text-slate-800 block text-xs">{{ $ord->item_name }}</span>
                        <span class="text-slate-400 text-[11px]">{{ $ord->order_number }} &bull; {{ $ord->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="text-right flex items-center gap-3">
                        <div>
                            <span class="font-bold text-slate-900 block">Rp{{ number_format($ord->gross_amount, 0, ',', '.') }}</span>
                            @if($ord->status === 'settlement')
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">
                                    Berhasil
                                </span>
                            @elseif($ord->status === 'pending')
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                                    Menunggu Pembayaran
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-slate-600 bg-slate-100 px-2 py-0.5 rounded">
                                    {{ ucfirst($ord->status) }}
                                </span>
                            @endif
                        </div>

                        @if($ord->status === 'pending')
                            <div class="flex items-center gap-1.5">
                                <button wire:click="syncOrder({{ $ord->id }})" wire:loading.attr="disabled"
                                        class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-medium text-[11px] rounded-lg transition-all flex items-center gap-1">
                                    <span wire:loading.remove wire:target="syncOrder({{ $ord->id }})">Klaim Kuota</span>
                                    <span wire:loading wire:target="syncOrder({{ $ord->id }})"><i class='bx bx-loader-alt animate-spin'></i></span>
                                </button>
                                <button wire:click="openOrderModal({{ $ord->id }})"
                                        class="px-2.5 py-1 bg-slate-900 hover:bg-slate-800 text-white font-medium text-[11px] rounded-lg transition-all">
                                    Bayar
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- MODAL PROTOTYPE PEMBAYARAN INSTAN (DEMO-READY FOR COMPETITION) --}}
    @if($showModal && $modalOrder)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm">
        <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-slate-100 relative animate-scaleUp">
            
            <button wire:click="closeModal" class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 text-2xl">
                <i class='bx bx-x'></i>
            </button>

            @if($isPaidSuccess)
                {{-- TAMPILAN JIKA SUDAH SUKSES --}}
                <div class="text-center py-4">
                    <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-4xl mx-auto mb-4">
                        <i class='bx bx-check'></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-200">
                        Pembayaran Berhasil
                    </span>
                    <h3 class="text-xl font-bold text-slate-900 mt-3 mb-1">Kuota Anda Telah Aktif!</h3>
                    <p class="text-slate-500 text-xs max-w-xs mx-auto mb-6">
                        Paket <strong>{{ $modalOrder->item_name }}</strong> berhasil diproses. Kuota melamar telah ditambahkan ke profil Anda.
                    </p>

                    <div class="space-y-2.5">
                        <a href="{{ route('applicant.map') }}"
                           class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold text-xs sm:text-sm rounded-xl shadow-lg shadow-blue-600/25 transition-all flex items-center justify-center gap-2"
                           style="text-decoration:none;">
                            <i class='bx bx-map-pin text-lg'></i>
                            <span>Cari Lowongan di Peta & Lamar Sekarang</span>
                        </a>
                        <button wire:click="closeModal"
                                class="w-full py-2.5 text-slate-600 hover:text-slate-900 font-semibold text-xs rounded-xl hover:bg-slate-100 transition-all">
                            Selesai
                        </button>
                    </div>
                </div>
            @else
                {{-- TAMPILAN CHECKOUT PROTOTYPE --}}
                <div class="text-center mb-5">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center text-2xl mx-auto mb-2">
                        <i class='bx bx-credit-card-front'></i>
                    </div>
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Checkout Pembayaran</span>
                    <h3 class="text-lg font-bold text-slate-900 mt-1">{{ $modalOrder->item_name }}</h3>
                    <span class="text-xs text-slate-400 font-mono">{{ $modalOrder->order_number }}</span>
                </div>

                {{-- Kotak Rincian & QRIS Demo --}}
                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 mb-5">
                    <div class="flex items-center justify-between text-xs text-slate-500 mb-2">
                        <span>Pilihan Metode:</span>
                        <span class="font-bold text-slate-800">QRIS / GoPay / DANA</span>
                    </div>
                    <div class="flex items-center justify-between text-xs text-slate-500 mb-3 pb-2 border-b border-slate-200">
                        <span>Status:</span>
                        <span class="inline-flex items-center gap-1 font-semibold text-amber-700 bg-amber-50 px-2 py-0.5 rounded border border-amber-200">
                            Menunggu Pembayaran
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700">Total Pembayaran:</span>
                        <span class="text-2xl font-black text-blue-700">Rp{{ number_format($modalOrder->gross_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                {{-- Tombol Aksi Prototype Demo --}}
                <div class="space-y-2.5">
                    <button wire:click="confirmQuickPayment"
                            wire:loading.attr="disabled"
                            class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 active:scale-95 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md shadow-emerald-600/20 transition-all flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="confirmQuickPayment">
                            <i class='bx bx-check-shield text-lg'></i> Konfirmasi Pembayaran (Simulasi Cepat 1-Klik)
                        </span>
                        <span wire:loading wire:target="confirmQuickPayment">
                            <i class='bx bx-loader-alt animate-spin text-base'></i> Mengaktifkan Kuota...
                        </span>
                    </button>

                    <div class="flex items-center justify-between gap-2 pt-2">
                        <button wire:click="openRealSnap"
                                class="text-[11px] text-blue-700 hover:text-blue-900 font-semibold underline">
                            Buka Midtrans Snap Asli
                        </button>
                        <button wire:click="closeModal"
                                class="text-[11px] text-slate-500 hover:text-slate-800 font-medium">
                            Batal
                        </button>
                    </div>
                </div>

                <p class="text-[10px] text-center text-slate-400 mt-4 leading-relaxed">
                    Mode presentasi lomba: Tombol konfirmasi di atas langsung mengaktifkan kuota dan mencatat transaksi lunas secara atomik di database.
                </p>
            @endif

        </div>
    </div>
    @endif

    {{-- MIDTRANS SNAP INTEGRATION SCRIPT --}}
    <script>
        document.addEventListener('livewire:initialized', () => {
            window.addEventListener('pay-with-snap', event => {
                const data = event.detail[0] || event.detail;
                if (typeof window.snap !== 'undefined' && data.snapToken) {
                    window.snap.pay(data.snapToken, {
                        onSuccess: function(result) {
                            @this.onPaymentSuccess(data.orderId);
                            setTimeout(() => window.location.reload(), 1200);
                        },
                        onPending: function(result) {
                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Menunggu proses pembayaran diselesaikan.', type: 'info' } }));
                        },
                        onError: function(result) {
                            window.dispatchEvent(new CustomEvent('notify', { detail: { message: 'Pembayaran gagal. Silakan coba kembali.', type: 'error' } }));
                        }
                    });
                }
            });
        });
    </script>
</div>
