<section class="py-3 mb-5">
    <div class="px-4">
        <div class="mt-3">
            <div>
                {!! $deliverySection->title ?? '' !!}
                <p class="lh-base fs-5 text-center">
                    {!! $deliverySection->description ?? '' !!}
                </p>
            </div>
        </div>
    </div>
    <img src="{{ asset($deliverySection->img ?? '') }}" alt="" class="w-100" />
</section>
