@extends('user.layout.master')
@section('content')
    <section class="py-5 mb-5">
        <div class="px-4 px-lg-5">
            @if(count($products) > 0)
                @foreach($products as $product)
                    @php
                        $isOrdered = in_array($product->id, $orderedProductIds);
                    @endphp

                    <div class="p-4 bg-secondary-subtle rounded-2 mb-3">
                        <h6 class="fw-semibold">{{ $product->name ?? '' }}</h6>
                        <hr />
                        <div>
                            <img src="{{ $product->file ? asset($product->file) : '' }}" alt="{{ $product->name ?? '' }}" class="product_img" />
                            <p class="mt-2"><small>Price: ৳ {{ $product->price ?? '' }}</small></p>
                            <p><small>Commission: ৳ {{ $product->commission ?? '' }}</small></p>
                            <p><small>Time: {{ now()->toDayDateTimeString() }}</small></p>
                        </div>

                        <form method="POST" action="{{ route('order.product') }}" class="orderForm">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="is_trial_task" value="{{ $is_trial_task }}">
                            <input type="hidden" name="task_id" value="{{ $task_id }}">

                            <button
                                type="submit"
                                class="btn w-100 btn-dark submit-btn" {{ $isOrdered ? 'disabled' : '' }}
                            >
                                {{ $isOrdered ? 'Already Ordered' : 'Place Order' }}
                            </button>

                            <!-- 1st time hidden this button  -->
                            <button type="button" class="btn w-100 btn-secondary waiting-btn d-none" disabled>
                                Please wait...
                                <span class="spinner-border spinner-border-sm"></span>
                                <span class="countdown"></span>
                            </button>
                        </form>
                    </div>
                @endforeach
            @else
                <div class="p-4 bg-secondary-subtle rounded-2 mb-3">
                    <p>Nothing found</p>
                </div>
            @endif
        </div>
    </section>

    <style>
        .product_img {
            max-height: 50% !important;
            max-width: 100% !important;
            width: 100% !important;
        }

    </style>
@endsection

@push('scripts')

    <script>
        $(document).ready(function () {
            $('.orderForm').on('submit', function (e) {
                e.preventDefault();

                const $form = $(this);
                const $submitBtn = $form.find('.submit-btn');
                const $waitingBtn = $form.find('.waiting-btn');
                const $countdown = $waitingBtn.find('.countdown');

                // prevent multiple clicks
                if ($submitBtn.data('clicked')) return false;
                $submitBtn.data('clicked', true);

                // hide submit, show waiting
                $submitBtn.addClass('d-none');
                $waitingBtn.removeClass('d-none');

                let countdown = 2;
                $countdown.text(`(${countdown}s)`);

                const timer = setInterval(() => {
                    countdown--;
                    $countdown.text(`(${countdown}s)`);

                    if (countdown <= 0) {
                        clearInterval(timer);
                        $countdown.text('');
                        // finally submit
                        $form.off('submit').submit();
                    }
                }, 1000);
            });
        });
    </script>

@endpush
