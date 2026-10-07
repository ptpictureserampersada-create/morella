<template x-if="$store.modals.culture">
  <div
    class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-black/75 backdrop-blur-sm overflow-y-auto animate-in fade-in duration-200"
    @keydown.escape.window="$store.modals.closeCulture()"
  >
    <div class="bg-white rounded-2xl max-w-2xl w-full my-auto overflow-hidden shadow-2xl border border-stone-200 text-stone-900 relative">
      <button
        type="button"
        @click="$store.modals.closeCulture()"
        class="absolute top-4 right-4 z-20 p-2 rounded-full bg-stone-900/80 text-white hover:bg-stone-950 transition-colors shadow-md"
        aria-label="Close"
      >
        <x-icon name="X" class="w-5 h-5" />
      </button>

      <div class="relative h-56 sm:h-64 w-full bg-stone-900 overflow-hidden">
        <img
          :src="$store.modals.culture.imageUrl"
          :alt="$store.modals.culture.title"
          referrerpolicy="no-referrer"
          class="w-full h-full object-cover"
          onerror="this.style.display = 'none'"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/40 to-transparent"></div>
        <div class="absolute bottom-5 left-5 right-5 text-white">
          <span
            class="text-[10px] font-semibold tracking-widest uppercase text-amber-300"
            x-text="$store.modals.culture.category.toUpperCase().replace('_', ' ') + ' · ARSIP KEBUDAYAAN DESA MORELA'"
          ></span>
          <h2
            class="font-serif text-2xl font-bold text-white mt-1"
            x-text="$store.ui.lang === 'id' ? $store.modals.culture.title : $store.modals.culture.titleEn"
          ></h2>
        </div>
      </div>

      <div class="p-6 sm:p-8 space-y-6 max-h-[60vh] overflow-y-auto">
        {{-- Historical Significance Ribbon --}}
        <div class="p-4 rounded-xl bg-amber-50/70 border border-amber-200/80 space-y-1">
          <div class="flex items-center gap-2 text-xs font-semibold text-amber-900 uppercase tracking-wide">
            <x-icon name="Award" class="w-4 h-4 text-amber-700" />
            <span><x-t id="Makna & Signifikansi Sejarah" en="Historical Significance" /></span>
          </div>
          <p
            class="text-xs text-amber-950 leading-relaxed font-serif italic"
            x-text="'&quot;' + $store.modals.culture.historicalSignificance + '&quot;'"
          ></p>
          <div class="text-[11px] text-amber-800 flex items-center gap-1 pt-1 font-mono">
            <x-icon name="Clock" class="w-3.5 h-3.5" />
            <span x-text="$store.modals.culture.periodOrTime"></span>
          </div>
        </div>

        {{-- Long form text --}}
        <div
          class="space-y-4 text-stone-800 text-sm leading-relaxed max-w-prose whitespace-pre-line"
          x-text="$store.ui.lang === 'id' ? $store.modals.culture.content : $store.modals.culture.contentEn"
        ></div>

        {{-- Tags --}}
        <div
          class="pt-4 border-t border-stone-200 flex flex-wrap items-center gap-2 text-xs text-stone-600"
          x-show="$store.modals.culture.tags && $store.modals.culture.tags.length > 0"
        >
          <x-icon name="Tag" class="w-3.5 h-3.5 text-stone-400" />
          <template x-for="(t, idx) in ($store.modals.culture.tags || [])" :key="idx">
            <span class="bg-stone-100 px-2.5 py-1 rounded-md text-stone-700" x-text="'#' + t"></span>
          </template>
        </div>
      </div>

      <div class="p-4 bg-stone-50 border-t border-stone-200 flex justify-end">
        <button
          type="button"
          @click="$store.modals.closeCulture()"
          class="px-4 py-2 bg-stone-900 hover:bg-stone-800 text-white rounded-lg text-xs font-medium transition-colors"
          x-text="$store.ui.lang === 'id' ? 'Selesai Membaca' : 'Close'"
        ></button>
      </div>
    </div>
  </div>
</template>
