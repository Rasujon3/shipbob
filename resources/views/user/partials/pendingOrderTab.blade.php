<!-- tab content two -->
<div
    class="tab-pane fade"
    id="pills-profile"
    role="tabpanel"
    aria-labelledby="pills-profile-tab"
    tabindex="0"
>
    @if(count($pendingProducts) > 0)
        @foreach($pendingProducts as $product)
{{--            <div class="p-4 bg-secondary-subtle rounded-2 mb-3">--}}
{{--                <h6 class="fw-semibold">{{ $product->name ?? '' }}</h6>--}}
{{--                <p><small>Order Amount: ৳ {{ $product->price }}</small></p>--}}
{{--                <p><small>Commission: ৳ {{ $product->commission }}</small></p>--}}
{{--                <button class="btn btn-primary w-100 order-btn" data-id="{{ $product->id }}">Pending Order</button>--}}
{{--            </div>--}}

            <div class="p-4 bg-secondary-subtle rounded-2 mb-3">
                <h6 class="fw-semibold">{{ $product->name ?? '' }}</h6>
                <hr />
                <div>
                    <img
                        src="{{ $product->file ? asset($product->file) : '' }}"
                        alt="{{ $product->name ?? '' }}"
                        class="product_img"
                        style="max-width: 100% !important;"
                    />
                    <p class="mt-2"><small>Price: ৳ {{ $product->price ?? '' }}</small></p>
                    <p><small>Commission: ৳ {{ $product->commission ?? '' }}</small></p>
                    @if($reserveData)
                        <p style="color: red">Congratulations, you got {{$reserveData->value}} {{$reserveData->unit}} Commission.</p>
                    @endif
                    <p><small>Time: {{ now()->toDayDateTimeString() }}</small></p>
                    <button class="btn btn-primary w-100 order-btn" data-id="{{ $product->id }}">Pending Order</button>
                </div>
            </div>
        @endforeach
    @else
        <p>Nothing found</p>
    @endif

</div>
