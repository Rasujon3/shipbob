@extends('user.layout.master')
@section('content')
    @include('user.profile.profileBackBtn')
    <!-- hero section start -->
    <section class="hero p-3">
        <div>
            <a href="{{ route('modify-withdraw-password') }}" class="underline-none text-decoration-none">
                <button
                    class="btn btn-secondary text-dark bg-secondary-subtle w-100 border-0 py-2 mt-2"
                >
                    Modify Withdraw Password
                </button>
            </a>

            <a href="{{ route('modify-login-password') }}" class="underline-none text-decoration-none">
                <button
                    class="btn btn-secondary text-dark bg-secondary-subtle w-100 border-0 py-2 mt-2"
                >
                    Change Login Password
                </button>
            </a>

        </div>
    </section>
    <!-- hero section end -->
@endsection


