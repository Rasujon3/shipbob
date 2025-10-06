@extends('user.layout.master')
@section('content')
    @include('user.profile.profileBackBtn')
    <!-- hero section start -->
    <section class="level-container p-3">
        <div class="position-relative">
            <a href="{{ route('user-level-details') }}">
                <img
                    src="{{ asset('assets/images/partials/vip-1.png') }}"
                    alt="vip-1"
                    class="w-100"
                />
            </a>
            <h5 class="position-absolute my-level">My Level</h5>
        </div>
        <div>
            <a href="{{ route('user-level-details') }}">
                <img
                    src="{{ asset('assets/images/partials/vip-2.png') }}"
                    alt="vip-2"
                    class="w-100"
                />
            </a>
        </div>
        <div>
            <a href="{{ route('user-level-details') }}">
                <img
                    src="{{ asset('assets/images/partials/vip-3.png') }}"
                    alt="vip-3"
                    class="w-100"
                />
            </a>
        </div>
        <div>
            <a href="{{ route('user-level-details') }}">
                <img
                    src="{{ asset('assets/images/partials/vip-4.png') }}"
                    alt="vip-4"
                    class="w-100"
                />
            </a>
        </div>
        <div>
            <a href="{{ route('user-level-details') }}">
                <img
                    src="{{ asset('assets/images/partials/vip-5.png') }}"
                    alt="vip-5"
                    class="w-100"
                />
            </a>
        </div>
        <div>
            <a href="{{ route('user-level-details') }}">
                <img
                    src="{{ asset('assets/images/partials/vip-6.png') }}"
                    alt="vip-6"
                    class="w-100"
                />
            </a>
        </div>

        <!-- <div
          class="position-relative rounded-2 bg-secondary text-white bg-gradient"
        >
          <div class="py-4 px-3">
            <div>
              <h2 class="text-center">VIP 1</h2>
              <p class="text-center">Mitgliedschaft starte</p>
            </div>
          </div>
          <div class="position-absolute top-0 start-0">
            <h6 class="p-3">My Level</h6>
          </div>
        </div>
        <div
          class="position-relative mt-3 rounded-2 bg-secondary text-white bg-gradient"
        >
          <div class="py-4 px-3">
            <div>
              <h2 class="text-center">VIP 1</h2>
              <p class="text-center">Mitgliedschaft starte</p>
            </div>
          </div>
          <div class="position-absolute top-0 start-0">
            <h6 class="p-3">My Level</h6>
          </div>
        </div> -->
    </section>
    <!-- hero section end -->
@endsection
