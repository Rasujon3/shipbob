<section>
    <div class="px-4">
        <img
            src="{{ asset($growthSection->img ?? '') }}"
            alt=""
            class="w-100"
        />
        <div>
            {!! $growthSection->title ?? '' !!}
        </div>
        <div class="d-flex gap-sm-3 gap-lg-4 mt-5">
            <div>
                <iconify-icon
                    icon="streamline-color:transfer-van-flat"
                    width="2rem"
                    height="2rem"
                ></iconify-icon>
            </div>
            <div>
                {!! $growthSection->sub_title ?? '' !!}
                <p>
                    {!! $growthSection->description ?? '' !!}
                </p>
            </div>
        </div>
    </div>
</section>
