@extends('layouts.app')

@php
  $cultureData = [
    'cultureItems' => $culture,
  ];
  $tabs = [
    ['id' => 'all', 'labelId' => 'Semua Arsip', 'labelEn' => 'All Archives'],
    ['id' => 'sejarah', 'labelId' => 'Sejarah & Asal Usul', 'labelEn' => 'History'],
    ['id' => 'tradisi', 'labelId' => 'Tradisi Pukul Sapu', 'labelEn' => 'Tradition'],
    ['id' => 'kesenian', 'labelId' => 'Kesenian & Tari', 'labelEn' => 'Arts & Music'],
    ['id' => 'kuliner', 'labelId' => 'Kuliner Tradisional', 'labelEn' => 'Culinary'],
    ['id' => 'cerita_rakyat', 'labelId' => 'Cerita Rakyat & Sasi', 'labelEn' => 'Folklore'],
  ];
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 space-y-12" x-data="cultureView({{ Js::from($cultureData) }})">

  {{-- Editorial Header --}}
  <div class="max-w-3xl space-y-3">
    <div class="text-xs font-semibold uppercase tracking-widest text-amber-700">
      <x-t id="Arsip Digital Kebudayaan & Nilai Leluhur" en="Digital Cultural Heritage Archive" />
    </div>
    <h1 class="font-serif text-3xl sm:text-4xl lg:text-5xl font-bold text-stone-900 tracking-tight">
      <x-t id="Budaya, Tradisi & Sejarah Morela" en="Culture, Tradition & History of Morela" />
    </h1>
    <p class="text-sm text-stone-600 leading-relaxed max-w-prose">
      <x-t
        id="Dokumentasi komprehensif sejarah pembentukan negeri, sakralnya atraksi Pukul Sapu (Ukuwala Mahiate), tarian heroik Cakalele, kearifan konservasi Sasi Laut, dan khazanah kuliner rempah Maluku."
        en="Comprehensive institutional repository preserving oral histories, sacred martial fraternities, heroic dances, customary marine conservation (Sasi), and indigenous gastronomic heritage."
      />
    </p>
  </div>

  {{-- Institutional Featured Spotlight: Tradisi Pukul Sapu --}}
  <div class="rounded-3xl bg-stone-900 text-stone-100 overflow-hidden border border-stone-800 shadow-xl grid grid-cols-1 lg:grid-cols-12">
    <div class="lg:col-span-7 p-6 sm:p-10 lg:p-12 flex flex-col justify-between space-y-6">
      <div class="space-y-4">
        <div class="inline-flex items-center gap-2 text-xs font-semibold text-amber-400 uppercase tracking-widest">
          <x-icon name="Award" class="w-4 h-4 text-amber-400" />
          <span>WARISAN BUDAYA TAKBENDA (WBTB) NASIONAL</span>
        </div>

        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-white tracking-tight leading-snug">
          Tradisi Sakral Pukul Sapu (Ukuwala Mahiate)
        </h2>

        <p class="text-xs sm:text-sm text-stone-300 leading-relaxed font-light">
          Ritual tahunan yang diselenggarakan setiap tanggal 7 Syawal setelah Idul Fitri di Negeri Morela. Saling cambuk dengan lidi pohon enau melambangkan keberanian, ketangguhan fisik, dan ikatan persaudaraan sejati yang disembuhkan dengan ramuan minyak kelapa dan rempah pusaka tanpa meninggalkan rasa dendam.
        </p>

        <blockquote class="border-l-2 border-amber-500 pl-4 py-1 text-xs text-amber-200/90 italic font-serif">
          "Biar luka di badan, hati tetap gandong — persaudaraan masyarakat Morela terpatri abadi melintasi generasi."
        </blockquote>
      </div>

      <div class="flex items-center gap-3 pt-2">
        <button
          type="button"
          @click="const found = cultureItems.find((i) => i.id === 'cult-2'); if (found) $store.modals.openCulture(found)"
          class="px-5 py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-xl text-xs font-semibold transition-colors flex items-center gap-2 shadow-sm"
        >
          <span x-text="$store.ui.lang === 'id' ? 'Baca Dokumentasi Lengkap' : 'Read Full Monograph'"></span>
          <x-icon name="ArrowRight" class="w-3.5 h-3.5" />
        </button>
      </div>
    </div>

    <div class="lg:col-span-5 relative min-h-[260px] bg-stone-950">
      <img
        src="https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&w=1000&q=80"
        alt="Pukul Sapu Culture Morela"
        class="w-full h-full object-cover"
        onerror="this.style.display = 'none'"
      />
      <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-transparent to-transparent lg:hidden"></div>
    </div>
  </div>

  {{-- Category Tabs --}}
  <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none border-b border-stone-200">
    @foreach ($tabs as $tab)
      <button
        type="button"
        @click="activeTab = '{{ $tab['id'] }}'"
        :class="activeTab === '{{ $tab['id'] }}' ? 'border-amber-600 text-amber-700' : 'border-transparent text-stone-500 hover:text-stone-900'"
        class="px-4 py-2 text-xs font-semibold transition-colors whitespace-nowrap border-b-2 -mb-[2px]"
      >
        <x-t :id="$tab['labelId']" :en="$tab['labelEn']" />
      </button>
    @endforeach
  </div>

  {{-- Cultural Items Grid --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <template x-for="item in filteredItems" :key="item.id">
      <div
        @click="$store.modals.openCulture(item)"
        class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-md hover:border-amber-300 transition-all cursor-pointer flex flex-col justify-between group"
      >
        <div>
          <div class="relative h-52 w-full bg-stone-100 overflow-hidden">
            <img
              :src="item.imageUrl"
              :alt="item.title"
              referrerpolicy="no-referrer"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              onerror="this.style.display = 'none'"
            />
            <div class="absolute top-3 left-3 bg-stone-900/80 backdrop-blur-xs text-white text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-md" x-text="item.category.replace('_', ' ')"></div>
          </div>

          <div class="p-6 space-y-3">
            <div class="text-[11px] text-stone-400 font-mono" x-text="item.periodOrTime"></div>

            <h3 class="font-serif text-lg font-bold text-stone-900 group-hover:text-amber-800 transition-colors leading-snug" x-text="$store.ui.lang === 'id' ? item.title : item.titleEn"></h3>

            <p class="text-xs text-stone-600 line-clamp-3 leading-relaxed" x-text="$store.ui.lang === 'id' ? item.summary : item.summaryEn"></p>

            <div class="pt-2 flex flex-wrap gap-1.5 text-[10px] text-stone-500">
              <template x-for="(t, idx) in item.tags" :key="idx">
                <span class="bg-stone-100 px-2 py-0.5 rounded text-stone-600" x-text="'#' + t"></span>
              </template>
            </div>
          </div>
        </div>

        <div class="p-6 pt-0">
          <div class="flex items-center gap-1.5 text-xs font-semibold text-amber-700 group-hover:text-amber-800">
            <span x-text="$store.ui.lang === 'id' ? 'Buka Arsip Lengkap' : 'Read Monograph'"></span>
            <x-icon name="ArrowRight" class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" />
          </div>
        </div>
      </div>
    </template>
  </div>

  {{-- Callout on Digital Preservation --}}
  <div class="p-6 sm:p-8 bg-stone-100 rounded-2xl border border-stone-200 flex flex-col md:flex-row items-center justify-between gap-6">
    <div class="space-y-1 text-center md:text-left">
      <div class="text-xs font-semibold uppercase tracking-wider text-stone-500">
        <x-t id="Arsip Komunitas & Tetua Adat" en="Community & Customary Oral History" />
      </div>
      <h4 class="font-serif text-lg font-bold text-stone-900">
        <x-t id="Punya Informasi Sejarah atau Dokumentasi Adat Morela?" en="Possess Archival Photographs or Oral Records?" />
      </h4>
      <p class="text-xs text-stone-600 max-w-xl">
        <x-t
          id="Masyarakat, mahasiswa, dan peneliti dapat menyumbangkan data naskah atau foto arsip untuk memperkaya khazanah digital kebudayaan desa."
          en="Community members and scholars are invited to submit historical manuscripts and photos to enrich the digital repository."
        />
      </p>
    </div>

    <button
      type="button"
      @click="alert($store.ui.lang === 'id' ? 'Silakan hubungi Pengelola Desa atau Posko KKN UNIDAR untuk penyerahan arsip foto &amp; dokumen adat.' : 'Please contact Village Administration or UNIDAR post to submit archival materials.')"
      class="px-5 py-2.5 bg-stone-900 hover:bg-stone-800 text-white rounded-xl text-xs font-semibold transition-colors whitespace-nowrap"
      x-text="$store.ui.lang === 'id' ? 'Kontribusi Arsip Adat' : 'Contribute Archive'"
    ></button>
  </div>

</div>

<script>
  function cultureView(data) {
    return {
      cultureItems: data.cultureItems,
      activeTab: 'all',
      get filteredItems() {
        return this.activeTab === 'all'
          ? this.cultureItems
          : this.cultureItems.filter((i) => i.category === this.activeTab);
      },
    };
  }
</script>
@endsection
