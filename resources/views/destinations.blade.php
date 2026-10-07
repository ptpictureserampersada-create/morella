@extends('layouts.app')

@php
  $destinationsData = [
    'destinations' => $destinations,
  ];
  $categories = [
    ['id' => 'all', 'labelId' => 'Semua Destinasi', 'labelEn' => 'All Spots', 'icon' => 'Layers'],
    ['id' => 'pantai', 'labelId' => 'Pantai', 'labelEn' => 'Beaches', 'icon' => 'Waves'],
    ['id' => 'alam', 'labelId' => 'Wisata Alam', 'labelEn' => 'Nature', 'icon' => 'TreePine'],
    ['id' => 'pemandangan', 'labelId' => 'Spot Pemandangan', 'labelEn' => 'Viewpoints', 'icon' => 'Mountain'],
    ['id' => 'religi_sejarah', 'labelId' => 'Wisata Religi & Sejarah', 'labelEn' => 'Historic & Sacred', 'icon' => 'Landmark'],
    ['id' => 'budaya', 'labelId' => 'Budaya & Tradisi', 'labelEn' => 'Culture', 'icon' => 'Sparkles'],
  ];
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 space-y-10" x-data="destinationsView({{ Js::from($destinationsData) }})">

  {{-- Editorial Header --}}
  <div class="space-y-3 max-w-3xl">
    <div class="text-xs font-semibold uppercase tracking-widest text-emerald-700">
      <x-t id="Eksplorasi Pariwisata Leihitu" en="Leihitu Tourism Exploration" />
    </div>
    <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 tracking-tight">
      <x-t id="Destinasi Wisata Negeri Morella" en="Tourist Destinations in Morella" />
    </h1>
    <p class="text-sm text-stone-600 leading-relaxed">
      <x-t
        id="Temukan pesona pantai pasir putih berair toska, tebing karang alami, perbukitan rempah pala warisan dunia, hingga arena sakral upacara adat persaudaraan."
        en="Explore turquoise lagoons, ancient nutmeg agro-forests, dramatic sea cliffs, and sacred ancestral cultural arenas along the northern coast of Ambon island."
      />
    </p>
  </div>

  {{-- Filter and Search Bar --}}
  <div class="p-4 sm:p-5 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-4">
    <div class="flex flex-col md:flex-row gap-3 items-center justify-between">
      {{-- Search box --}}
      <div class="relative w-full md:w-96">
        <x-icon name="Search" class="w-4 h-4 text-stone-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
        <input
          type="text"
          x-model="query"
          :placeholder="$store.ui.lang === 'id' ? 'Cari nama pantai, lokasi, fasilitas...' : 'Search destination, facility...'"
          class="w-full pl-10 pr-4 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-emerald-600"
        />
      </div>

      <div
        class="text-xs text-stone-500 font-mono self-end md:self-center"
        x-text="$store.ui.lang === 'id' ? 'Menampilkan ' + filtered.length + ' dari ' + destinations.length + ' destinasi' : 'Showing ' + filtered.length + ' of ' + destinations.length + ' spots'"
      ></div>
    </div>

    {{-- Category Pills (Functional buttons) --}}
    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
      @foreach ($categories as $cat)
        <button
          type="button"
          @click="selectedCat = '{{ $cat['id'] }}'"
          :class="selectedCat === '{{ $cat['id'] }}' ? 'bg-stone-900 text-white shadow-xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200'"
          class="flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-medium whitespace-nowrap transition-colors"
        >
          <x-icon :name="$cat['icon']" class="w-3.5 h-3.5" />
          <span><x-t :id="$cat['labelId']" :en="$cat['labelEn']" /></span>
        </button>
      @endforeach
    </div>
  </div>

  {{-- Grid of Destinations --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" x-show="filtered.length > 0">
    <template x-for="dest in filtered" :key="dest.id">
      <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
        <div>
          {{-- Photo showcase with category & QR --}}
          <div class="relative h-56 w-full bg-stone-100 overflow-hidden">
            <img
              :src="dest.imageUrl"
              :alt="dest.name"
              referrerpolicy="no-referrer"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              onerror="this.style.display = 'none'"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>

            <div class="absolute top-3 left-3 bg-stone-900/80 backdrop-blur-xs text-white text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-md" x-text="dest.category.replace('_', ' ')"></div>

            <button
              type="button"
              @click.stop="$store.modals.openQr({ title: dest.name, subtitle: dest.location, code: dest.slug, type: 'destinasi' })"
              class="absolute top-3 right-3 p-1.5 rounded-lg bg-white/90 text-stone-800 hover:bg-white hover:text-emerald-700 shadow-sm transition-colors"
              title="QR Code"
            >
              <x-icon name="QrCode" class="w-4 h-4" />
            </button>

            <div class="absolute bottom-3 right-3 bg-emerald-800/95 backdrop-blur-xs text-white text-xs font-mono font-semibold px-2.5 py-1 rounded-md" x-text="dest.ticketPrice"></div>
          </div>

          {{-- Content --}}
          <div class="p-5 sm:p-6 space-y-3">
            <div class="flex items-center gap-1.5 text-xs text-stone-500">
              <x-icon name="MapPin" class="w-3.5 h-3.5 text-emerald-600 shrink-0" />
              <span class="truncate" x-text="dest.location"></span>
            </div>

            <h3 class="font-serif text-xl font-bold text-stone-900 group-hover:text-emerald-800 transition-colors" x-text="$store.ui.lang === 'id' ? dest.name : dest.nameEn"></h3>

            <p class="text-xs text-stone-600 line-clamp-2 leading-relaxed" x-text="$store.ui.lang === 'id' ? dest.tagline : dest.taglineEn"></p>

            <div class="pt-2 border-t border-stone-100 flex items-center justify-between text-xs text-stone-500 font-mono">
              <span class="flex items-center gap-1">
                <x-icon name="Clock" class="w-3 h-3 text-stone-400" />
                <span class="truncate max-w-[140px]" x-text="dest.visitingHours"></span>
              </span>
              <span class="text-emerald-700 font-medium" x-text="dest.contactName.split(' ')[0]"></span>
            </div>
          </div>
        </div>

        {{-- Action Buttons --}}
        <div class="p-5 pt-0 grid grid-cols-2 gap-2">
          <button
            type="button"
            @click="$store.modals.openDestination(dest)"
            class="py-2.5 px-3 rounded-xl bg-emerald-700 hover:bg-emerald-800 text-white text-xs font-semibold transition-colors text-center"
            x-text="$store.ui.lang === 'id' ? 'Lihat Detail' : 'View Details'"
          ></button>

          <a
            href="{{ route('map') }}"
            class="py-2.5 px-3 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-800 text-xs font-medium transition-colors text-center"
            x-text="$store.ui.lang === 'id' ? 'Lihat di Peta' : 'On Map'"
          ></a>
        </div>
      </div>
    </template>
  </div>

  {{-- Empty State --}}
  <div class="p-12 text-center bg-white rounded-2xl border border-stone-200 space-y-3" x-show="filtered.length === 0">
    <p class="text-stone-500 text-sm" x-text="$store.ui.lang === 'id' ? 'Tidak ada destinasi yang cocok dengan pencarian Anda.' : 'No destinations match your search query.'"></p>
    <button
      type="button"
      @click="query = ''; selectedCat = 'all'"
      class="px-4 py-2 bg-stone-900 text-white text-xs rounded-lg"
      x-text="$store.ui.lang === 'id' ? 'Reset Pencarian' : 'Reset Filters'"
    ></button>
  </div>
</div>

<script>
  function destinationsView(data) {
    return {
      destinations: data.destinations,
      selectedCat: 'all',
      query: '',
      get filtered() {
        const published = this.destinations.filter((d) => d.published);
        return published.filter((d) => {
          const matchCat = this.selectedCat === 'all' || d.category === this.selectedCat;
          const q = this.query.toLowerCase();
          const matchText =
            d.name.toLowerCase().includes(q) ||
            d.description.toLowerCase().includes(q) ||
            d.location.toLowerCase().includes(q) ||
            d.tagline.toLowerCase().includes(q);
          return matchCat && matchText;
        });
      },
    };
  }
</script>
@endsection
