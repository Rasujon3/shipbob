@extends('user.layout.master')
@section('content')
    @include('user.profile.profileBackBtn')
    <!-- hero section start -->
    <section class="level-container p-3">
        <div class="position-relative">
            <a href="#">
                <img
                    src="{{ asset('assets/images/partials/vip-1.png') }}"
                    alt="vip-1"
                    class="w-100"
                />
            </a>
            <h5 class="position-absolute my-level">My Level</h5>
        </div>

        <div>
            <p class="mb-3">
                <small class="text-white">74560.36 more to upgrade to VIP2</small>
            </p>
            <div
                class="progress"
                role="progressbar"
                aria-label="Example 1px high"
                aria-valuenow="25"
                aria-valuemin="0"
                aria-valuemax="100"
                style="height: 5px"
            >
                <div class="progress-bar" style="width: 25%"></div>
            </div>
            <p class="d-flex justify-content-end mt-2">
                <small
                ><span class="text-primary">5439.64</span>
                    <span class="text-white">/ 10000</span></small
                >
            </p>
            <div class="text-white">
                <h2>Authority</h2>
                <div class="mt-3">
                    <h4 class="text-primary">Ordinary</h4>
                    <p><small>Profit rate: 0.52%</small></p>
                    <p><small>Number of assignments: 30 </small></p>
                    <p><small>Point Period: 365 Day</small></p>
                </div>
            </div>
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
