@extends('user.layout.master')
@section('content')
    <section>
        <div class="py-3 px-4">
            <div>
                <h3>Modify <span class="text-primary fw-semibold">Withdraw Password</span></h3>
            </div>
            <div class="bg-dark mt-auto px-5 py-5">
                <div>
                    <form action="{{ route('update-withdraw-password') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="current_withdraw_password" class="form-label text-white">Current Withdraw Password</label
                            >
                            <input
                                type="password"
                                name="current_withdraw_password"
                                class="form-control"
                                id="current_withdraw_password"
                                placeholder="**********"
                            />
                        </div>
                        @error('current_withdraw_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <div class="mb-3">
                            <label for="new_withdraw_password" class="form-label text-white">New Withdraw Password</label
                            >
                            <input
                                type="password"
                                name="new_withdraw_password"
                                class="form-control"
                                id="new_withdraw_password"
                                placeholder="**********"
                            />
                        </div>
                        @error('new_withdraw_password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <div class="mb-3">
                            <label for="new_withdraw_password_confirmation" class="form-label text-white">Confirm Withdraw Password</label
                            >
                            <input
                                type="password"
                                name="new_withdraw_password_confirmation"
                                class="form-control"
                                id="new_withdraw_password_confirmation"
                                placeholder="**********"
                            />
                        </div>
                        @error('new_withdraw_password_confirmation')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
