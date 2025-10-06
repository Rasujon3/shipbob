@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Video Section</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/settings')}}">Video Section
                                </a></li>
                        <li class="breadcrumb-item active">Video Section</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Video Section</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('update-set-off-video-section-content') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="video">Video <span class="required">*</span></label>
                                <input
                                    name="video"
                                    type="file"
                                    id="video"
                                    accept="video/*"
                                    class="dropify"
                                    data-height="150"
                                />
                                @error('video')
                                <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div id="video-preview" class="mt-3">
                                @if($videoSection && $videoSection->video)
                                    <video width="320" height="240" controls>
                                        <source src="{{ URL::to($videoSection->video) }}" type="video/mp4">
                                        Your browser is not enable to play the video.
                                    </video>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group w-100">
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </div>
                    <!-- /.card-body -->
            </form>
        </div>
    </section>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            // Dropify সক্রিয় করা
            $('.dropify').dropify();

            // ভিডিও লাইভ প্রিভিউ
            $('#video').on('change', function (event) {
                let file = event.target.files[0];
                let previewContainer = $('#video-preview');

                if (file && file.type.startsWith('video/')) {
                    let videoURL = URL.createObjectURL(file);
                    previewContainer.html(`
                    <video width="320" height="240" controls>
                        <source src="${videoURL}" type="${file.type}">
                        Your browser is not enable to play the video.
                    </video>
                `);
                } else {
                    previewContainer.html('<p class="text-danger">Select Video File</p>');
                }
            });
        });
    </script>
@endpush

