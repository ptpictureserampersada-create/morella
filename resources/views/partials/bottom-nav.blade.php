@php
  $items = [
    ['id' => 'home', 'labelId' => 'Beranda', 'labelEn' => 'Home', 'icon' => 'Home', 'route' => 'home'],
    ['id' => 'destinations', 'labelId' => 'Wisata', 'labelEn' => 'Explore', 'icon' => 'Compass', 'route' => 'destinations'],
    ['id' => 'map', 'labelId' => 'Peta', 'labelEn' => 'Map', 'icon' => 'Map', 'route' => 'map'],
    ['id' => 'umkm', 'labelId' => 'UMKM', 'labelEn' => 'Shop', 'icon' => 'ShoppingBag', 'route' => 'umkm'],
    ['id' => 'admin', 'labelId' => 'Admin', 'labelEn' => 'Admin', 'icon' => 'ShieldCheck', 'route' => 'admin'],
  ];
@endphp

<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-30 bg-stone-900/95 backdrop-blur-md border-t border-stone-800 pb-safe">
  <div class="flex items-center justify-around h-14 px-2">
    @foreach ($items as $item)
      @php $isActive = $currentView === $item['id']; @endphp
      <a
        href="{{ route($item['route']) }}"
        class="flex flex-col items-center justify-center flex-1 h-full py-1 min-w-[50px] transition-colors {{ $isActive ? 'text-emerald-400 font-semibold' : 'text-stone-400 hover:text-stone-200' }}"
      >
        <x-icon :name="$item['icon']" class="w-4 h-4 mb-0.5" />
        <span class="text-[10px] tracking-tight truncate max-w-[64px]">
          <x-t :id="$item['labelId']" :en="$item['labelEn']" />
        </span>
      </a>
    @endforeach
  </div>
</nav>
