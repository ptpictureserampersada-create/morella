<!doctype html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Negeri Morella - Jelajah Pesona Morella</title>
    <meta name="description" content="Portal Digital Pariwisata Desa Morela: Alam, Budaya & Masyarakat." />
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&family=Playfair+Display:ital,wght@0,600;0,700;1,600&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
      (function () {
        var lang = 'id';
        try {
          lang = sessionStorage.getItem('morela_lang') || 'id';
        } catch (e) {}
        document.documentElement.classList.add(lang === 'en' ? 'lang-en' : 'lang-id');
      })();
      window.MORELA_URLS = {
        tickets: @json(route('tickets')),
        book: @json(route('tickets.book')),
        confirm: @json(route('tickets.confirm')),
      };
    </script>
  </head>
  <body class="bg-stone-50 text-stone-900 antialiased font-sans selection:bg-emerald-100 selection:text-emerald-900">
    <div id="root">
      <div class="min-h-screen flex flex-col bg-stone-50 text-stone-900 selection:bg-emerald-100 selection:text-emerald-900 pb-16 lg:pb-0">
        {{-- Top Bar Header --}}
        @include('partials.header')

        {{-- Main View Area --}}
        <main class="flex-1 animate-in fade-in duration-150">
          @yield('content')
        </main>

        {{-- Institutional Footer --}}
        @include('partials.footer')

        {{-- Smartphone Bottom Navigation --}}
        @include('partials.bottom-nav')
      </div>

      {{-- Detail & Overlay Modals --}}
      @include('partials.modal-destination')
      @include('partials.modal-product')
      @include('partials.modal-culture')
      @include('partials.modal-article')
      @include('partials.modal-qrcode')
      @include('partials.modal-lightbox')
      @include('partials.modal-ticket')
    </div>

    @if (session('morela_alert'))
      <script>alert(@js(session('morela_alert')));</script>
    @endif
  </body>
</html>
