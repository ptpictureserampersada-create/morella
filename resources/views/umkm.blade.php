@extends('layouts.app')

@php
  $umkmData = [
    'umkmProducts' => $umkm,
  ];
  $categories = [
    ['id' => 'all', 'labelId' => 'Semua Produk', 'labelEn' => 'All Products'],
    ['id' => 'produk_olahan', 'labelId' => 'Minyak & Olahan Rempah', 'labelEn' => 'Spice Oils & Extracts'],
    ['id' => 'kuliner', 'labelId' => 'Kuliner & Camilan', 'labelEn' => 'Culinary & Snacks'],
    ['id' => 'kerajinan', 'labelId' => 'Kerajinan Tangan', 'labelEn' => 'Handicrafts'],
  ];
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 space-y-10" x-data="umkmView({{ Js::from($umkmData) }})">

  {{-- Editorial Header --}}
  <div class="space-y-3 max-w-3xl">
    <div class="text-xs font-semibold uppercase tracking-widest text-emerald-700">
      <x-t id="Pemberdayaan Ekonomi Masyarakat" en="Community Artisan Market" />
    </div>
    <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 tracking-tight">
      <x-t id="UMKM & Produk Kreatif Desa Morela" en="Local Crafts & Artisans of Morela" />
    </h1>
    <p class="text-sm text-stone-600 leading-relaxed">
      <x-t
        id="Dukung langsung perekonomian kelompok usaha ibu-ibu dan petani Negeri Morela. Dapatkan minyak kayu putih sulingan asli Leihitu, kenari gula aren, olahan pala berkualitas ekspor, dan anyaman lontar etnik."
        en="Directly support village home industries and cooperative artisans. Purchase authentic steam-distilled cajuput eucalyptus oil, canarium brittle, nutmeg delicacies, and handwoven crafts."
      />
    </p>
  </div>

  {{-- Control Strip --}}
  <div class="p-4 sm:p-5 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-4">
    <div class="flex flex-col md:flex-row gap-3 items-center justify-between">
      <div class="relative w-full md:w-96">
        <x-icon name="Search" class="w-4 h-4 text-stone-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
        <input
          type="text"
          x-model="search"
          :placeholder="$store.ui.lang === 'id' ? 'Cari minyak kayu putih, kenari, pala...' : 'Search eucalyptus, nutmeg, crafts...'"
          class="w-full pl-10 pr-4 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-emerald-600"
        />
      </div>

      <div class="flex items-center gap-3 self-end md:self-auto">
        {{-- View Mode Switcher --}}
        <div class="flex items-center bg-stone-100 p-1 rounded-xl">
          <button
            type="button"
            @click="viewMode = 'grid'"
            :class="viewMode === 'grid' ? 'bg-white text-stone-900 shadow-xs' : 'text-stone-500 hover:text-stone-900'"
            class="p-1.5 rounded-lg transition-colors"
            title="Grid View"
          >
            <x-icon name="LayoutGrid" class="w-4 h-4" />
          </button>
          <button
            type="button"
            @click="viewMode = 'table'"
            :class="viewMode === 'table' ? 'bg-white text-stone-900 shadow-xs' : 'text-stone-500 hover:text-stone-900'"
            class="p-1.5 rounded-lg transition-colors"
            title="Table View"
          >
            <x-icon name="Table" class="w-4 h-4" />
          </button>
        </div>

        <div
          class="text-xs text-stone-500 font-mono"
          x-text="filtered.length + ' ' + ($store.ui.lang === 'id' ? 'Produk' : 'Items')"
        ></div>
      </div>
    </div>

    {{-- Category Filter Pills --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-1 scrollbar-none">
      @foreach ($categories as $cat)
        <button
          type="button"
          @click="activeCategory = '{{ $cat['id'] }}'"
          :class="activeCategory === '{{ $cat['id'] }}' ? 'bg-stone-900 text-white shadow-xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
          class="px-3.5 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition-colors"
        >
          <x-t :id="$cat['labelId']" :en="$cat['labelEn']" />
        </button>
      @endforeach
    </div>
  </div>

  {{-- Grid View --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8" x-show="viewMode === 'grid'">
    <template x-for="prod in filtered" :key="prod.id">
      <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
        <div>
          <div class="relative h-52 w-full bg-stone-100 overflow-hidden">
            <img
              :src="prod.imageUrl"
              :alt="prod.name"
              referrerpolicy="no-referrer"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
            <div class="absolute top-3 left-3 bg-stone-900/80 backdrop-blur-xs text-white text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-md" x-text="prod.category.replace('_', ' ')"></div>

            <button
              type="button"
              @click.stop="$store.modals.openQr({ title: prod.name, subtitle: prod.priceFormatted + ' · ' + prod.sellerGroup, code: prod.id, type: 'umkm' })"
              class="absolute top-3 right-3 p-1.5 rounded-lg bg-white/90 text-stone-800 hover:bg-white hover:text-emerald-700 shadow-sm transition-colors"
              title="QR Code"
            >
              <x-icon name="QrCode" class="w-4 h-4" />
            </button>

            <div class="absolute bottom-3 right-3 bg-emerald-800/90 backdrop-blur-xs text-white text-xs font-mono font-bold px-2.5 py-1 rounded-md" x-text="prod.priceFormatted"></div>
          </div>

          <div class="p-5 space-y-2">
            <div class="text-[11px] text-stone-500 font-medium truncate" x-text="prod.sellerGroup"></div>

            <h3 class="font-serif text-lg font-bold text-stone-900 group-hover:text-emerald-800 transition-colors line-clamp-1" x-text="$store.ui.lang === 'id' ? prod.name : prod.nameEn"></h3>

            <p class="text-xs text-stone-600 line-clamp-2 leading-relaxed" x-text="$store.ui.lang === 'id' ? prod.description : prod.descriptionEn"></p>

            <div class="pt-2 flex items-center justify-between text-xs text-stone-500">
              <span>Satuan: <strong class="text-stone-700" x-text="prod.unit"></strong></span>
              <span class="flex items-center gap-1 text-emerald-700 font-medium">
                <x-icon name="CheckCircle2" class="w-3.5 h-3.5" />
                <span x-text="$store.ui.lang === 'id' ? 'Tersedia' : 'In Stock'"></span>
              </span>
            </div>
          </div>
        </div>

        {{-- Action Buttons: [Lihat Produk] - [Hubungi Penjual] --}}
        <div class="p-5 pt-0 grid grid-cols-2 gap-2">
          <button
            type="button"
            @click="$store.modals.openProduct(prod)"
            class="py-2.5 px-3 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-800 text-xs font-semibold transition-colors text-center"
            x-text="$store.ui.lang === 'id' ? 'Lihat Produk' : 'View Product'"
          ></button>

          <button
            type="button"
            @click="handleDirectWhatsApp(prod)"
            class="py-2.5 px-3 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold transition-colors flex items-center justify-center gap-1.5"
          >
            <x-icon name="Phone" class="w-3.5 h-3.5" />
            <span x-text="$store.ui.lang === 'id' ? 'Hubungi Penjual' : 'Contact Seller'"></span>
          </button>
        </div>
      </div>
    </template>
  </div>

  {{-- Table View matching user prompt format --}}
  <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs" x-show="viewMode === 'table'" x-cloak>
    <div class="overflow-x-auto">
      <table class="w-full text-left text-xs text-stone-700">
        <thead class="bg-stone-50 border-b border-stone-200 text-[11px] font-semibold uppercase tracking-wider text-stone-600">
          <tr>
            <th class="px-6 py-3.5"><x-t id="Produk" en="Product" /></th>
            <th class="px-6 py-3.5"><x-t id="Kategori" en="Category" /></th>
            <th class="px-6 py-3.5"><x-t id="Harga" en="Price" /></th>
            <th class="px-6 py-3.5"><x-t id="Pengelola / Kelompok" en="Producer Group" /></th>
            <th class="px-6 py-3.5 text-right"><x-t id="Aksi" en="Action" /></th>
          </tr>
        </thead>
        <tbody class="divide-y divide-stone-200">
          <template x-for="prod in filtered" :key="prod.id">
            <tr class="hover:bg-stone-50/80 transition-colors">
              <td class="px-6 py-4 font-semibold text-stone-900 flex items-center gap-3">
                <img
                  :src="prod.imageUrl"
                  :alt="prod.name"
                  referrerpolicy="no-referrer"
                  class="w-10 h-10 rounded-lg object-cover bg-stone-100"
                />
                <div>
                  <div x-text="$store.ui.lang === 'id' ? prod.name : prod.nameEn"></div>
                  <div class="text-[10px] text-stone-400 font-normal" x-text="prod.unit"></div>
                </div>
              </td>
              <td class="px-6 py-4 capitalize" x-text="prod.category.replace('_', ' ')"></td>
              <td class="px-6 py-4 font-mono font-bold text-emerald-700" x-text="prod.priceFormatted"></td>
              <td class="px-6 py-4 text-stone-600" x-text="prod.sellerGroup"></td>
              <td class="px-6 py-4 text-right space-x-2">
                <button
                  type="button"
                  @click="$store.modals.openProduct(prod)"
                  class="px-3 py-1.5 bg-stone-100 hover:bg-stone-200 text-stone-800 rounded-lg font-medium"
                  x-text="$store.ui.lang === 'id' ? 'Lihat Produk' : 'View'"
                ></button>
                <button
                  type="button"
                  @click="handleDirectWhatsApp(prod)"
                  class="px-3 py-1.5 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-semibold inline-flex items-center gap-1"
                >
                  <x-icon name="Phone" class="w-3 h-3" />
                  <span x-text="$store.ui.lang === 'id' ? 'Hubungi Penjual' : 'Order'"></span>
                </button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>

  {{-- Community Order Assistance Box --}}
  <div class="p-6 rounded-2xl bg-emerald-900 text-white flex flex-col sm:flex-row items-center justify-between gap-4">
    <div class="space-y-1 text-center sm:text-left">
      <h4 class="font-serif text-lg font-bold">
        <x-t id="Butuh Pengiriman Paket Oleh-Oleh Jumlah Besar?" en="Bulk Souvenir Orders & Shipping?" />
      </h4>
      <p class="text-xs text-emerald-200">
        <x-t
          id="Posko KKN Mahasiswa UNIDAR dan Koperasi Desa Morela siap membantu pengemasan bingkisan resmi."
          en="Village cooperative and UNIDAR community team assist with customized gift boxes and national deliveries."
        />
      </p>
    </div>
    <a
      href="https://wa.me/6281234567810?text=Halo%20Admin%20UMKM%20Morela%20Tourism"
      target="_blank"
      rel="noreferrer"
      class="px-5 py-2.5 bg-amber-400 hover:bg-amber-300 text-stone-950 rounded-xl text-xs font-bold transition-colors whitespace-nowrap"
    >
      <x-t id="Konsultasi Pesanan Khusus" en="Consult Wholesale Order" />
    </a>
  </div>

</div>

<script>
  function umkmView(data) {
    return {
      umkmProducts: data.umkmProducts,
      activeCategory: 'all',
      search: '',
      viewMode: 'grid',
      get filtered() {
        const published = this.umkmProducts.filter((p) => p.published);
        return published.filter((p) => {
          const matchCat = this.activeCategory === 'all' || p.category === this.activeCategory;
          const q = this.search.toLowerCase();
          const matchText =
            p.name.toLowerCase().includes(q) ||
            p.description.toLowerCase().includes(q) ||
            p.sellerGroup.toLowerCase().includes(q) ||
            p.sellerName.toLowerCase().includes(q);
          return matchCat && matchText;
        });
      },
      handleDirectWhatsApp(prod) {
        const phone = prod.sellerPhone.replace(/[^0-9]/g, '');
        const message = encodeURIComponent(
          $store.ui.lang === 'id'
            ? `Halo ${prod.sellerName} (${prod.sellerGroup}), saya tertarik memesan produk "${prod.name}" (${prod.priceFormatted}/${prod.unit}) yang saya temukan di portal Negeri Morella.`
            : `Hello ${prod.sellerName}, I would like to purchase "${prod.nameEn || prod.name}" from the Negeri Morella marketplace.`
        );
        window.open(`https://wa.me/${phone}?text=${message}`, '_blank');
      },
    };
  }
</script>
@endsection
