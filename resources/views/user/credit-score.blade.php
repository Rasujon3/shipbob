@extends('user.layout.master')
@section('content')
    <section>
        <div class="banner">
          <!-- Additional required wrapper -->
          <div>
            <!-- Slides -->
            <div class="position-relative">
              <img
                src="{{ $creditData ? asset($creditData->credit->img) : asset('assets/images/partials/shap1.png') }}"
                style="width: 100%"
                class="p-5"
                alt=""
              />
              @if (!$creditData)
              <img
                src="{{ asset('assets/images/partials/icon1.png') }}"
                alt=""
                class="position-absolute top-50 start-50 compass"
                style="width: 40px"
              />
              @endif
            </div>
            <div class="px-4 pb-5 mb-5 transform: rotate(30deg)">
              {!! $rules->description ?? '' !!}
            </div>
          </div>
        </div>
      </section>
@endsection
