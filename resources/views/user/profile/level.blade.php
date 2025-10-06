@extends('user.layout.master')
@section('content')
    @include('user.profile.profileBackBtn')
    <!-- hero section start -->
    <section class="level-container">
        @if(count($levels) > 0)
            @foreach($levels as $level)
                <div class="position-relative m-2 rounded-2 bg-secondary text-white bg-gradient">
                    <div class="py-4 px-3">
                        <div>
                            <h2 class="text-center">{{ $level->title ?? '' }}</h2>
                            <p class="text-center">{{ $level->description ?? '' }}</p>
                        </div>
                    </div>
                    @if($level->assignLevel && $level->assignLevel->contains('user_id', Auth::user()->id))
                        <div class="position-absolute top-0 start-0">
                            <h6 class="p-3">My Level</h6>
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <p>No Level Found.</p>
        @endif
    </section>
    <!-- hero section end -->
@endsection
