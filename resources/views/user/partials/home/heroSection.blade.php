<!-- hero section start -->
<section class="hero">
    <div class="px-4 py-3">
        <div>
            {!! $heroSection->title ?? ''  !!}
            <p class="text-primary w-75">
                {!! $heroSection->sub_title ?? ''  !!}
            </p>

            <a href="{{ route('user-setoff') }}" class="btn btn-dark rounded-5 px-4 py-2">
                <span class="text-uppercase">Start Now</span>
                <iconify-icon
                    icon="heroicons:arrow-up-right-solid"
                    width="1.2em"
                    height="1.2em"
                ></iconify-icon>
            </a>
        </div>

        <div class="w-full overflow-hidden px-5 w-75 mx-auto mt-3">
            <img src="{{ asset($heroSection->banner_img ?? '') }}" class="img-fluid" alt="banner"/>
        </div>
        <div class="mt-3 mb-5">
            {!! $heroSection->slogan ?? ''  !!}
        </div>
    </div>
</section>
<!-- hero section end -->
