<!-- Global Section start -->
<section class="py-3 mb-5">
    <div class="px-4">
        <div class="mt-3">
            <div>
                {!! $globalSection->title ?? '' !!}
                <p class="lh-base fs-5">
                    {!! $globalSection->description ?? '' !!}
                </p>
            </div>
        </div>
        <img
            src="{{ asset($globalSection->img ?? '') }}"
            alt=""
            class="w-100"
        />
    </div>
</section>
<!-- Global Section end -->
