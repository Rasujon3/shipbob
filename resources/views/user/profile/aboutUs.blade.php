@extends('user.layout.master')
@section('content')
    @include('user.profile.profileBackBtn')
    <!-- hero section start -->
    <section class="hero p-3">
        <div>
            <h4 class="text-center">{{ setting()->company_name ?? 'ShipBob' }}</h4>
            <a href="{{ route('user-agreement') }}" class="underline-none text-decoration-none">
                <button class="btn btn-secondary text-dark bg-secondary-subtle w-100 border-0 py-2 mt-2">
                    User Agreement
                </button>
            </a>

            <a href="{{ route('user-privacy') }}" class="underline-none text-decoration-none">
                <button class="btn btn-secondary text-dark bg-secondary-subtle w-100 border-0 py-2 mt-2">
                    Privacy
                </button>
            </a>
        </div>
    </section>
    <!-- hero section end -->
@endsection
