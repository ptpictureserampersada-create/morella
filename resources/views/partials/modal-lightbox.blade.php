@php($galleryItems = $gallery ?? [])
<div
  class="contents"
  x-data="{ gallery: {{ Js::from($galleryItems) }} }"
  @keydown.escape.window="if ($store.modals.lightboxIndex !== null) $store.modals.closeLightbox()"
  @keydown.arrow-right.window="if ($store.modals.lightboxIndex !== null && gallery.length) $store.modals.openLightbox(($store.modals.lightboxIndex + 1) % gallery.length)"
  @keydown.arrow-left.window="if ($store.modals.lightboxIndex !== null && gallery.length) $store.modals.openLightbox(($store.modals.lightboxIndex - 1 + gallery.length) % gallery.length)"
>
  <template x-if="$store.modals.lightboxIndex !== null && gallery[$store.modals.lightboxIndex]">
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/95 backdrop-blur-md p-4 animate-in fade-in duration-200">
      {{-- Close button --}}
      <button
        type="button"
        @click="$store.modals.closeLightbox()"
        class="absolute top-5 right-5 z-20 p-2.5 rounded-full bg-stone-900/80 text-stone-300 hover:text-white hover:bg-stone-800 transition-colors"
        aria-label="Close"
      >
        <x-icon name="X" class="w-6 h-6" />
      </button>

      {{-- Prev button --}}
      <button
        type="button"
        @click="$store.modals.openLightbox(($store.modals.lightboxIndex - 1 + gallery.length) % gallery.length)"
        class="absolute left-4 top-1/2 -translate-y-1/2 z-20 p-3 rounded-full bg-stone-900/80 text-white hover:bg-stone-800 transition-colors shadow-lg"
        aria-label="Previous image"
      >
        <x-icon name="ChevronLeft" class="w-6 h-6" />
      </button>

      {{-- Next button --}}
      <button
        type="button"
        @click="$store.modals.openLightbox(($store.modals.lightboxIndex + 1) % gallery.length)"
        class="absolute right-4 top-1/2 -translate-y-1/2 z-20 p-3 rounded-full bg-stone-900/80 text-white hover:bg-stone-800 transition-colors shadow-lg"
        aria-label="Next image"
      >
        <x-icon name="ChevronRight" class="w-6 h-6" />
      </button>

      {{-- Image container --}}
      <div class="max-w-5xl max-h-[85vh] flex flex-col items-center">
        <div class="relative overflow-hidden rounded-xl bg-stone-900 flex items-center justify-center max-h-[72vh]">
          <template x-if="gallery[$store.modals.lightboxIndex].videoUrl">
            <video
              :src="gallery[$store.modals.lightboxIndex].videoUrl"
              class="max-h-[72vh] max-w-full object-contain rounded-xl select-none"
              controls
              autoplay
            ></video>
          </template>
          <template x-if="!gallery[$store.modals.lightboxIndex].videoUrl">
            <img
              :src="gallery[$store.modals.lightboxIndex].imageUrl"
              :alt="gallery[$store.modals.lightboxIndex].title"
              referrerpolicy="no-referrer"
              class="max-h-[72vh] max-w-full object-contain rounded-xl select-none"
              onerror="this.style.display = 'none'"
            />
          </template>
        </div>

        {{-- Caption & Metadata strip --}}
        <div class="mt-4 text-center max-w-xl text-stone-200 space-y-1.5 px-4">
          <h4
            class="font-serif text-lg font-semibold text-white"
            x-text="$store.ui.lang === 'id' ? gallery[$store.modals.lightboxIndex].title : gallery[$store.modals.lightboxIndex].titleEn"
          ></h4>
          <div class="flex flex-wrap items-center justify-center gap-3 text-xs text-stone-400">
            <span class="flex items-center gap-1">
              <x-icon name="MapPin" class="w-3.5 h-3.5 text-emerald-400" />
              <span x-text="gallery[$store.modals.lightboxIndex].location"></span>
            </span>
            <span>·</span>
            <span class="flex items-center gap-1">
              <x-icon name="Camera" class="w-3.5 h-3.5 text-stone-400" />
              <span x-text="gallery[$store.modals.lightboxIndex].photographer"></span>
            </span>
            <span>·</span>
            <span class="flex items-center gap-1">
              <x-icon name="Calendar" class="w-3.5 h-3.5 text-stone-400" />
              <span x-text="gallery[$store.modals.lightboxIndex].dateTaken"></span>
            </span>
          </div>
          <div class="text-[11px] text-stone-500 pt-1">
            <span x-text="($store.modals.lightboxIndex + 1) + ' / ' + gallery.length"></span>
          </div>
        </div>
      </div>
    </div>
  </template>
</div>
