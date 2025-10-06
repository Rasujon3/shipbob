@extends('user.layout.master')
@section('content')
    <main class="main-body overflow-hidden bg-secondary-subtle">
        @include('user.profile.profileBackBtn')
        <!-- hero section start -->
        <section class="hero">
            <div class="position-relative">
                <img
                    src="{{ asset($img->img ?? '') }}"
                    alt=""
                    class="w-100 p-2"
                />
                <div class="position-absolute top-50 ps-5 translate-middle-y">
                    <h6>My Asset:</h6>
                    <h5 class="fw-semibold">৳ {{auth()->user()->main_balance}}</h5>
                </div>
            </div>
            <div class="px-3 bg-white py-3 m-2 rounded-2">
                <h3 class="fw-semibold fs-5">Bank</h3>
                <div class="mt-3">
                    <button class="d-flex align-items-center gap-2 border-0 bg-white">
                        <iconify-icon
                            icon="mdi:card-account-details-outline"
                            width="1.7rem"
                            height="1.7rem"
                        ></iconify-icon>
                        <span class="fw-semibold">{{$user->paymentmethod->bank_name ?? ""}} (Account Number: {{$user->paymentmethod->account_number ?? ""}})</span>
                    </button>
                </div>
            </div>
            <div class="px-3 bg-white py-3 m-2 rounded-2">
                <h3 class="fw-semibold fs-5">Quick Selection</h3>
                <div class="mt-3">
                    <div class="row border-0 bg-white">

                        <div class="col">
                            @foreach($packages as $package)

                                <p class="fw-semibold px-4 py-3 rounded-5 bg-secondary-subtle my-package" data-id="{{$package->id}}" v-id="{{$package->recharge_amount}}" style="cursor: pointer;">
                                    ৳ {{$package->recharge_amount}}
                                </p>
                            @endforeach
                        </div>

                    </div>
                </div>
                <!-- cash-in form start -->
                <div class="mt-3">
                    <form action="{{url('save-cashin')}}" method="POST">
                        @csrf

                        <input type="hidden" name="selected_package_id" id="selected_package_id" value=""/>

                        <div class="input-group mb-3">
                            <span class="input-group-text fs-5">৳</span>
                            <input
                                type="text"
                                class="form-control py-2 px-3 fs-5"
                                name="amount"
                                id="package_amount"
                                aria-label="Amount (to the nearest dollar)"
                            />
                            <button
                                class="btn btn-dark w-100 mt-3 rounded-5 py-2 fw-semibold"
                            >
                                Cash In
                            </button>
                        </div>
                        <div class="d-none">
                            <h6 class="fw-semibold">Cash In Rules</h6>
                            <p><small>1. Minimum deposit amount: ৳10000 </small></p>
                            <p>
                                <small
                                >2. Monday to Sunday 10:00-22:00(Except special holidays)
                                </small>
                            </p>
                        </div>
                    </form>
                </div>
                <!-- cash-in form end -->
            </div>
        </section>
        <!-- hero section end -->
    </main>
@endsection


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
  $(document).ready(function(){
     $(document).on('click', '.my-package', function(e){
       e.preventDefault();
       let package_id = $(this).data('id');
       let package_price = $(this).attr('v-id');
       $('#selected_package_id').val(package_id);
       $('#package_amount').val(package_price);

     });
  });
</script>
