<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <link rel="stylesheet" href="{{ asset('assets/css/index.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/swiper-bundle.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('custom/toastr.css') }}">

    <title>{{ setting()->company_name ?? 'ShipBob' }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/icons/favicon.jpg') }}" />
  </head>
  <body>
    <main class="main-body overflow-hidden pb-5 mb-4">
      @include('user.layout.navbar')

      @yield('content')

      @if(Auth::check())
        @include('user.layout.bottomNav')
      @endif

    </main>

    <!-- jQuery -->
    <script src="{{ asset('custom/custom_js.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/swiper.min.js') }}"></script>
    <script src="{{ asset('assets/js/iconfy-icon.min.js') }}"></script>
    <script src="{{ asset('custom/toastr.js') }}"></script>
    <script src="{{ asset('dropify/dist/js/dropify.min.js')}}"></script>
    <script>
        const swiper = new Swiper('.banner, .setoff-carousel', {
            loop: true,
            autoplay: {
                delay: 3000,
            },
            slidesPerView: 1,
            spaceBetween: 0,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    </script>

    @if(Session::has('message'))
        @toastr("{{ Session::get('message') }}")
    @endif

    @stack('scripts')
  </body>
</html>
