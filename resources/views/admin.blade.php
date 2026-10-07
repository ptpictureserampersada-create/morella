@extends('layouts.app')

@php
$adminData = [
    'destinations' => $destinations,
    'umkm' => $umkm,
    'news' => $news,
    'events' => $events,
    'culture' => $culture,
    'gallery' => $gallery,
    'team' => $team,
    'bookings' => $bookings,
    'paymentSettings' => $paymentSettings,
    'heroSliders' => $heroSliders,
    'contactInfo' => $contactInfo,
    'heroText' => $heroText,
    'webVisits' => $webVisits,
];
@endphp

@section('content')
<div x-data="adminView({{ Js::from($adminData) }}, {{ $isAdminAuthenticated ? 'true' : 'false' }})">

  {{-- GERBANG AKSES (belum login) --}}
  <template x-if="!isAuthenticated">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 flex items-center justify-center min-h-[60vh]">
      <div class="bg-white p-8 rounded-3xl border border-stone-200 shadow-xl max-w-sm w-full text-center space-y-6">
        <div class="w-16 h-16 mx-auto bg-stone-100 rounded-2xl flex items-center justify-center">
          <x-icon name="Shield" class="w-8 h-8 text-stone-700" />
        </div>
        <div>
          <h2 class="font-serif text-2xl font-bold text-stone-900">Akses Terkunci</h2>
          <p class="text-xs text-stone-500 mt-2">Masukkan username dan kata sandi untuk mengakses portal administrasi dan pengelola data.</p>
        </div>

        <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
          @csrf
          <input
            type="text"
            name="username"
            value="{{ old('username') }}"
            placeholder="Username..."
            required
            autofocus
            class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-center"
          />
          <input
            type="password"
            name="password"
            placeholder="Kata Sandi..."
            required
            class="w-full px-4 py-3 rounded-xl border border-stone-300 text-sm focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 text-center"
          />
          @error('username')
            <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
          @enderror
          @error('password')
            <p class="text-xs text-red-600 font-medium">{{ $message }}</p>
          @enderror
          <button
            type="submit"
            class="w-full py-3 bg-emerald-700 hover:bg-emerald-600 text-white rounded-xl text-sm font-semibold transition-colors"
          >
            Masuk Dashboard
          </button>
        </form>
      </div>
    </div>
  </template>

  {{-- DASHBOARD ADMIN (sudah login) --}}
  <template x-if="isAuthenticated">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 space-y-8">

      {{-- Admin Top Header & Role Switcher --}}
      <div class="p-6 bg-stone-900 text-white rounded-3xl border border-stone-800 shadow-xl flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
        <div class="space-y-1">
          <div class="flex items-center gap-2 text-xs font-semibold text-emerald-400 uppercase tracking-widest">
            <x-icon name="Shield" class="w-4 h-4 text-emerald-400" />
            <span>PORTAL ADMINISTRASI & PENGELOLA</span>
          </div>
          <h1 class="font-serif text-2xl sm:text-3xl font-bold text-white tracking-tight">
            Dashboard Negeri Morella
          </h1>
          <p class="text-xs text-stone-400">
            Kelola konten destinasi wisata, katalog UMKM, arsip kebudayaan, agenda desa, dan data pengabdian mahasiswa.
          </p>
        </div>

        <div class="flex flex-col gap-2 self-stretch md:self-auto">
          <div class="p-3 bg-stone-800/90 rounded-2xl border border-stone-700/80 space-y-1.5">
            <div class="text-[10px] text-stone-400 uppercase font-semibold">
              Pilih Mode Akses Pengguna (Role):
            </div>
            <div class="flex items-center gap-1 text-xs">
              <button
                type="button"
                @click="$store.ui.setRole('admin_desa')"
                :class="$store.ui.role === 'admin_desa' ? 'bg-emerald-600 text-white shadow-xs' : 'text-stone-300 hover:text-white hover:bg-stone-700'"
                class="px-3 py-1.5 rounded-lg font-medium transition-all"
              >
                Admin Desa
              </button>
              <button
                type="button"
                @click="$store.ui.setRole('mahasiswa')"
                :class="$store.ui.role === 'mahasiswa' ? 'bg-emerald-600 text-white shadow-xs' : 'text-stone-300 hover:text-white hover:bg-stone-700'"
                class="px-3 py-1.5 rounded-lg font-medium transition-all"
              >
                Mahasiswa UNIDAR
              </button>
              <button
                type="button"
                @click="$store.ui.setRole('pengunjung')"
                :class="$store.ui.role === 'pengunjung' ? 'bg-emerald-600 text-white shadow-xs' : 'text-stone-300 hover:text-white hover:bg-stone-700'"
                class="px-3 py-1.5 rounded-lg font-medium transition-all"
              >
                Tinjau Pengunjung
              </button>
            </div>
          </div>

          <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button
              type="submit"
              class="w-full flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-red-600 hover:bg-red-500 text-white text-xs font-semibold transition-colors shadow-xs"
            >
              <x-icon name="LogOut" class="w-3.5 h-3.5" />
              <span>Keluar (Logout)</span>
            </button>
          </form>
        </div>
      </div>

      {{-- Navigation Tabs --}}
      <div class="flex items-center gap-1 sm:gap-2 overflow-x-auto pb-2 scrollbar-none border-b border-stone-200">
        <button
          type="button"
          @click="setTab('overview')"
          :class="activeTab === 'overview' ? 'bg-stone-900 text-white shadow-xs' : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-100'"
          class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors"
        >
          📊 Dashboard &amp; Statistik
        </button>
        <button
          type="button"
          @click="setTab('destinasi')"
          :class="activeTab === 'destinasi' ? 'bg-stone-900 text-white shadow-xs' : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-100'"
          class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors"
        >
          🏝️ Destinasi Wisata
        </button>
        <button
          type="button"
          @click="setTab('tiket')"
          :class="activeTab === 'tiket' ? 'bg-stone-900 text-white shadow-xs' : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-100'"
          class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors"
        >
          🎫 E-Tiket &amp; Retribusi
        </button>
        <button
          type="button"
          @click="setTab('umkm')"
          :class="activeTab === 'umkm' ? 'bg-stone-900 text-white shadow-xs' : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-100'"
          class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors"
        >
          🛍️ UMKM &amp; Produk
        </button>
        <button
          type="button"
          @click="setTab('berita')"
          :class="activeTab === 'berita' ? 'bg-stone-900 text-white shadow-xs' : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-100'"
          class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors"
        >
          📰 Warta &amp; Berita
        </button>
        <button
          type="button"
          @click="setTab('agenda')"
          :class="activeTab === 'agenda' ? 'bg-stone-900 text-white shadow-xs' : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-100'"
          class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors"
        >
          📅 Agenda &amp; Event
        </button>
        <button
          type="button"
          @click="setTab('budaya')"
          :class="activeTab === 'budaya' ? 'bg-stone-900 text-white shadow-xs' : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-100'"
          class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors"
        >
          🏛️ Budaya &amp; Tradisi
        </button>
        <button
          type="button"
          @click="setTab('galeri')"
          :class="activeTab === 'galeri' ? 'bg-stone-900 text-white shadow-xs' : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-100'"
          class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors"
        >
          📸 Galeri
        </button>
        <button
          type="button"
          @click="setTab('tim')"
          :class="activeTab === 'tim' ? 'bg-stone-900 text-white shadow-xs' : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-100'"
          class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors"
        >
          👥 Tim Pengabdian
        </button>
        <button
          type="button"
          @click="setTab('pengaturan')"
          :class="activeTab === 'pengaturan' ? 'bg-stone-900 text-white shadow-xs' : 'bg-white text-stone-600 border border-stone-200 hover:bg-stone-100'"
          class="px-3.5 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition-colors"
        >
          ⚙️ Pengaturan
        </button>
      </div>

      {{-- TAB CONTENT: 📊 OVERVIEW & STATISTIK --}}
      <div x-show="activeTab === 'overview'" class="space-y-8">
        {{-- Top 4 KPI Metrics --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-1">
            <div class="text-[11px] font-semibold uppercase text-stone-500">TOTAL DESTINASI</div>
            <div class="font-mono text-3xl font-bold text-stone-900 tabular-nums" x-text="destinations.length"></div>
            <div class="text-[11px] text-emerald-700 font-medium">100% Aktif &amp; Ber-QR Code</div>
          </div>

          <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-1">
            <div class="text-[11px] font-semibold uppercase text-stone-500">TOTAL UMKM</div>
            <div class="font-mono text-3xl font-bold text-stone-900 tabular-nums" x-text="umkmProducts.length"></div>
            <div class="text-[11px] text-emerald-700 font-medium">Minyak Kayu Putih, Kenari, Pala</div>
          </div>

          <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-1">
            <div class="text-[11px] font-semibold uppercase text-stone-500">TOTAL BERITA</div>
            <div class="font-mono text-3xl font-bold text-stone-900 tabular-nums" x-text="news.length"></div>
            <div class="text-[11px] text-stone-500 font-medium">Artikel Warta Desa &amp; KKN</div>
          </div>

          <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-1">
            <div class="text-[11px] font-semibold uppercase text-stone-500">TOTAL GALERI</div>
            <div class="font-mono text-3xl font-bold text-stone-900 tabular-nums" x-text="gallery.length"></div>
            <div class="text-[11px] text-stone-500 font-medium">Foto Terkurasi Desa</div>
          </div>
        </div>

        {{-- Visitor Traffic Chart & Analysis --}}
        <div class="p-6 sm:p-8 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-6">
          <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="space-y-1">
              <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-emerald-700">
                <x-icon name="BarChart3" class="w-4 h-4 text-emerald-700" />
                <span>KUNJUNGAN WEBSITE PORTAL</span>
              </div>
              <h3 class="font-serif text-xl font-bold text-stone-900">
                Statistik Trafik &amp; Penjelajahan Wisatawan
              </h3>
            </div>

            @php
              $visitMonthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
              $visitNow = \Illuminate\Support\Carbon::now('Asia/Jayapura');
              $visitChart = $visitChart ?? [];
              $visitMax = max(1, ...array_column($visitChart, 'count'));
            @endphp
            <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-xl text-right">
              <div class="text-[10px] text-emerald-800 font-semibold uppercase">{{ $visitMonthNames[$visitNow->month - 1] }} {{ $visitNow->year }}</div>
              <div class="font-mono text-2xl font-bold text-emerald-900 tabular-nums">
                <span x-text="webVisits.toLocaleString('id-ID')"></span> Hits
              </div>
            </div>
          </div>

          {{-- ASCII-styled & Bar visualization --}}
          <div class="space-y-3 p-4 bg-stone-900 text-stone-100 rounded-xl font-mono text-xs overflow-x-auto">
            <div class="text-stone-400">=== STATISTIK KUNJUNGAN 3 BULAN TERAKHIR ===</div>
            @foreach ($visitChart as $row)
              @php
                $rowLabel = $visitMonthNames[(int) substr($row['month'], 5, 2) - 1] . ' ' . substr($row['month'], 0, 4);
                $rowWidth = (int) round($row['count'] / $visitMax * 100);
                $isCurrentMonth = $loop->last;
              @endphp
              <div class="flex items-center gap-3 {{ $isCurrentMonth ? 'font-bold text-emerald-300' : '' }}">
                <span class="w-24 {{ $isCurrentMonth ? '' : 'text-stone-400' }}">{{ $rowLabel }}</span>
                <div class="flex-1 bg-stone-800 h-4 rounded overflow-hidden">
                  <div class="{{ $isCurrentMonth ? 'bg-emerald-400' : 'bg-emerald-500' }} h-full" style="width: {{ $rowWidth }}%"></div>
                </div>
                <span class="w-16 text-right" @if ($isCurrentMonth) x-text="webVisits.toLocaleString('id-ID')" @endif>{{ number_format($row['count'], 0, ',', '.') }}</span>
              </div>
            @endforeach
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs text-stone-600">
            <div class="p-3 bg-stone-50 rounded-xl border border-stone-200">
              <strong class="text-stone-900">Halaman Terpopuler:</strong> Pantai Lubang Buaya Morella (48% views)
            </div>
            <div class="p-3 bg-stone-50 rounded-xl border border-stone-200">
              <strong class="text-stone-900">Pencarian Terbanyak:</strong> "Tradisi Pukul Sapu" &amp; "Minyak Kayu Putih"
            </div>
            <div class="p-3 bg-stone-50 rounded-xl border border-stone-200">
              <strong class="text-stone-900">Perangkat Pengunjung:</strong> Mobile Android 82%, Desktop 18%
            </div>
          </div>
        </div>
      </div>

      {{-- TAB CONTENT: 🏝️ DESTINASI WISATA CRUD --}}
      <div x-show="activeTab === 'destinasi'" class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h3 class="font-serif text-xl font-bold text-stone-900">
              Kelola Destinasi Wisata
            </h3>
            <p class="text-xs text-stone-500">
              Tambah, perbarui informasi tiket &amp; jam buka, buat QR Code, atau nonaktifkan destinasi.
            </p>
          </div>

          <button
            type="button"
            @click="isAddDestModalOpen = true"
            class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-semibold flex items-center gap-1.5 self-start sm:self-auto"
          >
            <x-icon name="Plus" class="w-4 h-4" />
            <span>Tambah Destinasi Baru</span>
          </button>
        </div>

        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-700">
              <thead class="bg-stone-50 border-b border-stone-200 text-[11px] font-semibold uppercase text-stone-600">
                <tr>
                  <th class="px-5 py-3.5">Destinasi</th>
                  <th class="px-5 py-3.5">Kategori</th>
                  <th class="px-5 py-3.5">Tiket</th>
                  <th class="px-5 py-3.5">Status</th>
                  <th class="px-5 py-3.5">QR Code</th>
                  <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-stone-200">
                <template x-for="dest in destinations" :key="dest.id">
                  <tr class="hover:bg-stone-50/80 transition-colors">
                    <td class="px-5 py-3.5 font-semibold text-stone-900 flex items-center gap-3">
                      <img
                        :src="dest.imageUrl"
                        :alt="dest.name"
                        class="w-10 h-10 rounded-lg object-cover bg-stone-100"
                        onerror="this.style.display='none'"
                      />
                      <div>
                        <div x-text="dest.name"></div>
                        <div class="text-[10px] text-stone-400 font-normal truncate max-w-[200px]" x-text="dest.location"></div>
                      </div>
                    </td>
                    <td class="px-5 py-3.5 capitalize" x-text="destCat(dest.category)"></td>
                    <td class="px-5 py-3.5 font-mono" x-text="dest.ticketPrice"></td>
                    <td class="px-5 py-3.5">
                      <span
                        class="px-2 py-0.5 rounded-full text-[10px] font-semibold"
                        :class="dest.published ? 'bg-emerald-50 text-emerald-700' : 'bg-stone-100 text-stone-600'"
                        x-text="dest.published ? 'Publish' : 'Draft'"
                      ></span>
                    </td>
                    <td class="px-5 py-3.5">
                      <button
                        type="button"
                        @click="$store.modals.openQr({ title: dest.name, subtitle: dest.location, code: dest.slug, type: 'destinasi' })"
                        class="p-1.5 bg-stone-100 hover:bg-stone-200 rounded-lg text-emerald-700"
                        title="Lihat QR Code"
                      >
                        <x-icon name="QrCode" class="w-4 h-4" />
                      </button>
                    </td>
                    <td class="px-5 py-3.5 text-right space-x-2">
                      <form method="POST" :action="entityUrl('destinasi', dest.id, 'perbarui')" class="inline">
                        @csrf
                        <input type="hidden" name="published" :value="dest.published ? '0' : '1'" />
                        <button
                          type="submit"
                          class="px-2.5 py-1 text-[11px] rounded bg-stone-100 hover:bg-stone-200 font-medium"
                          x-text="dest.published ? 'Sembunyikan' : 'Tayangkan'"
                        ></button>
                      </form>
                      <form
                        method="POST"
                        :action="entityUrl('destinasi', dest.id, 'hapus')"
                        class="inline"
                        @submit="confirmSubmit($event, 'Yakin ingin menghapus ' + dest.name + '?')"
                      >
                        @csrf
                        <button type="submit" class="p-1 text-rose-600 hover:text-rose-800" title="Hapus Destinasi">
                          <x-icon name="Trash2" class="w-4 h-4" />
                        </button>
                      </form>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- TAB CONTENT: 🎫 E-TIKET & RETRIBUSI CRUD --}}
      <div x-show="activeTab === 'tiket'" class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h3 class="font-serif text-xl font-bold text-stone-900">
              Manajemen E-Tiket &amp; Retribusi Desa
            </h3>
            <p class="text-xs text-stone-500">
              Pantau pendapatan tiket masuk, validasi check-in pengunjung di pintu gerbang, dan kelola status pembayaran.
            </p>
          </div>

          <button
            type="button"
            @click="goTickets()"
            class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-semibold flex items-center gap-1.5 self-start sm:self-auto"
          >
            <x-icon name="Plus" class="w-4 h-4" />
            <span>Buka Formulir Pemesanan Tiket</span>
          </button>
        </div>

        {{-- Ticket Financial Summary Cards --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
          <div class="p-4 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-1">
            <div class="text-[10px] uppercase font-semibold text-stone-500">TOTAL PENDAPATAN</div>
            <div class="font-mono text-xl font-bold text-emerald-800 tabular-nums">
              <span x-text="'Rp ' + fmt(totalRevenue)"></span>
            </div>
            <div class="text-[10px] text-stone-400">Kas Pokdarwis &amp; Kebersihan</div>
          </div>

          <div class="p-4 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-1">
            <div class="text-[10px] uppercase font-semibold text-stone-500">TIKET TERJUAL</div>
            <div class="font-mono text-xl font-bold text-stone-900 tabular-nums" x-text="bookings.length"></div>
            <div class="text-[10px] text-stone-400">Total Transaksi Masuk</div>
          </div>

          <div class="p-4 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-1">
            <div class="text-[10px] uppercase font-semibold text-stone-500">SUDAH CHECK-IN</div>
            <div class="font-mono text-xl font-bold text-blue-700 tabular-nums" x-text="countStatus('checked_in')"></div>
            <div class="text-[10px] text-stone-400">Pengunjung di Lokasi</div>
          </div>

          <div class="p-4 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-1">
            <div class="text-[10px] uppercase font-semibold text-stone-500">BELUM LUNAS / ON-SITE</div>
            <div class="font-mono text-xl font-bold text-amber-600 tabular-nums" x-text="countStatus('pending')"></div>
            <div class="text-[10px] text-stone-400">Menunggu Bayar di Loket</div>
          </div>
        </div>

        {{-- Quick Check-In Barcode Scanner Simulation --}}
        <div class="p-5 bg-stone-900 text-white rounded-2xl border border-stone-800 space-y-3">
          <div class="flex items-center gap-2 text-xs font-semibold text-emerald-400 uppercase tracking-wide">
            <x-icon name="QrCode" class="w-4 h-4" />
            <span>Validasi Check-in Tiket Pengunjung (Pintu Masuk)</span>
          </div>

          <div class="flex flex-col sm:flex-row gap-2">
            <input
              type="text"
              x-model="checkInCode"
              placeholder="Pindai QR / Ketik Kode Booking (Contoh: MOR-2609-8812)..."
              class="flex-1 px-4 py-2 bg-stone-800 border border-stone-700 rounded-xl text-xs font-mono text-white placeholder-stone-500 focus:outline-none focus:border-emerald-500"
            />
            <button
              type="button"
              @click="doCheckIn()"
              class="px-5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-semibold rounded-xl"
            >
              Validasi Check-In
            </button>
          </div>

          <template x-if="checkInMessage">
            <div
              class="p-3 rounded-xl text-xs font-medium"
              :class="checkInSuccess ? 'bg-emerald-950 border border-emerald-500 text-emerald-200' : 'bg-rose-950 border border-rose-500 text-rose-200'"
              x-text="checkInMessage"
            ></div>
          </template>
        </div>

        {{-- Payment Settings --}}
        <div class="p-5 bg-white rounded-2xl border border-stone-200 shadow-xs space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <h4 class="font-serif font-bold text-stone-900 text-base">Pengaturan Rekening &amp; QRIS</h4>
              <p class="text-xs text-stone-500">Nomor rekening tujuan pembayaran tiket masuk</p>
            </div>
            <button
              type="button"
              @click="togglePayment()"
              class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors"
              :class="isEditingPayment ? 'bg-emerald-700 hover:bg-emerald-800 text-white' : 'bg-stone-100 hover:bg-stone-200 text-stone-800'"
              x-text="isEditingPayment ? 'Simpan Pengaturan' : 'Edit Rekening'"
            ></button>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-stone-700 mb-1">Rekening BCA</label>
              <input
                type="text"
                :value="isEditingPayment ? tempPaymentSettings.rekeningBCA : paymentSettings.rekeningBCA"
                @input="tempPaymentSettings = { ...tempPaymentSettings, rekeningBCA: $event.target.value }"
                :disabled="!isEditingPayment"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-stone-700 mb-1">Rekening Mandiri</label>
              <input
                type="text"
                :value="isEditingPayment ? tempPaymentSettings.rekeningMandiri : paymentSettings.rekeningMandiri"
                @input="tempPaymentSettings = { ...tempPaymentSettings, rekeningMandiri: $event.target.value }"
                :disabled="!isEditingPayment"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-stone-700 mb-1">Rekening Bank Maluku</label>
              <input
                type="text"
                :value="isEditingPayment ? tempPaymentSettings.rekeningMaluku : paymentSettings.rekeningMaluku"
                @input="tempPaymentSettings = { ...tempPaymentSettings, rekeningMaluku: $event.target.value }"
                :disabled="!isEditingPayment"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-stone-700 mb-1">Link / URL Gambar QRIS</label>
              <input
                type="text"
                :value="isEditingPayment ? tempPaymentSettings.qrisUrl : paymentSettings.qrisUrl"
                @input="tempPaymentSettings = { ...tempPaymentSettings, qrisUrl: $event.target.value }"
                :disabled="!isEditingPayment"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              />
            </div>
          </div>
        </div>

        {{-- Bookings Table --}}
        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-700">
              <thead class="bg-stone-50 border-b border-stone-200 text-[11px] font-semibold uppercase text-stone-600">
                <tr>
                  <th class="px-5 py-3.5">Kode Booking</th>
                  <th class="px-5 py-3.5">Destinasi</th>
                  <th class="px-5 py-3.5">Pengunjung</th>
                  <th class="px-5 py-3.5">Tanggal</th>
                  <th class="px-5 py-3.5">Nominal</th>
                  <th class="px-5 py-3.5">Metode Bayar</th>
                  <th class="px-5 py-3.5">Status</th>
                  <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-stone-200">
                <template x-for="b in bookings" :key="b.id">
                  <tr class="hover:bg-stone-50/80 transition-colors">
                    <td class="px-5 py-3.5 font-mono font-bold text-stone-900" x-text="b.bookingCode"></td>
                    <td class="px-5 py-3.5 font-semibold text-stone-800" x-text="b.destinationName"></td>
                    <td class="px-5 py-3.5">
                      <div class="font-medium text-stone-900" x-text="b.visitorName"></div>
                      <div class="text-[10px] text-stone-400 font-mono" x-text="b.visitorPhone"></div>
                    </td>
                    <td class="px-5 py-3.5 font-mono text-stone-600" x-text="b.visitDate"></td>
                    <td class="px-5 py-3.5 font-mono font-bold text-emerald-800" x-text="'Rp ' + fmt(b.totalAmount)"></td>
                    <td class="px-5 py-3.5 uppercase font-mono text-[11px]" x-text="(b.paymentMethod || '').replace('_', ' ')"></td>
                    <td class="px-5 py-3.5">
                      <span
                        class="px-2 py-0.5 rounded-full text-[10px] font-semibold uppercase"
                        :class="statusClass(b.paymentStatus)"
                        x-text="statusLabel(b.paymentStatus)"
                      ></span>
                    </td>
                    <td class="px-5 py-3.5 text-right space-x-1.5 whitespace-nowrap">
                      <template x-if="b.paymentStatus === 'pending'">
                        <form method="POST" :action="entityUrl('tiket', b.id, 'status')" class="inline">
                          @csrf
                          <input type="hidden" name="status" value="paid" />
                          <button type="submit" class="px-2 py-1 bg-emerald-700 text-white rounded text-[11px] font-medium" title="Tandai Sudah Bayar">
                            Set Lunas
                          </button>
                        </form>
                      </template>
                      <template x-if="b.paymentStatus === 'paid'">
                        <form method="POST" :action="entityUrl('tiket', b.id, 'status')" class="inline">
                          @csrf
                          <input type="hidden" name="status" value="checked_in" />
                          <button type="submit" class="px-2 py-1 bg-blue-700 text-white rounded text-[11px] font-medium" title="Konfirmasi Check-In">
                            Check-In
                          </button>
                        </form>
                      </template>
                      <button
                        type="button"
                        @click="$store.modals.openTicket(b)"
                        class="p-1 text-stone-600 hover:text-stone-900"
                        title="Cetak E-Tiket"
                      >
                        <x-icon name="Printer" class="w-3.5 h-3.5" />
                      </button>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- TAB CONTENT: 🛍️ UMKM CRUD --}}
      <div x-show="activeTab === 'umkm'" class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h3 class="font-serif text-xl font-bold text-stone-900">
              Kelola Produk UMKM &amp; Ekonomi Kreatif
            </h3>
            <p class="text-xs text-stone-500">
              Daftarkan produk olahan minyak kayu putih, kenari, sambal, kerajinan tangan, dan kontak penjual.
            </p>
          </div>

          <button
            type="button"
            @click="isAddUMKMModalOpen = true"
            class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-semibold flex items-center gap-1.5 self-start sm:self-auto"
          >
            <x-icon name="Plus" class="w-4 h-4" />
            <span>Tambah Produk UMKM</span>
          </button>
        </div>

        <div class="bg-white rounded-2xl border border-stone-200 overflow-hidden shadow-xs">
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-stone-700">
              <thead class="bg-stone-50 border-b border-stone-200 text-[11px] font-semibold uppercase text-stone-600">
                <tr>
                  <th class="px-5 py-3.5">Produk</th>
                  <th class="px-5 py-3.5">Harga</th>
                  <th class="px-5 py-3.5">Pengelola</th>
                  <th class="px-5 py-3.5">Kontak WhatsApp</th>
                  <th class="px-5 py-3.5 text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-stone-200">
                <template x-for="prod in umkmProducts" :key="prod.id">
                  <tr class="hover:bg-stone-50/80 transition-colors">
                    <td class="px-5 py-3.5 font-semibold text-stone-900" x-text="prod.name"></td>
                    <td class="px-5 py-3.5 font-mono text-emerald-700 font-bold" x-text="prod.priceFormatted"></td>
                    <td class="px-5 py-3.5 text-stone-600" x-text="prod.sellerGroup"></td>
                    <td class="px-5 py-3.5 font-mono" x-text="prod.sellerPhone"></td>
                    <td class="px-5 py-3.5 text-right space-x-2">
                      <form method="POST" :action="entityUrl('umkm', prod.id, 'hapus')" class="inline">
                        @csrf
                        <button type="submit" class="p-1 text-rose-600 hover:text-rose-800" title="Hapus Produk">
                          <x-icon name="Trash2" class="w-4 h-4" />
                        </button>
                      </form>
                    </td>
                  </tr>
                </template>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {{-- TAB CONTENT: 📰 BERITA CRUD --}}
      <div x-show="activeTab === 'berita'" class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h3 class="font-serif text-xl font-bold text-stone-900">
              Kelola Berita &amp; Publikasi Kegiatan
            </h3>
            <p class="text-xs text-stone-500">
              Tulis artikel perkembangan pariwisata dan catatan pengabdian mahasiswa UNIDAR.
            </p>
          </div>

          <button
            type="button"
            @click="isAddNewsModalOpen = true"
            class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-semibold flex items-center gap-1.5 self-start sm:self-auto"
          >
            <x-icon name="Plus" class="w-4 h-4" />
            <span>Tulis Berita Baru</span>
          </button>
        </div>

        <div class="space-y-3">
          <template x-for="item in news" :key="item.id">
            <div class="p-4 bg-white rounded-2xl border border-stone-200 shadow-xs flex items-center justify-between gap-4">
              <div>
                <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded bg-stone-100 text-stone-700" x-text="item.category"></span>
                <h4 class="font-serif font-bold text-stone-900 text-base mt-1" x-text="item.title"></h4>
                <div class="text-xs text-stone-400 font-mono mt-0.5">
                  <span x-text="item.publishedDate"></span> · <span x-text="item.author"></span>
                </div>
              </div>

              <form method="POST" :action="entityUrl('berita', item.id, 'hapus')">
                @csrf
                <button type="submit" class="p-2 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Berita">
                  <x-icon name="Trash2" class="w-4 h-4" />
                </button>
              </form>
            </div>
          </template>
        </div>
      </div>

      {{-- TAB CONTENT: 📅 AGENDA CRUD --}}
      <div x-show="activeTab === 'agenda'" class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div>
            <h3 class="font-serif text-xl font-bold text-stone-900">
              Kelola Agenda &amp; Kalender Acara
            </h3>
            <p class="text-xs text-stone-500">
              Jadwalkan festival Pukul Sapu, lokakarya desa, atau aksi bersih lingkungan.
            </p>
          </div>

          <button
            type="button"
            @click="openAddEventModal()"
            class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-semibold flex items-center gap-1.5 self-start sm:self-auto"
          >
            <x-icon name="Plus" class="w-4 h-4" />
            <span>Tambah Acara Baru</span>
          </button>
        </div>

        <div class="space-y-3">
          <template x-for="evt in events" :key="evt.id">
            <div class="p-4 bg-white rounded-2xl border border-stone-200 shadow-xs flex items-center justify-between gap-4">
              <div>
                <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded bg-emerald-50 text-emerald-800" x-text="evt.category"></span>
                <h4 class="font-serif font-bold text-stone-900 text-base mt-1" x-text="evt.title"></h4>
                <div class="text-xs text-stone-500 font-mono mt-0.5">
                  <span x-text="evt.date"></span> · <span x-text="evt.location"></span>
                </div>
              </div>

              <form method="POST" :action="entityUrl('agenda', evt.id, 'hapus')">
                @csrf
                <button type="submit" class="p-2 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition-colors">
                  <x-icon name="Trash2" class="w-4 h-4" />
                </button>
              </form>
            </div>
          </template>
        </div>
      </div>

      {{-- TAB CONTENT: 🏛️ BUDAYA CRUD --}}
      <div x-show="activeTab === 'budaya'" class="space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
          <h3 class="font-serif text-xl font-bold text-stone-900">
            Arsip Kebudayaan &amp; Adat Morella (<span x-text="cultureItems.length"></span> Naskah)
          </h3>
          <button
            type="button"
            @click="isAddCultureModalOpen = true"
            class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-semibold flex items-center gap-1.5"
          >
            <x-icon name="Plus" class="w-4 h-4" />
            <span>Tambah Acara Baru</span>
          </button>
        </div>
        <div class="space-y-3">
          <template x-for="c in cultureItems" :key="c.id">
            <div class="p-4 bg-white rounded-2xl border border-stone-200 flex items-center justify-between">
              <div>
                <div class="text-[10px] uppercase font-semibold text-amber-800" x-text="c.category"></div>
                <h4 class="font-serif font-bold text-stone-900" x-text="c.title"></h4>
                <p class="text-xs text-stone-500 line-clamp-1" x-text="c.summary"></p>
              </div>
              <form method="POST" :action="entityUrl('budaya', c.id, 'hapus')">
                @csrf
                <button type="submit" class="p-2 text-rose-600 hover:text-rose-800">
                  <x-icon name="Trash2" class="w-4 h-4" />
                </button>
              </form>
            </div>
          </template>
        </div>
      </div>

      {{-- TAB CONTENT: 📸 GALERI CRUD --}}
      <div x-show="activeTab === 'galeri'" class="space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
          <h3 class="font-serif text-xl font-bold text-stone-900">
            Koleksi Foto Dokumentasi (<span x-text="gallery.length"></span> Foto)
          </h3>
          <button
            type="button"
            @click="isAddGalleryModalOpen = true"
            class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-semibold flex items-center gap-1.5"
          >
            <x-icon name="Plus" class="w-4 h-4" />
            <span>Tambah Data Baru</span>
          </button>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
          <template x-for="g in gallery" :key="g.id">
            <div class="relative rounded-xl overflow-hidden border border-stone-200 group bg-stone-100">
              <template x-if="g.videoUrl">
                <video :src="g.videoUrl" class="w-full h-32 object-cover bg-stone-900" controls preload="metadata"></video>
              </template>
              <template x-if="!g.videoUrl">
                <img :src="g.imageUrl" :alt="g.title" class="w-full h-32 object-cover" onerror="this.style.display='none'" />
              </template>
              <div class="p-2 text-[11px] font-semibold truncate bg-white" x-text="g.title"></div>
              <form method="POST" :action="entityUrl('galeri', g.id, 'hapus')" class="absolute top-2 right-2">
                @csrf
                <button type="submit" class="p-1.5 bg-rose-600 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                  <x-icon name="Trash2" class="w-3.5 h-3.5" />
                </button>
              </form>
            </div>
          </template>
        </div>
      </div>

      {{-- TAB CONTENT: 👥 TIM CRUD --}}
      <div x-show="activeTab === 'tim'" class="space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
          <h3 class="font-serif text-xl font-bold text-stone-900">
            Tim Pengabdian Mahasiswa UNIDAR Ambon
          </h3>
          <button
            type="button"
            @click="isAddTeamModalOpen = true"
            class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl text-xs font-semibold flex items-center gap-1.5"
          >
            <x-icon name="Plus" class="w-4 h-4" />
            <span>Tambah Data Baru</span>
          </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <template x-for="m in team" :key="m.id">
            <div class="p-4 bg-white rounded-2xl border border-stone-200 flex items-center justify-between gap-3">
              <div class="flex items-center gap-3">
                <img :src="m.photoUrl" :alt="m.name" class="w-12 h-12 rounded-full object-cover" />
                <div>
                  <h5 class="font-serif font-bold text-stone-900 text-sm" x-text="m.name"></h5>
                  <div class="text-xs text-emerald-700" x-text="m.role"></div>
                  <div class="text-[11px] text-stone-400" x-text="m.department"></div>
                </div>
              </div>
              <form method="POST" :action="entityUrl('tim', m.id, 'hapus')">
                @csrf
                <button type="submit" class="p-2 text-rose-600 hover:text-rose-800">
                  <x-icon name="Trash2" class="w-4 h-4" />
                </button>
              </form>
            </div>
          </template>
        </div>
      </div>

      {{-- TAB CONTENT: ⚙️ PENGATURAN --}}
      <div x-show="activeTab === 'pengaturan'" class="p-6 bg-white rounded-2xl border border-stone-200 space-y-6 max-w-2xl">
        <h3 class="font-serif text-xl font-bold text-stone-900">
          Pengaturan &amp; Sinkronisasi Portal
        </h3>

        <div class="space-y-2 text-xs text-stone-600">
          <p>
            Seluruh data yang Anda buat, edit, atau hapus tersimpan secara persisten pada browser lokal (localStorage).
          </p>
        </div>

        <div class="pt-6 border-t border-stone-200">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-4">
            <div>
              <h4 class="font-serif font-bold text-stone-900 text-base">Pengaturan Slider Latar Belakang (Hero)</h4>
              <p class="text-xs text-stone-500">Masukkan link URL atau unggah gambar untuk slider (1 baris untuk 1 gambar)</p>
            </div>
            <div class="flex items-center gap-2">
              <template x-if="isEditingSliders">
                <label class="cursor-pointer px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-800 rounded-xl text-xs font-semibold flex items-center gap-1.5 transition-colors">
                  <x-icon name="Plus" class="w-4 h-4" />
                  <span>Upload Gambar Lokal</span>
                  <input
                    type="file"
                    accept="image/*"
                    class="hidden"
                    @change="uploadSlider($event)"
                  />
                </label>
              </template>
              <button
                type="button"
                @click="toggleSliders()"
                class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors"
                :class="isEditingSliders ? 'bg-emerald-700 hover:bg-emerald-800 text-white' : 'bg-stone-100 hover:bg-stone-200 text-stone-800'"
                x-text="isEditingSliders ? 'Simpan Gambar' : 'Edit Gambar'"
              ></button>
            </div>
          </div>

          <textarea
            :value="isEditingSliders ? tempSliders : heroSliders.join('\n')"
            @input="tempSliders = $event.target.value"
            :disabled="!isEditingSliders"
            rows="5"
            class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
            placeholder="https://... atau klik Upload Gambar Lokal"
          ></textarea>

          <template x-if="!isEditingSliders && heroSliders.length > 0">
            <div class="mt-3 flex gap-3 overflow-x-auto pb-2">
              <template x-for="(url, idx) in heroSliders" :key="idx">
                <div class="relative shrink-0 group">
                  <img
                    :src="url"
                    :alt="'Preview ' + (idx + 1)"
                    class="w-32 h-20 object-cover rounded-xl border border-stone-200 shadow-sm transition-opacity group-hover:opacity-90"
                  />
                  <div class="absolute top-1 left-1 bg-black/60 text-white text-[10px] px-1.5 rounded-md font-mono" x-text="idx + 1"></div>
                  <button
                    type="button"
                    @click="deleteSlider(idx)"
                    class="absolute top-1 right-1 bg-rose-500 text-white p-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity hover:bg-rose-600"
                    title="Hapus Gambar"
                  >
                    <x-icon name="X" class="w-3 h-3" />
                  </button>
                </div>
              </template>
            </div>
          </template>
        </div>

        <div class="pt-4 border-t border-stone-200">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h4 class="font-serif font-bold text-stone-900 text-base">Pengaturan Kontak &amp; Alamat</h4>
              <p class="text-xs text-stone-500">Informasi alamat dan nomor kontak yang tampil di bagian Footer website</p>
            </div>
            <button
              type="button"
              @click="toggleContact()"
              class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors"
              :class="isEditingContact ? 'bg-emerald-700 hover:bg-emerald-800 text-white' : 'bg-stone-100 hover:bg-stone-200 text-stone-800'"
              x-text="isEditingContact ? 'Simpan Kontak' : 'Edit Kontak'"
            ></button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-stone-700 mb-1">Alamat Lengkap</label>
              <textarea
                :value="isEditingContact ? tempContact.address : contactInfo.address"
                @input="tempContact = { ...tempContact, address: $event.target.value }"
                :disabled="!isEditingContact"
                rows="2"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              ></textarea>
            </div>
            <div>
              <label class="block text-xs font-semibold text-stone-700 mb-1">Nomor Telepon / Kontak</label>
              <input
                type="text"
                :value="isEditingContact ? tempContact.phone : contactInfo.phone"
                @input="tempContact = { ...tempContact, phone: $event.target.value }"
                :disabled="!isEditingContact"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              />
            </div>
          </div>
        </div>

        <div class="pt-4 border-t border-stone-200">
          <div class="flex items-center justify-between mb-4">
            <div>
              <h4 class="font-serif font-bold text-stone-900 text-base">Pengaturan Teks Beranda (Hero)</h4>
              <p class="text-xs text-stone-500">Ubah atau hapus (kosongkan) teks yang muncul di atas gambar slider beranda</p>
            </div>
            <button
              type="button"
              @click="toggleHeroText()"
              class="px-4 py-2 rounded-xl text-xs font-semibold transition-colors"
              :class="isEditingHeroText ? 'bg-emerald-700 hover:bg-emerald-800 text-white' : 'bg-stone-100 hover:bg-stone-200 text-stone-800'"
              x-text="isEditingHeroText ? 'Simpan Teks' : 'Edit Teks'"
            ></button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-stone-700 mb-1">Badge (Label Atas) [ID]</label>
              <input
                type="text"
                :value="isEditingHeroText ? tempHeroText.badge : heroText.badge"
                @input="tempHeroText = { ...tempHeroText, badge: $event.target.value }"
                :disabled="!isEditingHeroText"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-stone-700 mb-1">Badge (Label Atas) [EN]</label>
              <input
                type="text"
                :value="isEditingHeroText ? tempHeroText.badgeEn : heroText.badgeEn"
                @input="tempHeroText = { ...tempHeroText, badgeEn: $event.target.value }"
                :disabled="!isEditingHeroText"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-stone-700 mb-1">Judul Utama [ID]</label>
              <input
                type="text"
                :value="isEditingHeroText ? tempHeroText.title : heroText.title"
                @input="tempHeroText = { ...tempHeroText, title: $event.target.value }"
                :disabled="!isEditingHeroText"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              />
            </div>
            <div>
              <label class="block text-xs font-semibold text-stone-700 mb-1">Judul Utama [EN]</label>
              <input
                type="text"
                :value="isEditingHeroText ? tempHeroText.titleEn : heroText.titleEn"
                @input="tempHeroText = { ...tempHeroText, titleEn: $event.target.value }"
                :disabled="!isEditingHeroText"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              />
            </div>

            <div>
              <label class="block text-xs font-semibold text-stone-700 mb-1">Tagline [ID]</label>
              <textarea
                :value="isEditingHeroText ? tempHeroText.tagline : heroText.tagline"
                @input="tempHeroText = { ...tempHeroText, tagline: $event.target.value }"
                :disabled="!isEditingHeroText"
                rows="2"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              ></textarea>
            </div>
            <div>
              <label class="block text-xs font-semibold text-stone-700 mb-1">Tagline [EN]</label>
              <textarea
                :value="isEditingHeroText ? tempHeroText.taglineEn : heroText.taglineEn"
                @input="tempHeroText = { ...tempHeroText, taglineEn: $event.target.value }"
                :disabled="!isEditingHeroText"
                rows="2"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              ></textarea>
            </div>

            <div>
              <label class="block text-xs font-semibold text-stone-700 mb-1">Deskripsi Singkat [ID]</label>
              <textarea
                :value="isEditingHeroText ? tempHeroText.description : heroText.description"
                @input="tempHeroText = { ...tempHeroText, description: $event.target.value }"
                :disabled="!isEditingHeroText"
                rows="3"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              ></textarea>
            </div>
            <div>
              <label class="block text-xs font-semibold text-stone-700 mb-1">Deskripsi Singkat [EN]</label>
              <textarea
                :value="isEditingHeroText ? tempHeroText.descriptionEn : heroText.descriptionEn"
                @input="tempHeroText = { ...tempHeroText, descriptionEn: $event.target.value }"
                :disabled="!isEditingHeroText"
                rows="3"
                class="w-full px-3 py-2 border border-stone-300 rounded-lg text-xs disabled:bg-stone-50 disabled:text-stone-500 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              ></textarea>
            </div>
          </div>
        </div>

        <div class="pt-4 border-t border-stone-200">
          <button
            type="button"
            @click="resetAll()"
            class="flex items-center gap-2 px-4 py-2.5 bg-rose-50 text-rose-700 hover:bg-rose-100 border border-rose-200 rounded-xl text-xs font-semibold transition-colors"
          >
            <x-icon name="RotateCcw" class="w-4 h-4" />
            <span>Kembalikan Seluruh Data ke Default (Reset)</span>
          </button>
        </div>
      </div>

      {{-- MODAL: Tambah Destinasi --}}
      <template x-if="isAddDestModalOpen">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs">
          <div class="bg-white rounded-2xl max-w-lg w-full p-6 text-stone-900 shadow-2xl max-h-[85vh] overflow-y-auto">
            <div class="flex items-center justify-between pb-3 border-b border-stone-200">
              <h3 class="font-serif font-bold text-lg">Tambah Destinasi Baru</h3>
              <button type="button" @click="isAddDestModalOpen = false">
                <x-icon name="X" class="w-5 h-5 text-stone-400" />
              </button>
            </div>

            <form method="POST" action="{{ route('admin.create', ['entity' => 'destinasi']) }}" class="space-y-4 pt-4 text-xs">
              @csrf
              <div>
                <label class="font-semibold text-stone-700 block mb-1">Nama Destinasi</label>
                <input
                  type="text"
                  required
                  name="name"
                  x-model="newDest.name"
                  placeholder="Contoh: Pantai Pasir Putih Morella"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                />
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="font-semibold text-stone-700 block mb-1">Kategori</label>
                  <select name="category" x-model="newDest.category" class="w-full px-3 py-2 border border-stone-300 rounded-lg">
                    <option value="pantai">Pantai</option>
                    <option value="alam">Wisata Alam</option>
                    <option value="pemandangan">Spot Pemandangan</option>
                    <option value="religi_sejarah">Wisata Religi &amp; Sejarah</option>
                    <option value="budaya">Budaya &amp; Tradisi</option>
                  </select>
                </div>
                <div>
                  <label class="font-semibold text-stone-700 block mb-1">Harga Tiket</label>
                  <input
                    type="text"
                    name="ticketPrice"
                    x-model="newDest.ticketPrice"
                    placeholder="Rp 5.000 / orang"
                    class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                  />
                </div>
              </div>

              <div>
                <label class="font-semibold text-stone-700 block mb-1">Tagline Singkat</label>
                <input
                  type="text"
                  name="tagline"
                  x-model="newDest.tagline"
                  placeholder="Pemandangan pesisir menawan dengan air jernih"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                />
              </div>

              <div>
                <label class="font-semibold text-stone-700 block mb-1">Deskripsi Lengkap</label>
                <textarea
                  rows="3"
                  name="description"
                  x-model="newDest.description"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                ></textarea>
              </div>

              <div>
                <label class="font-semibold text-stone-700 flex justify-between items-center mb-1">
                  <span>URL Foto Sampul</span>
                  <label class="cursor-pointer text-[10px] bg-stone-100 hover:bg-stone-200 px-2 py-1 rounded text-stone-700 font-medium transition-colors">
                    Upload Lokal
                    <input type="file" accept="image/*" class="hidden" @change="processUpload($event, 'newDest', 'imageUrl', 1000)" />
                  </label>
                </label>
                <input
                  type="text"
                  name="imageUrl"
                  x-model="newDest.imageUrl"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg font-mono text-[11px]"
                  placeholder="https://... atau klik Upload Lokal"
                />
              </div>

              <input type="hidden" name="nameEn" value="" />
              <input type="hidden" name="taglineEn" value="" />
              <input type="hidden" name="descriptionEn" value="" />
              <input type="hidden" name="location" value="Negeri Morella, Kec. Leihitu" />
              <input type="hidden" name="visitingHours" value="Setiap Hari: 07.00 - 18.00 WIT" />
              <input type="hidden" name="ticketPriceNum" value="5000" />
              <input type="hidden" name="contactName" value="Pokdarwis Desa Morella" />
              <input type="hidden" name="contactPhone" value="+6281234567801" />
              <input type="hidden" name="featured" value="1" />
              <input type="hidden" name="published" value="1" />

              <div class="flex justify-end gap-2 pt-3 border-t border-stone-200">
                <button type="button" @click="isAddDestModalOpen = false" class="px-4 py-2 bg-stone-100 rounded-lg font-medium">
                  Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-semibold">
                  Simpan &amp; Buat QR Code
                </button>
              </div>
            </form>
          </div>
        </div>
      </template>

      {{-- MODAL: Tambah UMKM --}}
      <template x-if="isAddUMKMModalOpen">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs">
          <div class="bg-white rounded-2xl max-w-lg w-full p-6 text-stone-900 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-stone-200">
              <h3 class="font-serif font-bold text-lg">Tambah Produk UMKM</h3>
              <button type="button" @click="isAddUMKMModalOpen = false">
                <x-icon name="X" class="w-5 h-5 text-stone-400" />
              </button>
            </div>

            <form method="POST" action="{{ route('admin.create', ['entity' => 'umkm']) }}" class="space-y-4 pt-4 text-xs">
              @csrf
              <div>
                <label class="font-semibold text-stone-700 block mb-1">Nama Produk</label>
                <input
                  type="text"
                  required
                  name="name"
                  x-model="newUMKM.name"
                  placeholder="Contoh: Minyak Kayu Putih Murni 100ml"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                />
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="font-semibold text-stone-700 block mb-1">Harga Format</label>
                  <input
                    type="text"
                    name="priceFormatted"
                    x-model="newUMKM.priceFormatted"
                    placeholder="Rp 35.000"
                    class="w-full px-3 py-2 border border-stone-300 rounded-lg font-mono"
                  />
                </div>
                <div>
                  <label class="font-semibold text-stone-700 block mb-1">Satuan</label>
                  <input
                    type="text"
                    name="unit"
                    x-model="newUMKM.unit"
                    placeholder="Botol 100ml / Box"
                    class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                  />
                </div>
              </div>

              <div>
                <label class="font-semibold text-stone-700 block mb-1">Nama Penjual &amp; Kelompok</label>
                <input
                  type="text"
                  name="sellerName"
                  x-model="newUMKM.sellerName"
                  placeholder="Nama Pengrajin / Penanggung Jawab"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                />
              </div>

              <div>
                <label class="font-semibold text-stone-700 block mb-1">Nomor WhatsApp</label>
                <input
                  type="text"
                  name="sellerPhone"
                  x-model="newUMKM.sellerPhone"
                  placeholder="+6281234567810"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg font-mono"
                />
              </div>

              <div>
                <label class="font-semibold text-stone-700 flex justify-between items-center mb-1">
                  <span>URL Foto Produk</span>
                  <label class="cursor-pointer text-[10px] bg-stone-100 hover:bg-stone-200 px-2 py-1 rounded text-stone-700 font-medium transition-colors">
                    Upload Lokal
                    <input type="file" accept="image/*" class="hidden" @change="processUpload($event, 'newUMKM', 'imageUrl', 800)" />
                  </label>
                </label>
                <input
                  type="text"
                  name="imageUrl"
                  x-model="newUMKM.imageUrl"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg font-mono text-[11px]"
                  placeholder="https://... atau klik Upload Lokal"
                />
              </div>

              <input type="hidden" name="nameEn" value="" />
              <input type="hidden" name="category" value="kuliner" />
              <input type="hidden" name="price" value="25000" />
              <input type="hidden" name="sellerGroup" value="Kelompok Usaha Warga Morella" />
              <input type="hidden" name="sellerAddress" value="Negeri Morella, Leihitu" />
              <input type="hidden" name="description" value="" />
              <input type="hidden" name="descriptionEn" value="" />
              <input type="hidden" name="featured" value="1" />
              <input type="hidden" name="inStock" value="1" />
              <input type="hidden" name="published" value="1" />

              <div class="flex justify-end gap-2 pt-3 border-t border-stone-200">
                <button type="button" @click="isAddUMKMModalOpen = false" class="px-4 py-2 bg-stone-100 rounded-lg font-medium">
                  Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-semibold">
                  Simpan Produk
                </button>
              </div>
            </form>
          </div>
        </div>
      </template>

      {{-- MODAL: Tambah Berita --}}
      <template x-if="isAddNewsModalOpen">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs">
          <div class="bg-white rounded-2xl max-w-lg w-full p-6 text-stone-900 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-stone-200">
              <h3 class="font-serif font-bold text-lg">Tulis Berita / Warta Baru</h3>
              <button type="button" @click="isAddNewsModalOpen = false">
                <x-icon name="X" class="w-5 h-5 text-stone-400" />
              </button>
            </div>

            <form method="POST" action="{{ route('admin.create', ['entity' => 'berita']) }}" class="space-y-4 pt-4 text-xs">
              @csrf
              <div>
                <label class="font-semibold text-stone-700 block mb-1">Judul Berita</label>
                <input
                  type="text"
                  required
                  name="title"
                  x-model="newArticle.title"
                  placeholder="Judul artikel berita..."
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                />
              </div>

              <div>
                <label class="font-semibold text-stone-700 block mb-1">Ringkasan (1-2 Kalimat)</label>
                <input
                  type="text"
                  name="summary"
                  x-model="newArticle.summary"
                  placeholder="Ringkasan warta..."
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                />
              </div>

              <div>
                <label class="font-semibold text-stone-700 block mb-1">Isi Berita</label>
                <textarea
                  rows="4"
                  name="content"
                  x-model="newArticle.content"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                ></textarea>
              </div>

              <div>
                <label class="font-semibold text-stone-700 flex justify-between items-center mb-1">
                  <span>URL Foto Berita</span>
                  <label class="cursor-pointer text-[10px] bg-stone-100 hover:bg-stone-200 px-2 py-1 rounded text-stone-700 font-medium transition-colors">
                    Upload Lokal
                    <input type="file" accept="image/*" class="hidden" @change="processUpload($event, 'newArticle', 'imageUrl', 800)" />
                  </label>
                </label>
                <input
                  type="text"
                  name="imageUrl"
                  x-model="newArticle.imageUrl"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg font-mono text-[11px]"
                  placeholder="https://... atau klik Upload Lokal"
                />
              </div>

              <input type="hidden" name="titleEn" value="" />
              <input type="hidden" name="category" value="desa" />
              <input type="hidden" name="author" value="Tim Humas Desa Morella" />
              <input type="hidden" name="authorRole" value="Pengelola Informasi" />
              <input type="hidden" name="publishedDate" value="29 September 2026" />
              <input type="hidden" name="readTime" value="3 menit baca" />
              <input type="hidden" name="summaryEn" value="" />
              <input type="hidden" name="contentEn" value="" />
              <input type="hidden" name="published" value="1" />

              <div class="flex justify-end gap-2 pt-3 border-t border-stone-200">
                <button type="button" @click="isAddNewsModalOpen = false" class="px-4 py-2 bg-stone-100 rounded-lg font-medium">
                  Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-semibold">
                  Tayangkan Berita
                </button>
              </div>
            </form>
          </div>
        </div>
      </template>

      {{-- MODAL: Tambah Agenda --}}
      <template x-if="isAddEventModalOpen">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs">
          <div class="bg-white rounded-2xl max-w-lg w-full p-6 text-stone-900 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-stone-200">
              <h3 class="font-serif font-bold text-lg">Tambah Agenda Kegiatan</h3>
              <button type="button" @click="isAddEventModalOpen = false">
                <x-icon name="X" class="w-5 h-5 text-stone-400" />
              </button>
            </div>

            <form method="POST" action="{{ route('admin.create', ['entity' => 'agenda']) }}" enctype="multipart/form-data" class="space-y-4 pt-4 text-xs">
              @csrf
              <div>
                <label class="font-semibold text-stone-700 block mb-1">Nama Acara</label>
                <input
                  type="text"
                  required
                  name="title"
                  x-model="newEvent.title"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                />
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="font-semibold text-stone-700 block mb-1">Tanggal</label>
                  <input
                    type="text"
                    name="date"
                    x-model="newEvent.date"
                    class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                  />
                </div>
                <div>
                  <label class="font-semibold text-stone-700 block mb-1">Lokasi</label>
                  <input
                    type="text"
                    name="location"
                    x-model="newEvent.location"
                    class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                  />
                </div>
              </div>

              <div>
                <label class="font-semibold text-stone-700 block mb-1">Deskripsi Singkat</label>
                <textarea
                  rows="3"
                  name="description"
                  x-model="newEvent.description"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                ></textarea>
              </div>

              <div>
                <label class="font-semibold text-stone-700 flex justify-between items-center mb-1">
                  <span>URL Foto Agenda</span>
                  <label class="cursor-pointer text-[10px] bg-stone-100 hover:bg-stone-200 px-2 py-1 rounded text-stone-700 font-medium transition-colors">
                    Upload Lokal
                    <input type="file" accept="image/*" class="hidden" @change="processUpload($event, 'newEvent', 'imageUrl', 800)" />
                  </label>
                </label>
                <input
                  type="text"
                  name="imageUrl"
                  x-model="newEvent.imageUrl"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg font-mono text-[11px]"
                  placeholder="https://... atau klik Upload Lokal"
                />
              </div>

              <div>
                <label class="font-semibold text-stone-700 flex justify-between items-center mb-1">
                  <span>File Video Agenda</span>
                  <span class="text-[10px] text-stone-400">Opsional · min 2 MB, maks 500 MB</span>
                </label>
                <input
                  type="file"
                  name="video"
                  accept="video/*"
                  @change="validateEventVideo($event)"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg text-[11px] file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:bg-emerald-50 file:text-emerald-700 file:text-[10px] file:font-semibold hover:file:bg-emerald-100"
                />
                <p x-show="eventVideoError" x-text="eventVideoError" class="text-[10px] text-rose-600 font-medium mt-1"></p>
                <p x-show="eventVideoInfo" x-text="eventVideoInfo" class="text-[10px] text-emerald-700 font-medium mt-1"></p>
              </div>

              <input type="hidden" name="titleEn" value="" />
              <input type="hidden" name="time" value="09.00 WIT" />
              <input type="hidden" name="organizer" value="Pemerintah Desa Morella" />
              <input type="hidden" name="category" value="budaya" />
              <input type="hidden" name="descriptionEn" value="" />
              <input type="hidden" name="status" value="upcoming" />
              <input type="hidden" name="contactPerson" value="+6281234567800" />

              <div class="flex justify-end gap-2 pt-3 border-t border-stone-200">
                <button type="button" @click="isAddEventModalOpen = false" class="px-4 py-2 bg-stone-100 rounded-lg font-medium">
                  Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-semibold">
                  Simpan Acara
                </button>
              </div>
            </form>
          </div>
        </div>
      </template>

      {{-- MODAL: Tambah Culture --}}
      <template x-if="isAddCultureModalOpen">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs">
          <div class="bg-white rounded-2xl max-w-lg w-full p-6 text-stone-900 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-stone-200">
              <h3 class="font-serif font-bold text-lg">Tambah Naskah Budaya</h3>
              <button type="button" @click="isAddCultureModalOpen = false">
                <x-icon name="X" class="w-5 h-5 text-stone-400" />
              </button>
            </div>

            <form method="POST" action="{{ route('admin.create', ['entity' => 'budaya']) }}" class="space-y-4 pt-4 text-xs">
              @csrf
              <div>
                <label class="font-semibold text-stone-700 block mb-1">Judul Naskah / Acara</label>
                <input
                  type="text"
                  required
                  name="title"
                  x-model="newCulture.title"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                />
              </div>

              <div>
                <label class="font-semibold text-stone-700 block mb-1">Kategori</label>
                <select name="category" x-model="newCulture.category" class="w-full px-3 py-2 border border-stone-300 rounded-lg">
                  <option value="sejarah">Sejarah</option>
                  <option value="tradisi">Tradisi</option>
                  <option value="kesenian">Kesenian</option>
                  <option value="kuliner">Kuliner</option>
                  <option value="cerita_rakyat">Cerita Rakyat</option>
                  <option value="tokoh">Tokoh</option>
                </select>
              </div>

              <div>
                <label class="font-semibold text-stone-700 block mb-1">Ringkasan</label>
                <input
                  type="text"
                  name="summary"
                  x-model="newCulture.summary"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                />
              </div>

              <div>
                <label class="font-semibold text-stone-700 block mb-1">Konten Lengkap</label>
                <textarea
                  rows="4"
                  name="content"
                  x-model="newCulture.content"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                ></textarea>
              </div>

              <div>
                <label class="font-semibold text-stone-700 flex justify-between items-center mb-1">
                  <span>URL Foto Naskah</span>
                  <label class="cursor-pointer text-[10px] bg-stone-100 hover:bg-stone-200 px-2 py-1 rounded text-stone-700 font-medium transition-colors">
                    Upload Lokal
                    <input type="file" accept="image/*" class="hidden" @change="processUpload($event, 'newCulture', 'imageUrl', 800)" />
                  </label>
                </label>
                <input
                  type="text"
                  name="imageUrl"
                  x-model="newCulture.imageUrl"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg font-mono text-[11px]"
                  placeholder="https://... atau klik Upload Lokal"
                />
              </div>

              <input type="hidden" name="titleEn" value="" />
              <input type="hidden" name="summaryEn" value="" />
              <input type="hidden" name="contentEn" value="" />
              <input type="hidden" name="periodOrTime" value="" />
              <input type="hidden" name="historicalSignificance" value="" />
              <input type="hidden" name="published" value="1" />

              <div class="flex justify-end gap-2 pt-3 border-t border-stone-200">
                <button type="button" @click="isAddCultureModalOpen = false" class="px-4 py-2 bg-stone-100 rounded-lg font-medium">
                  Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-semibold">
                  Simpan Naskah
                </button>
              </div>
            </form>
          </div>
        </div>
      </template>

      {{-- MODAL: Tambah Galeri --}}
      <template x-if="isAddGalleryModalOpen">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs">
          <div class="bg-white rounded-2xl max-w-lg w-full p-6 text-stone-900 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-stone-200">
              <h3 class="font-serif font-bold text-lg">Tambah Foto Galeri</h3>
              <button type="button" @click="isAddGalleryModalOpen = false">
                <x-icon name="X" class="w-5 h-5 text-stone-400" />
              </button>
            </div>

            <form method="POST" action="{{ route('admin.create', ['entity' => 'galeri']) }}" class="space-y-4 pt-4 text-xs" enctype="multipart/form-data">
              @csrf
              <div>
                <label class="font-semibold text-stone-700 block mb-1">Judul Foto</label>
                <input
                  type="text"
                  required
                  name="title"
                  x-model="newGallery.title"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                />
              </div>

              <div>
                <label class="font-semibold text-stone-700 block mb-1">Kategori</label>
                <select name="category" x-model="newGallery.category" class="w-full px-3 py-2 border border-stone-300 rounded-lg">
                  <option value="alam">Alam &amp; Lingkungan</option>
                  <option value="budaya">Budaya &amp; Tradisi</option>
                  <option value="masyarakat">Sosial Masyarakat</option>
                  <option value="pengabdian">Pengabdian KKN</option>
                  <option value="wisata">Pariwisata</option>
                </select>
              </div>

              <div>
                <label class="font-semibold text-stone-700 block mb-1">Lokasi</label>
                <input
                  type="text"
                  name="location"
                  x-model="newGallery.location"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                />
              </div>

              <div>
                <label class="font-semibold text-stone-700 flex justify-between items-center mb-1">
                  <span>URL Foto (Link)</span>
                  <label class="cursor-pointer text-[10px] bg-stone-100 hover:bg-stone-200 px-2 py-1 rounded text-stone-700 font-medium transition-colors">
                    Upload Lokal
                    <input type="file" accept="image/*" class="hidden" @change="processUpload($event, 'newGallery', 'imageUrl', 800)" />
                  </label>
                </label>
                <input
                  type="text"
                  name="imageUrl"
                  x-model="newGallery.imageUrl"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg font-mono text-[11px]"
                  placeholder="https://... atau klik Upload Lokal"
                />
              </div>

              <div>
                <label class="font-semibold text-stone-700 block mb-1">
                  Upload Video Lokal <span class="font-normal text-stone-500">(Maks 500 MB)</span>
                </label>
                <input
                  type="file"
                  name="video"
                  accept="video/*"
                  class="w-full px-3 py-1.5 border border-stone-300 rounded-lg text-stone-600 bg-stone-50"
                />
              </div>

              <input type="hidden" name="titleEn" value="" />
              <input type="hidden" name="photographer" value="Tim Dokumentasi" />
              <input type="hidden" name="dateTaken" value="2026" />

              <div class="flex justify-end gap-2 pt-3 border-t border-stone-200">
                <button type="button" @click="isAddGalleryModalOpen = false" class="px-4 py-2 bg-stone-100 rounded-lg font-medium">
                  Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-semibold">
                  Simpan Foto
                </button>
              </div>
            </form>
          </div>
        </div>
      </template>

      {{-- MODAL: Tambah Tim --}}
      <template x-if="isAddTeamModalOpen">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-xs">
          <div class="bg-white rounded-2xl max-w-lg w-full p-6 text-stone-900 shadow-2xl">
            <div class="flex items-center justify-between pb-3 border-b border-stone-200">
              <h3 class="font-serif font-bold text-lg">Tambah Anggota Tim</h3>
              <button type="button" @click="isAddTeamModalOpen = false">
                <x-icon name="X" class="w-5 h-5 text-stone-400" />
              </button>
            </div>

            <form method="POST" action="{{ route('admin.create', ['entity' => 'tim']) }}" class="space-y-4 pt-4 text-xs">
              @csrf
              <div>
                <label class="font-semibold text-stone-700 block mb-1">Nama Lengkap</label>
                <input
                  type="text"
                  required
                  name="name"
                  x-model="newTeam.name"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                />
              </div>

              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="font-semibold text-stone-700 block mb-1">Peran / Jabatan</label>
                  <input
                    type="text"
                    name="role"
                    x-model="newTeam.role"
                    class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                  />
                </div>
                <div>
                  <label class="font-semibold text-stone-700 block mb-1">Fakultas / Jurusan</label>
                  <input
                    type="text"
                    name="department"
                    x-model="newTeam.department"
                    class="w-full px-3 py-2 border border-stone-300 rounded-lg"
                  />
                </div>
              </div>

              <div>
                <label class="font-semibold text-stone-700 flex justify-between items-center mb-1">
                  <span>URL Foto (Link)</span>
                  <label class="cursor-pointer text-[10px] bg-stone-100 hover:bg-stone-200 px-2 py-1 rounded text-stone-700 font-medium transition-colors">
                    Upload Lokal
                    <input type="file" accept="image/*" class="hidden" @change="processUpload($event, 'newTeam', 'photoUrl', 800)" />
                  </label>
                </label>
                <input
                  type="text"
                  name="photoUrl"
                  x-model="newTeam.photoUrl"
                  class="w-full px-3 py-2 border border-stone-300 rounded-lg font-mono text-[11px]"
                  placeholder="https://... atau klik Upload Lokal"
                />
              </div>

              <input type="hidden" name="university" value="Universitas Darussalam Ambon" />
              <input type="hidden" name="bio" value="" />
              <input type="hidden" name="contribution" value="" />

              <div class="flex justify-end gap-2 pt-3 border-t border-stone-200">
                <button type="button" @click="isAddTeamModalOpen = false" class="px-4 py-2 bg-stone-100 rounded-lg font-medium">
                  Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-lg font-semibold">
                  Simpan Anggota
                </button>
              </div>
            </form>
          </div>
        </div>
      </template>

    </div>
  </template>
</div>

<script>
function adminView(data, isAdminAuthenticated) {
  return {
    isAuthenticated: false,
    activeTab: 'overview',

    destinations: data.destinations || [],
    umkmProducts: data.umkm || [],
    news: data.news || [],
    events: data.events || [],
    cultureItems: data.culture || [],
    gallery: data.gallery || [],
    team: data.team || [],
    bookings: data.bookings || [],
    paymentSettings: data.paymentSettings || {},
    heroSliders: data.heroSliders || [],
    contactInfo: data.contactInfo || {},
    heroText: data.heroText || {},
    webVisits: data.webVisits || 0,

    urls: {
      admin: @json(url('/admin')),
      tickets: @json(route('tickets')),
      checkin: @json(route('tickets.checkin')),
      payment: @json(route('admin.payment')),
      sliders: @json(route('admin.sliders')),
      contact: @json(route('admin.contact')),
      heroText: @json(route('admin.heroText')),
      reset: @json(route('admin.reset')),
    },

    isAddDestModalOpen: false,
    isAddUMKMModalOpen: false,
    isAddNewsModalOpen: false,
    isAddEventModalOpen: false,
    eventVideoError: '',
    eventVideoInfo: '',
    isAddCultureModalOpen: false,
    isAddGalleryModalOpen: false,
    isAddTeamModalOpen: false,

    checkInCode: '',
    checkInMessage: '',
    checkInSuccess: false,

    isEditingPayment: false,
    tempPaymentSettings: {},
    isEditingSliders: false,
    tempSliders: '',
    isEditingContact: false,
    tempContact: {},
    isEditingHeroText: false,
    tempHeroText: {},

    newDest: {
      name: '',
      category: 'pantai',
      tagline: '',
      description: '',
      ticketPrice: 'Rp 5.000 / orang',
      imageUrl: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1000&q=80',
    },
    newUMKM: {
      name: '',
      priceFormatted: 'Rp 25.000',
      unit: 'Kemasan / Botol',
      sellerName: '',
      sellerPhone: '+6281234567810',
      imageUrl: 'https://images.unsplash.com/photo-1608571423902-eed4a5ad8108?auto=format&fit=crop&w=800&q=80',
    },
    newArticle: {
      title: '',
      summary: '',
      content: '',
      imageUrl: 'https://images.unsplash.com/photo-1531403009284-440f080d1e12?auto=format&fit=crop&w=1000&q=80',
    },
    newEvent: {
      title: '',
      date: '10 Oktober 2026',
      location: 'Negeri Morella',
      description: '',
      imageUrl: 'https://images.unsplash.com/photo-1533105079780-92b9be482077?auto=format&fit=crop&w=1000&q=80',
    },
    newCulture: {
      title: '',
      category: 'sejarah',
      summary: '',
      content: '',
      imageUrl: 'https://images.unsplash.com/photo-1599839619722-39751411ea63?auto=format&fit=crop&w=1000&q=80',
    },
    newGallery: {
      title: '',
      category: 'alam',
      location: 'Negeri Morella',
      imageUrl: 'https://images.unsplash.com/photo-1599839619722-39751411ea63?auto=format&fit=crop&w=1000&q=80',
    },
    newTeam: {
      name: '',
      role: 'Mahasiswa KKN',
      department: '',
      photoUrl: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=800&q=80',
    },

    init() {
      this.isAuthenticated = isAdminAuthenticated === true;

      if (!this.isAuthenticated) {
        return;
      }

      try {
        var tab = sessionStorage.getItem('morela_admin_tab');
        if (tab) {
          this.activeTab = tab;
        }
      } catch (e) {}
    },

    setTab(tab) {
      this.activeTab = tab;
      try { sessionStorage.setItem('morela_admin_tab', tab); } catch (e) {}
    },

    goTickets() {
      window.location.href = this.urls.tickets;
    },

    fmt(n) {
      return (Number(n) || 0).toLocaleString('id-ID');
    },

    get totalRevenue() {
      return this.bookings.filter(function (b) {
        return b.paymentStatus === 'paid' || b.paymentStatus === 'checked_in';
      }).reduce(function (sum, b) {
        return sum + (Number(b.totalAmount) || 0);
      }, 0);
    },

    countStatus(status) {
      return this.bookings.filter(function (b) {
        return b.paymentStatus === status;
      }).length;
    },

    statusClass(status) {
      if (status === 'paid') return 'bg-emerald-100 text-emerald-800';
      if (status === 'checked_in') return 'bg-blue-100 text-blue-800';
      if (status === 'cancelled') return 'bg-rose-100 text-rose-800';
      return 'bg-amber-100 text-amber-800';
    },

    statusLabel(status) {
      if (status === 'paid') return 'Lunas';
      if (status === 'checked_in') return 'Checked-In';
      if (status === 'cancelled') return 'Batal';
      return 'Pending';
    },

    destCat(category) {
      return String(category || '').replace('_', ' ');
    },

    entityUrl(entity, id, action) {
      return this.urls.admin + '/' + entity + '/' + id + '/' + action;
    },

    postJson(url, payload, done) {
      fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify(payload || {}),
      })
        .then(function (res) { return res.json().catch(function () { return {}; }); })
        .then(function (json) { if (done) done(json); })
        .catch(function () { if (done) done({}); });
    },

    confirmSubmit(e, message) {
      if (!confirm(message)) {
        e.preventDefault();
      }
    },

    processUpload(e, form, field, maxWidth) {
      var self = this;
      var file = e.target.files && e.target.files[0];
      if (!file) return;
      compressImage(file, maxWidth, 0.7)
        .then(function (compressed) {
          self[form][field] = compressed;
        })
        .catch(function (err) {
          console.error(err);
          alert('Gagal memproses gambar');
        });
    },

    openAddEventModal() {
      this.eventVideoError = '';
      this.eventVideoInfo = '';
      this.isAddEventModalOpen = true;
    },

    validateEventVideo(e) {
      var file = e.target.files && e.target.files[0];
      this.eventVideoError = '';
      this.eventVideoInfo = '';
      if (!file) return;

      var minBytes = 2 * 1024 * 1024;
      var maxBytes = 500 * 1024 * 1024;
      var sizeMb = file.size / (1024 * 1024);

      if (file.size < minBytes) {
        this.eventVideoError = 'Ukuran video minimal 2 MB (file ini ' + sizeMb.toFixed(1) + ' MB).';
        e.target.value = '';
        return;
      }
      if (file.size > maxBytes) {
        this.eventVideoError = 'Ukuran video maksimal 500 MB (file ini ' + sizeMb.toFixed(1) + ' MB).';
        e.target.value = '';
        return;
      }
      this.eventVideoInfo = file.name + ' (' + sizeMb.toFixed(1) + ' MB) siap diunggah.';
    },

    doCheckIn() {
      var self = this;
      var code = this.checkInCode.trim();
      if (!code) return;
      this.postJson(this.urls.checkin, { code: code }, function (json) {
        if (json.success) {
          self.checkInMessage = 'Tiket ' + code + ' berhasil divalidasi Masuk (Checked-In)!';
          self.checkInSuccess = true;
          self.bookings = self.bookings.map(function (b) {
            if (String(b.bookingCode).toUpperCase() === code.toUpperCase()) {
              return Object.assign({}, b, { paymentStatus: 'checked_in' });
            }
            return b;
          });
          self.checkInCode = '';
        } else {
          self.checkInMessage = 'Kode ' + code + ' tidak ditemukan atau tidak valid.';
          self.checkInSuccess = false;
        }
        setTimeout(function () { self.checkInMessage = ''; }, 4000);
      });
    },

    togglePayment() {
      var self = this;
      if (!this.isEditingPayment) {
        this.tempPaymentSettings = Object.assign({}, this.paymentSettings);
        this.isEditingPayment = true;
        return;
      }
      this.postJson(this.urls.payment, this.tempPaymentSettings, function () {
        self.paymentSettings = Object.assign({}, self.tempPaymentSettings);
        self.isEditingPayment = false;
        alert('Pengaturan rekening & QRIS berhasil disimpan!');
      });
    },

    toggleSliders() {
      var self = this;
      if (!this.isEditingSliders) {
        this.tempSliders = this.heroSliders.join('\n');
        this.isEditingSliders = true;
        return;
      }
      var newUrls = this.tempSliders.split('\n').map(function (url) {
        return url.trim();
      }).filter(function (url) {
        return url.length > 0;
      });
      this.postJson(this.urls.sliders, { urls: this.tempSliders }, function () {
        self.heroSliders = newUrls;
        self.isEditingSliders = false;
        alert('Pengaturan gambar slider berhasil disimpan!');
      });
    },

    uploadSlider(e) {
      var self = this;
      var file = e.target.files && e.target.files[0];
      if (!file) return;
      compressImage(file, 1920, 0.7)
        .then(function (compressed) {
          self.tempSliders = self.tempSliders ? self.tempSliders + '\n' + compressed : compressed;
        })
        .catch(function (err) {
          console.error(err);
          alert('Gagal memproses gambar');
        });
    },

    deleteSlider(idx) {
      var self = this;
      if (!confirm('Hapus gambar slider ke-' + (idx + 1) + '?')) return;
      var newSliders = this.heroSliders.slice();
      newSliders.splice(idx, 1);
      this.postJson(this.urls.sliders, { urls: newSliders.join('\n') }, function () {
        self.heroSliders = newSliders;
      });
    },

    toggleContact() {
      var self = this;
      if (!this.isEditingContact) {
        this.tempContact = Object.assign({}, this.contactInfo);
        this.isEditingContact = true;
        return;
      }
      this.postJson(this.urls.contact, this.tempContact, function () {
        self.contactInfo = Object.assign({}, self.tempContact);
        self.isEditingContact = false;
        alert('Informasi kontak berhasil disimpan!');
      });
    },

    toggleHeroText() {
      var self = this;
      if (!this.isEditingHeroText) {
        this.tempHeroText = Object.assign({}, this.heroText);
        this.isEditingHeroText = true;
        return;
      }
      this.postJson(this.urls.heroText, this.tempHeroText, function () {
        self.heroText = Object.assign({}, self.tempHeroText);
        self.isEditingHeroText = false;
        alert('Pengaturan teks beranda berhasil disimpan!');
      });
    },

    resetAll() {
      if (!confirm('Apakah Anda yakin ingin mengatur ulang seluruh data kembali ke data contoh bawaan?')) return;
      this.postJson(this.urls.reset, {}, function () {
        alert('Data telah dikembalikan ke kondisi awal (default seeder)!');
        window.location.reload();
      });
    },
  };
}
</script>
@endsection
