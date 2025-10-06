@extends('user.layout.master')
@section('content')
    @include('user.profile.profileBackBtn')
    <!-- hero section start -->
    <section class="hero p-2">
        <div class="accordion" id="accordionExample">
            @if(count($helpCenter) > 0)
                @foreach($helpCenter as $help)
                    @php
                        $collapseId = 'collapse' . $loop->index; // ইউনিক আইডি
                    @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $loop->index }}">
                            <button
                                class="accordion-button collapsed"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $collapseId }}"
                                aria-expanded="false"
                                aria-controls="{{ $collapseId }}"
                            >
                                <small>{!! $help->title ?? '' !!}</small>
                            </button>
                        </h2>
                        <div
                            id="{{ $collapseId }}"
                            class="accordion-collapse collapse"
                            aria-labelledby="heading{{ $loop->index }}"
                            data-bs-parent="#accordionExample"
                        >
                            <div class="accordion-body">
                                {!! $help->description ?? '' !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>
    <!-- hero section end -->
@endsection
