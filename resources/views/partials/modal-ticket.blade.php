<div
  class="contents"
  x-data="{
    copied: false,
    copyCode(ticket) {
      navigator.clipboard.writeText(ticket.bookingCode);
      this.copied = true;
      setTimeout(() => (this.copied = false), 2000);
    },
    shareWhatsApp(ticket) {
      const text = encodeURIComponent(
        'E-Tiket Resmi Morela Tourism:\n' +
        'Kode Booking: ' + ticket.bookingCode + '\n' +
        'Destinasi: ' + ticket.destinationName + '\n' +
        'Tanggal: ' + ticket.visitDate + '\n' +
        'Pengunjung: ' + ticket.visitorName + ' (' + ticket.adultCount + ' Dewasa, ' + ticket.childCount + ' Anak)\n' +
        'Total: Rp ' + ticket.totalAmount.toLocaleString('id-ID') + '\n' +
        'Status: ' + ticket.paymentStatus.toUpperCase()
      );
      window.open('https://wa.me/?text=' + text, '_blank');
    },
  }"
>
  <template x-if="$store.modals.ticketForPrint">
    <div
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-black/80 backdrop-blur-sm overflow-y-auto animate-in fade-in duration-200"
      @keydown.escape.window="$store.modals.closeTicket()"
    >
      <div class="bg-white rounded-3xl max-w-lg w-full my-auto overflow-hidden shadow-2xl border border-stone-200 text-stone-900 relative">

        {{-- Floating Close Button --}}
        <button
          type="button"
          @click="$store.modals.closeTicket()"
          class="absolute top-4 right-4 z-20 p-2 rounded-full bg-stone-900/80 text-white hover:bg-stone-950 transition-colors shadow-md"
          aria-label="Close"
        >
          <x-icon name="X" class="w-5 h-5" />
        </button>

        {{-- E-Ticket Header Strip --}}
        <div class="bg-gradient-to-r from-emerald-800 via-teal-800 to-stone-900 text-white p-6 pb-8 relative overflow-hidden">
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-white/10 backdrop-blur-xs flex items-center justify-center border border-white/20">
              <x-icon name="Compass" class="w-5 h-5 text-emerald-300" />
            </div>
            <div>
              <span class="text-[10px] uppercase font-semibold tracking-widest text-emerald-300">
                PEMERINTAH NEGERI MORELA &amp; POKDARWIS
              </span>
              <h3 class="font-serif text-xl font-bold tracking-tight text-white">
                E-TIKET WISATA RESMI
              </h3>
            </div>
          </div>

          <div class="mt-4 flex items-center justify-between">
            <div class="space-y-0.5">
              <div class="text-[10px] text-stone-300 uppercase">KODE BOOKING</div>
              <div
                class="font-mono text-lg font-bold text-amber-300 tracking-wider"
                x-text="$store.modals.ticketForPrint.bookingCode"
              ></div>
            </div>
            <div class="text-right">
              <span
                class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold uppercase"
                :class="$store.modals.ticketForPrint.paymentStatus === 'paid'
                  ? 'bg-emerald-500/20 text-emerald-200 border border-emerald-400/40'
                  : ($store.modals.ticketForPrint.paymentStatus === 'checked_in'
                    ? 'bg-blue-500/20 text-blue-200 border border-blue-400/40'
                    : 'bg-amber-500/20 text-amber-200 border border-amber-400/40')"
              >
                <x-icon name="CheckCircle2" class="w-3.5 h-3.5" />
                <span
                  x-text="$store.modals.ticketForPrint.paymentStatus === 'paid'
                    ? 'LUNAS (CONFIRMED)'
                    : ($store.modals.ticketForPrint.paymentStatus === 'checked_in' ? 'CHECKED-IN' : 'MENUNGGU BAYAR')"
                ></span>
              </span>
            </div>
          </div>
        </div>

        {{-- Tear-off Scallop Line --}}
        <div class="relative flex items-center justify-between px-6 -my-3 z-10">
          <div class="w-6 h-6 rounded-full bg-black/80 -ml-9"></div>
          <div class="flex-1 border-b-2 border-dashed border-stone-300 mx-2"></div>
          <div class="w-6 h-6 rounded-full bg-black/80 -mr-9"></div>
        </div>

        {{-- Ticket Body --}}
        <div class="p-6 sm:p-7 space-y-6 pt-6">
          {{-- Destination & Visit Date --}}
          <div class="space-y-2">
            <div class="text-xs font-semibold text-emerald-800 uppercase tracking-wide">
              Destinasi Tujuan
            </div>
            <h4
              class="font-serif text-xl font-bold text-stone-900 leading-snug"
              x-text="$store.modals.ticketForPrint.destinationName"
            ></h4>
            <div class="flex items-center gap-1.5 text-xs text-stone-500">
              <x-icon name="MapPin" class="w-3.5 h-3.5 text-emerald-600 shrink-0" />
              <span x-text="$store.modals.ticketForPrint.destinationLocation"></span>
            </div>
          </div>

          {{-- Details Definition Grid --}}
          <div class="grid grid-cols-2 gap-4 p-4 rounded-2xl bg-stone-50 border border-stone-200 text-xs">
            <div>
              <div class="text-[10px] text-stone-400 uppercase font-semibold">TANGGAL KUNJUNGAN</div>
              <div class="font-semibold text-stone-800 text-sm mt-0.5" x-text="$store.modals.ticketForPrint.visitDate"></div>
            </div>

            <div>
              <div class="text-[10px] text-stone-400 uppercase font-semibold">PEMESAN / WISATAWAN</div>
              <div class="font-semibold text-stone-800 truncate text-sm mt-0.5" x-text="$store.modals.ticketForPrint.visitorName"></div>
            </div>

            <div>
              <div class="text-[10px] text-stone-400 uppercase font-semibold">JUMLAH PENGUNJUNG</div>
              <div class="font-medium text-stone-800 mt-0.5">
                <span x-text="$store.modals.ticketForPrint.adultCount + ' Dewasa'"></span><template x-if="$store.modals.ticketForPrint.childCount > 0"><span x-text="' · ' + $store.modals.ticketForPrint.childCount + ' Anak'"></span></template>
              </div>
            </div>

            <div>
              <div class="text-[10px] text-stone-400 uppercase font-semibold">METODE BAYAR</div>
              <div class="font-medium text-stone-800 uppercase mt-0.5" x-text="$store.modals.ticketForPrint.paymentMethod.replace('_', ' ')"></div>
            </div>
          </div>

          {{-- Financial Breakdown --}}
          <div class="space-y-1.5 text-xs text-stone-600 border-t border-b border-stone-200 py-3">
            <div class="flex justify-between">
              <span x-text="'Tiket Masuk (' + $store.modals.ticketForPrint.adultCount + 'x @Rp ' + $store.modals.ticketForPrint.pricePerTicket.toLocaleString('id-ID') + '):'"></span>
              <span class="font-mono" x-text="'Rp ' + ($store.modals.ticketForPrint.adultCount * $store.modals.ticketForPrint.pricePerTicket).toLocaleString('id-ID')"></span>
            </div>
            <template x-if="$store.modals.ticketForPrint.childCount > 0">
              <div class="flex justify-between">
                <span x-text="'Tiket Anak (' + $store.modals.ticketForPrint.childCount + 'x @Rp ' + ($store.modals.ticketForPrint.pricePerTicket / 2).toLocaleString('id-ID') + '):'"></span>
                <span class="font-mono" x-text="'Rp ' + ($store.modals.ticketForPrint.childCount * ($store.modals.ticketForPrint.pricePerTicket / 2)).toLocaleString('id-ID')"></span>
              </div>
            </template>
            <div class="flex justify-between">
              <span>Retribusi Kebersihan &amp; Asuransi Pesisir:</span>
              <span class="font-mono" x-text="'Rp ' + $store.modals.ticketForPrint.cleanlinessFee.toLocaleString('id-ID')"></span>
            </div>
            <div class="flex justify-between pt-1.5 border-t border-stone-100 font-bold text-stone-900 text-sm">
              <span>Total Pembayaran:</span>
              <span class="font-mono text-emerald-800 font-bold text-base" x-text="'Rp ' + $store.modals.ticketForPrint.totalAmount.toLocaleString('id-ID')"></span>
            </div>
          </div>

          {{-- Barcode & Verification QR Pattern --}}
          <div class="p-4 bg-stone-50 rounded-2xl border border-stone-200 flex flex-col items-center justify-center space-y-2">
            <div class="w-32 h-32 bg-white p-2 rounded-lg border border-stone-200 flex items-center justify-center shadow-xs">
              {{-- Dynamic QR Matrix Pattern --}}
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

                <rect x="38" y="38" width="8" height="8" fill="#0f172a" />
                <rect x="52" y="38" width="6" height="6" fill="#047857" />
                <rect x="42" y="52" width="6" height="6" fill="#047857" />
                <rect x="54" y="52" width="8" height="8" fill="#0f172a" />
                <rect x="70" y="42" width="8" height="8" fill="#0f172a" />
                <rect x="40" y="70" width="8" height="8" fill="#0f172a" />
                <rect x="60" y="70" width="8" height="8" fill="#047857" />
                <rect x="74" y="74" width="8" height="8" fill="#0f172a" />
              </svg>
            </div>

            <div
              class="font-mono text-[11px] text-stone-500 tracking-wider"
              x-text="$store.modals.ticketForPrint.qrValidationCode"
            ></div>
            <p class="text-[10px] text-stone-400 text-center">
              Tunjukkan barcode / QR ini kepada petugas di loket masuk wisata Negeri Morela.
            </p>
          </div>
        </div>

        {{-- Action Controls --}}
        <div class="p-4 sm:p-5 bg-stone-50 border-t border-stone-200 flex flex-wrap items-center justify-between gap-2">
          <button
            type="button"
            @click="copyCode($store.modals.ticketForPrint)"
            class="flex items-center gap-1.5 px-3 py-2 bg-white border border-stone-300 hover:bg-stone-100 rounded-xl text-xs font-medium text-stone-700 transition-colors"
          >
            <template x-if="copied"><x-icon name="Check" class="w-3.5 h-3.5 text-emerald-600" /></template>
            <template x-if="!copied"><x-icon name="Copy" class="w-3.5 h-3.5" /></template>
            <span x-text="copied ? 'Tersalin!' : 'Salin Kode'"></span>
          </button>

          <div class="flex items-center gap-2">
            <button
              type="button"
              @click="shareWhatsApp($store.modals.ticketForPrint)"
              class="flex items-center gap-1.5 px-3 py-2 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 rounded-xl text-xs font-semibold transition-colors"
            >
              <x-icon name="Share2" class="w-3.5 h-3.5" />
              <span>Kirim WhatsApp</span>
            </button>

            <button
              type="button"
              @click="morelaPrintTicket()"
              class="flex items-center gap-1.5 px-4 py-2 bg-stone-900 hover:bg-stone-800 text-white rounded-xl text-xs font-semibold transition-colors shadow-xs"
            >
              <x-icon name="Printer" class="w-3.5 h-3.5" />
              <span>Cetak Tiket</span>
            </button>
          </div>
        </div>

      </div>
    </div>
  </template>
</div>
