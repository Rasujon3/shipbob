@extends('user.layout.master')
@section('content')
    @include('user.profile.profileBackBtn')
      <!-- hero section start -->
      <section class="hero">
        <div class="position-relative">
          <img
            src="{{ asset('assets/images/hero/banner-3.png') }}"
            alt=""
            class="w-100 p-2"
          />
          <div
            class="position-absolute top-50 ps-5 translate-middle-y text-white"
          >
            <h4 class="text-warning">+৳300</h4>
            <p class="fw-light w-50">
              <small
                >You can get ৳300 for signing in continuously for 7 days</small
              >
            </p>
          </div>
        </div>
        <div class="d-flex px-2 gap-2">
          <div class="col">
            <img src="./assets/images/partials/wqd.png" alt="" class="w-100" />
            <p class="text-center"><small>Day 1</small></p>
          </div>
          <div class="col">
            <img src="./assets/images/partials/wqd.png" alt="" class="w-100" />
            <p class="text-center"><small>Day 2</small></p>
          </div>
          <div class="col">
            <img src="./assets/images/partials/wqd.png" alt="" class="w-100" />
            <p class="text-center"><small>Day 3</small></p>
          </div>
          <div class="col">
            <img src="./assets/images/partials/wqd.png" alt="" class="w-100" />
            <p class="text-center"><small>Day 4</small></p>
          </div>
          <div class="col">
            <img src="./assets/images/partials/wqd.png" alt="" class="w-100" />
            <p class="text-center"><small>Day 5</small></p>
          </div>
          <div class="col">
            <img src="./assets/images/partials/wqd.png" alt="" class="w-100" />
            <p class="text-center"><small>Day 6</small></p>
          </div>
          <div class="col">
            <img src="./assets/images/partials/wqd.png" alt="" class="w-100" />
            <p class="text-center"><small>Day 7</small></p>
          </div>
        </div>
        <div
          class="d-flex align-items-center justify-content-between px-3 bg-white py-3 m-2 rounded-2"
        >
          <div>
            <h3 class="fw-semibold fs-5">/ 7 Days</h3>
            <span class="fw-semibold">Consecutive sign-in days</span>
          </div>
          <div>
            <button
              class="d-flex align-items-center gap-2 border-0 btn btn-dark rounded-5 fs-5 px-4 py-2"
            >
              <span class="fw-semibold">Sign in </span>
            </button>
          </div>
        </div>
        <div class="px-3 bg-white py-3 m-2 rounded-2">
          <h3 class="fw-semibold fs-5">Rules</h3>
          <div class="mt-3 p-3 bg-secondary-subtle rounded-4">
            You can sign in every day .There are reward for signing in on the
            same day .If you sign in continuously for 7 day ,you can get a
            considerable reward
          </div>
        </div>
      </section>
      <!-- hero section end -->
@endsection
