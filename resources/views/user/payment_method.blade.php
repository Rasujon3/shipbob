@extends('user.layout.master')
@section('content')
    <main class="main-body overflow-hidden bg-secondary-subtle">
      <!-- header section start -->
      <header class="shadow-sm py-3 px-3 mx-auto bg-white">
        <div class="d-flex align-items-center justify-content-between">
          <div class="d-flex gap-3 align-items-center">
            <button class="bg-transparent border-0">
              <iconify-icon
                icon="qlementine-icons:user-16"
                width="3rem"
                height="3rem"
              ></iconify-icon>
            </button>
            <div>
              <p>UID: {{ Auth::user()->uid ?? '' }}</p>
              <h5 class="fs-5">{{ Auth::user()->username ?? '' }}</h5>
            </div>
          </div>
          <div class="mt-3">
              <a href="{{ url('/logout') }}" class="underline-none text-decoration-none">
                  <button
                      class="btn btn-outline-dark d-flex align-content-center gap-2 my-2"
                  >
                      <iconify-icon
                          icon="mage:logout"
                          width="1.2em"
                          height="1.2em"
                      ></iconify-icon>
                      <span> Logout</span>
                  </button>
              </a>
          </div>
        </div>
      </header>
      <!-- header section end-->

        @include('user.profile.profileBackBtn')

      <!-- hero section start -->
      <section class="hero">
        <div class="px-3 bg-white py-3 m-2 rounded-2">
          <h3 class="fw-semibold fs-5">Information Entry</h3>
          <div class="mt-3">
             <form action="{{route('paymentmethods.store')}}" method="POST">
              @csrf
              <input type="hidden" name="user_id" value="{{$user->id}}"/>
               <div class="form-group">
               	 <label for="mobile_no" class="my-2" style="font-weight: bold;">Mobile Phone Number: </label>
               	 <input type="text" class="form-control my-2" id="mobile_no" name="mobile_no" placeholder="Mobile Phone Number" value="{{old('mobile_no',$user->paymentmethod->mobile_no ?? '')}}">
               </div>

               <div class="form-group">
               	 <label for="account_holder" class="my-2" style="font-weight: bold;">Account Holder Name: </label>
               	 <input type="text" class="form-control my-2" id="account_holder" name="account_holder" placeholder="Account Holder Name" value="{{old('account_holder',$user->paymentmethod->account_holder ?? '')}}">
               </div>

               <div class="form-group">
               	 <label for="account_number" class="my-2" style="font-weight: bold;">Account Number: </label>
               	 <input type="text" class="form-control my-2" id="account_number" name="account_number" placeholder="Account Number" value="{{old('account_number',$user->paymentmethod->account_number ?? '')}}">
               </div>


               <div class="form-group">
               	 <label for="bank_name" class="my-2" style="font-weight: bold;">Bank Name: </label>
               	 <input type="text" class="form-control my-2" id="bank_name" name="bank_name" placeholder="Bank Name" value="{{old('bank_name',$user->paymentmethod->bank_name ?? '')}}">
               </div>


               <div class="form-group">
               	 <label for="branch_name" class="my-2" style="font-weight: bold;">Branch Name: </label>
               	 <input type="text" class="form-control my-2" id="branch_name" name="branch_name" placeholder="Branch Name" value="{{old('branch_name',$user->paymentmethod->branch_name ?? '')}}">
               </div>

               <div class="form-group">
               	 <label for="routing_number" class="my-2" style="font-weight: bold;">Routing Number: </label>
               	 <input type="text" class="form-control my-2" id="routing_number" name="routing_number" placeholder="Routing Number"  value="{{old('routing_number',$user->paymentmethod->routing_number ?? '')}}">
               </div>

               <div class="form-group my-2">
               	 <button type="submit" class="btn btn-submit btn-success">Submit</button>
               </div>

             </form>
          </div>

      </section>
    </main>
@endsection

