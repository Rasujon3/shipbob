@extends('user.layout.master')
@section('content')
    <section>
        <div class="py-3 px-4">
            <div>
                <h3>Modify <span class="text-primary fw-semibold">Login Password</span></h3>
            </div>
            <div class="bg-dark mt-auto px-5 py-5">
                <div>
                    <form action="{{ route('update-login-password') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="current_login_password" class="form-label text-white">Current Login Password</label
                            >
                            <input
                                type="password"
                                name="current_login_password"
                                class="form-control"
                                id="current_login_password"
                                placeholder="**********"
                            />
                        </div>
                        @error('current_login_password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <div class="mb-3">
                            <label for="new_login_password" class="form-label text-white">New Login Password</label>
                            <input
                                type="password"
                                name="new_login_password"
                                class="form-control"
                                id="new_login_password"
                                placeholder="**********"
                            />
                        </div>
                        @error('new_login_password')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <div class="mb-3">
                            <label for="new_login_password_confirmation" class="form-label text-white">Confirm Login Password</label
                            >
                            <input
                                type="password"
                                name="new_login_password_confirmation"
                                class="form-control"
                                id="new_login_password_confirmation"
                                placeholder="**********"
                            />
                        </div>
                        @error('new_login_password_confirmation')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
