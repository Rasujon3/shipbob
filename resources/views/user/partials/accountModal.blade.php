<!-- account modal start -->
<div
    class="modal fade"
    id="account-modal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Account Details
                </h1>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <div
                    class="d-flex border justify-content-center rounded-2 pt-2 mb-3 border-dark"
                >
                    <div class="text-center">
                        <h6>My Account</h6>
{{--                        <p>৳ {{ Auth::user()->main_balance ?? '0' }}</p>--}}
                        <p>৳ {{ $frozenAmount ? - $frozenAmount : Auth::user()->main_balance }}</p>
                    </div>
                </div>
                <div
                    class="d-flex border justify-content-center rounded-2 pt-2 mb-3 border-dark"
                >
                    <div class="text-center">
                        <h6>Commission</h6>
                        <p>৳ {{ $commissionSum ?? '0' }}</p>
                    </div>
                </div>
                <div
                    class="d-flex border justify-content-center rounded-2 pt-2 mb-3 border-dark"
                >
                    <div class="text-center">
                        <h6>Reserved Amount</h6>
{{--                        <p>৳ {{ $frozenAmount ? - $frozenAmount : '0' }}</p>--}}
                        <p>৳ {{ $frozenAmount ? (Auth::user()->main_balance + $frozenAmount) : '0' }}</p>
                    </div>
                </div>
                <div
                    class="d-flex border justify-content-center rounded-2 pt-2 border-dark mb-3"
                >
                    <div class="text-center">
                        <h6>Order Number</h6>
                        <p>{{ $orderCompletedCount ?? '0' }}/{{ $totalTaskCount ?? '0' }}</p>
                    </div>
                </div>
                <div
                    class="d-flex bg-secondary-subtle justify-content-center rounded-2 pt-2"
                >
                    <div class="text-center">
                        <h6>Trial Bonus</h6>
                        <p>৳ {{ Auth::user()->balance ?? '0' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- account modal end -->
