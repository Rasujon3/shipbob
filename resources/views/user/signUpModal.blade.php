<!-- sign up modal start -->
<div
    class="modal fade"
    id="signup-modal"
    tabindex="-1"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">{!! $signUpContents->title ?? '' !!}</h1>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <div>
                    <form action="{{ route('user-sign-up') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Username <span class="text-danger required">*</span></label>
                            <input
                                type="text"
                                name="username"
                                class="form-control"
                                id="username"
                                aria-describedby="emailHelp"
                                placeholder="Username"
                                required=""
                                value="{{ old('username') }}"
                            />

                        </div>

                        @error('username')
                            <span class="alert alert-danger w-100 d-block">{{ $message }}</span>
                        @enderror

                        <div class="mb-3">
                            <label for="mobileNumber" class="form-label">Mobile Number <span class="text-danger required">*</span></label>
                            <input
                                type="text"
                                name="phone"
                                class="form-control"
                                id="mobileNumber"
                                aria-describedby="emailHelp"
                                placeholder="Enter your mobile number"
                                required=""
                                value="{{ old('phone') }}"
                            />

                        </div>

                        @error('phone')
                        <span class="alert alert-danger w-100 d-block">{{ $message }}</span>
                        @enderror

                        <div class="mb-3">
                            <label for="password" class="form-label">Password <span class="text-danger required">*</span></label>
                            <input
                                type="password"
                                name="password"
                                class="form-control"
                                id="password"
                                placeholder="**********"
                                required=""
                                value="{{ old('password') }}"
                            />

                        </div>

                        @error('password')
                        <span class="alert alert-danger w-100 d-block">{{ $message }}</span>
                        @enderror

                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm Password <span class="text-danger required">*</span></label>
                            <input
                                type="password"
                                name="password_confirmation"
                                class="form-control"
                                id="confirmPassword"
                                placeholder="**********"
                                required=""
                                value="{{ old('password_confirmation') }}"
                            />

                        </div>

                        @error('confirm_password')
                        <span class="alert alert-danger w-100 d-block">{{ $message }}</span>
                        @enderror

                        <div class="mb-3">
                            <label for="withdrawalPassword" class="form-label">Withdrawal Password <span class="text-danger required">*</span></label>
                            <input
                                type="password"
                                name="withdraw_password"
                                class="form-control"
                                id="withdrawalPassword"
                                placeholder="**********"
                                required=""
                                value="{{ old('withdraw_password') }}"
                            />

                        </div>

                        @error('withdraw_password')
                        <span class="alert alert-danger w-100 d-block">{{ $message }}</span>
                        @enderror

                        <div class="mb-3">
                            <label for="invitation_code" class="form-label">Invitation Code <span class="text-danger required">*</span></label>
                            <input
                                type="text"
                                name="invitation_code"
                                class="form-control"
                                id="invitation_code"
                                aria-describedby="emailHelp"
                                placeholder="Invitation Code"
                                required=""
                                value="{{ old('invitation_code') }}"
                            />
                        </div>
                        @error('invitation_code')
                            <span class="alert alert-danger w-100 d-block">{{ $message }}</span>
                        @enderror

                        <button
                            type="submit"
                            class="btn btn-dark py-2 w-100 d-flex gap-2 align-items-center justify-content-center"
                        >
                            <span>Sign up</span>
                            <iconify-icon
                                icon="maki:arrow"
                                width="1.2em"
                                height="1.2em"
                            ></iconify-icon>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- sign up modal end -->

