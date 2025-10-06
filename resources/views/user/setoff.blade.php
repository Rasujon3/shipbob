@extends('user.layout.master')
@section('content')
    <section class="py-5 mb-5">
        <div class="px-4 px-lg-5">
          @include('user.partials.setoff.videoSection')

          @include('user.partials.setoff.sliderSection')

            <form action="{{ route('product-order') }}" method="POST">
                @csrf
                <input type="hidden" name="is_trial_task" value="{{ $is_trial_task }}">
                <input type="hidden" name="task_id" value="{{ $task_id }}">

                <button
                    type="submit"
                    class="d-flex align-items-center gap-2 justify-content-center btn btn-primary w-100 py-3"
                >
                    <iconify-icon
                        icon="hugeicons:start-up-01"
                        width="1.2em"
                        height="1.2em"
                    ></iconify-icon>
                    <span>Start</span>
                </button>
            </form>

            <div class="d-flex gap-3 mt-3">
            <button
              data-bs-toggle="modal"
              data-bs-target="#account-modal"
              class="d-flex align-items-center gap-2 justify-content-center btn btn-outline-primary w-100 py-3"
            >
              <iconify-icon
                icon="solar:user-outline"
                width="1.2em"
                height="1.2em"
              ></iconify-icon>
              <span>Account</span>
            </button>

            <button
              data-bs-toggle="modal"
              data-bs-target="#account-history-modal"
              class="d-flex align-items-center gap-2 justify-content-center btn btn-outline-primary w-100 py-3"
            >
              <iconify-icon
                icon="material-symbols:history-rounded"
                width="1.2em"
                height="1.2em"
              ></iconify-icon>
              <span>Account History</span>
            </button>
          </div>
        </div>

        @include('user.partials.accountModal')
        @include('user.partials.accountHistory')

      </section>
@endsection

