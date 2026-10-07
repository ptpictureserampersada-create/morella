<template x-if="$store.modals.article">
  <div
    class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-black/75 backdrop-blur-sm overflow-y-auto animate-in fade-in duration-200"
    @keydown.escape.window="$store.modals.closeArticle()"
  >
    <div class="bg-white rounded-2xl max-w-2xl w-full my-auto overflow-hidden shadow-2xl border border-stone-200 text-stone-900 relative">
      <button
        type="button"
        @click="$store.modals.closeArticle()"
        class="absolute top-4 right-4 z-20 p-2 rounded-full bg-stone-900/80 text-white hover:bg-stone-950 transition-colors shadow-md"
        aria-label="Close"
      >
        <x-icon name="X" class="w-5 h-5" />
      </button>

      <div class="relative h-60 sm:h-72 w-full bg-stone-900 overflow-hidden">
        <img
          :src="$store.modals.article.imageUrl"
          :alt="$store.modals.article.title"
          referrerpolicy="no-referrer"
          class="w-full h-full object-cover"
          onerror="this.style.display = 'none'"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/35 to-transparent"></div>
        <div class="absolute bottom-5 left-5 right-5 text-white">
          <span
            class="text-[11px] font-semibold uppercase tracking-widest text-emerald-300"
            x-text="$store.modals.article.category + ' · WARTA DESA &amp; PENGABDIAN'"
          ></span>
          <h2
            class="font-serif text-xl sm:text-2xl font-bold text-white mt-1 leading-snug"
            x-text="$store.ui.lang === 'id' ? $store.modals.article.title : $store.modals.article.titleEn"
          ></h2>
        </div>
      </div>

      <div class="p-6 sm:p-8 space-y-5 max-h-[60vh] overflow-y-auto">
        {{-- Metadata bar --}}
        <div class="flex flex-wrap items-center gap-3 text-xs text-stone-500 pb-3 border-b border-stone-200">
          <span class="flex items-center gap-1.5 font-medium text-stone-800">
            <x-icon name="User" class="w-3.5 h-3.5 text-emerald-600" />
            <span x-text="$store.modals.article.author + ' (' + $store.modals.article.authorRole + ')'"></span>
          </span>
          <span>·</span>
          <span class="flex items-center gap-1">
            <x-icon name="Calendar" class="w-3.5 h-3.5 text-stone-400" />
            <span x-text="$store.modals.article.publishedDate"></span>
          </span>
          <span>·</span>
          <span class="flex items-center gap-1">
            <x-icon name="Clock" class="w-3.5 h-3.5 text-stone-400" />
            <span x-text="$store.modals.article.readTime"></span>
          </span>
        </div>

        {{-- Article Summary --}}
        <div
          class="p-4 bg-emerald-50/60 rounded-xl border border-emerald-100 text-xs sm:text-sm text-emerald-950 font-medium leading-relaxed italic"
          x-text="'&quot;' + ($store.ui.lang === 'id' ? $store.modals.article.summary : $store.modals.article.summaryEn) + '&quot;'"
        ></div>

        {{-- Full content --}}
        <div
          class="text-stone-800 text-sm leading-relaxed space-y-4 whitespace-pre-line max-w-prose"
          x-text="$store.ui.lang === 'id' ? $store.modals.article.content : $store.modals.article.contentEn"
        ></div>
      </div>

      <div class="p-4 bg-stone-50 border-t border-stone-200 flex justify-end">
        <button
          type="button"
          @click="$store.modals.closeArticle()"
          class="px-4 py-2 bg-stone-900 hover:bg-stone-800 text-white rounded-lg text-xs font-medium transition-colors"
          x-text="$store.ui.lang === 'id' ? 'Tutup' : 'Close'"
        ></button>
      </div>
    </div>
  </div>
</template>
