@extends('layouts.app')

@php
  $homeData = [
    'destinations' => $destinations,
    'umkmProducts' => $umkm,
    'events' => $events,
    'news' => $news,
    'heroSliders' => $heroSliders,
  ];
  $categories = [
    ['id' => 'all', 'labelId' => 'Semua Destinasi', 'labelEn' => 'All Spots', 'icon' => 'Layers'],
    ['id' => 'pantai', 'labelId' => 'Pantai', 'labelEn' => 'Beaches', 'icon' => 'Waves'],
    ['id' => 'alam', 'labelId' => 'Wisata Alam', 'labelEn' => 'Nature', 'icon' => 'TreePine'],
    ['id' => 'pemandangan', 'labelId' => 'Spot Pemandangan', 'labelEn' => 'Viewpoints', 'icon' => 'Mountain'],
    ['id' => 'religi_sejarah', 'labelId' => 'Wisata Religi & Sejarah', 'labelEn' => 'Heritage', 'icon' => 'Landmark'],
    ['id' => 'budaya', 'labelId' => 'Budaya & Tradisi', 'labelEn' => 'Culture', 'icon' => 'Sparkles'],
  ];
@endphp

@section('content')
<div class="space-y-16 sm:space-y-24 pb-16" x-data="homeView({{ Js::from($homeData) }})">

  {{-- 1. HERO UTAMA --}}
  <section class="relative min-h-[580px] sm:min-h-[640px] flex items-center justify-center bg-stone-900 text-white overflow-hidden">
    {{-- Background Image with Scrim --}}
    <div class="absolute inset-0 z-0">
      @if (! empty($heroSliders))
        <template x-for="(url, idx) in heroSliders" :key="idx">
          <img
            :src="url"
            :alt="'Negeri Morella Slider ' + (idx + 1)"
            class="absolute inset-0 w-full h-full object-cover scale-105 transition-opacity duration-1000"
            :class="idx === currentSliderIndex ? 'opacity-45' : 'opacity-0'"
          />
        </template>
      @else
        <div class="absolute inset-0 bg-stone-800"></div>
      @endif
      <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-900/60 to-black/40"></div>
    </div>

    {{-- Content Container --}}
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24 flex flex-col lg:flex-row items-center gap-12">

      {{-- Left / Main Text --}}
      <div class="flex-1 text-left">
        @if (($heroText['badge'] ?? '') !== '' || ($heroText['badgeEn'] ?? '') !== '')
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-950/80 border border-emerald-500/40 text-emerald-300 text-xs font-medium mb-6 backdrop-blur-md">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>
              <x-t :id="$heroText['badge'] ?? ''" :en="$heroText['badgeEn'] ?? ''" />
            </span>
          </div>
        @endif

        @if (($heroText['title'] ?? '') !== '' || ($heroText['titleEn'] ?? '') !== '')
          <h1 class="font-serif text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white leading-tight sm:leading-tight">
            <x-t :id="$heroText['title'] ?? ''" :en="$heroText['titleEn'] ?? ''" />
          </h1>
        @endif

        @if (($heroText['tagline'] ?? '') !== '' || ($heroText['taglineEn'] ?? '') !== '')
          <p class="mt-4 sm:mt-6 text-base sm:text-xl text-stone-200 font-light max-w-2xl leading-relaxed">
            <x-t :id="$heroText['tagline'] ?? ''" :en="$heroText['taglineEn'] ?? ''" />
          </p>
        @endif

        @if (($heroText['description'] ?? '') !== '' || ($heroText['descriptionEn'] ?? '') !== '')
          <p class="mt-4 text-xs sm:text-sm text-stone-300 max-w-xl border-l-2 border-emerald-500 pl-4">
            <x-t :id="$heroText['description'] ?? ''" :en="$heroText['descriptionEn'] ?? ''" />
          </p>
        @endif

        {{-- Three Primary Hero Buttons --}}
        <div class="mt-8 sm:mt-10 flex flex-wrap items-center gap-3 sm:gap-4">
          <a
            href="{{ route('destinations') }}"
            class="px-6 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-semibold text-sm transition-all shadow-lg shadow-emerald-950/50 flex items-center gap-2 group"
          >
            <span><x-t id="Jelajahi Destinasi" en="Explore Destinations" /></span>
            <x-icon name="ArrowRight" class="w-4 h-4 group-hover:translate-x-1 transition-transform" />
          </a>

          <a
            href="{{ route('culture') }}"
            class="px-6 py-3 rounded-xl bg-stone-800/90 hover:bg-stone-700 text-stone-100 font-semibold text-sm transition-all border border-stone-700 backdrop-blur-sm"
          >
            <span><x-t id="Tentang Morella" en="About Morella" /></span>
          </a>

          <a
            href="{{ route('events') }}"
            class="px-6 py-3 rounded-xl bg-amber-600/90 hover:bg-amber-500 text-white font-semibold text-sm transition-all shadow-md backdrop-blur-sm"
          >
            <span><x-t id="Agenda & Kegiatan" en="Agenda & Events" /></span>
          </a>
        </div>

        {{-- Quick Search Strip --}}
        <div class="mt-10 max-w-xl">
          <div class="relative group">
            <input
              type="text"
              x-model="localSearch"
              :placeholder="$store.ui.lang === 'id' ? 'Cari pantai, air toska, benteng, atau tradisi Negeri Morella...' : 'Search beaches, turquoise coves, heritage, or events...'"
              class="w-full pl-11 pr-24 py-3.5 bg-stone-900/50 hover:bg-stone-900/80 border border-stone-700/80 rounded-xl text-white placeholder-stone-400 text-xs sm:text-sm focus:outline-none focus:border-emerald-500 focus:bg-stone-900/90 backdrop-blur-md shadow-xl transition-all"
            />
            <x-icon name="Search" class="w-4 h-4 text-emerald-500 absolute left-4 top-1/2 -translate-y-1/2" />
            <button
              type="button"
              x-show="localSearch"
              x-cloak
              @click="localSearch = ''"
              class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-stone-400 hover:text-white px-2 py-1 bg-stone-800 rounded"
            >
              Clear
            </button>
          </div>
        </div>
      </div>

      {{-- Right / Infographic Panel --}}
      <div class="hidden lg:block w-full max-w-sm shrink-0">
        <div class="bg-white/10 backdrop-blur-xl border border-white/20 rounded-3xl p-6 shadow-2xl relative overflow-hidden">
          <div class="absolute -top-10 -right-10 w-32 h-32 bg-emerald-500/20 blur-3xl rounded-full"></div>
          <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-amber-500/20 blur-3xl rounded-full"></div>

          <div class="relative z-10">
            <h3 class="text-white font-serif text-lg font-bold mb-5 flex items-center gap-2">
              <x-icon name="Compass" class="w-5 h-5 text-emerald-400" />
              <x-t id="Sekilas Negeri Morella" en="Negeri Morella at a Glance" />
            </h3>

            <div class="space-y-4">
              <div class="flex items-center gap-4 p-3 rounded-2xl bg-stone-900/40 border border-white/10 hover:bg-stone-900/60 transition-colors">
                <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center shrink-0">
                  <x-icon name="Waves" class="w-5 h-5 text-emerald-400" />
                </div>
                <div>
                  <div class="text-[10px] text-stone-400 uppercase font-semibold tracking-wider">Topografi</div>
                  <div class="text-sm text-stone-100 font-medium">Pesisir &amp; Teluk Toska</div>
                </div>
              </div>

              <div class="flex items-center gap-4 p-3 rounded-2xl bg-stone-900/40 border border-white/10 hover:bg-stone-900/60 transition-colors">
                <div class="w-10 h-10 rounded-full bg-amber-500/20 flex items-center justify-center shrink-0">
                  <x-icon name="Landmark" class="w-5 h-5 text-amber-400" />
                </div>
                <div>
                  <div class="text-[10px] text-stone-400 uppercase font-semibold tracking-wider">Ikon Budaya</div>
                  <div class="text-sm text-stone-100 font-medium">Tradisi Pukul Sapu</div>
                </div>
              </div>

              <div class="flex items-center gap-4 p-3 rounded-2xl bg-stone-900/40 border border-white/10 hover:bg-stone-900/60 transition-colors">
                <div class="w-10 h-10 rounded-full bg-blue-500/20 flex items-center justify-center shrink-0">
                  <x-icon name="MapPin" class="w-5 h-5 text-blue-400" />
                </div>
                <div>
                  <div class="text-[10px] text-stone-400 uppercase font-semibold tracking-wider">Lokasi</div>
                  <div class="text-sm text-stone-100 font-medium">Leihitu, Maluku Tengah</div>
                </div>
              </div>

              <div class="flex items-center gap-4 p-3 rounded-2xl bg-stone-900/40 border border-white/10 hover:bg-stone-900/60 transition-colors">
                <div class="w-10 h-10 rounded-full bg-purple-500/20 flex items-center justify-center shrink-0">
                  <x-icon name="TreePine" class="w-5 h-5 text-purple-400" />
                </div>
                <div>
                  <div class="text-[10px] text-stone-400 uppercase font-semibold tracking-wider">Komoditas</div>
                  <div class="text-sm text-stone-100 font-medium">Cengkeh &amp; Pala</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- 2. STATISTIK POTENSI DESA & KUNJUNGAN --}}
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-8 sm:-mt-12 relative z-20">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
      <div class="p-4 sm:p-5 bg-white rounded-2xl border border-stone-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-700 flex items-center justify-center shrink-0">
          <x-icon name="Compass" class="w-6 h-6" />
        </div>
        <div>
          <div class="font-mono text-xl sm:text-2xl font-bold text-stone-900 tabular-nums">
            {{ count($destinations) }}
          </div>
          <div class="text-[11px] text-stone-500 uppercase font-medium">
            <x-t id="Destinasi Wisata" en="Destinations" />
          </div>
        </div>
      </div>

      <div class="p-4 sm:p-5 bg-white rounded-2xl border border-stone-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center shrink-0">
          <x-icon name="ShoppingBag" class="w-6 h-6" />
        </div>
        <div>
          <div class="font-mono text-xl sm:text-2xl font-bold text-stone-900 tabular-nums">
            {{ count($umkm) }}
          </div>
          <div class="text-[11px] text-stone-500 uppercase font-medium">
            <x-t id="Produk UMKM" en="Local Artisans" />
          </div>
        </div>
      </div>

      <div class="p-4 sm:p-5 bg-white rounded-2xl border border-stone-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center shrink-0">
          <x-icon name="BookOpen" class="w-6 h-6" />
        </div>
        <div>
          <div class="font-mono text-xl sm:text-2xl font-bold text-stone-900 tabular-nums">
            {{ count($news) }}
          </div>
          <div class="text-[11px] text-stone-500 uppercase font-medium">
            <x-t id="Warta Desa" en="News Articles" />
          </div>
        </div>
      </div>

      <div class="p-4 sm:p-5 bg-white rounded-2xl border border-stone-200 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center shrink-0">
          <x-icon name="Eye" class="w-6 h-6" />
        </div>
        <div>
          <div class="font-mono text-xl sm:text-2xl font-bold text-stone-900 tabular-nums">
            {{ number_format($webVisits ?? 0, 0, ',', '.') }}
          </div>
          <div class="text-[11px] text-stone-500 uppercase font-medium">
            <x-t id="Kunjungan Portal" en="Web Visitors" />
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- 3. DESTINASI UNGGULAN & FILTER --}}
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
      <div class="space-y-1">
        <div class="text-xs font-semibold uppercase tracking-widest text-emerald-700">
          <x-t id="Pesona Bahari & Alam Leihitu" en="Coastal & Nature Charms" />
        </div>
        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900">
          <x-t id="Destinasi Unggulan Negeri Morella" en="Featured Destinations in Negeri Morella" />
        </h2>
      </div>

      <a
        href="{{ route('destinations') }}"
        class="text-xs sm:text-sm font-semibold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group self-start md:self-auto"
      >
        <span><x-t id="Lihat Semua Destinasi" en="View All Destinations" /></span>
        <x-icon name="ChevronRight" class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" />
      </a>
    </div>

    {{-- Category Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
      @foreach ($categories as $cat)
        <button
          type="button"
          @click="activeCategory = '{{ $cat['id'] }}'"
          :class="activeCategory === '{{ $cat['id'] }}' ? 'bg-stone-900 text-white shadow-xs' : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-100'"
          class="flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-medium whitespace-nowrap transition-all"
        >
          <x-icon :name="$cat['icon']" class="w-3.5 h-3.5" />
          <span><x-t :id="$cat['labelId']" :en="$cat['labelEn']" /></span>
        </button>
      @endforeach
    </div>

    {{-- Destination Cards Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
      <template x-for="dest in filteredDestinations" :key="dest.id">
        <div class="group bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
          <div>
            {{-- Visual Image with Category & QR Trigger --}}
            <div class="relative h-52 w-full overflow-hidden bg-stone-100">
              <img
                :src="dest.imageUrl"
                :alt="dest.name"
                referrerpolicy="no-referrer"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                onerror="this.style.display = 'none'"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-60"></div>

              {{-- Category unboxed tag on top-left --}}
              <div class="absolute top-3 left-3 bg-stone-900/80 backdrop-blur-xs text-white text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-md" x-text="dest.category.replace('_', ' ')"></div>

              {{-- QR button top-right --}}
              <button
                type="button"
                @click.stop="$store.modals.openQr({ title: dest.name, subtitle: dest.location, code: dest.slug, type: 'destinasi' })"
                class="absolute top-3 right-3 p-1.5 rounded-lg bg-white/90 text-stone-800 hover:bg-white hover:text-emerald-700 shadow-sm transition-colors"
                title="QR Code"
              >
                <x-icon name="QrCode" class="w-4 h-4" />
              </button>

              {{-- Price Tag on Bottom Right --}}
              <div class="absolute bottom-3 right-3 bg-emerald-800/90 backdrop-blur-xs text-white text-xs font-mono font-semibold px-2.5 py-1 rounded-md" x-text="dest.ticketPrice"></div>
            </div>

            {{-- Card Content --}}
            <div class="p-5 space-y-2">
              <div class="flex items-center gap-1.5 text-xs text-stone-500">
                <x-icon name="MapPin" class="w-3.5 h-3.5 text-emerald-600 shrink-0" />
                <span class="truncate" x-text="dest.location"></span>
              </div>

              <h3 class="font-serif text-lg font-bold text-stone-900 group-hover:text-emerald-800 transition-colors" x-text="$store.ui.lang === 'id' ? dest.name : dest.nameEn"></h3>

              <p class="text-xs text-stone-600 line-clamp-2 leading-relaxed" x-text="$store.ui.lang === 'id' ? dest.tagline : dest.taglineEn"></p>
            </div>
          </div>

          {{-- Card Footer Action --}}
          <div class="px-5 pb-5 pt-2 border-t border-stone-100 flex items-center justify-between">
            <button
              type="button"
              @click="$store.modals.openDestination(dest)"
              class="w-full flex items-center justify-center gap-1.5 py-2 px-4 rounded-xl bg-stone-50 hover:bg-emerald-700 hover:text-white text-stone-800 text-xs font-semibold transition-all group-hover:bg-emerald-700 group-hover:text-white"
            >
              <span x-text="$store.ui.lang === 'id' ? 'Lihat Destinasi →' : 'View Destination →'"></span>
            </button>
          </div>
        </div>
      </template>
    </div>
  </section>

  {{-- 4. BUDAYA & TRADISI PUKUL SAPU SPOTLIGHT --}}
  <section class="bg-stone-900 text-stone-100 py-16 sm:py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

        <div class="space-y-6">
          <div class="inline-flex items-center gap-2 text-xs font-semibold text-amber-400 uppercase tracking-widest">
            <x-icon name="Sparkles" class="w-4 h-4" />
            <span><x-t id="Warisan Leluhur Maluku" en="Ancestral Maluku Heritage" /></span>
          </div>

          <h2 class="font-serif text-3xl sm:text-4xl font-bold text-white tracking-tight leading-snug">
            <x-t
              id="Atraksi Pukul Sapu: Simbol Persaudaraan Sejati Tanpa Dendam"
              en="The Sacred Pukul Sapu: Eternal Fraternity of Negeri Morella"
            />
          </h2>

          <p class="text-sm text-stone-300 leading-relaxed font-light">
            <x-t
              id="Diselenggarakan setiap 7 Syawal setelah Idul Fitri, para pemuda Negeri Negeri Morella saling menyabetkan lidi pohon enau pilihan. Tidak ada rasa dendam, melainkan kobaran semangat persaudaraan dan ketahanan jiwa yang disembuhkan dengan minyak pusaka berkhasiat tinggi warisan leluhur."
              en="Enacted annually on the 7th of Shawwal, youth from Negeri Negeri Morella demonstrate physical endurance and kinship through ceremonial broom whips, healed naturally with heirloom herbal oils."
            />
          </p>

          <div class="grid grid-cols-2 gap-3 text-xs text-stone-300">
            <div class="p-3 bg-stone-800/80 rounded-xl border border-stone-700/60">
              <div class="font-semibold text-amber-400 text-sm">7 Syawal</div>
              <div class="text-[11px] text-stone-400 mt-0.5">Waktu Pelaksanaan Adat</div>
            </div>
            <div class="p-3 bg-stone-800/80 rounded-xl border border-stone-700/60">
              <div class="font-semibold text-amber-400 text-sm">WBTb Nasional</div>
              <div class="text-[11px] text-stone-400 mt-0.5">Warisan Budaya Takbenda</div>
            </div>
          </div>

          <div class="pt-2 flex items-center gap-3">
            <a
              href="{{ route('culture') }}"
              class="px-5 py-2.5 bg-amber-600 hover:bg-amber-500 text-white rounded-xl text-xs font-semibold transition-colors flex items-center gap-2"
            >
              <span><x-t id="Buka Arsip Budaya Negeri Morella" en="Open Cultural Archive" /></span>
              <x-icon name="ArrowRight" class="w-4 h-4" />
            </a>
          </div>
        </div>

        {{-- Visual Cultural Card --}}
        <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-stone-800 bg-stone-950">
          <img
            src="https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&w=1000&q=80"
            alt="Pukul Sapu Negeri Morella Tradition"
            class="w-full h-80 sm:h-96 object-cover"
            onerror="this.style.display = 'none'"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-transparent to-transparent"></div>
          <div class="absolute bottom-5 left-5 right-5 text-stone-200 text-xs">
            <div class="font-serif text-lg font-bold text-white">
              Ukuwala Mahiate (Tradisi Pukul Sapu)
            </div>
            <p class="text-[11px] text-stone-400 mt-1">
              Lapangan Upacara Adat Negeri Negeri Morella, Leihitu, Maluku Tengah
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- 5. UMKM & EKONOMI KREATIF PREVIEW --}}
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
      <div class="space-y-1">
        <div class="text-xs font-semibold uppercase tracking-widest text-emerald-700">
          <x-t id="Ekonomi Kreatif Warga" en="Artisanal Economy" />
        </div>
        <h2 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900">
          <x-t id="Produk Olahan & Kerajinan Negeri Morella" en="Local Artisan Products & Crafts" />
        </h2>
      </div>

      <a
        href="{{ route('umkm') }}"
        class="text-xs sm:text-sm font-semibold text-emerald-700 hover:text-emerald-800 flex items-center gap-1 group self-start md:self-auto"
      >
        <span><x-t id="Kunjungi Katalog UMKM" en="Visit Full Marketplace" /></span>
        <x-icon name="ChevronRight" class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" />
      </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <template x-for="prod in featuredUMKM" :key="prod.id">
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between group">
          <div>
            <div class="relative h-44 w-full bg-stone-100 overflow-hidden">
              <img
                :src="prod.imageUrl"
                :alt="prod.name"
                referrerpolicy="no-referrer"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <div class="absolute top-2.5 left-2.5 bg-stone-900/80 text-white text-[10px] font-semibold px-2 py-0.5 rounded uppercase" x-text="prod.category.replace('_', ' ')"></div>
            </div>

            <div class="p-4 space-y-1.5">
              <div class="text-[11px] text-stone-500 font-medium" x-text="prod.sellerGroup"></div>
              <h4 class="font-serif font-bold text-stone-900 text-sm line-clamp-1 group-hover:text-emerald-800 transition-colors" x-text="$store.ui.lang === 'id' ? prod.name : prod.nameEn"></h4>
              <div class="font-mono text-base font-bold text-emerald-700 tabular-nums">
                <span x-text="prod.priceFormatted"></span>
                <span class="text-[10px] text-stone-500 font-sans font-normal ml-1">/<span x-text="prod.unit"></span></span>
              </div>
            </div>
          </div>

          <div class="p-4 pt-0">
            <button
              type="button"
              @click="$store.modals.openProduct(prod)"
              class="w-full py-2 bg-stone-100 hover:bg-emerald-700 hover:text-white text-stone-800 rounded-xl text-xs font-semibold transition-colors"
              x-text="$store.ui.lang === 'id' ? 'Lihat Produk' : 'View Product'"
            ></button>
          </div>
        </div>
      </template>
    </div>
  </section>

  {{-- 6. AGENDA & BERITA MORELA --}}
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

      {{-- Agenda & Event --}}
      <div class="space-y-6">
        <div class="flex items-center justify-between">
          <div>
            <div class="text-xs font-semibold uppercase tracking-widest text-emerald-700">
              <x-t id="Kalender Kegiatan" en="Upcoming Calendar" />
            </div>
            <h3 class="font-serif text-2xl font-bold text-stone-900">
              <x-t id="Agenda & Event Desa" en="Village Agenda & Events" />
            </h3>
          </div>
          <a
            href="{{ route('events') }}"
            class="text-xs font-semibold text-emerald-700 hover:underline"
          >
            <x-t id="Semua Agenda →" en="All Events →" />
          </a>
        </div>

        <div class="space-y-4">
          <template x-for="evt in upcomingEvents" :key="evt.id">
            <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-xs flex flex-col sm:flex-row gap-4 items-start justify-between">
              <div class="space-y-1.5 flex-1">
                <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-emerald-50 text-emerald-800 border border-emerald-200 uppercase" x-text="evt.category"></span>
                <h4 class="font-serif font-bold text-stone-900 text-base" x-text="$store.ui.lang === 'id' ? evt.title : evt.titleEn"></h4>
                <p class="text-xs text-stone-600 line-clamp-2" x-text="$store.ui.lang === 'id' ? evt.description : evt.descriptionEn"></p>
                <div class="flex items-center gap-3 text-xs text-stone-500 pt-1">
                  <span class="flex items-center gap-1 font-mono">
                    <x-icon name="Calendar" class="w-3.5 h-3.5 text-stone-400" />
                    <span x-text="evt.date"></span>
                  </span>
                  <span>·</span>
                  <span class="truncate" x-text="evt.location"></span>
                </div>
              </div>
            </div>
          </template>
        </div>
      </div>

      {{-- Berita Terkini --}}
      <div class="space-y-6">
        <div class="flex items-center justify-between">
          <div>
            <div class="text-xs font-semibold uppercase tracking-widest text-emerald-700">
              <x-t id="Warta Komunitas" en="Village Dispatch" />
            </div>
            <h3 class="font-serif text-2xl font-bold text-stone-900">
              <x-t id="Berita & Publikasi Terkini" en="Latest News & Articles" />
            </h3>
          </div>
          <a
            href="{{ route('news') }}"
            class="text-xs font-semibold text-emerald-700 hover:underline"
          >
            <x-t id="Semua Berita →" en="All News →" />
          </a>
        </div>

        <div class="space-y-4">
          <template x-for="article in latestNews" :key="article.id">
            <div
              @click="$store.modals.openArticle(article)"
              class="p-4 bg-white rounded-2xl border border-stone-200 shadow-xs hover:border-emerald-300 cursor-pointer transition-all flex items-center gap-4 group"
            >
              <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden bg-stone-100 shrink-0">
                <img
                  :src="article.imageUrl"
                  :alt="article.title"
                  referrerpolicy="no-referrer"
                  class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                />
              </div>
              <div class="space-y-1 flex-1 min-w-0">
                <div class="text-[10px] text-stone-400 font-mono">
                  <span x-text="article.publishedDate"></span> · <span x-text="article.readTime"></span>
                </div>
                <h4 class="font-serif font-bold text-stone-900 text-sm group-hover:text-emerald-800 transition-colors line-clamp-2" x-text="$store.ui.lang === 'id' ? article.title : article.titleEn"></h4>
                <p class="text-xs text-stone-500 line-clamp-1" x-text="$store.ui.lang === 'id' ? article.summary : article.summaryEn"></p>
              </div>
            </div>
          </template>
        </div>
      </div>
    </div>
  </section>

  {{-- 7. BANNER PENGABDIAN UNIVERSITAS DARUSSALAM AMBON --}}
  <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="p-8 sm:p-12 rounded-3xl bg-gradient-to-r from-emerald-900 via-teal-900 to-stone-900 text-white relative overflow-hidden shadow-xl border border-emerald-700/40">
      <div class="max-w-2xl space-y-4 relative z-10">
        <div class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-amber-300">
          <x-icon name="Award" class="w-4 h-4" />
          <span>PROGRAM PENGABDIAN MASYARAKAT</span>
        </div>

        <h3 class="font-serif text-2xl sm:text-3xl font-bold text-white tracking-tight">
          Universitas Darussalam Ambon
        </h3>

        <p class="text-xs sm:text-sm text-stone-200 font-light leading-relaxed">
          <x-t
            id="“Digitalisasi Potensi Pariwisata dan Ekonomi Kreatif Desa Morela” — Sinergi civitas akademika, mahasiswa KKN, Pemerintah Negeri Negeri Morella, dan Pokdarwis dalam mewujudkan desa wisata berdaya saing global."
            en="“Digitalization of Negeri Morella Village Tourism & Creative Economy” — Academic synergy between UNIDAR students, village government, and customary tourism leaders."
          />
        </p>

        <div class="pt-2 flex flex-wrap items-center gap-3">
          <a
            href="{{ route('program') }}"
            class="px-5 py-2.5 bg-amber-500 hover:bg-amber-400 text-stone-950 font-semibold rounded-xl text-xs transition-colors shadow-md"
          >
            <x-t id="Lihat Tim & Hasil Pengabdian" en="View Team & Outcomes" />
          </a>

          <a
            href="{{ route('map') }}"
            class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white font-medium rounded-xl text-xs transition-colors border border-white/20"
          >
            <x-t id="Buka Peta Wisata Interaktif" en="Explore Interactive Map" />
          </a>
        </div>
      </div>

      <div class="absolute right-0 bottom-0 translate-x-12 translate-y-12 opacity-10 pointer-events-none hidden lg:block">
        <x-icon name="Compass" class="w-96 h-96 text-white" />
      </div>
    </div>
  </section>

</div>

<script>
  function homeView(data) {
    return {
      destinations: data.destinations,
      umkmProducts: data.umkmProducts,
      events: data.events,
      news: data.news,
      heroSliders: data.heroSliders,
      activeCategory: 'all',
      localSearch: '',
      currentSliderIndex: 0,
      init() {
        if (!this.heroSliders || this.heroSliders.length === 0) return;
        setInterval(() => {
          this.currentSliderIndex = (this.currentSliderIndex + 1) % this.heroSliders.length;
        }, 5000);
      },
      get filteredDestinations() {
        const featured = this.destinations.filter((d) => d.published);
        return featured.filter((d) => {
          const matchesCategory = this.activeCategory === 'all' || d.category === this.activeCategory;
          const q = this.localSearch.toLowerCase();
          const matchesSearch =
            d.name.toLowerCase().includes(q) ||
            d.tagline.toLowerCase().includes(q) ||
            d.location.toLowerCase().includes(q);
          return matchesCategory && matchesSearch;
        });
      },
      get featuredUMKM() {
        return this.umkmProducts.filter((p) => p.featured && p.published).slice(0, 4);
      },
      get upcomingEvents() {
        return this.events.filter((e) => e.status !== 'completed').slice(0, 2);
      },
      get latestNews() {
        return this.news.slice(0, 3);
      },
    };
  }
</script>
@endsection
