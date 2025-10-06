@extends('user.layout.master')
@section('content')
    @include('user.profile.profileBackBtn')
    <!-- hero section start -->
    <section class="hero">
    <div class="px-3 bg-white py-3 m-2 rounded-2">
      <!-- tab start  -->
      <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
          <button
            class="nav-link active"
            id="pills-home-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-home"
            type="button"
            role="tab"
            aria-controls="pills-home"
            aria-selected="true"
          >
            Account
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            class="nav-link"
            id="pills-profile-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-profile"
            type="button"
            role="tab"
            aria-controls="pills-profile"
            aria-selected="false"
          >
            Withdraw
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            class="nav-link"
            id="pills-contact-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-contact"
            type="button"
            role="tab"
            aria-controls="pills-contact"
            aria-selected="false"
          >
            Commission
          </button>
        </li>
        <li class="nav-item" role="presentation">
          <button
            class="nav-link"
            id="pills-cont-tab"
            data-bs-toggle="pill"
            data-bs-target="#pills-cont"
            type="button"
            role="tab"
            aria-controls="pills-cont"
            aria-selected="false"
          >
            Deposit
          </button>
        </li>
      </ul>
      <!-- tab content one -->
      <div class="tab-content" id="pills-tabContent">
        <div
          class="tab-pane fade show active"
          id="pills-home"
          role="tabpanel"
          aria-labelledby="pills-home-tab"
          tabindex="0"
        >
         @foreach($fiveDeposits as $row1)
          <div
            class="d-flex border justify-content-between align-items-center px-3 py-2 rounded-2 pt-2 mb-3 border-dark"
          >
            <div class="">
              <h5>CASHIN</h5>
              <h6>UUID: {{$row1->uuid}}</h6>
              <p class="text-secondary">
                <small>{{$row1->date}} {{$row1->time}}</small>
              </p>
              @if($row1->status == 'Approved')
               <span class="badge bg-success">Approved</span>
              @else
               <span class="badge bg-danger">Pending</span>
              @endif
            </div>
            <div>
              <p>৳ {{$row1->amount}}</p>
            </div>
          </div>
          @endforeach
          @foreach($fiveWithdraws as $row2)
          <div
            class="d-flex border justify-content-between align-items-center px-3 py-2 rounded-2 pt-2 mb-3 border-dark"
          >
            <div class="">
              <h5>CASHOUT</h5>
              <h6>UUID: {{$row2->uuid}}</h6>
             
              <p class="text-secondary">
                <small>{{$row2->date}} {{$row2->time}}</small>
              </p>
              @if($row2->status == 'Approved')
               <span class="badge bg-success">Approved</span>
              @else
               <span class="badge bg-danger">Pending</span>
              @endif
            </div>
            <div>
              <p>৳ {{$row2->amount}}</p>

            </div>
          </div>
          @endforeach
        </div>

        <!-- tab content two -->
        <div
          class="tab-pane fade"
          id="pills-profile"
          role="tabpanel"
          aria-labelledby="pills-profile-tab"
          tabindex="0"
        >
          @foreach($withdraws as $row3)
          <div
            class="d-flex border justify-content-between align-items-center px-3 py-2 rounded-2 pt-2 mb-3 border-dark"
          >
            <div class="">
              <h5>CASHOUT</h5>
              <h6>UUID: {{$row3->uuid}}</h6>
             
              <p class="text-secondary">
                <small>{{$row3->date}} {{$row3->time}}</small>
              </p>
              @if($row3->status == 'Approved')
               <span class="badge bg-success">Approved</span>
              @else
               <span class="badge bg-danger">Pending</span>
              @endif
            </div>
            <div>
              <p>৳ {{$row3->amount}}</p>

            </div>
          </div>
          @endforeach
        </div>
        <!-- tab content three -->
        <div
          class="tab-pane fade"
          id="pills-contact"
          role="tabpanel"
          aria-labelledby="pills-contact-tab"
          tabindex="0"
        >
           @foreach($commissions as $row4)
          <div
            class="d-flex border justify-content-between align-items-center px-3 py-2 rounded-2 pt-2 mb-3 border-dark"
          >
            <div class="">
              <h5>COMMISIONS</h5>
              <h6>UUID: {{$row4->uuid}}</h6>
             
              <p class="text-secondary">
                <small>{{$row4->date}} {{$row4->time}}</small>
              </p>
              @if($row4->status == 'Approved')
               <span class="badge bg-success">Approved</span>
              @else
               <span class="badge bg-danger">Pending</span>
              @endif
            </div>
            <div>
              <p>৳ {{$row4->bouns_amount}}</p>

            </div>
          </div>
          @endforeach
        </div>
        <!-- tab content four -->
        <div
          class="tab-pane fade"
          id="pills-cont"
          role="tabpanel"
          aria-labelledby="pills-cont-tab"
          tabindex="0"
        >
          @foreach($deposits as $row5)
          <div
            class="d-flex border justify-content-between align-items-center px-3 py-2 rounded-2 pt-2 mb-3 border-dark"
          >
            <div class="">
              <h5>CASHIN</h5>
              <h6>UUID: {{$row5->uuid}}</h6>
             
              <p class="text-secondary">
                <small>{{$row5->date}} {{$row5->time}}</small>
              </p>
              @if($row5->status == 'Approved')
               <span class="badge bg-success">Approved</span>
              @else
               <span class="badge bg-danger">Pending</span>
              @endif
            </div>
            <div>
              <p>৳ {{$row5->amount}}</p>

            </div>
          </div>
          @endforeach
        </div>
      </div>
      <!-- tab end -->
    </div>
    </section>
    <!-- hero section end -->
@endsection
