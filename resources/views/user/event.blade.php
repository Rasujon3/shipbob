@extends('user.layout.master')

@section('content')
    <section>
        @if(count($events) > 0)
            <div class="banner mt-2">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    @foreach($events as $event)
                        <div class="swiper-slide position-relative">
                            <img
                                src="{{ asset($event->img ?? '') }}"
                                style="width: 100%; object-fit: cover"
                                alt="{{ $event->title ?? '' }}"
                            />
                            <div class="image-title-overlay">
                                {{ $event->title ?? '' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <p>No event found.</p>
        @endif
            {{--
                        @if(count($packages) > 0)
                            <div class="container mt-4">
                                <h4 class="mb-3">Packages</h4>
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="packagesTable">
                                        <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th>Title</th>
                                            <th>Recharge (৳)</th>
                                            <th>Bonus (৳)</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($packages as $index => $package)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $package->title }}</td>
                                                <td>{{ $package->recharge_amount }}</td>
                                                <td>{{ $package->bonus_amount }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <p>No package found.</p>
                        @endif
                        --}}
    </section>

    <style>
        .swiper-slide {
            position: relative;
        }

        .image-title-overlay {
            position: absolute;
            top: 95%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            background-color: rgba(0, 0, 0, 0.5); /* Optional: semi-transparent background */
            padding: 10px 20px;
            font-size: 24px;
            font-weight: bold;
            border-radius: 8px;
            text-align: center;
        }

    </style>
@endsection
