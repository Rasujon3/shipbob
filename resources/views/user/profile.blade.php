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

      <!-- back button start -->
      <div class="px-3 bg-white py-1 m-2 rounded-2">
          <a href="{{ route('user-index') }}" class="underline-none text-decoration-none">
              <button
                  class="btn btn-outline-dark d-flex align-content-center gap-2 my-2"
              >
                  <iconify-icon
                      icon="ep:back"
                      width="1.2em"
                      height="1.2em"
                  ></iconify-icon>
                  <span> Back</span>
              </button>
          </a>
      </div>
      <!-- back button end -->

      <!-- hero section start -->
      <section class="hero">
        <div class="px-3 bg-white py-3 m-2 rounded-2">
          <h3 class="fw-semibold fs-5">Channel Function</h3>
          <div class="mt-3">
              <a href="{{ route('user-cash-in') }}" class="underline-none text-decoration-none">
                <button class="d-flex align-items-center gap-2 border-0 bg-white">
                  <iconify-icon
                    icon="streamline-freehand-color:cash-payment-coin-1"
                    width="1.7rem"
                    height="1.7rem"
                  ></iconify-icon>
                  <span>Cash In</span>
                </button>
              </a>
          </div>
            <div class="mt-3">
                <button
                    data-bs-toggle="modal"
                    data-bs-target="#cashout-modal"
                    class="d-flex align-items-center gap-2 border-0 bg-white"
                >
                    <iconify-icon
                        icon="bi:cash-coin"
                        width="1.7rem"
                        height="1.7rem"
                    ></iconify-icon>
                    <span>Cash Out</span>
                </button>
            </div>
        </div>
        <div class="px-3 bg-white py-3 m-2 rounded-2">
          <h3 class="fw-semibold fs-5">More Details on Features and rewards</h3>
          <div class="mt-3">
              <a href="{{ route('user-account-details') }}" class="underline-none text-decoration-none">
                <button class="d-flex align-items-center gap-2 border-0 bg-white">
                  <iconify-icon
                    icon="mdi:card-account-details-outline"
                    width="1.7rem"
                    height="1.7rem"
                  ></iconify-icon>
                  <span>Profile Details</span>
                </button>
              </a>
          </div>
          <div class="mt-3">
            <button data-bs-toggle="modal"
                    data-bs-target="#bind-wallet-modal"
                    class="d-flex align-items-center gap-2 border-0 bg-white"
            >
              <iconify-icon
                icon="proicons:credit-card"
                width="1.7rem"
                height="1.7rem"
              ></iconify-icon>
              <span>Bind Wallet Account</span>
            </button>
          </div>
          <div class="mt-3">
              <a href="{{ route('user-level') }}" class="underline-none text-decoration-none">
                  <button class="d-flex align-items-center gap-2 border-0 bg-white">
                      <iconify-icon
                          icon="ri:vip-crown-2-line"
                          width="1.7rem"
                          height="1.7rem"
                      ></iconify-icon>
                      <span>VIP</span>
                  </button>
              </a>
          </div>
          <div class="mt-3">
              <a href="{{ route('user-sign-in-details') }}" class="underline-none text-decoration-none">
                  <button class="d-flex align-items-center gap-2 border-0 bg-white">
                      <iconify-icon
                          icon="fluent-mdl2:signin"
                          width="1.7rem"
                          height="1.7rem"
                      ></iconify-icon>
                      <span>Sign In</span>
                  </button>
              </a>
          </div>
          <div class="mt-3">
              <a href="{{ route('user-technical-support') }}" class="underline-none text-decoration-none">
                  <button class="d-flex align-items-center gap-2 border-0 bg-white">
                      <iconify-icon
                          icon="streamline-ultimate:headphones-customer-support-question"
                          width="1.7rem"
                          height="1.7rem"
                      ></iconify-icon>
                      <span>Technical Support</span>
                  </button>
              </a>
          </div>
            <div class="mt-3">
                <a href="{{ route('user-help-center') }}" class="underline-none text-decoration-none">
                    <button class="d-flex align-items-center gap-2 border-0 bg-white">
                        <iconify-icon
                            icon="streamline-freehand-color:information-desk-question-help"
                            width="1.7rem"
                            height="1.7rem"
                        ></iconify-icon>
                        <span>Help Center</span>
                    </button>
                </a>
            </div>
          <div class="mt-3 d-none">
              <a href="{{ route('user-problem-help') }}" class="underline-none text-decoration-none">
                  <button class="d-flex align-items-center gap-2 border-0 bg-white">
                      <iconify-icon
                          icon="la:hands-helping"
                          width="1.7rem"
                          height="1.7rem"
                      ></iconify-icon>
                      <span>Problem Help</span>
                  </button>
              </a>
          </div>
          <div class="mt-3">
              <a href="{{ route('user-about-us') }}" class="underline-none text-decoration-none">
                  <button class="d-flex align-items-center gap-2 border-0 bg-white">
                      <iconify-icon
                          icon="tabler:arrow-roundabout-right"
                          width="1.7rem"
                          height="1.7rem"
                      ></iconify-icon>
                      <span>About Us</span>
                  </button>
              </a>
          </div>
          <div class="mt-3">
              <a href="{{ route('user-settings') }}" class="underline-none text-decoration-none">
                  <button class="d-flex align-items-center gap-2 border-0 bg-white">
                      <iconify-icon
                          icon="lsicon:setting-outline"
                          width="1.7rem"
                          height="1.7rem"
                      ></iconify-icon>
                      <span>Settings</span>
                  </button>
              </a>
          </div>
        </div>
      </section>
      <!-- hero section end -->
        @include('user.profile.cashOutPasswordModal')
        @include('user.profile.bindWalletPasswordModal')
    </main>
@endsection
