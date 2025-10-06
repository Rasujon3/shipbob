<!-- video section 1 start -->
<section class="pb-5 position-relative">
    <div
        style="height: 5px; width: 100%; background-color: white"
        class="position-absolute top-0"
    ></div>
    <video
        src="{{ asset($heroSection->video_url ?? '') }}"
        muted
        loop
        autoplay
        class="object-fit-fill"
        style="width: 100%; background-color: white"
    ></video>
</section>
<!-- video section 1 end -->
