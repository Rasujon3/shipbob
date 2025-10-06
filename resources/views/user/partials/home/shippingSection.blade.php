<section class="py-3 mb-5">
    <img src="{{ asset($shippingSection->img ?? '') }}" alt="" class="w-100" />
    <div class="px-4">
        <div class="mt-3">
            <div>
                {!! $shippingSection->title ?? '' !!}
                <p class="lh-base fs-5">
                    {!! $shippingSection->description ?? '' !!}
                </p>
            </div>
        </div>
        <img
            src="{{ asset($shippingSection->img2 ?? '') }}"
            alt=""
            class="w-100"
        />
    </div>
</section>
