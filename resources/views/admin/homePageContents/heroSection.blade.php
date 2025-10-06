@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Hero Section</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/settings')}}">Hero Section
                                </a></li>
                        <li class="breadcrumb-item active">Hero Section</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Hero Section</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('update-hero-section-content') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Title <span class="required">*</span></label>
                                <textarea
                                    name="title"
                                    class="form-control description"
                                    id="title"
                                    placeholder="Title"
                                >
                                    {!! old('title', $heroSection ? $heroSection->title : "") !!}
                                </textarea>
                                @error('title')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="sub_title">Sub Title <span class="required">*</span></label>
                                <textarea
                                    name="sub_title"
                                    class="form-control description"
                                    id="sub_title"
                                    placeholder="Sub Title"
                                >
                                    {!! old('sub_title', $heroSection ? $heroSection->sub_title : "") !!}
                                </textarea>
                                @error('sub_title')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="slogan">Slogan <span class="required">*</span></label>
                                <textarea
                                    name="slogan"
                                    class="form-control description"
                                    id="slogan"
                                    placeholder="Description"
                                >
                                    {!! old('slogan', $heroSection ? $heroSection->slogan : "") !!}
                                </textarea>
                                @error('slogan')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="banner_img">Banner Image <span class="required">*</span></label>
                                <input
                                    name="banner_img"
                                    type="file"
                                    id="banner_img"
                                    accept="image/*"
                                    class="dropify"
                                    data-height="150"
                                    data-default-file="{{ URL::to($heroSection ? $heroSection->banner_img : '') }}"
                                />
                                @error('banner_img')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
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
