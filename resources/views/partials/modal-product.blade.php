<template x-if="$store.modals.product">
  <div
    class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-black/75 backdrop-blur-sm overflow-y-auto animate-in fade-in duration-200"
    @keydown.escape.window="$store.modals.closeProduct()"
  >
    <div class="bg-white rounded-2xl max-w-xl w-full my-auto overflow-hidden shadow-2xl border border-stone-200 text-stone-900 relative">
      {{-- Close Button --}}
      <button
        type="button"
        @click="$store.modals.closeProduct()"
        class="absolute top-4 right-4 z-20 p-2 rounded-full bg-stone-900/80 text-white hover:bg-stone-950 transition-colors shadow-md"
        aria-label="Close"
      >
        <x-icon name="X" class="w-5 h-5" />
      </button>

      {{-- Product Image --}}
      <div class="relative h-56 sm:h-72 w-full bg-stone-100 overflow-hidden">
        <img
          :src="$store.modals.product.imageUrl"
          :alt="$store.modals.product.name"
          referrerpolicy="no-referrer"
          class="w-full h-full object-cover"
          onerror="this.style.display = 'none'"
        />
        <div class="absolute top-4 left-4 bg-emerald-700 text-white text-[11px] font-semibold px-2.5 py-1 rounded-md uppercase tracking-wider shadow-sm" x-text="$store.modals.product.category.replace('_', ' ')"></div>
      </div>

      {{-- Info Content --}}
      <div class="p-6 sm:p-7 space-y-5">
        <div>
          <h3 class="font-serif text-xl sm:text-2xl font-bold text-stone-900" x-text="$store.ui.lang === 'id' ? $store.modals.product.name : $store.modals.product.nameEn"></h3>
          <div class="flex items-baseline gap-2 mt-2">
            <span class="font-mono text-2xl font-bold text-emerald-700 tabular-nums" x-text="$store.modals.product.priceFormatted"></span>
            <span class="text-xs text-stone-500">
              / <span x-text="$store.modals.product.unit"></span>
            </span>
          </div>
        </div>

        <div class="p-3.5 bg-stone-50 rounded-xl border border-stone-200 space-y-1.5 text-xs text-stone-700">
          <div class="font-semibold text-stone-900 uppercase tracking-wide text-[10px] text-stone-500">
            <x-t id="Deskripsi Produk" en="Product Details" />
          </div>
          <p class="leading-relaxed" x-text="$store.ui.lang === 'id' ? $store.modals.product.description : $store.modals.product.descriptionEn"></p>
        </div>

        {{-- Seller / Group Credentials --}}
        <div class="p-4 rounded-xl bg-stone-50 border border-stone-200 space-y-2 text-xs">
          <div class="flex items-center gap-2 text-stone-900 font-semibold">
            <x-icon name="Store" class="w-4 h-4 text-emerald-600" />
            <span x-text="$store.modals.product.sellerGroup"></span>
          </div>
          <div class="text-stone-600">
            <span class="font-medium text-stone-800" x-text="$store.ui.lang === 'id' ? 'Penanggung Jawab:' : 'Host / Contact:'"></span> <span x-text="$store.modals.product.sellerName"></span>
          </div>
          <div class="flex items-center gap-2 text-stone-500">
            <x-icon name="MapPin" class="w-3.5 h-3.5 text-stone-400 shrink-0" />
            <span x-text="$store.modals.product.sellerAddress"></span>
          </div>
        </div>

        {{-- Action Row --}}
        <div class="flex items-center gap-3 pt-2">
          <button
            type="button"
            @click="morelaWhatsApp(
              $store.modals.product.sellerPhone,
              $store.ui.lang === 'id'
                ? ('Halo ' + $store.modals.product.sellerName + ' (' + $store.modals.product.sellerGroup + '), saya tertarik memesan produk &quot;' + $store.modals.product.name + '&quot; (' + $store.modals.product.priceFormatted + '/' + $store.modals.product.unit + ') yang saya lihat di portal Negeri Morella.')
                : ('Hello ' + $store.modals.product.sellerName + ', I would like to order &quot;' + ($store.modals.product.nameEn || $store.modals.product.name) + '&quot; (' + $store.modals.product.priceFormatted + '/' + $store.modals.product.unit + ') from the Negeri Morella marketplace.')
            )"
            class="flex-1 flex items-center justify-center gap-2 py-3 px-4 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm"
          >
            <x-icon name="Phone" class="w-4 h-4" />
            <span x-text="$store.ui.lang === 'id' ? 'Hubungi Penjual (WhatsApp)' : 'Contact Seller (WhatsApp)'"></span>
          </button>

          <button
            type="button"
            @click="$store.modals.openQr({ title: $store.modals.product.name, subtitle: $store.modals.product.priceFormatted + ' · ' + $store.modals.product.sellerGroup, code: $store.modals.product.id, type: 'umkm' })"
            class="p-3 bg-stone-100 hover:bg-stone-200 text-stone-800 rounded-xl transition-colors"
            title="QR Code Produk"
          >
            <x-icon name="QrCode" class="w-5 h-5 text-emerald-700" />
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
