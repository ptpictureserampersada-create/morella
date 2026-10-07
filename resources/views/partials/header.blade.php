@php
  $navLinks = [
    ['id' => 'home', 'labelId' => 'Beranda', 'labelEn' => 'Home', 'route' => 'home'],
    ['id' => 'destinations', 'labelId' => 'Destinasi', 'labelEn' => 'Destinations', 'route' => 'destinations'],
    ['id' => 'tickets', 'labelId' => 'Tiket Wisata', 'labelEn' => 'E-Ticket', 'route' => 'tickets', 'icon' => 'Ticket'],
    ['id' => 'culture', 'labelId' => 'Budaya', 'labelEn' => 'Culture', 'route' => 'culture'],
    ['id' => 'umkm', 'labelId' => 'UMKM', 'labelEn' => 'Local Market', 'route' => 'umkm'],
    ['id' => 'map', 'labelId' => 'Peta Wisata', 'labelEn' => 'Map', 'route' => 'map'],
    ['id' => 'social', 'labelId' => 'Media Sosial', 'labelEn' => 'Social Media', 'route' => 'social'],
    ['id' => 'events', 'labelId' => 'Agenda', 'labelEn' => 'Events', 'route' => 'events'],
    ['id' => 'news', 'labelId' => 'Berita', 'labelEn' => 'News', 'route' => 'news'],
    ['id' => 'gallery', 'labelId' => 'Galeri', 'labelEn' => 'Gallery', 'route' => 'gallery'],
    ['id' => 'program', 'labelId' => 'Pengabdian UNIDAR', 'labelEn' => 'UNIDAR Program', 'route' => 'program'],
  ];
  $moreLinks = [
    ['id' => 'events', 'labelId' => 'Agenda & Kegiatan', 'labelEn' => 'Events & Calendar', 'route' => 'events'],
    ['id' => 'news', 'labelId' => 'Warta Desa & KKN', 'labelEn' => 'Village & Student News', 'route' => 'news'],
    ['id' => 'gallery', 'labelId' => 'Galeri Dokumentasi', 'labelEn' => 'Photo Gallery', 'route' => 'gallery'],
    ['id' => 'program', 'labelId' => 'Pengabdian Mahasiswa UNIDAR', 'labelEn' => 'UNIDAR Community Service', 'route' => 'program'],
  ];
  $moreActive = in_array($currentView, ['events', 'news', 'gallery', 'program'], true);
@endphp

<header
  class="sticky top-0 z-40 bg-stone-900/95 backdrop-blur-md border-b border-stone-800 text-stone-100 transition-colors"
  x-data="{ mobileMenuOpen: false }"
>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between h-16 sm:h-20">

      {{-- Zone 1: Brand title wordmark --}}
      <a href="{{ route('home') }}" class="flex items-center text-left group focus-visible:outline-none">
        <div>
          <div class="font-serif text-lg sm:text-xl font-bold tracking-tight text-white flex items-center gap-2">
            Negeri Morella
          </div>
          <p class="text-[10px] text-stone-400 font-sans tracking-wide uppercase">
            <x-t id="Leihitu · Maluku Tengah" en="Leihitu · Central Maluku" />
          </p>
        </div>
      </a>

      {{-- Zone 2: Clean text navigation links (Desktop) --}}
      <nav class="hidden lg:flex items-center gap-1 xl:gap-2 text-sm font-medium">
        @foreach (array_slice($navLinks, 0, 7) as $item)
          <a
            href="{{ route($item['route']) }}"
            class="px-3 py-1.5 rounded-lg transition-colors whitespace-nowrap flex items-center gap-1.5 {{ $currentView === $item['id'] ? 'text-emerald-400 font-semibold bg-stone-800/80' : 'text-stone-300 hover:text-white hover:bg-stone-800/40' }}"
          >
            @isset($item['icon'])
              <x-icon :name="$item['icon']" class="w-3.5 h-3.5 text-emerald-400" />
            @endisset
            <span><x-t :id="$item['labelId']" :en="$item['labelEn']" /></span>
          </a>
        @endforeach

        <div class="relative group">
          <button
            type="button"
            class="px-3 py-1.5 rounded-lg transition-colors text-stone-300 hover:text-white hover:bg-stone-800/40 {{ $moreActive ? 'text-emerald-400 font-semibold' : '' }}"
          >
            <x-t id="Lainnya ▾" en="More ▾" />
          </button>
          <div class="absolute right-0 top-full mt-1 w-52 py-2 bg-stone-900 border border-stone-800 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-150">
            @foreach ($moreLinks as $item)
              <a
                href="{{ route($item['route']) }}"
                class="w-full block text-left px-4 py-2 text-sm text-stone-300 hover:text-emerald-400 hover:bg-stone-800"
              >
                <x-t :id="$item['labelId']" :en="$item['labelEn']" />
              </a>
            @endforeach
          </div>
        </div>
      </nav>

      {{-- Zone 3: Language Toggle & Admin/Role Portal Action --}}
      <div class="flex items-center gap-2 sm:gap-3">
        {{-- Language Switcher --}}
        <div class="flex items-center bg-stone-800/90 rounded-lg p-0.5 border border-stone-700/60 text-xs">
          <button
            type="button"
            @click="$store.ui.setLang('id')"
            :class="$store.ui.lang === 'id' ? 'bg-emerald-700 text-white shadow-sm' : 'text-stone-400 hover:text-stone-200'"
            class="px-2 py-1 rounded-md transition-colors font-medium"
            title="Bahasa Indonesia"
          >
            ID
          </button>
          <button
            type="button"
            @click="$store.ui.setLang('en')"
            :class="$store.ui.lang === 'en' ? 'bg-emerald-700 text-white shadow-sm' : 'text-stone-400 hover:text-stone-200'"
            class="px-2 py-1 rounded-md transition-colors font-medium"
            title="English"
          >
            EN
          </button>
        </div>

        {{-- Admin Dashboard CTA Button --}}
        <a
          href="{{ route('admin') }}"
          class="flex items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm font-medium rounded-lg transition-all whitespace-nowrap {{ $currentView === 'admin' ? 'bg-amber-600 text-white shadow-md shadow-amber-900/40 ring-1 ring-amber-400' : 'bg-emerald-700 hover:bg-emerald-600 text-white shadow-sm' }}"
        >
          <x-icon name="Shield" class="w-3.5 h-3.5" />
          <span><x-t id="Dashboard Admin" en="Admin Portal" /></span>
        </a>

        {{-- Mobile menu hamburger --}}
        <button
          type="button"
          @click="mobileMenuOpen = !mobileMenuOpen"
          class="lg:hidden p-2 rounded-lg text-stone-400 hover:text-white hover:bg-stone-800 focus:outline-none"
          aria-label="Toggle Menu"
        >
          <template x-if="!mobileMenuOpen"><x-icon name="Menu" class="w-5 h-5" /></template>
          <template x-if="mobileMenuOpen"><x-icon name="X" class="w-5 h-5" /></template>
        </button>
      </div>
    </div>
  </div>

  {{-- Mobile Menu Dropdown --}}
  <div
    x-show="mobileMenuOpen"
    x-cloak
    class="lg:hidden bg-stone-900 border-b border-stone-800 px-4 pt-2 pb-6 space-y-1 animate-in fade-in slide-in-from-top-2 duration-150"
  >
    @foreach ($navLinks as $item)
      <a
        href="{{ route($item['route']) }}"
        class="w-full text-left px-3 py-2.5 rounded-lg text-sm font-medium flex items-center justify-between {{ $currentView === $item['id'] ? 'bg-emerald-950/80 text-emerald-400 font-semibold' : 'text-stone-300 hover:bg-stone-800 hover:text-white' }}"
      >
        <span class="flex items-center gap-2">
          @isset($item['icon'])
            <x-icon :name="$item['icon']" class="w-4 h-4 text-emerald-400" />
          @endisset
          <span><x-t :id="$item['labelId']" :en="$item['labelEn']" /></span>
        </span>
      </a>
    @endforeach

    <div class="pt-3 border-t border-stone-800 mt-2 flex items-center justify-between text-xs text-stone-400 px-3">
      <span x-text="'Role: ' + ($store.ui.role === 'admin_desa' ? 'Pemerintah Negeri' : ($store.ui.role === 'mahasiswa' ? 'Mahasiswa UNIDAR' : 'Wisatawan'))"></span>
      <a href="{{ route('admin') }}" class="text-emerald-400 hover:underline font-medium">
        Buka Dashboard ➔
      </a>
    </div>
  </div>
</header>
