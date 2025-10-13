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
                style="width: 40px !important;"
              />
              @endif
            </div>
              <p id="notice-message" class="text-center text-danger pb-2" style="font-weight: bold !important;">
                  {{ ($creditData && $creditData->credit->notice) ? $creditData->credit->notice : '' }}
              </p>
            <div class="px-4 pb-5 mb-5 transform: rotate(30deg)">
              {!! $rules->description ?? '' !!}
            </div>
          </div>
        </div>
      </section>
    <style>
        /* keyframe-গুলি তৈরি করা হচ্ছে */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        /* ফেড-ইন এবং ফেড-আউট ক্লাস তৈরি */
        .fade-in {
            opacity: 1;
            animation: fadeIn 1s ease-in-out forwards; /* 1 সেকেন্ড ধরে ফেড-ইন হবে */
        }

        .fade-out {
            opacity: 0;
            animation: fadeOut 1s ease-in-out forwards; /* 1 সেকেন্ড ধরে ফেড-আউট হবে */
        }
    </style>
@endsection

@push('scripts')

    <script src="{{asset('custom/multiple_files.js')}}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const noticeElement = document.getElementById('notice-message');
            const content = noticeElement.textContent.trim();

            // যদি নোটিশ এলিমেন্ট এবং তাতে কোনো টেক্সট থাকে তবেই ফাংশনটি চলবে
            if (noticeElement && content.length > 0) {

                // প্রাথমিক অবস্থা: নোটিশটি লুকানো আছে
                // note: .hidden ক্লাসটি CSS-এ opacity: 0 নিশ্চিত করছে

                // লুপটি শুরু করার জন্য প্রধান ফাংশন
                function startLoop() {

                    // 1. Fade In (1 সেকেন্ড)
                    noticeElement.classList.remove('hidden', 'fade-out');
                    noticeElement.classList.add('fade-in');

                    // 2. Remain Visible (5 সেকেন্ড) - ফেড-ইন শেষ হওয়ার পর থেকে
                    // মোট: 1s (Fade In) + 5s (Stay Visible) = 6 সেকেন্ড অপেক্ষা
                    setTimeout(() => {

                        // 3. Fade Out (1 সেকেন্ড)
                        noticeElement.classList.remove('fade-in');
                        noticeElement.classList.add('fade-out');

                        // 4. Remain Hidden (1 সেকেন্ড) - ফেড-আউট শেষ হওয়ার পর থেকে
                        // মোট: 6s + 1s (Fade Out) + 1s (Stay Hidden) = 8 সেকেন্ড অপেক্ষা
                        setTimeout(() => {

                            // নিশ্চিত করুন যে অ্যানিমেশন ক্লাসগুলো মুছে গেছে এবং এটি অদৃশ্য অবস্থায় আছে
                            noticeElement.classList.remove('fade-out');
                            noticeElement.classList.add('hidden'); // .hidden শুধু opacity: 0 রাখবে

                            // লুপটি পুনরায় শুরু করুন
                            startLoop();

                        }, 1000); // 1 সেকেন্ড অপেক্ষা (Remain Hidden)

                    }, 5000); // 5 সেকেন্ড অপেক্ষা (Remain Visible)

                }

                // প্রথমবার লুপ শুরু করুন
                startLoop();
            }
        });
    </script>

@endpush
