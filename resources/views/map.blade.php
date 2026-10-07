@extends('layouts.app')

@php
  $mapData = [
    'mapMarkers' => $mapMarkers,
    'destinations' => $destinations,
  ];
  $filters = [
    ['id' => 'all', 'labelId' => 'Semua Titik', 'labelEn' => 'All Spots'],
    ['id' => 'wisata', 'labelId' => '🔵 Wisata', 'labelEn' => '🔵 Destinations'],
    ['id' => 'umkm', 'labelId' => '🟢 UMKM', 'labelEn' => '🟢 Crafts'],
    ['id' => 'kuliner', 'labelId' => '🟠 Kuliner', 'labelEn' => '🟠 Culinary'],
    ['id' => 'penginapan', 'labelId' => '🟣 Penginapan', 'labelEn' => '🟣 Stays'],
    ['id' => 'fasilitas', 'labelId' => '🔴 Fasilitas', 'labelEn' => '🔴 Facilities'],
  ];
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 space-y-8" x-data="mapView({{ Js::from($mapData) }})">

  {{-- Editorial Header --}}
  <div class="space-y-3 max-w-3xl">
    <div class="text-xs font-semibold uppercase tracking-widest text-emerald-700">
      <x-t id="Navigasi Spasial Desa" en="Spatial Navigation & Wayfinding" />
    </div>
    <h1 class="font-serif text-3xl sm:text-4xl font-bold text-stone-900 tracking-tight">
      <x-t id="Peta Wisata & Jelajah Negeri Morella" en="Explore Morella: Interactive Tourist Map" />
    </h1>
    <p class="text-sm text-stone-600 leading-relaxed">
      <x-t
        id="Petakan rute perjalanan Anda menyusuri pesisir utara Leihitu. Temukan titik pantai eksotis, lokasi sentra kerajinan minyak kayu putih, warung kuliner tradisional papeda, homestay warga, dan fasilitas umum."
        en="Plan your itinerary along the northern Leihitu coastal highway. Locate azure beaches, artisanal eucalyptus oil distilleries, traditional culinary stops, homestays, and municipal amenities."
      />
    </p>
  </div>

  {{-- Main Interactive Map Component --}}
  <div class="bg-white rounded-2xl border border-stone-200 shadow-sm overflow-hidden">
    {{-- Map Control Bar --}}
    <div class="p-4 sm:p-5 border-b border-stone-200 bg-stone-50 flex flex-wrap items-center justify-between gap-3">
      <div class="flex items-center gap-2">
        <x-icon name="Layers" class="w-4 h-4 text-emerald-700" />
        <span class="text-xs font-semibold uppercase tracking-wider text-stone-700">
          <x-t id="Kategori Marker Lokasi" en="Location Pin Categories" />
        </span>
      </div>

      {{-- Filter buttons --}}
      <div class="flex flex-wrap items-center gap-1.5 text-xs">
        @foreach ($filters as $tab)
          <button
            type="button"
            @click="filterType = '{{ $tab['id'] }}'"
            :class="filterType === '{{ $tab['id'] }}' ? 'bg-stone-900 text-white shadow-xs' : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-100'"
            class="px-3 py-1.5 rounded-lg font-medium transition-colors"
          >
            <x-t :id="$tab['labelId']" :en="$tab['labelEn']" />
          </button>
        @endforeach
      </div>
    </div>

    {{-- Map Canvas and Split Detail Area --}}
    <div class="grid grid-cols-1 lg:grid-cols-3">
      {{-- Interactive SVG Map Viewport (2 Columns on large) --}}
      <div class="lg:col-span-2 relative bg-sky-900/10 min-h-[420px] sm:min-h-[500px] overflow-hidden select-none border-b lg:border-b-0 lg:border-r border-stone-200 flex items-center justify-center p-2 sm:p-4">

        {{-- Custom SVG Coastal Cartography of Morella / Leihitu --}}
        <svg viewBox="0 0 1000 600" class="w-full h-full max-h-[560px] object-contain drop-shadow-sm">
          <defs>
            {{-- Sea Water Gradient --}}
            <linearGradient id="seaGrad" x1="0%" y1="0%" x2="0%" y2="100%">
              <stop offset="0%" stop-color="#0284c7" stop-opacity="0.35" />
              <stop offset="50%" stop-color="#0ea5e9" stop-opacity="0.25" />
              <stop offset="100%" stop-color="#38bdf8" stop-opacity="0.15" />
            </linearGradient>

            {{-- Coastal Coral Reef Glow --}}
            <linearGradient id="reefGrad" x1="0%" y1="0%" x2="100%" y2="0%">
              <stop offset="0%" stop-color="#14b8a6" stop-opacity="0.4" />
              <stop offset="100%" stop-color="#2dd4bf" stop-opacity="0.2" />
            </linearGradient>

            {{-- Land Elevation Gradient --}}
            <linearGradient id="landGrad" x1="0%" y1="0%" x2="0%" y2="100%">
              <stop offset="0%" stop-color="#ecfdf5" />
              <stop offset="60%" stop-color="#d1fae5" />
              <stop offset="100%" stop-color="#a7f3d0" />
            </linearGradient>

            {{-- Hill Shading --}}
            <linearGradient id="hillGrad" x1="0%" y1="0%" x2="0%" y2="100%">
              <stop offset="0%" stop-color="#059669" stop-opacity="0.25" />
              <stop offset="100%" stop-color="#047857" stop-opacity="0.4" />
            </linearGradient>
          </defs>

          {{-- Base Sea Background --}}
          <rect width="1000" height="600" fill="url(#seaGrad)" />

          {{-- Sea Grid Nautical Lines --}}
          <line x1="0" y1="150" x2="1000" y2="150" stroke="#0284c7" stroke-width="0.5" stroke-dasharray="4 8" opacity="0.3" />
          <line x1="0" y1="300" x2="1000" y2="300" stroke="#0284c7" stroke-width="0.5" stroke-dasharray="4 8" opacity="0.3" />
          <line x1="250" y1="0" x2="250" y2="600" stroke="#0284c7" stroke-width="0.5" stroke-dasharray="4 8" opacity="0.3" />
          <line x1="750" y1="0" x2="750" y2="600" stroke="#0284c7" stroke-width="0.5" stroke-dasharray="4 8" opacity="0.3" />

          {{-- Water Label --}}
          <text x="180" y="80" fill="#0369a1" font-size="18" font-style="italic" opacity="0.6" font-weight="bold">
            LAUT SERAM (SERAM SEA)
          </text>
          <text x="180" y="105" fill="#0284c7" font-size="12" opacity="0.5">
            Pesisir Utara Jazirah Leihitu · Maluku Tengah
          </text>

          {{-- Coral Reef Fringes --}}
          <path
            d="M 50,220 Q 220,180 420,200 T 700,210 Q 880,240 980,260 L 980,310 Q 750,260 500,250 Q 250,250 50,270 Z"
            fill="url(#reefGrad)"
          />

          {{-- Main Landmass of Morella Peninsula --}}
          <path
            d="M 0,260 Q 180,230 320,240 Q 420,210 520,230 Q 640,240 760,220 Q 880,250 1000,280 L 1000,600 L 0,600 Z"
            fill="url(#landGrad)"
            stroke="#10b981"
            stroke-width="2"
          />

          {{-- Mountain Ridge & Nutmeg Forests in the South --}}
          <path
            d="M 100,600 Q 260,390 420,440 Q 560,400 700,450 Q 850,380 980,600 Z"
            fill="url(#hillGrad)"
          />
          <path
            d="M 220,600 Q 360,460 500,500 Q 640,450 780,600 Z"
            fill="#065f46"
            opacity="0.25"
          />

          {{-- Main Coastal Road of Leihitu --}}
          <path
            d="M 0,310 Q 200,290 350,300 Q 500,290 650,295 Q 850,310 1000,340"
            fill="none"
            stroke="#f59e0b"
            stroke-width="4"
            stroke-dasharray="8 4"
          />

          {{-- Village Settlement Blocks --}}
          <rect x="460" y="310" width="80" height="40" rx="4" fill="#fbbf24" opacity="0.3" />
          <text x="500" y="335" fill="#92400e" font-size="11" font-weight="bold" text-anchor="middle">
            NEGERI MORELLA
          </text>

          {{-- Compass Rose --}}
          <g transform="translate(80, 80)">
            <circle cx="0" cy="0" r="26" fill="white" opacity="0.8" stroke="#cbd5e1" stroke-width="1" />
            <polygon points="0,-22 5,-4 0,0 -5,-4" fill="#ef4444" />
            <polygon points="0,22 5,4 0,0 -5,4" fill="#64748b" />
            <polygon points="-22,0 -4,-5 0,0 -4,5" fill="#64748b" />
            <polygon points="22,0 4,-5 0,0 4,5" fill="#64748b" />
            <text x="0" y="-25" fill="#ef4444" font-size="10" font-weight="bold" text-anchor="middle">U</text>
          </g>
        </svg>

        {{-- Interactive Marker Overlay (Positioned via Percentage) --}}
        <div class="absolute inset-0 pointer-events-none">
          <template x-for="marker in filteredMarkers" :key="marker.id">
            <div
              :style="{ left: marker.mapX + '%', top: marker.mapY + '%' }"
              class="absolute -translate-x-1/2 -translate-y-1/2 pointer-events-auto cursor-pointer group z-10"
              @click="selectedMarker = marker"
            >
              {{-- Pulsing ring for active pin --}}
              <span
                x-show="selectedMarker && selectedMarker.id === marker.id"
                class="absolute -inset-2 rounded-full bg-emerald-500/40 animate-ping"
              ></span>

              {{-- Marker Pin Button --}}
              <div
                :class="markerColor(marker.type) + (selectedMarker && selectedMarker.id === marker.id ? ' scale-125 ring-4 ring-white' : '')"
                class="relative w-8 h-8 rounded-full flex items-center justify-center shadow-lg transition-transform duration-200 group-hover:scale-125 ring-2"
              >
                <x-icon name="MapPin" class="w-4 h-4" />
              </div>

              {{-- Tooltip on hover --}}
              <div class="absolute left-1/2 bottom-full mb-1 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-stone-900 text-white text-[11px] px-2 py-1 rounded shadow-md pointer-events-none whitespace-nowrap font-medium z-30" x-text="marker.title"></div>
            </div>
          </template>
        </div>

        {{-- Map legend at bottom left --}}
        <div class="absolute bottom-3 left-3 bg-white/90 backdrop-blur-xs px-3 py-2 rounded-lg border border-stone-200 text-[10px] text-stone-600 space-y-1 shadow-xs hidden sm:block">
          <div class="font-semibold text-stone-800">
            <x-t id="Jalur Jalan:" en="Road Legend:" />
          </div>
          <div class="flex items-center gap-1.5">
            <span class="w-3.5 h-1 bg-amber-500 rounded" />
            <span>Jalan Utama Pesisir Leihitu</span>
          </div>
        </div>
      </div>

      {{-- Selected Marker Detail Card (Right Column) --}}
      <div class="p-5 sm:p-6 bg-white flex flex-col justify-between space-y-4">
        <template x-if="selectedMarker">
          <div class="space-y-4">
            <div class="space-y-1.5">
              <div class="flex items-center justify-between">
                <span :class="'text-[11px] font-semibold px-2 py-0.5 rounded-full border ' + markerBadgeBg(selectedMarker.type)" x-text="selectedMarker.categoryLabel"></span>
                <template x-if="selectedMarker.rating">
                  <span class="flex items-center gap-1 text-xs font-semibold text-amber-600">
                    <x-icon name="Star" class="w-3.5 h-3.5 fill-amber-400 text-amber-500" />
                    <span x-text="selectedMarker.rating"></span>
                  </span>
                </template>
              </div>

              <h3 class="font-serif text-xl font-bold text-stone-900" x-text="selectedMarker.title"></h3>

              <p class="text-xs text-stone-500 flex items-center gap-1">
                <x-icon name="MapPin" class="w-3.5 h-3.5 text-stone-400 shrink-0" />
                <span x-text="selectedMarker.location"></span>
              </p>
            </div>

            <div class="p-3.5 bg-stone-50 rounded-xl border border-stone-200 text-xs text-stone-700 leading-relaxed" x-text="selectedMarker.description"></div>

            <template x-if="selectedMarker.phone">
              <div class="flex items-center gap-2 text-xs text-stone-600 pt-1">
                <x-icon name="Phone" class="w-3.5 h-3.5 text-emerald-600" />
                <a
                  :href="'https://wa.me/' + selectedMarker.phone.replace(/[^0-9]/g, '')"
                  target="_blank"
                  rel="noreferrer"
                  class="hover:text-emerald-700 hover:underline"
                  x-text="selectedMarker.phone"
                ></a>
              </div>
            </template>

            {{-- Action Buttons --}}
            <div class="pt-2 space-y-2">
              <button
                type="button"
                @click="handleOpenLinkedDestination(selectedMarker)"
                class="w-full flex items-center justify-center gap-2 py-2.5 px-4 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-semibold transition-colors shadow-xs"
              >
                <x-icon name="Info" class="w-4 h-4" />
                <span x-text="$store.ui.lang === 'id' ? 'Lihat Informasi Lengkap' : 'View Full Details'"></span>
              </button>

              <button
                type="button"
                @click="$store.modals.openQr({ title: selectedMarker.title, subtitle: selectedMarker.location, code: selectedMarker.id, type: 'marker' })"
                class="w-full flex items-center justify-center gap-2 py-2 px-4 bg-stone-100 hover:bg-stone-200 text-stone-800 rounded-xl text-xs font-medium transition-colors"
              >
                <span x-text="$store.ui.lang === 'id' ? '📱 Buat QR Code Lokasi' : '📱 Generate Spot QR'"></span>
              </button>
            </div>
          </div>
        </template>

        <template x-if="!selectedMarker">
          <div class="text-center py-12 text-stone-400 text-xs" x-text="$store.ui.lang === 'id' ? 'Klik salah satu pin marker pada peta untuk melihat detail.' : 'Select a pin marker on the map to view details.'"></div>
        </template>

        <div class="text-[11px] text-stone-400 border-t border-stone-100 pt-3">
          📍 Koordinat Geografis: Pesisir Utara Jazirah Leihitu (-3.58°, 128.08°)
        </div>
      </div>
    </div>
  </div>

  {{-- How to Reach Morella (Panduan Rute Wisatawan) --}}
  <div class="bg-stone-50 rounded-2xl border border-stone-200 p-6 sm:p-8 space-y-6">
    <div class="space-y-1">
      <span class="text-xs font-semibold uppercase tracking-widest text-emerald-800">
        <x-t id="Aksesibilitas & Petunjuk Perjalanan" en="Travel Guide & Accessibility" />
      </span>
      <h3 class="font-serif text-xl font-bold text-stone-900">
        <x-t id="Cara Menuju Desa Morella dari Kota Ambon" en="How to Reach Morella from Ambon City" />
      </h3>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs text-stone-700">
      <div class="p-4 bg-white rounded-xl border border-stone-200 space-y-2">
        <div class="flex items-center gap-2 font-semibold text-stone-900 text-sm">
          <x-icon name="Car" class="w-4 h-4 text-emerald-700" />
          <span><x-t id="Kendaraan Pribadi / Rental" en="Private Car / Rental" /></span>
        </div>
        <p class="leading-relaxed">
          <x-t
            id="Jarak tempuh sekitar 32 km dari pusat Kota Ambon melalui jalur Hitu-Leihitu (± 45–60 menit). Jalan beraspal mulus dengan suguhan panorama perbukitan hijau dan pesisir teluk."
            en="Approximately 32 km from Ambon City Center via the Hitu-Leihitu coastal route (45–60 mins) along scenic asphalt roads."
          />
        </p>
      </div>

      <div class="p-4 bg-white rounded-xl border border-stone-200 space-y-2">
        <div class="flex items-center gap-2 font-semibold text-stone-900 text-sm">
          <x-icon name="Bike" class="w-4 h-4 text-emerald-700" />
          <span><x-t id="Sepeda Motor / Touring" en="Motorcycle Touring" /></span>
        </div>
        <p class="leading-relaxed">
          <x-t
            id="Sangat direkomendasikan bagi penikmat touring. Angin pesisir sejuk, banyak spot singgah foto di tepi tebing, dan mudah bermanuver ke jalan setapak pantai."
            en="Ideal for scenic motorcycling with refreshing sea breezes and multiple cliffside photography lookout points."
          />
        </p>
      </div>

      <div class="p-4 bg-white rounded-xl border border-stone-200 space-y-2">
        <div class="flex items-center gap-2 font-semibold text-stone-900 text-sm">
          <x-icon name="Compass" class="w-4 h-4 text-emerald-700" />
          <span><x-t id="Transportasi Umum / Angkot" en="Public Minibus (Angkot)" /></span>
        </div>
        <p class="leading-relaxed">
          <x-t
            id="Tersedia angkutan pedesaan trayek Ambon - Hitu - Morella dari Terminal Mardika Ambon. Tarif terjangkau (± Rp 15.000 - Rp 20.000 / orang)."
            en="Local transport minibuses depart regularly from Mardika Terminal Ambon towards Leihitu and Morella at nominal fares."
          />
        </p>
      </div>
    </div>
  </div>

</div>

<script>
  function mapView(data) {
    return {
      mapMarkers: data.mapMarkers,
      destinations: data.destinations,
      filterType: 'all',
      selectedMarker: data.mapMarkers.length > 0 ? data.mapMarkers[0] : null,
      get filteredMarkers() {
        return this.filterType === 'all'
          ? this.mapMarkers
          : this.mapMarkers.filter((m) => m.type === this.filterType);
      },
      markerColor(type) {
        switch (type) {
          case 'wisata': return 'bg-blue-600 text-white ring-blue-300';
          case 'umkm': return 'bg-emerald-600 text-white ring-emerald-300';
          case 'kuliner': return 'bg-amber-500 text-white ring-amber-200';
          case 'penginapan': return 'bg-purple-600 text-white ring-purple-300';
          case 'fasilitas': return 'bg-rose-600 text-white ring-rose-300';
          default: return 'bg-stone-700 text-white ring-stone-400';
        }
      },
      markerBadgeBg(type) {
        switch (type) {
          case 'wisata': return 'bg-blue-50 text-blue-700 border-blue-200';
          case 'umkm': return 'bg-emerald-50 text-emerald-700 border-emerald-200';
          case 'kuliner': return 'bg-amber-50 text-amber-800 border-amber-200';
          case 'penginapan': return 'bg-purple-50 text-purple-700 border-purple-200';
          case 'fasilitas': return 'bg-rose-50 text-rose-700 border-rose-200';
          default: return 'bg-stone-100 text-stone-700 border-stone-200';
        }
      },
      handleOpenLinkedDestination(marker) {
        const matched = this.destinations.find((d) => d.name.toLowerCase().includes(marker.title.toLowerCase()) || marker.title.toLowerCase().includes(d.name.toLowerCase()));
        if (matched) {
          $store.modals.openDestination(matched);
        }
      },
    };
  }
</script>
@endsection
