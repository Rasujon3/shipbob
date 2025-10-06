@if(count($sliders) > 0)
    <!-- carousel start  -->
    <section class="overflow-hidden my-4">
        <div class="setoff-carousel">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Slides -->
                @foreach($sliders as $slider)
                    <div class="swiper-slide">
                        <img
                            src="{{ asset($slider->img ?? '') }}"
                            style="width: 100%"
                            class="object-fit-cover"
                            alt=""
                        />
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- carousel end  -->
@endif
