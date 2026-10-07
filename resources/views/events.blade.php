@extends('layouts.app')

@php
  $eventsData = [
    'events' => $events,
  ];
  $tabs = [
    ['id' => 'all', 'labelId' => 'Semua Agenda', 'labelEn' => 'All Events'],
    ['id' => 'upcoming', 'labelId' => 'Akan Datang', 'labelEn' => 'Upcoming'],
    ['id' => 'budaya', 'labelId' => 'Adat & Budaya', 'labelEn' => 'Culture'],
    ['id' => 'pengabdian', 'labelId' => 'Pengabdian Mahasiswa', 'labelEn' => 'Community Service'],
    ['id' => 'completed', 'labelId' => 'Telah Terlaksana', 'labelEn' => 'Completed'],
  ];
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 space-y-10" x-data="eventsView({{ Js::from($eventsData) }})">

  {{-- Editorial Header --}}
  <div class="space-y-3 max-w-3xl">
    <div class="text-xs font-semibold uppercase tracking-widest text-emerald-700">
      <x-t id="Kalender Kegiatan & Perayaan" en="Community Calendar & Celebrations" />
    </div>
    <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 tracking-tight">
      <x-t id="Agenda & Event Negeri Morella" en="Events & Agenda in Morella" />
    </h1>
    <p class="text-sm text-stone-600 leading-relaxed">
      <x-t
        id="Pantau jadwal upacara adat sakral tahunan, kegiatan kolaborasi pengabdian mahasiswa Universitas Darussalam Ambon, pelatihan desa, dan festival seni pesisir Leihitu."
        en="Stay informed regarding sacred annual cultural ceremonies, UNIDAR academic community engagements, conservation drives, and youth cultural festivals."
      />
    </p>
  </div>

  {{-- Filter Tabs --}}
  <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none border-b border-stone-200">
    @foreach ($tabs as $tab)
      <button
        type="button"
        @click="filter = '{{ $tab['id'] }}'"
        :class="filter === '{{ $tab['id'] }}' ? 'border-emerald-700 text-emerald-800' : 'border-transparent text-stone-500 hover:text-stone-900'"
        class="px-4 py-2 text-xs font-semibold transition-colors whitespace-nowrap border-b-2 -mb-[2px]"
      >
        <x-t :id="$tab['labelId']" :en="$tab['labelEn']" />
      </button>
    @endforeach
  </div>

  {{-- Events List --}}
  <div class="grid grid-cols-1 md:grid-cols-2 gap-6 sm:gap-8">
    <template x-for="evt in filtered" :key="evt.id">
      <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs hover:shadow-md transition-all flex flex-col justify-between">
        <div>
          <div class="relative h-48 w-full bg-stone-100 overflow-hidden">
            <img
              :src="evt.imageUrl"
              :alt="evt.title"
              referrerpolicy="no-referrer"
              class="w-full h-full object-cover"
              onerror="this.style.display = 'none'"
            />
            <div class="absolute top-3 left-3 bg-stone-900/80 backdrop-blur-xs text-white text-[10px] font-semibold uppercase tracking-wider px-2.5 py-1 rounded-md" x-text="evt.category"></div>

            <div
              class="absolute top-3 right-3 text-[10px] font-semibold uppercase px-2.5 py-1 rounded-md shadow-xs"
              :class="evt.status === 'upcoming' ? 'bg-emerald-600 text-white' : evt.status === 'ongoing' ? 'bg-amber-500 text-white' : 'bg-stone-600 text-white'"
            >
              <span x-text="evt.status === 'upcoming' ? 'Akan Datang' : evt.status === 'ongoing' ? 'Berlangsung' : 'Selesai'"></span>
            </div>
          </div>

          <div class="p-6 space-y-4">
            <div class="space-y-1">
              <h3 class="font-serif text-xl font-bold text-stone-900 leading-snug" x-text="$store.ui.lang === 'id' ? evt.title : evt.titleEn"></h3>
              <div class="text-xs text-stone-500 font-medium">
                <span x-text="$store.ui.lang === 'id' ? 'Penyelenggara:' : 'Organizer:'"></span>
                <span x-text="evt.organizer"></span>
              </div>
            </div>

            <div class="space-y-2 text-xs text-stone-600 p-3.5 bg-stone-50 rounded-xl border border-stone-200">
              <div class="flex items-center gap-2 font-mono">
                <x-icon name="Calendar" class="w-4 h-4 text-emerald-600 shrink-0" />
                <span x-text="evt.date"></span>
                <span class="text-stone-400">|</span>
                <x-icon name="Clock" class="w-4 h-4 text-emerald-600 shrink-0" />
                <span x-text="evt.time"></span>
              </div>

              <div class="flex items-center gap-2">
                <x-icon name="MapPin" class="w-4 h-4 text-emerald-600 shrink-0" />
                <span class="truncate" x-text="evt.location"></span>
              </div>
            </div>

            <p class="text-xs text-stone-700 leading-relaxed" x-text="$store.ui.lang === 'id' ? evt.description : evt.descriptionEn"></p>

            <template x-if="evt.videoUrl">
              <a
                :href="evt.videoUrl"
                target="_blank"
                rel="noreferrer"
                class="inline-flex items-center gap-2 py-2 px-3.5 bg-rose-50 hover:bg-rose-100 text-rose-700 border border-rose-200 rounded-lg text-xs font-semibold transition-colors w-fit"
              >
                <x-icon name="Play" class="w-3.5 h-3.5" />
                <span x-text="$store.ui.lang === 'id' ? 'Tonton Video Kegiatan' : 'Watch Event Video'"></span>
              </a>
            </template>
          </div>
        </div>

        <div class="p-6 pt-0 border-t border-stone-100 flex items-center justify-between text-xs">
          <span class="text-stone-500">
            Kontak Info: <strong class="text-stone-800" x-text="evt.contactPerson"></strong>
          </span>

          <a
            :href="'https://wa.me/' + evt.contactPerson.replace(/[^0-9]/g, '')"
            target="_blank"
            rel="noreferrer"
            class="py-1.5 px-3 bg-stone-100 hover:bg-stone-200 text-stone-800 rounded-lg font-medium transition-colors"
            x-text="$store.ui.lang === 'id' ? 'Tanya Panitia' : 'Contact'"
          ></a>
        </div>
      </div>
    </template>
  </div>

</div>

<script>
  function eventsView(data) {
    return {
      events: data.events,
      filter: 'all',
      get filtered() {
        return this.filter === 'all'
          ? this.events
          : this.events.filter((e) => e.status === this.filter || e.category === this.filter);
      },
    };
  }
</script>
@endsection
