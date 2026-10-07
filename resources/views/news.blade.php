@extends('layouts.app')

@php
  $newsData = [
    'news' => $news,
  ];
  $categories = [
    ['id' => 'all', 'labelId' => 'Semua Warta', 'labelEn' => 'All Articles'],
    ['id' => 'mahasiswa', 'labelId' => 'Kegiatan Mahasiswa UNIDAR', 'labelEn' => 'UNIDAR Students'],
    ['id' => 'desa', 'labelId' => 'Berita Desa', 'labelEn' => 'Village News'],
    ['id' => 'pariwisata', 'labelId' => 'Pariwisata', 'labelEn' => 'Tourism'],
    ['id' => 'budaya', 'labelId' => 'Budaya', 'labelEn' => 'Culture'],
    ['id' => 'umkm', 'labelId' => 'UMKM', 'labelEn' => 'Crafts & Market'],
    ['id' => 'lingkungan', 'labelId' => 'Lingkungan', 'labelEn' => 'Environment'],
  ];
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 space-y-10" x-data="newsView({{ Js::from($newsData) }})">

  {{-- Editorial Header --}}
  <div class="space-y-3 max-w-3xl">
    <div class="text-xs font-semibold uppercase tracking-widest text-emerald-700">
      <x-t id="Publikasi & Kabar Pesisir" en="Village Dispatches & News" />
    </div>
    <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 tracking-tight">
      <x-t id="Warta Desa & Catatan Pengabdian" en="News & Community Service Dispatches" />
    </h1>
    <p class="text-sm text-stone-600 leading-relaxed">
      <x-t
        id="Informasi terhangat seputar perkembangan ekowisata Negeri Morella, dokumentasi program pengabdian mahasiswa Universitas Darussalam Ambon, dan inovasi kelompok usaha warga."
        en="Latest updates on ecotourism initiatives, community service documentation from UNIDAR Ambon students, and local economic breakthroughs."
      />
    </p>
  </div>

  {{-- Search and Category Filter --}}
  <div class="p-4 sm:p-5 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-4">
    <div class="flex flex-col sm:flex-row gap-3 items-center justify-between">
      <div class="relative w-full sm:w-96">
        <x-icon name="Search" class="w-4 h-4 text-stone-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
        <input
          type="text"
          x-model="query"
          :placeholder="$store.ui.lang === 'id' ? 'Cari judul artikel, topik, penulis...' : 'Search article headlines...'"
          class="w-full pl-10 pr-4 py-2 bg-stone-50 border border-stone-200 rounded-xl text-xs sm:text-sm text-stone-900 placeholder-stone-400 focus:outline-none focus:border-emerald-600"
        />
      </div>

      <div
        class="text-xs text-stone-500 font-mono"
        x-text="filtered.length + ' ' + ($store.ui.lang === 'id' ? 'Artikel Terpublikasi' : 'Articles Published')"
      ></div>
    </div>

    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 scrollbar-none">
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

  {{-- News Grid --}}
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
    <template x-for="item in filtered" :key="item.id">
      <article
        @click="$store.modals.openArticle(item)"
        class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-md hover:border-emerald-300 transition-all cursor-pointer flex flex-col justify-between group"
      >
        <div>
          <div class="relative h-48 w-full bg-stone-100 overflow-hidden">
            <img
              :src="item.imageUrl"
              :alt="item.title"
              referrerpolicy="no-referrer"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              onerror="this.style.display = 'none'"
            />
            <div class="absolute top-3 left-3 bg-stone-900/80 text-white text-[10px] font-semibold uppercase px-2 py-0.5 rounded backdrop-blur-xs" x-text="item.category"></div>
          </div>

          <div class="p-5 sm:p-6 space-y-3">
            <div class="flex items-center gap-2 text-[11px] text-stone-400 font-mono">
              <span x-text="item.publishedDate"></span>
              <span>·</span>
              <span x-text="item.readTime"></span>
            </div>

            <h3 class="font-serif text-lg font-bold text-stone-900 group-hover:text-emerald-800 transition-colors leading-snug line-clamp-2" x-text="$store.ui.lang === 'id' ? item.title : item.titleEn"></h3>

            <p class="text-xs text-stone-600 line-clamp-3 leading-relaxed" x-text="$store.ui.lang === 'id' ? item.summary : item.summaryEn"></p>
          </div>
        </div>

        <div class="p-5 sm:p-6 pt-0 border-t border-stone-100 flex items-center justify-between text-xs">
          <span class="text-stone-500 text-[11px] truncate max-w-[170px]" x-text="item.author"></span>
          <span class="text-emerald-700 font-semibold group-hover:translate-x-1 transition-transform inline-flex items-center gap-1">
            <span x-text="$store.ui.lang === 'id' ? 'Baca Selengkapnya' : 'Read'"></span>
            <x-icon name="ArrowRight" class="w-3.5 h-3.5" />
          </span>
        </div>
      </article>
    </template>
  </div>

</div>

<script>
  function newsView(data) {
    return {
      news: data.news,
      activeCategory: 'all',
      query: '',
      get filtered() {
        return this.news.filter((item) => {
          const matchCat = this.activeCategory === 'all' || item.category === this.activeCategory;
          const q = this.query.toLowerCase();
          const matchQuery =
            item.title.toLowerCase().includes(q) ||
            item.summary.toLowerCase().includes(q) ||
            item.author.toLowerCase().includes(q);
          return matchCat && matchQuery;
        });
      },
    };
  }
</script>
@endsection
