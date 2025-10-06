<section class="py-3 mb-5" style="background-color: #d4e7f0">
    <div class="px-4">
        <div class="mt-3">
            <div>
                {!! $courierSection->title ?? '' !!}
                <p class="lh-base fs-5 text-center">
                    {!! $courierSection->description ?? '' !!}
                </p>
            </div>
        </div>
    </div>
    <video
        src="{{ asset($courierSection->video ?? '') }}"
        autoplay
        loop
        muted
        class="w-100"
    ></video>
</section>
