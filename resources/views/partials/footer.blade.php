@php
  $quickLinks = [
    ['labelId' => 'Destinasi Wisata Unggulan', 'labelEn' => 'Featured Destinations', 'route' => 'destinations'],
    ['labelId' => 'Pemesanan E-Tiket Wisata', 'labelEn' => 'E-Ticketing Booking', 'route' => 'tickets'],
    ['labelId' => 'Arsip Budaya & Tradisi Pukul Sapu', 'labelEn' => 'Culture & Pukul Sapu Archive', 'route' => 'culture'],
    ['labelId' => 'Katalog UMKM & Oleh-oleh', 'labelEn' => 'Local Artisan Crafts & Market', 'route' => 'umkm'],
    ['labelId' => 'Peta Interaktif Leihitu', 'labelEn' => 'Interactive Map of Leihitu', 'route' => 'map'],
    ['labelId' => 'Dashboard Pengelola Desa', 'labelEn' => 'Village Administration Dashboard', 'route' => 'admin'],
  ];

  $visitNow = \Illuminate\Support\Carbon::now('Asia/Jayapura');
  $visitMonthId = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'][$visitNow->month - 1] . ' ' . $visitNow->year;
  $visitMonthEn = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][$visitNow->month - 1] . ' ' . $visitNow->year;
@endphp

<footer class="bg-stone-950 text-stone-300 border-t border-stone-800">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">

      {{-- Column 1: Portal Identity --}}
      <div class="space-y-4">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-600 to-teal-800 flex items-center justify-center text-white shadow-md border border-emerald-500/30">
            <x-icon name="Compass" class="w-5 h-5 text-emerald-200" />
          </div>
          <span class="font-serif text-xl font-bold tracking-tight text-white">
            MORELA TOURISM
          </span>
        </div>
        <p class="text-xs text-stone-400 leading-relaxed">
          <x-t
            id="Portal Digital Pariwisata Desa Morela: Media promosi keindahan alam pesisir, cagar budaya sakral, serta etalase produk UMKM kreatif masyarakat Leihitu, Maluku Tengah."
            en="Digital Tourism Portal of Morela Village: Promoting pristine coastal landscapes, sacred cultural heritage, and artisanal creative local crafts of Leihitu, Central Maluku."
          />
        </p>
        <div class="text-xs text-stone-400 pt-2 space-y-1">
          <div class="flex items-center gap-2">
            <x-icon name="MapPin" class="w-3.5 h-3.5 text-emerald-500 shrink-0" />
            <span>{{ $contactInfo['address'] ?? '' }}</span>
          </div>
          <div class="flex items-center gap-2">
            <x-icon name="Phone" class="w-3.5 h-3.5 text-emerald-500 shrink-0" />
            <span>{{ $contactInfo['phone'] ?? '' }}</span>
          </div>
        </div>
      </div>

      {{-- Column 2: Program Pengabdian UNIDAR --}}
      <div class="space-y-4">
        <div class="flex items-center gap-2 text-sm font-semibold text-white uppercase tracking-wider">
          <x-icon name="Award" class="w-4 h-4 text-amber-400" />
          <span><x-t id="Program Pengabdian" en="Community Service" /></span>
        </div>
        <div class="p-3.5 rounded-xl bg-stone-900 border border-stone-800 space-y-2">
          <div class="text-xs font-semibold text-emerald-400">
            UNIVERSITAS DARUSSALAM AMBON
          </div>
          <p class="text-[11px] text-stone-300 leading-relaxed">
            <x-t
              id="Program Pengabdian Masyarakat Mahasiswa: &quot;Digitalisasi Potensi Pariwisata dan Ekonomi Kreatif Desa Morela&quot;."
              en="Student Community Service Program: &quot;Digitalization of Tourism & Creative Economy in Morela Village&quot;."
            />
          </p>
          <a
            href="{{ route('program') }}"
            class="text-xs text-amber-400 hover:text-amber-300 font-medium inline-flex items-center gap-1 pt-1"
          >
            <span><x-t id="Lihat Profil Tim & DPL" en="View Team & Advisors" /></span>
            <x-icon name="ExternalLink" class="w-3 h-3" />
          </a>
        </div>
      </div>

      {{-- Column 3: Navigasi Cepat --}}
      <div class="space-y-4">
        <div class="text-sm font-semibold text-white uppercase tracking-wider">
          <x-t id="Navigasi Pintar" en="Quick Navigation" />
        </div>
        <ul class="space-y-2 text-xs">
          @foreach ($quickLinks as $link)
            <li>
              <a href="{{ route($link['route']) }}" class="hover:text-emerald-400 transition-colors flex items-center gap-1.5">
                <span><x-t :id="$link['labelId']" :en="$link['labelEn']" /></span>
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      {{-- Column 4: Statistik & Informasi Kunjungan --}}
      <div class="space-y-4">
        <div class="text-sm font-semibold text-white uppercase tracking-wider">
          <x-t id="Statistik Portal" en="Portal Analytics" />
        </div>
        <div class="p-3.5 rounded-xl bg-stone-900 border border-stone-800 space-y-2">
          <div class="text-[11px] text-stone-400">
            <x-t :id="'Kunjungan Bulan Ini (' . $visitMonthId . ')'" :en="'Visits This Month (' . $visitMonthEn . ')'" />
          </div>
          <div class="font-mono text-2xl font-bold text-white tabular-nums tracking-tight">
            {{ number_format($webVisits ?? 0, 0, ',', '.') }}
          </div>
          <div class="w-full bg-stone-800 h-1.5 rounded-full overflow-hidden">
            <div class="bg-emerald-500 h-full w-[85%] rounded-full"></div>
          </div>
          <p class="text-[10px] text-stone-400">
            <x-t id="Tingkat kunjungan wisatawan meningkat 140%" en="Tourist inquiries up by 140%" />
          </p>
        </div>
      </div>
    </div>

    <div class="mt-12 pt-8 border-t border-stone-800/80 flex flex-col sm:flex-row items-center justify-between text-xs text-stone-400 gap-4">
      <p>
        © 2026 Pemerintah Negeri Morela &amp; Program Pengabdian Mahasiswa Universitas Darussalam Ambon. Hak Cipta Dilindungi.
      </p>
      <div class="flex items-center gap-4">
        <span>Kecamatan Leihitu, Maluku Tengah</span>
        <span>·</span>
        <span>Provinsi Maluku, Indonesia</span>
      </div>
    </div>
  </div>
</footer>
