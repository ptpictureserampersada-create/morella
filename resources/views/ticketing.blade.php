@extends('layouts.app')

@php
$ticketData = [
    'destinations' => $destinations,
    'bookings' => $bookings,
    'preselectedDestId' => request()->query('destinasi'),
];
@endphp

@section('content')
<div
  class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 space-y-10"
  x-data="ticketingView({{ Js::from($ticketData) }})"
>
  {{-- Editorial Header --}}
  <div class="space-y-3 max-w-3xl">
    <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-emerald-700">
      <x-icon name="Ticket" class="w-4 h-4" />
      <x-t id="Sistem Pembayaran E-Tiket Resmi" en="Official E-Ticketing System" />
    </div>
    <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-stone-900 tracking-tight">
      <x-t id="Tiket Wisata & Retribusi Desa Morela" en="Negeri Morella E-Ticketing" />
    </h1>
    <p class="text-sm text-stone-600 leading-relaxed max-w-prose"
      data-i18n-id="Pesan tiket masuk wisata Negeri Morela secara online, cepat, dan transparan. Mendukung pembayaran instan melalui QRIS Nasional (Bank Maluku Malut, BCA, BRI, Mandiri, e-Wallet) dan Virtual Account."
      data-i18n-en="Book tourist entrance tickets online for Morela destinations with instant cashless payments via QRIS, Virtual Account, or on-site counter payments."></p>
  </div>

  {{-- Navigation Sub-Tabs --}}
  <div class="flex items-center gap-2 border-b border-stone-200 overflow-x-auto pb-1 scrollbar-none">
    <button
      @click="activeTab = 'pesan'; bookingStep = 'form'"
      :class="activeTab === 'pesan' ? 'border-emerald-700 text-emerald-800' : 'border-transparent text-stone-500 hover:text-stone-900'"
      class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold transition-colors border-b-2 -mb-[2px] whitespace-nowrap"
    >
      <x-icon name="CreditCard" class="w-4 h-4" />
      <x-t id="Pesan Tiket Baru" en="Book New Ticket" />
    </button>

    <button
      @click="activeTab = 'cek'"
      :class="activeTab === 'cek' ? 'border-emerald-700 text-emerald-800' : 'border-transparent text-stone-500 hover:text-stone-900'"
      class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold transition-colors border-b-2 -mb-[2px] whitespace-nowrap"
    >
      <x-icon name="Search" class="w-4 h-4" />
      <x-t id="Cek Status & Cetak Tiket" en="Find / Print E-Ticket" />
    </button>

    <button
      @click="activeTab = 'info'"
      :class="activeTab === 'info' ? 'border-emerald-700 text-emerald-800' : 'border-transparent text-stone-500 hover:text-stone-900'"
      class="flex items-center gap-2 px-4 py-2.5 text-xs font-semibold transition-colors border-b-2 -mb-[2px] whitespace-nowrap"
    >
      <x-icon name="Info" class="w-4 h-4" />
      <x-t id="Daftar Tarif & Regulasi" en="Tariff & Regulations" />
    </button>
  </div>

  {{-- TAB 1: PESAN TIKET ONLINE --}}
  <template x-if="activeTab === 'pesan'">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

      {{-- Left Form Column (7 Cols) --}}
      <div class="lg:col-span-7 space-y-6">

        {{-- Step: Booking Form --}}
        <template x-if="bookingStep === 'form'">
          <form @submit.prevent="handleCreateOrder" class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-xs space-y-6">

            {{-- Step 1: Destination Selection --}}
            <div class="space-y-3">
              <div class="flex items-center gap-2 text-xs font-semibold text-stone-900 uppercase tracking-wide">
                <span class="w-5 h-5 rounded-full bg-emerald-700 text-white flex items-center justify-center text-[10px]">1</span>
                <x-t id="Pilih Destinasi Wisata" en="Select Destination" />
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <template x-for="dest in availableDestinations" :key="dest.id">
                  <div
                    @click="selectedDestId = dest.id"
                    :class="selectedDestId === dest.id ? 'border-emerald-600 bg-emerald-50/50 ring-2 ring-emerald-500/20' : 'border-stone-200 hover:bg-stone-50'"
                    class="p-3.5 rounded-2xl border cursor-pointer transition-all flex items-start gap-3"
                  >
                    <img
                      :src="dest.imageUrl"
                      :alt="dest.name"
                      class="w-12 h-12 rounded-xl object-cover bg-stone-100 shrink-0"
                    />
                    <div class="min-w-0">
                      <h4 class="font-serif font-bold text-xs text-stone-900 truncate" x-text="dest.name"></h4>
                      <div class="font-mono text-xs font-semibold text-emerald-800 mt-0.5" x-text="dest.ticketPrice"></div>
                      <div class="text-[10px] text-stone-500 truncate" x-text="dest.visitingHours"></div>
                    </div>
                  </div>
                </template>
              </div>
            </div>

            {{-- Step 2: Date & Visitor Quantities --}}
            <div class="space-y-3 pt-4 border-t border-stone-100">
              <div class="flex items-center gap-2 text-xs font-semibold text-stone-900 uppercase tracking-wide">
                <span class="w-5 h-5 rounded-full bg-emerald-700 text-white flex items-center justify-center text-[10px]">2</span>
                <x-t id="Tanggal Kunjungan & Jumlah Pengunjung" en="Visit Date & Visitors" />
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label class="text-[11px] font-semibold text-stone-600 block mb-1">
                    Tanggal Rencana Kunjungan
                  </label>
                  <input
                    type="date"
                    :min="todayStr"
                    x-model="visitDate"
                    class="w-full px-3 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs font-medium text-stone-900 focus:outline-none focus:border-emerald-600"
                    required
                  />
                </div>

                <div>
                  <label class="text-[11px] font-semibold text-stone-600 block mb-1">
                    Pengunjung Dewasa
                  </label>
                  <div class="flex items-center border border-stone-200 rounded-xl bg-stone-50 overflow-hidden">
                    <button
                      type="button"
                      @click="adultCount = Math.max(1, adultCount - 1)"
                      class="px-3 py-2 text-stone-600 hover:bg-stone-200 text-xs font-bold"
                    >
                      -
                    </button>
                    <span class="flex-1 text-center font-mono font-bold text-xs text-stone-900" x-text="adultCount"></span>
                    <button
                      type="button"
                      @click="adultCount = adultCount + 1"
                      class="px-3 py-2 text-stone-600 hover:bg-stone-200 text-xs font-bold"
                    >
                      +
                    </button>
                  </div>
                </div>

                <div>
                  <label class="text-[11px] font-semibold text-stone-600 block mb-1">
                    Anak-anak (&lt; 10 Thn)
                  </label>
                  <div class="flex items-center border border-stone-200 rounded-xl bg-stone-50 overflow-hidden">
                    <button
                      type="button"
                      @click="childCount = Math.max(0, childCount - 1)"
                      class="px-3 py-2 text-stone-600 hover:bg-stone-200 text-xs font-bold"
                    >
                      -
                    </button>
                    <span class="flex-1 text-center font-mono font-bold text-xs text-stone-900" x-text="childCount"></span>
                    <button
                      type="button"
                      @click="childCount = childCount + 1"
                      class="px-3 py-2 text-stone-600 hover:bg-stone-200 text-xs font-bold"
                    >
                      +
                    </button>
                  </div>
                </div>
              </div>
            </div>

            {{-- Step 3: Visitor Contact Details --}}
            <div class="space-y-3 pt-4 border-t border-stone-100">
              <div class="flex items-center gap-2 text-xs font-semibold text-stone-900 uppercase tracking-wide">
                <span class="w-5 h-5 rounded-full bg-emerald-700 text-white flex items-center justify-center text-[10px]">3</span>
                <x-t id="Data Diri Pemesan (Pengunjung)" en="Lead Visitor Information" />
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                <div>
                  <label class="font-semibold text-stone-600 block mb-1">Nama Lengkap *</label>
                  <input
                    type="text"
                    required
                    x-model="visitorName"
                    placeholder="Contoh: Muhammad Ramli"
                    class="w-full px-3 py-2 border border-stone-200 rounded-xl bg-stone-50 focus:border-emerald-600"
                  />
                </div>

                <div>
                  <label class="font-semibold text-stone-600 block mb-1">Nomor WhatsApp / HP *</label>
                  <input
                    type="tel"
                    required
                    x-model="visitorPhone"
                    placeholder="Contoh: 081234567890"
                    class="w-full px-3 py-2 border border-stone-200 rounded-xl bg-stone-50 focus:border-emerald-600 font-mono"
                  />
                </div>

                <div>
                  <label class="font-semibold text-stone-600 block mb-1">Alamat Email</label>
                  <input
                    type="email"
                    x-model="visitorEmail"
                    placeholder="ramli@gmail.com"
                    class="w-full px-3 py-2 border border-stone-200 rounded-xl bg-stone-50 focus:border-emerald-600"
                  />
                </div>

                <div>
                  <label class="font-semibold text-stone-600 block mb-1">Asal Kota / Domisili</label>
                  <input
                    type="text"
                    x-model="visitorCity"
                    placeholder="Kota Ambon / Jakarta / Luar Negeri"
                    class="w-full px-3 py-2 border border-stone-200 rounded-xl bg-stone-50 focus:border-emerald-600"
                  />
                </div>
              </div>
            </div>

            {{-- Step 4: Payment Methods --}}
            <div class="space-y-3 pt-4 border-t border-stone-100">
              <div class="flex items-center gap-2 text-xs font-semibold text-stone-900 uppercase tracking-wide">
                <span class="w-5 h-5 rounded-full bg-emerald-700 text-white flex items-center justify-center text-[10px]">4</span>
                <x-t id="Pilih Metode Pembayaran" en="Payment Method" />
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                <div
                  @click="paymentMethod = 'qris'"
                  :class="paymentMethod === 'qris' ? 'border-emerald-600 bg-emerald-50/60 ring-2 ring-emerald-500/20' : 'border-stone-200 hover:bg-stone-50'"
                  class="p-3.5 rounded-2xl border cursor-pointer transition-all space-y-1.5"
                >
                  <div class="flex items-center justify-between">
                    <span class="font-bold text-stone-900 flex items-center gap-1.5">
                      <x-icon name="QrCode" class="w-4 h-4 text-emerald-700" />
                      <span>QRIS Instan</span>
                    </span>
                    <span class="text-[10px] font-semibold text-emerald-800 bg-emerald-100 px-1.5 py-0.5 rounded">
                      Populer
                    </span>
                  </div>
                  <p class="text-[11px] text-stone-500 leading-tight">
                    Bank Maluku Malut, BCA, BRI, GoPay, OVO, Dana.
                  </p>
                </div>

                <div
                  @click="paymentMethod = 'va_bca'"
                  :class="(paymentMethod === 'va_bca' || paymentMethod === 'va_maluku') ? 'border-emerald-600 bg-emerald-50/60 ring-2 ring-emerald-500/20' : 'border-stone-200 hover:bg-stone-50'"
                  class="p-3.5 rounded-2xl border cursor-pointer transition-all space-y-1.5"
                >
                  <div class="flex items-center justify-between">
                    <span class="font-bold text-stone-900 flex items-center gap-1.5">
                      <x-icon name="Building2" class="w-4 h-4 text-emerald-700" />
                      <span>Virtual Account</span>
                    </span>
                  </div>
                  <p class="text-[11px] text-stone-500 leading-tight">
                    Transfer otomatis via ATM atau Mobile Banking.
                  </p>
                </div>

                <div
                  @click="paymentMethod = 'cash_on_site'"
                  :class="paymentMethod === 'cash_on_site' ? 'border-emerald-600 bg-emerald-50/60 ring-2 ring-emerald-500/20' : 'border-stone-200 hover:bg-stone-50'"
                  class="p-3.5 rounded-2xl border cursor-pointer transition-all space-y-1.5"
                >
                  <div class="flex items-center justify-between">
                    <span class="font-bold text-stone-900 flex items-center gap-1.5">
                      <x-icon name="DollarSign" class="w-4 h-4 text-emerald-700" />
                      <span>Bayar di Lokasi</span>
                    </span>
                  </div>
                  <p class="text-[11px] text-stone-500 leading-tight">
                    Bayar tunai di pos loket masuk Pokdarwis Morela.
                  </p>
                </div>
              </div>
            </div>

            {{-- Submit Action --}}
            <div class="pt-4 border-t border-stone-200 flex items-center justify-between">
              <div class="space-y-0.5">
                <span class="text-[10px] text-stone-400 uppercase font-semibold">Total Tagihan</span>
                <div class="font-mono text-xl font-bold text-emerald-800" x-text="'Rp ' + grandTotal.toLocaleString('id-ID')"></div>
              </div>

              <button
                type="submit"
                class="px-6 py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold rounded-xl text-xs sm:text-sm flex items-center gap-2 shadow-sm transition-all"
              >
                <span>Lanjutkan Pembayaran</span>
                <x-icon name="ArrowRight" class="w-4 h-4" />
              </button>
            </div>

          </form>
        </template>

        {{-- Step: Payment Pending & Simulated QRIS --}}
        <template x-if="bookingStep === 'payment_pending' && currentBooking">
          <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-xs space-y-6 animate-in fade-in">
            <div class="flex items-center justify-between pb-4 border-b border-stone-200">
              <div class="space-y-0.5">
                <span class="text-[10px] font-semibold text-amber-700 uppercase tracking-wider">
                  MENUNGGU PEMBAYARAN
                </span>
                <h3 class="font-serif text-xl font-bold text-stone-900">
                  Selesaikan Pembayaran E-Tiket
                </h3>
              </div>

              <div class="text-right">
                <span class="text-[10px] text-stone-400 block">Sisa Waktu:</span>
                <span class="font-mono font-bold text-rose-600 text-base" x-text="formatTimer(paymentTimer)"></span>
              </div>
            </div>

            {{-- QRIS Graphic Simulator --}}
            <div class="p-6 bg-stone-50 rounded-2xl border border-stone-200 flex flex-col items-center justify-center space-y-4">
              <div class="flex items-center gap-2">
                <div class="font-bold text-stone-900 text-sm tracking-widest">
                  QRIS · STANDAR PEMBAYARAN NASIONAL
                </div>
              </div>

              {{-- QRIS SVG Matrix --}}
              <div class="w-56 h-56 bg-white p-4 rounded-2xl border-2 border-stone-300 shadow-sm flex flex-col items-center justify-center relative">
                <svg viewBox="0 0 100 100" class="w-full h-full text-stone-900" fill="currentColor">
                  <rect x="5" y="5" width="28" height="28" rx="2" fill="#047857" />
                  <rect x="10" y="10" width="18" height="18" fill="white" />
                  <rect x="14" y="14" width="10" height="10" fill="#047857" />

                  <rect x="67" y="5" width="28" height="28" rx="2" fill="#047857" />
                  <rect x="72" y="10" width="18" height="18" fill="white" />
                  <rect x="76" y="14" width="10" height="10" fill="#047857" />

                  <rect x="5" y="67" width="28" height="28" rx="2" fill="#047857" />
                  <rect x="10" y="72" width="18" height="18" fill="white" />
                  <rect x="14" y="76" width="10" height="10" fill="#047857" />

                  <rect x="36" y="12" width="6" height="6" fill="#0f172a" />
                  <rect x="48" y="12" width="6" height="6" fill="#047857" />
                  <rect x="36" y="38" width="8" height="8" fill="#0f172a" />
                  <rect x="48" y="38" width="6" height="6" fill="#047857" />
                  <rect x="60" y="38" width="8" height="8" fill="#0f172a" />
                  <rect x="38" y="52" width="8" height="8" fill="#047857" />
                  <rect x="52" y="52" width="8" height="8" fill="#0f172a" />
                  <rect x="70" y="42" width="8" height="8" fill="#047857" />
                  <rect x="40" y="70" width="8" height="8" fill="#0f172a" />
                  <rect x="56" y="70" width="8" height="8" fill="#047857" />
                  <rect x="70" y="70" width="8" height="8" fill="#0f172a" />

                  <circle cx="50" cy="50" r="8" fill="white" stroke="#047857" stroke-width="2" />
                  <circle cx="50" cy="50" r="4" fill="#047857" />
                </svg>

                <div class="absolute bottom-2 text-[9px] font-mono text-stone-400">
                  NMID: ID1029384756201
                </div>
              </div>

              <div class="text-center space-y-1">
                <div class="text-xs font-semibold text-stone-800">
                  Merchant: <span class="text-emerald-800">POKDARWIS NEGERI MORELA</span>
                </div>
                <div class="font-mono text-xl font-bold text-stone-900" x-text="'Rp ' + currentBooking.totalAmount.toLocaleString('id-ID')"></div>
                <div class="text-[11px] text-stone-500">
                  Kode Booking: <strong class="font-mono" x-text="currentBooking.bookingCode"></strong>
                </div>
              </div>
            </div>

            <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 text-xs text-emerald-900 space-y-1">
              <div class="font-semibold">Instruksi Pembayaran:</div>
              <ol class="list-decimal list-inside space-y-1 text-[11px]">
                <li>Buka aplikasi Mobile Banking (Bank Maluku Malut, BCA, BRI, Mandiri) atau e-Wallet (GoPay, OVO, Dana).</li>
                <li>Pilih menu <strong>Pindai / Scan QRIS</strong> lalu arahkan kamera ke barcode di atas.</li>
                <li>Periksa nama penerima bertuliskan <strong>POKDARWIS NEGERI MORELA</strong>.</li>
                <li>Setelah transaksi berhasil, klik tombol verifikasi di bawah ini.</li>
              </ol>
            </div>

            {{-- Simulation button --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
              <button
                @click="bookingStep = 'form'"
                class="w-full sm:w-auto px-4 py-2.5 bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs rounded-xl font-medium"
              >
                Kembali Ubah Pesanan
              </button>

              <button
                @click="handleSimulatePaymentSuccess"
                class="w-full sm:w-auto px-6 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs sm:text-sm font-semibold rounded-xl flex items-center justify-center gap-2 shadow-sm"
              >
                <x-icon name="CheckCircle2" class="w-4 h-4" />
                <span>Saya Sudah Bayar (Konfirmasi)</span>
              </button>
            </div>
          </div>
        </template>

        {{-- Step: Success & Verified E-Ticket Display --}}
        <template x-if="bookingStep === 'success' && currentBooking">
          <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-xs space-y-6 animate-in zoom-in-95">
            <div class="text-center space-y-2">
              <div class="w-14 h-14 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center mx-auto shadow-inner">
                <x-icon name="CheckCircle2" class="w-8 h-8" />
              </div>
              <h3 class="font-serif text-2xl font-bold text-stone-900">
                Pembayaran Berhasil Dikonfirmasi!
              </h3>
              <p class="text-xs text-stone-600 max-w-md mx-auto">
                Terima kasih, <strong x-text="currentBooking.visitorName"></strong>. E-Tiket resmi Anda telah diterbitkan dan tercatat dalam basis data pengelola desa.
              </p>
            </div>

            {{-- Compact E-Ticket Preview Card --}}
            <div class="p-5 rounded-2xl bg-stone-900 text-white space-y-4">
              <div class="flex items-center justify-between border-b border-stone-800 pb-3">
                <div class="text-[10px] text-emerald-400 font-mono tracking-widest uppercase">
                  NEGERI MORELLA · E-TICKET PASS
                </div>
                <span class="text-[10px] px-2 py-0.5 rounded bg-emerald-600 text-white font-semibold">
                  CONFIRMED (LUNAS)
                </span>
              </div>

              <div class="grid grid-cols-2 gap-3 text-xs">
                <div>
                  <div class="text-[10px] text-stone-400 uppercase">KODE BOOKING</div>
                  <div class="font-mono text-base font-bold text-amber-300" x-text="currentBooking.bookingCode"></div>
                </div>
                <div>
                  <div class="text-[10px] text-stone-400 uppercase">TANGGAL WISATA</div>
                  <div class="font-semibold text-stone-100" x-text="currentBooking.visitDate"></div>
                </div>
              </div>

              <div>
                <div class="text-[10px] text-stone-400 uppercase">DESTINASI</div>
                <div class="font-serif text-base font-bold text-white" x-text="currentBooking.destinationName"></div>
              </div>

              <div class="flex items-center justify-between text-xs pt-2 border-t border-stone-800 font-mono">
                <span class="text-stone-400" x-text="'Total: Rp ' + currentBooking.totalAmount.toLocaleString('id-ID')"></span>
                <span class="text-emerald-400" x-text="currentBooking.adultCount + ' Dewasa'"></span>
              </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <button
                @click="$store.modals.openTicket(currentBooking)"
                class="w-full py-3 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded-xl flex items-center justify-center gap-2 shadow-xs"
              >
                <x-icon name="Printer" class="w-4 h-4" />
                <span>Buka & Cetak E-Tiket</span>
              </button>

              <a
                :href="'https://wa.me/?text=' + encodeURIComponent('E-Tiket Wisata Morela:\nKode: ' + currentBooking.bookingCode + '\nDestinasi: ' + currentBooking.destinationName + '\nTanggal: ' + currentBooking.visitDate)"
                target="_blank"
                rel="noreferrer"
                class="w-full py-3 bg-stone-100 hover:bg-stone-200 text-stone-800 text-xs font-semibold rounded-xl flex items-center justify-center gap-2"
              >
                <x-icon name="Share2" class="w-4 h-4" />
                <span>Kirim ke WhatsApp</span>
              </a>
            </div>

            <div class="text-center">
              <button
                @click="bookingStep = 'form'; currentBooking = null"
                class="text-xs text-stone-500 hover:text-stone-900 underline"
              >
                Pesan Tiket untuk Destinasi Lain
              </button>
            </div>
          </div>
        </template>

      </div>

      {{-- Right Summary Column (5 Cols) --}}
      <div class="lg:col-span-5 space-y-6">

        {{-- Selected Destination Showcase Card --}}
        <div class="bg-white rounded-3xl border border-stone-200 overflow-hidden shadow-xs">
          <div class="relative h-44 w-full bg-stone-900">
            <img
              :src="currentDest.imageUrl"
              :alt="currentDest.name"
              class="w-full h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
            <div class="absolute bottom-3 left-4 right-4 text-white">
              <span class="text-[10px] text-emerald-300 font-semibold uppercase">
                DESTINASI PILIHAN
              </span>
              <h4 class="font-serif text-lg font-bold text-white truncate" x-text="currentDest.name"></h4>
            </div>
          </div>

          <div class="p-5 space-y-4 text-xs">
            <div class="flex items-center gap-2 text-stone-600">
              <x-icon name="MapPin" class="w-3.5 h-3.5 text-emerald-600 shrink-0" />
              <span class="truncate" x-text="currentDest.location"></span>
            </div>

            <div class="flex items-center gap-2 text-stone-600">
              <x-icon name="Clock" class="w-3.5 h-3.5 text-emerald-600 shrink-0" />
              <span x-text="currentDest.visitingHours"></span>
            </div>

            {{-- Calculation Details --}}
            <div class="p-4 rounded-2xl bg-stone-50 border border-stone-200 space-y-2">
              <div class="font-semibold text-stone-900 uppercase text-[10px] text-stone-500">
                Rincian Biaya Tiket
              </div>

              <div class="flex justify-between text-stone-600">
                <span x-text="'Tiket Dewasa (' + adultCount + 'x):'"></span>
                <span class="font-mono" x-text="'Rp ' + (adultCount * pricePerTicket).toLocaleString('id-ID')"></span>
              </div>

              <template x-if="childCount > 0">
                <div class="flex justify-between text-stone-600">
                  <span x-text="'Tiket Anak (' + childCount + 'x):'"></span>
                  <span class="font-mono" x-text="'Rp ' + (childCount * Math.round(pricePerTicket * 0.5)).toLocaleString('id-ID')"></span>
                </div>
              </template>

              <div class="flex justify-between text-stone-600">
                <span>Retribusi Sampah & Asuransi:</span>
                <span class="font-mono" x-text="'Rp ' + cleanlinessFee.toLocaleString('id-ID')"></span>
              </div>

              <div class="flex justify-between pt-2 border-t border-stone-200 font-bold text-stone-900 text-sm">
                <span>Total Pembayaran:</span>
                <span class="font-mono text-emerald-800 text-base font-bold" x-text="'Rp ' + grandTotal.toLocaleString('id-ID')"></span>
              </div>
            </div>

            <div class="p-3 bg-emerald-50/60 rounded-xl border border-emerald-100 flex items-start gap-2 text-[11px] text-emerald-950">
              <x-icon name="ShieldCheck" class="w-4 h-4 text-emerald-600 shrink-0 mt-0.5" />
              <span>
                Dana retribusi dikelola secara transparan oleh Pemerintah Negeri Morela untuk pembersihan pantai dan pelestarian terumbu karang.
              </span>
            </div>
          </div>
        </div>

        {{-- Quick Benefits --}}
        <div class="p-5 rounded-3xl bg-stone-900 text-stone-200 space-y-3 text-xs">
          <div class="font-semibold text-white uppercase text-[10px] tracking-wider text-amber-400">
            Keuntungan Tiket Online Morela
          </div>
          <ul class="space-y-2 text-[11px]">
            <li class="flex items-center gap-2">
              <x-icon name="CheckCircle2" class="w-3.5 h-3.5 text-emerald-400 shrink-0" />
              <span>Jalur masuk prioritas tanpa antre di pos loket.</span>
            </li>
            <li class="flex items-center gap-2">
              <x-icon name="CheckCircle2" class="w-3.5 h-3.5 text-emerald-400 shrink-0" />
              <span>Tercatat otomatis dan terlindungi asuransi pesisir.</span>
            </li>
            <li class="flex items-center gap-2">
              <x-icon name="CheckCircle2" class="w-3.5 h-3.5 text-emerald-400 shrink-0" />
              <span>E-Tiket aman tersimpan di smartphone dapat dicetak kapan saja.</span>
            </li>
          </ul>
        </div>

      </div>
    </div>
  </template>

  {{-- TAB 2: CEK STATUS / CARI E-TIKET --}}
  <template x-if="activeTab === 'cek'">
    <div class="max-w-2xl mx-auto space-y-6">
      <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-xs space-y-6">
        <div class="space-y-1">
          <h3 class="font-serif text-xl font-bold text-stone-900">
            Cek Status Pemesanan & Cetak Ulang E-Tiket
          </h3>
          <p class="text-xs text-stone-500">
            Masukkan Kode Booking yang Anda terima saat pemesanan (Contoh: <code class="text-emerald-700 font-mono font-bold">MOR-2609-8812</code>) atau nomor WhatsApp Anda.
          </p>
        </div>

        <form @submit.prevent="handleSearchTicket" class="flex gap-2">
          <input
            type="text"
            required
            x-model="searchBookingCode"
            placeholder="Masukkan Kode Booking (MOR-XXXX-XXXX)..."
            class="flex-1 px-4 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-xs font-mono text-stone-900 focus:outline-none focus:border-emerald-600"
          />
          <button
            type="submit"
            class="px-5 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold rounded-xl flex items-center gap-1.5"
          >
            <x-icon name="Search" class="w-4 h-4" />
            <span>Cari Tiket</span>
          </button>
        </form>

        <template x-if="searchError">
          <div class="p-4 bg-rose-50 border border-rose-200 rounded-xl text-xs text-rose-700 flex items-center gap-2">
            <x-icon name="AlertCircle" class="w-4 h-4 shrink-0" />
            <span x-text="searchError"></span>
          </div>
        </template>

        {{-- Found Result Card --}}
        <template x-if="foundBooking">
          <div class="p-5 rounded-2xl bg-stone-50 border border-stone-200 space-y-4 animate-in fade-in">
            <div class="flex items-center justify-between">
              <div class="space-y-0.5">
                <span class="text-[10px] text-stone-400 uppercase font-semibold">KODE BOOKING</span>
                <div class="font-mono text-lg font-bold text-stone-900" x-text="foundBooking.bookingCode"></div>
              </div>

              <span
                :class="foundBooking.paymentStatus === 'paid' ? 'bg-emerald-100 text-emerald-800' : (foundBooking.paymentStatus === 'checked_in' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800')"
                class="px-2.5 py-1 rounded-full text-[10px] font-semibold uppercase"
                x-text="foundBooking.paymentStatus === 'paid' ? 'LUNAS' : (foundBooking.paymentStatus === 'checked_in' ? 'CHECKED-IN' : 'PENDING')"
              ></span>
            </div>

            <div class="space-y-1 text-xs">
              <div class="font-serif text-base font-bold text-stone-900" x-text="foundBooking.destinationName"></div>
              <div class="text-stone-500">
                Pengunjung: <strong x-text="foundBooking.visitorName"></strong> (<span x-text="foundBooking.adultCount"></span> Dewasa)
              </div>
              <div class="text-stone-500 font-mono">
                Tanggal Kunjungan: <span x-text="foundBooking.visitDate"></span>
              </div>
            </div>

            <div class="pt-2 flex items-center gap-3">
              <button
                @click="$store.modals.openTicket(foundBooking)"
                class="flex-1 py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-semibold flex items-center justify-center gap-1.5"
              >
                <x-icon name="Printer" class="w-4 h-4" />
                <span>Lihat & Cetak E-Tiket</span>
              </button>
            </div>
          </div>
        </template>
      </div>
    </div>
  </template>

  {{-- TAB 3: DAFTAR TARIF & REGULASI DESA --}}
  <template x-if="activeTab === 'info'">
    <div class="max-w-4xl mx-auto space-y-6">
      <div class="bg-white rounded-3xl border border-stone-200 p-6 sm:p-8 shadow-xs space-y-6">
        <div class="space-y-1">
          <h3 class="font-serif text-xl font-bold text-stone-900">
            Tarif Retribusi Resmi & Regulasi Wisatawan
          </h3>
          <p class="text-xs text-stone-500">
            Berdasarkan Keputusan Musyawarah Saniri & Pemerintah Negeri Morela tahun 2026.
          </p>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs text-stone-700">
            <thead class="bg-stone-50 border-b border-stone-200 text-[11px] font-semibold uppercase text-stone-600">
              <tr>
                <th class="px-4 py-3">Nama Destinasi</th>
                <th class="px-4 py-3">Kategori</th>
                <th class="px-4 py-3">Tiket Dewasa</th>
                <th class="px-4 py-3">Tiket Anak</th>
                <th class="px-4 py-3">Jam Operasional</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-stone-200">
              @foreach ($destinations as $d)
                <tr class="hover:bg-stone-50">
                  <td class="px-4 py-3 font-semibold text-stone-900">{{ $d['name'] }}</td>
                  <td class="px-4 py-3 capitalize">{{ str_replace('_', ' ', $d['category']) }}</td>
                  <td class="px-4 py-3 font-mono font-bold text-emerald-800">{{ $d['ticketPrice'] }}</td>
                  <td class="px-4 py-3 font-mono">
                    {{ $d['ticketPriceNum'] > 0 ? 'Rp ' . number_format(round($d['ticketPriceNum'] * 0.5), 0, ',', '.') : 'Gratis' }}
                  </td>
                  <td class="px-4 py-3 font-mono text-stone-500 text-[11px]">{{ $d['visitingHours'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="p-4 bg-stone-50 rounded-2xl border border-stone-200 space-y-2 text-xs text-stone-600">
          <div class="font-semibold text-stone-900">Tata Tertib & Keselamatan Wisatawan:</div>
          <ul class="list-disc list-inside space-y-1 text-[11px]">
            <li>Dilarang menginjak, mengambil, atau merusak karang laut di sekitar Pantai Lubang Buaya.</li>
            <li>Wisatawan wajib menjaga kebersihan dan membuang sampah pada tempat yang disediakan.</li>
            <li>Hormati kearifan lokal adat dan busana sopan saat berkunjung ke situs sejarah dan permukiman adat Morela.</li>
          </ul>
        </div>
      </div>
    </div>
  </template>

</div>

<script>
function ticketingView(data) {
  var available = data.destinations.filter(function (d) { return d.published && d.ticketPriceNum > 0; });
  var defaultDest = null;
  if (data.preselectedDestId) {
    defaultDest = data.destinations.find(function (d) { return d.id === data.preselectedDestId; }) || null;
  }
  if (!defaultDest) {
    defaultDest = available[0] || data.destinations[0] || null;
  }

  return {
    destinations: data.destinations,
    bookings: data.bookings,
    activeTab: 'pesan',
    selectedDestId: defaultDest ? defaultDest.id : '',
    todayStr: new Date().toISOString().split('T')[0],
    visitDate: new Date().toISOString().split('T')[0],
    adultCount: 2,
    childCount: 0,
    visitorName: '',
    visitorPhone: '',
    visitorEmail: '',
    visitorCity: 'Kota Ambon',
    paymentMethod: 'qris',
    bookingStep: 'form',
    currentBooking: null,
    paymentTimer: 900,
    cleanlinessFee: 2000,
    searchBookingCode: '',
    foundBooking: null,
    searchError: '',
    saving: false,

    init() {
      var self = this;
      setInterval(function () {
        if (self.bookingStep === 'payment_pending' && self.paymentTimer > 0) {
          self.paymentTimer = self.paymentTimer - 1;
        }
      }, 1000);
    },

    get availableDestinations() {
      return this.destinations.filter(function (d) { return d.published && d.ticketPriceNum > 0; });
    },
    get currentDest() {
      var self = this;
      return this.destinations.find(function (d) { return d.id === self.selectedDestId; }) || this.destinations[0];
    },
    get pricePerTicket() {
      return this.currentDest ? this.currentDest.ticketPriceNum : 5000;
    },
    get ticketSubtotal() {
      return (this.adultCount * this.pricePerTicket) + (this.childCount * Math.round(this.pricePerTicket * 0.5));
    },
    get grandTotal() {
      return this.ticketSubtotal + this.cleanlinessFee;
    },

    formatTimer(seconds) {
      var mins = Math.floor(seconds / 60);
      var secs = seconds % 60;
      return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
    },

    async handleCreateOrder() {
      var lang = Alpine.store('ui').lang;
      if (!this.visitorName.trim()) {
        alert(lang === 'id' ? 'Silakan masukkan nama lengkap Anda.' : 'Please enter your full name.');
        return;
      }
      if (!this.visitorPhone.trim()) {
        alert(lang === 'id' ? 'Silakan masukkan nomor telepon / WhatsApp.' : 'Please enter your phone number.');
        return;
      }
      if (this.saving) return;
      this.saving = true;

      var dest = this.currentDest;
      try {
        var res = await fetch(window.MORELA_URLS.book, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({
            destinationId: dest.id,
            destinationName: dest.name,
            destinationLocation: dest.location,
            visitDate: this.visitDate,
            visitorName: this.visitorName,
            visitorEmail: this.visitorEmail || (this.visitorName.toLowerCase().replace(/\s+/g, '') + '@gmail.com'),
            visitorPhone: this.visitorPhone,
            visitorCity: this.visitorCity,
            adultCount: this.adultCount,
            childCount: this.childCount,
            pricePerTicket: this.pricePerTicket,
            cleanlinessFee: this.cleanlinessFee,
            totalAmount: this.grandTotal,
            paymentMethod: this.paymentMethod,
          }),
        });
        var booking = await res.json();
        this.bookings.push(booking);
        this.currentBooking = booking;
        this.bookingStep = 'payment_pending';
        this.paymentTimer = 900;
        window.scrollTo({ top: 300, behavior: 'smooth' });
      } finally {
        this.saving = false;
      }
    },

    async handleSimulatePaymentSuccess() {
      if (!this.currentBooking) return;
      try {
        var res = await fetch(window.MORELA_URLS.confirm, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          },
          body: JSON.stringify({ bookingCode: this.currentBooking.bookingCode }),
        });
        if (res.ok) {
          this.currentBooking = await res.json();
        }
      } catch (e) {
        /* mode simulasi: tampilan tetap lanjut walau jaringan gagal */
      }
      this.bookingStep = 'success';
    },

    handleSearchTicket() {
      this.searchError = '';
      this.foundBooking = null;

      var code = this.searchBookingCode.trim().toUpperCase();
      var matched = this.bookings.find(function (b) {
        return b.bookingCode.toUpperCase() === code || (b.visitorPhone || '').includes(code);
      });

      if (matched) {
        this.foundBooking = matched;
      } else {
        this.searchError = Alpine.store('ui').lang === 'id'
          ? 'Tiket dengan Kode Booking tersebut tidak ditemukan. Pastikan format penulisan benar (Contoh: MOR-2609-8812).'
          : 'Booking code not found. Please check and try again.';
      }
    },
  };
}
</script>
@endsection
