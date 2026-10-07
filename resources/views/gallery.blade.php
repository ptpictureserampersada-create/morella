@extends('layouts.app')

@php
  $galleryData = [
    'gallery' => $gallery,
  ];
  $categories = [
    ['id' => 'all', 'labelId' => 'Semua Foto', 'labelEn' => 'All Photos'],
    ['id' => 'wisata', 'labelId' => '🏝️ Wisata', 'labelEn' => '🏝️ Tourism'],
    ['id' => 'alam', 'labelId' => '📷 Alam', 'labelEn' => '📷 Nature'],
    ['id' => 'budaya', 'labelId' => '🎭 Budaya', 'labelEn' => '🎭 Culture'],
    ['id' => 'masyarakat', 'labelId' => '👥 Masyarakat', 'labelEn' => '👥 Community'],
    ['id' => 'pengabdian', 'labelId' => '🎓 Pengabdian', 'labelEn' => '🎓 University'],
  ];
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 space-y-10" x-data="galleryView({{ Js::from($galleryData) }})">

  {{-- Editorial Header --}}
  <div class="space-y-3 max-w-3xl">
    <div class="text-xs font-semibold uppercase tracking-widest text-emerald-700">
      <x-t id="Dokumentasi Visual & Lensa Pesisir" en="Visual Lens & Documentation" />
    </div>
    <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 tracking-tight">
      <x-t id="Galeri Fotografi Negeri Morella" en="Photo Gallery of Morella" />
    </h1>
    <p class="text-sm text-stone-600 leading-relaxed">
      <x-t
        id="Potret keindahan pantai toska, khidmatnya adat istiadat leluhur, senyum hangat masyarakat pesisir, dan jejak program pengabdian mahasiswa di Jazirah Leihitu."
        en="A visual archive capturing turquoise waters, solemn ancestral ceremonies, community daily life, and university community service moments."
      />
    </p>
  </div>

  {{-- Category Filter --}}
  <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none border-b border-stone-200">
    @foreach ($categories as $cat)
      <button
        type="button"
        @click="activeCategory = '{{ $cat['id'] }}'"
        :class="activeCategory === '{{ $cat['id'] }}' ? 'border-emerald-700 text-emerald-800' : 'border-transparent text-stone-500 hover:text-stone-900'"
        class="px-4 py-2 text-xs font-semibold transition-colors whitespace-nowrap border-b-2 -mb-[2px]"
      >
        <x-t :id="$cat['labelId']" :en="$cat['labelEn']" />
      </button>
    @endforeach
  </div>

  {{-- Masonry-like Responsive Grid --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
    <template x-for="photo in filtered" :key="photo.id">
      <div
        @click="$store.modals.openLightbox(gallery.findIndex((g) => g.id === photo.id))"
        class="group relative bg-stone-900 rounded-2xl overflow-hidden shadow-xs hover:shadow-lg transition-all cursor-pointer aspect-4/3"
      >
        <img
          :src="photo.imageUrl"
          :alt="photo.title"
          referrerpolicy="no-referrer"
          class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-90 group-hover:opacity-100"
          onerror="this.style.display = 'none'"
        />

        {{-- Gradient Overlay --}}
        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>

        {{-- Top Category Badge --}}
        <div class="absolute top-3 left-3 bg-stone-900/80 backdrop-blur-xs text-white text-[10px] font-semibold uppercase px-2 py-0.5 rounded" x-text="photo.category"></div>

        {{-- Hover Fullscreen Icon --}}
        <div class="absolute top-3 right-3 p-1.5 rounded-full bg-white/20 text-white opacity-0 group-hover:opacity-100 transition-opacity">
          <x-icon name="Maximize2" class="w-3.5 h-3.5" />
        </div>

        {{-- Bottom Caption --}}
        <div class="absolute bottom-3.5 left-3.5 right-3.5 text-white space-y-1">
          <h4 class="font-serif font-bold text-sm leading-tight text-white group-hover:text-emerald-300 transition-colors line-clamp-1" x-text="$store.ui.lang === 'id' ? photo.title : photo.titleEn"></h4>
          <div class="flex items-center justify-between text-[10px] text-stone-300">
            <span class="flex items-center gap-1 truncate max-w-[150px]">
              <x-icon name="MapPin" class="w-3 h-3 text-emerald-400 shrink-0" />
              <span class="truncate" x-text="photo.location"></span>
            </span>
            <span class="font-mono text-stone-400" x-text="photo.dateTaken"></span>
          </div>
        </div>
      </div>
    </template>
  </div>

  <div class="p-4 rounded-xl bg-stone-50 border border-stone-200 text-center text-xs text-stone-500">
    💡 <x-t id="Klik pada foto mana pun untuk membuka tampilan layar penuh (fullscreen lightbox)." en="Click any photo to open fullscreen high-resolution lightbox." />
  </div>

</div>

<script>
  function galleryView(data) {
    return {
      gallery: data.gallery,
      activeCategory: 'all',
      get filtered() {
        return this.activeCategory === 'all'
          ? this.gallery
          : this.gallery.filter((item) => item.category === this.activeCategory);
      },
    };
  }
</script>
@endsection
