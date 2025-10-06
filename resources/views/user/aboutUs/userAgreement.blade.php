@extends('user.layout.master')
@section('content')
    @include('user.profile.profileBackBtn')
    <section class="px-3 bg-white py-1 m-2 rounded-2">
        @if($aboutUs && $aboutUs->user_agreement)
            {!! $aboutUs->user_agreement ?? '' !!}
        @else
            <p>Nothing found.</p>
        @endif


    </section>
@endsection
