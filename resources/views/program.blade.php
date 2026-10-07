@extends('layouts.app')

@php
$programPillars = [
    [
        'title' => 'Pilar 1: Digitalisasi Pariwisata & Media Web Portal',
        'desc' => 'Pembangunan website resmi terintegrasi "Negeri Morella", penyusunan katalog informasi destinasi, dan implementasi QR Code di titik-titik wisata desa.',
        'icon' => '🌐',
    ],
    [
        'title' => 'Pilar 2: Pemberdayaan & Rebranding Produk UMKM',
        'desc' => 'Peningkatan standardisasi kemasan higienis minyak kayu putih, pembuatan label resmi halua kenari dan rempah pala, serta pembukaan jalur pemasaran daring.',
        'icon' => '🛍️',
    ],
    [
        'title' => 'Pilar 3: Digitalisasi & Pelestarian Arsip Budaya Adat',
        'desc' => 'Pendokumentasian tradisi sakral Pukul Sapu, sejarah pembentukan negeri adat, wawancara tetua adat dan saniri negeri, serta penulisan narasi dwibahasa.',
        'icon' => '🏛️',
    ],
    [
        'title' => 'Pilar 4: Penguatan Kapasitas Pokdarwis & Sadar Lingkungan',
        'desc' => 'Lokakarya sadar wisata (sapta pesona) bagi pemuda desa, aksi bersih pantai di Pantai Lubang Buaya, dan pembuatan papan himbauan ramah lingkungan.',
        'icon' => '🌿',
    ],
];

$outcomes = [
    ['metric' => '1 Portal', 'label' => 'Website Pariwisata Resmi Desa Morela Berbasis Web & Mobile'],
    ['metric' => '100% QR Code', 'label' => 'Destinasi Wisata Dilengkapi Kode Respons Cepat Pindai'],
    ['metric' => '6 Produk', 'label' => 'UMKM Didampingi Desain Kemasan & Pemasaran Online'],
    ['metric' => '30+ Pemuda', 'label' => 'Tergabung dalam Pelatihan Sadar Wisata Digital Pokdarwis'],
];
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-16 space-y-16">

    {{-- Hero Header --}}
    <div class="space-y-4 max-w-3xl">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-semibold uppercase tracking-wider">
            <x-icon name="GraduationCap" class="w-4 h-4 text-emerald-700" />
            <span>PROGRAM PENGABDIAN MASYARAKAT</span>
        </div>

        <h1 class="font-serif text-3xl sm:text-5xl font-bold text-stone-900 tracking-tight leading-tight">
            Universitas Darussalam Ambon
        </h1>

        <div class="p-4 rounded-xl bg-stone-100 border-l-4 border-emerald-600 text-stone-800 text-sm font-medium">
            &ldquo;Digitalisasi Potensi Pariwisata dan Ekonomi Kreatif Desa Morela, Kecamatan Leihitu, Kabupaten Maluku Tengah&rdquo;
        </div>

        <p class="text-xs sm:text-sm text-stone-600 leading-relaxed max-w-prose">
            Program kolaborasi terpadu antara civitas akademika Universitas Darussalam Ambon bersama Pemerintah Negeri Morela, Lembaga Adat Saniri Negeri, dan Kelompok Sadar Wisata (Pokdarwis) guna mengoptimalkan potensi bahari dan nilai luhur budaya Morela.
        </p>
    </div>

    {{-- Profil Universitas & Mitra Desa --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <div class="p-6 sm:p-8 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-4">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-xl bg-emerald-50 text-emerald-700">
                    <x-icon name="Building" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="font-serif text-lg font-bold text-stone-900">
                        Universitas Darussalam Ambon (UNIDAR)
                    </h3>
                    <p class="text-xs text-stone-500">
                        Lembaga Pendidikan Tinggi Berbasis Keilmuan & Pengabdian di Maluku
                    </p>
                </div>
            </div>
            <p class="text-xs text-stone-600 leading-relaxed">
                Sebagai perguruan tinggi terkemuka di Maluku, UNIDAR Ambon senantiasa berkomitmen menjalankan Tri Dharma Perguruan Tinggi, khususnya pengabdian masyarakat yang berorientasi pada pemecahan masalah riil pedesaan pesisir dan pulau-pulau kecil.
            </p>
        </div>

        <div class="p-6 sm:p-8 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-4">
            <div class="flex items-center gap-3">
                <div class="p-3 rounded-xl bg-amber-50 text-amber-700">
                    <x-icon name="HeartHandshake" class="w-6 h-6" />
                </div>
                <div>
                    <h3 class="font-serif text-lg font-bold text-stone-900">
                        Pemerintah Negeri Adat Morela
                    </h3>
                    <p class="text-xs text-stone-500">
                        Mitra Strategis & Tuan Rumah Program Pengabdian
                    </p>
                </div>
            </div>
            <p class="text-xs text-stone-600 leading-relaxed">
                Pemerintah Negeri Morela bersama Bapa Raja, Saniri Negeri, dan Pokdarwis memberikan dukungan penuh terhadap proses riset lapangan, wawancara adat, hingga keberlanjutan pemeliharaan infrastruktur portal pariwisata.
            </p>
        </div>
    </div>

    {{-- Program Kerja (4 Pilar) --}}
    <div class="space-y-6">
        <div>
            <div class="text-xs font-semibold uppercase tracking-widest text-emerald-700">
                Fokus Rencana Aksi
            </div>
            <h2 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900">
                4 Pilar Program Kerja Pengabdian
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($programPillars as $p)
            <div class="p-6 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-3">
                <div class="text-3xl">{{ $p['icon'] }}</div>
                <h4 class="font-serif text-lg font-bold text-stone-900">
                    {{ $p['title'] }}
                </h4>
                <p class="text-xs text-stone-600 leading-relaxed">
                    {{ $p['desc'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Hasil Kegiatan & Dampak Capaian --}}
    <div class="p-8 sm:p-10 rounded-3xl bg-stone-900 text-white space-y-8">
        <div class="space-y-2">
            <div class="text-xs font-semibold text-amber-400 uppercase tracking-widest">
                Milestone & Deliverables
            </div>
            <h3 class="font-serif text-2xl sm:text-3xl font-bold text-white">
                Hasil Capaian Program Pengabdian
            </h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach ($outcomes as $o)
            <div class="p-5 rounded-2xl bg-stone-800/80 border border-stone-700/60 space-y-2">
                <div class="font-mono text-2xl font-bold text-emerald-400 tabular-nums">
                    {{ $o['metric'] }}
                </div>
                <p class="text-xs text-stone-300 leading-relaxed">
                    {{ $o['label'] }}
                </p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Tim Pengabdian (Dosen Pembimbing & Mahasiswa) --}}
    <div class="space-y-6">
        <div>
            <div class="text-xs font-semibold uppercase tracking-widest text-emerald-700">
                Struktur Personalia
            </div>
            <h2 class="font-serif text-2xl sm:text-3xl font-bold text-stone-900">
                Dosen Pembimbing & Tim Mahasiswa UNIDAR
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($team as $member)
            <div class="p-6 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-4 flex flex-col justify-between">
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <img
                            src="{{ $member['photoUrl'] }}"
                            alt="{{ $member['name'] }}"
                            referrerpolicy="no-referrer"
                            class="w-14 h-14 rounded-full object-cover border-2 border-emerald-500"
                        />
                        <div>
                            <h4 class="font-serif font-bold text-stone-900 text-sm">
                                {{ $member['name'] }}
                            </h4>
                            <div class="text-xs text-emerald-700 font-medium">
                                {{ $member['role'] }}
                            </div>
                            <div class="text-[11px] text-stone-400">
                                {{ $member['department'] }}
                            </div>
                        </div>
                    </div>

                    <p class="text-xs text-stone-600 leading-relaxed">
                        {{ $member['bio'] }}
                    </p>
                </div>

                <div class="pt-3 border-t border-stone-100 text-[11px] text-stone-500">
                    <strong class="text-stone-700">Peran:</strong> {{ $member['contribution'] }}
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
