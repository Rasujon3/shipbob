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

                        <form method="POST" action="{{ route('order.product') }}">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="is_trial_task" value="{{ $is_trial_task }}">
                            <input type="hidden" name="task_id" value="{{ $task_id }}">

                            <button type="submit" class="btn w-100 btn-dark" {{ $isOrdered ? 'disabled' : '' }}>
                                {{ $isOrdered ? 'Already Ordered' : 'Place Order' }}
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
