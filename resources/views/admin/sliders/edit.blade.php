@extends('admin_master')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Edit Slider</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{URL::to('/sliders')}}">All Slider
                                </a></li>
                            <li class="breadcrumb-item active">Edit Slider</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <section class="content">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Edit Slider</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{route('sliders.update',$slider->id)}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="card-body">
                        <div class="row">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="img">Slider Image <span class="required">*</span></label>
                                        <input
                                            name="img"
                                            type="file"
                                            id="img"
                                            accept="image/*"
                                            class="dropify"
                                            data-height="150"
                                            data-default-file="{{ URL::to($slider->img ?? '') }}"
                                        />
                                        @error('img')
                                        <span class="alert alert-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group w-100 px-2">
                                <button type="submit" class="btn btn-success">Save Changes</button>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </form>
            </div>
        </section>
    </div>

@endsection

@push('scripts')

    <script>

    </script>

@endpush
