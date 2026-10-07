<template x-if="$store.modals.destination">
  <div
    class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-black/75 backdrop-blur-sm overflow-y-auto animate-in fade-in duration-200"
    @keydown.escape.window="$store.modals.closeDestination()"
  >
    <div class="bg-white rounded-2xl max-w-3xl w-full my-auto overflow-hidden shadow-2xl border border-stone-200 text-stone-900 relative">

      {{-- Floating Close Button --}}
      <button
        type="button"
        @click="$store.modals.closeDestination()"
        class="absolute top-4 right-4 z-20 p-2 rounded-full bg-stone-900/80 text-white hover:bg-stone-950 transition-colors shadow-md"
        aria-label="Close"
      >
        <x-icon name="X" class="w-5 h-5" />
      </button>

      {{-- Hero Visual Area with Scrim --}}
      <div class="relative h-64 sm:h-80 w-full bg-stone-900 overflow-hidden">
        <img
          :src="$store.modals.destination.imageUrl"
          :alt="$store.modals.destination.name"
          referrerpolicy="no-referrer"
          class="w-full h-full object-cover"
          onerror="this.style.display = 'none'"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/40 to-transparent"></div>

        <div class="absolute bottom-5 left-5 right-5 text-white">
          <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-300 mb-1">
            <span x-text="$store.modals.destination.category.toUpperCase().replace('_', ' & ')"></span>
            <span>·</span>
            <span>NEGERI MORELA</span>
          </div>
          <h2 class="font-serif text-2xl sm:text-3xl font-bold text-white tracking-tight" x-text="$store.ui.lang === 'id' ? $store.modals.destination.name : $store.modals.destination.nameEn"></h2>
          <p class="text-xs sm:text-sm text-stone-200 mt-1 max-w-xl font-light" x-text="$store.ui.lang === 'id' ? $store.modals.destination.tagline : $store.modals.destination.taglineEn"></p>
        </div>
      </div>

      {{-- Content Body --}}
      <div class="p-6 sm:p-8 space-y-6 max-h-[60vh] overflow-y-auto">

        {{-- Quick Specifications Bar --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 p-4 rounded-xl bg-stone-50 border border-stone-200 text-xs">
          <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg bg-emerald-100 text-emerald-800">
              <x-icon name="Clock" class="w-4 h-4" />
            </div>
            <div>
              <div class="text-[10px] text-stone-500 uppercase font-semibold">
                <x-t id="Jam Kunjungan" en="Opening Hours" />
              </div>
              <div class="font-medium text-stone-800" x-text="$store.modals.destination.visitingHours"></div>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg bg-emerald-100 text-emerald-800">
              <x-icon name="Ticket" class="w-4 h-4" />
            </div>
            <div>
              <div class="text-[10px] text-stone-500 uppercase font-semibold">
                <x-t id="Harga Tiket" en="Ticket Fee" />
              </div>
              <div class="font-medium text-stone-800 font-mono tabular-nums" x-text="$store.modals.destination.ticketPrice"></div>
            </div>
          </div>

          <div class="flex items-center gap-3">
            <div class="p-2 rounded-lg bg-emerald-100 text-emerald-800">
              <x-icon name="MapPin" class="w-4 h-4" />
            </div>
            <div>
              <div class="text-[10px] text-stone-500 uppercase font-semibold">
                <x-t id="Wilayah" en="Location" />
              </div>
              <div class="font-medium text-stone-800 truncate max-w-[160px]" x-text="$store.modals.destination.location"></div>
            </div>
          </div>
        </div>

        {{-- Description --}}
        <div class="space-y-2">
          <h4 class="text-sm font-semibold uppercase tracking-wider text-stone-900">
            <x-t id="Deskripsi Lengkap" en="About Destination" />
          </h4>
          <p class="text-sm text-stone-700 leading-relaxed max-w-prose" x-text="$store.ui.lang === 'id' ? $store.modals.destination.description : $store.modals.destination.descriptionEn"></p>
        </div>

        {{-- Facilities --}}
        <div class="space-y-2" x-show="$store.modals.destination.facilities && $store.modals.destination.facilities.length > 0">
          <h4 class="text-sm font-semibold uppercase tracking-wider text-stone-900">
            <x-t id="Fasilitas Tersedia" en="Available Amenities" />
          </h4>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
            <template x-for="(fac, idx) in ($store.modals.destination.facilities || [])" :key="idx">
              <div class="flex items-center gap-2 text-stone-700">
                <x-icon name="CheckCircle2" class="w-4 h-4 text-emerald-600 shrink-0" />
                <span x-text="fac"></span>
              </div>
            </template>
          </div>
        </div>

        {{-- Photo Gallery Grid --}}
        <div class="space-y-2" x-show="$store.modals.destination.galleryImages && $store.modals.destination.galleryImages.length > 0">
          <h4 class="text-sm font-semibold uppercase tracking-wider text-stone-900">
            <x-t id="Galeri Foto" en="Photo Gallery" />
          </h4>
          <div class="grid grid-cols-3 gap-2">
            <template x-for="(img, idx) in ($store.modals.destination.galleryImages || [])" :key="idx">
              <div class="h-24 sm:h-32 rounded-lg overflow-hidden bg-stone-100 border border-stone-200">
                <img
                  :src="img"
                  :alt="$store.modals.destination.name + ' ' + (idx + 1)"
                  referrerpolicy="no-referrer"
                  class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                />
              </div>
            </template>
          </div>
        </div>

        {{-- Contact Box --}}
        <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 flex flex-col sm:flex-row items-center justify-between gap-4">
          <div class="space-y-0.5 text-center sm:text-left">
            <span class="text-[11px] font-semibold text-emerald-800 uppercase tracking-wide">
              <x-t id="Kontak Pengelola / Pokdarwis" en="Local Host Contact" />
            </span>
            <div class="font-semibold text-stone-900 text-sm" x-text="$store.modals.destination.contactName"></div>
            <div class="text-xs text-stone-600 font-mono" x-text="$store.modals.destination.contactPhone"></div>
          </div>

          <div class="flex items-center gap-2">
            <button
              type="button"
              @click="morelaWhatsApp(
                $store.modals.destination.contactPhone,
                $store.ui.lang === 'id'
                  ? ('Halo ' + $store.modals.destination.contactName + ', saya ingin bertanya tentang informasi kunjungan ke ' + $store.modals.destination.name + ' melalui portal Negeri Morella.')
                  : ('Hello ' + $store.modals.destination.contactName + ', I would like to inquire about visiting ' + ($store.modals.destination.nameEn || $store.modals.destination.name) + ' via Negeri Morella portal.')
              )"
              class="flex items-center gap-2 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-semibold transition-colors shadow-xs"
            >
              <x-icon name="Phone" class="w-3.5 h-3.5" />
              <span x-text="$store.ui.lang === 'id' ? 'Hubungi Pengelola' : 'Chat Host via WhatsApp'"></span>
            </button>
          </div>
        </div>
      </div>

      {{-- Footer Actions --}}
      <div class="p-4 sm:p-5 bg-stone-50 border-t border-stone-200 flex flex-wrap items-center justify-between gap-3">
        <div class="flex items-center gap-2">
          <button
            type="button"
            @click="morelaBookFromModal()"
            class="flex items-center gap-1.5 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg text-xs font-semibold transition-colors shadow-xs"
          >
            <x-icon name="Ticket" class="w-4 h-4" />
            <span x-text="$store.ui.lang === 'id' ? 'Pesan E-Tiket Online' : 'Book E-Ticket'"></span>
          </button>

          <button
            type="button"
            @click="$store.modals.openQr({ title: $store.modals.destination.name, subtitle: $store.modals.destination.location, code: $store.modals.destination.slug, type: 'destinasi' })"
            class="flex items-center gap-2 px-3.5 py-2 bg-white border border-stone-300 hover:bg-stone-100 text-stone-800 rounded-lg text-xs font-medium transition-colors"
          >
            <x-icon name="QrCode" class="w-4 h-4 text-emerald-700" />
            <span x-text="$store.ui.lang === 'id' ? 'QR Code' : 'QR'"></span>
          </button>
        </div>

        <div class="flex items-center gap-2">
          <button
            type="button"
            @click="morelaShare($store.modals.destination.name, $store.modals.destination.tagline)"
            class="flex items-center gap-1.5 px-3 py-2 text-stone-600 hover:text-stone-900 hover:bg-stone-100 rounded-lg text-xs font-medium transition-colors"
          >
            <x-icon name="Share2" class="w-4 h-4" />
            <span x-text="$store.ui.lang === 'id' ? 'Bagikan' : 'Share'"></span>
          </button>
          <button
            type="button"
            @click="$store.modals.closeDestination()"
            class="px-4 py-2 bg-stone-900 hover:bg-stone-800 text-white rounded-lg text-xs font-medium transition-colors"
            x-text="$store.ui.lang === 'id' ? 'Tutup' : 'Close'"
          ></button>
        </div>
      </div>
    </div>
  </div>
</template>
