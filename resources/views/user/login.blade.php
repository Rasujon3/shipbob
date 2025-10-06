@extends('user.layout.master')
@section('content')
    <section>
        <div class="py-3 px-4">
            <div>
                {!! $loginPageContents->name ?? '' !!}
            </div>
            {!! $loginPageContents->title ?? '' !!}
            {!! $loginPageContents->description ?? '' !!}
        </div>
        <div class="bg-warning mt-auto px-5 py-5"
             style="background: url('{{ asset($loginPageContents?->img) }}') no-repeat center center; background-size: cover;"
        >
            <div>
                <form action="{{ route('user-login') }}" method="post">
                    @csrf
                    <!-- Hidden field for lat/long -->
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    <div class="mb-3">
                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            id="exampleInputEmail1"
                            aria-describedby="emailHelp"
                            placeholder="Enter username"
                        />
                    </div>
                    <div class="mb-3">
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            id="exampleInputPassword1"
                            placeholder="Password"
                        />
                    </div>

                    <div class="text-end">
                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal" class="">
                            Forgot password?
                        </a>
                    </div>

                    <button type="submit" class="btn btn-primary">Log in</button>

                    <div id="emailHelp" class="form-text text-primary text-center">
                        Don't have any account,
                        <button
                            type="button"
                            data-bs-toggle="modal"
                            data-bs-target="#signup-modal"
                            class="btn btn-link text-primary fw-medium"
                        >
                            Sign up
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <!-- login end -->
    @include('user.signUpModal')
    @include('user.forgetPasswordModal')
    <footer class="">
        <div class="text-center py-3">
            <small
            >&copy; {{ date('Y') }} Copyright Right Reserved by
                <a href="{{ route('user-index') }}" class="text-primary text-decoration-none">{{ setting()->company_name ?? 'ShipBob' }}</a></small
            >
        </div>
    </footer>
@endsection

@push('scripts')
    <script>
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                document.getElementById("latitude").value = position.coords.latitude;
                document.getElementById("longitude").value = position.coords.longitude;
            });
        }
    </script>
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let myModal = new bootstrap.Modal(document.getElementById("signup-modal"));
                myModal.show();
            });
        </script>
    @endif
@endpush
