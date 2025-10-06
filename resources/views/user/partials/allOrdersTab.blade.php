<div
    class="tab-pane fade show active"
    id="pills-home"
    role="tabpanel"
    aria-labelledby="pills-home-tab"
    tabindex="0"
>
    @if(count($completedOrders) > 0)
        @foreach($completedOrders as $order)
            <div class="p-4 bg-secondary-subtle rounded-2 mb-3">
                <h6 class="fw-semibold">{{ $order->product->name ?? '' }}</h6>
                <hr />
                <p><small>Order number: {{ $order->order_number ?? '' }}</small></p>
                <p><small>Order Amount: ৳ {{ $order->product->price ?? '0' }}</small></p>
                <p><small>Commission: ৳ {{ $order->product->commission ?? '0' }}</small></p>
                <p><small>Time: {{ $order->created_at->format('d-m-y h:i A') }}</small></p>
                <button class="btn btn-dark w-100" disabled>Completed</button>
            </div>
        @endforeach
    @else
        <p>Nothing found</p>
    @endif

</div>
