@extends('user.layout.master')
@section('content')
    @include('user.profile.profileBackBtn')
    <!-- hero section start -->
    <section class="hero">
    <div class="position-relative">
      <img src="{{ asset('assets/images/hero/banner-4.png') }}" alt="" class="w-100" />
      <div class="position-absolute bottom-0 start-50 translate-middle-x text-white">
          <a href="{{ $settings->telegram_group_link ?? '' }}" class="underline-none text-decoration-none" target="_blank">
              <div class="bg-white py-2 px-5 rounded-5 d-flex align-items-center gap-2">
                  <iconify-icon
                      icon="logos:telegram"
                      width="2rem"
                      height="2rem"
                  ></iconify-icon>
                  <span class="text-black">Telegram</span>
              </div>
          </a>
      </div>
    </div>
    </section>
    <!-- hero section end -->
@endsection
