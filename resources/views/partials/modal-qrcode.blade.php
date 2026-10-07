<div class="contents" x-data="{ copied: false }">
  <template x-if="$store.modals.qr">
    <div
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm animate-in fade-in duration-200"
      @keydown.escape.window="$store.modals.closeQr()"
    >
      <div class="bg-white rounded-2xl max-w-sm w-full p-6 text-stone-900 shadow-2xl relative border border-stone-200">
        <button
          type="button"
          @click="$store.modals.closeQr()"
          class="absolute top-4 right-4 text-stone-400 hover:text-stone-700 p-1.5 rounded-full hover:bg-stone-100 transition-colors"
          aria-label="Close"
        >
          <x-icon name="X" class="w-5 h-5" />
        </button>

        <div class="text-center space-y-1 mb-5">
          <span class="text-[11px] font-semibold text-emerald-700 uppercase tracking-widest">
            <x-t id="QR Code Resmi Wisata Desa Morela" en="Official Negeri Morella QR" />
          </span>
          <h3 class="font-serif text-xl font-bold text-stone-900" x-text="$store.modals.qr.title"></h3>
          <p class="text-xs text-stone-500" x-text="$store.modals.qr.subtitle"></p>
        </div>

        {{-- QR Code Presentation Box --}}
        <div class="bg-stone-50 border-2 border-stone-200 rounded-xl p-5 flex flex-col items-center justify-center shadow-inner relative group">
          <div class="w-48 h-48 bg-white p-3 rounded-lg shadow-sm border border-stone-200 flex flex-col items-center justify-center relative">
            {{-- SVG Rendered Realistic QR Code Matrix --}}
            <svg viewBox="0 0 100 100" class="w-full h-full text-stone-900" fill="currentColor">
              {{-- Outer Corners Finder Patterns --}}
              <rect x="5" y="5" width="28" height="28" rx="3" fill="#0f172a" />
              <rect x="10" y="10" width="18" height="18" fill="white" />
              <rect x="14" y="14" width="10" height="10" fill="#0f172a" />

              <rect x="67" y="5" width="28" height="28" rx="3" fill="#0f172a" />
              <rect x="72" y="10" width="18" height="18" fill="white" />
              <rect x="76" y="14" width="10" height="10" fill="#0f172a" />

              <rect x="5" y="67" width="28" height="28" rx="3" fill="#0f172a" />
              <rect x="10" y="72" width="18" height="18" fill="white" />
              <rect x="14" y="76" width="10" height="10" fill="#0f172a" />

              {{-- Timing Marks --}}
              <rect x="36" y="8" width="4" height="4" fill="#0f172a" />
              <rect x="44" y="8" width="4" height="4" fill="#0f172a" />
              <rect x="52" y="8" width="4" height="4" fill="#0f172a" />
              <rect x="60" y="8" width="4" height="4" fill="#0f172a" />

              <rect x="8" y="36" width="4" height="4" fill="#0f172a" />
              <rect x="8" y="44" width="4" height="4" fill="#0f172a" />
              <rect x="8" y="52" width="4" height="4" fill="#0f172a" />
              <rect x="8" y="60" width="4" height="4" fill="#0f172a" />

              {{-- Data Grid Dots --}}
              <rect x="38" y="38" width="6" height="6" fill="#047857" />
              <rect x="48" y="38" width="6" height="6" fill="#0f172a" />
              <rect x="58" y="38" width="6" height="6" fill="#047857" />
              <rect x="40" y="48" width="8" height="8" fill="#0f172a" />
              <rect x="52" y="48" width="8" height="8" fill="#0f172a" />
              <rect x="38" y="60" width="6" height="6" fill="#0f172a" />
              <rect x="48" y="60" width="6" height="6" fill="#047857" />
              <rect x="58" y="60" width="6" height="6" fill="#0f172a" />

              <rect x="70" y="38" width="6" height="6" fill="#0f172a" />
              <rect x="80" y="42" width="6" height="6" fill="#0f172a" />
              <rect x="86" y="52" width="6" height="6" fill="#0f172a" />
              <rect x="74" y="60" width="6" height="6" fill="#0f172a" />
              <rect x="84" y="68" width="6" height="6" fill="#0f172a" />
              <rect x="72" y="78" width="6" height="6" fill="#0f172a" />
              <rect x="82" y="86" width="6" height="6" fill="#0f172a" />

              <rect x="38" y="74" width="6" height="6" fill="#0f172a" />
              <rect x="48" y="78" width="6" height="6" fill="#0f172a" />
              <rect x="58" y="84" width="6" height="6" fill="#0f172a" />

              {{-- Center Seal Badge --}}
              <circle cx="50" cy="50" r="9" fill="white" stroke="#047857" stroke-width="2" />
              <circle cx="50" cy="50" r="5" fill="#047857" />
            </svg>
          </div>

          <p class="text-[11px] text-stone-500 mt-3 font-medium text-center">
            <x-t
              id="Pindai dengan kamera smartphone untuk membuka informasi lengkap lokasi ini."
              en="Scan with smartphone camera to open complete guide for this destination."
            />
          </p>
        </div>

        {{-- Action Controls --}}
        <div class="grid grid-cols-2 gap-2 mt-4">
          <button
            type="button"
            @click="navigator.clipboard.writeText(window.location.origin + '/#' + $store.modals.qr.type + '/' + $store.modals.qr.code); copied = true; setTimeout(() => (copied = false), 2000)"
            class="flex items-center justify-center gap-1.5 py-2 px-3 bg-stone-100 hover:bg-stone-200 text-stone-800 rounded-lg text-xs font-medium transition-colors"
          >
            <template x-if="copied"><x-icon name="Check" class="w-3.5 h-3.5 text-emerald-600" /></template>
            <template x-if="!copied"><x-icon name="Copy" class="w-3.5 h-3.5" /></template>
            <span x-text="copied ? ($store.ui.lang === 'id' ? 'Tersalin!' : 'Copied!') : ($store.ui.lang === 'id' ? 'Salin Tautan' : 'Copy Link')"></span>
          </button>
          <button
            type="button"
            @click="window.print()"
            class="flex items-center justify-center gap-1.5 py-2 px-3 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-medium transition-colors"
          >
            <x-icon name="Printer" class="w-3.5 h-3.5" />
            <span><x-t id="Cetak QR Wisata" en="Print Badge" /></span>
          </button>
        </div>

        <div class="mt-3 text-center">
          <span class="text-[10px] text-stone-400">
            Kolaborasi Universitas Darussalam Ambon &amp; Desa Morela
          </span>
        </div>
      </div>
    </div>
  </template>
</div>
