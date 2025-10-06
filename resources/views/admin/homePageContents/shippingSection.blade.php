@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Shipping Section</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/settings')}}">Shipping Section
                                </a></li>
                        <li class="breadcrumb-item active">Shipping Section</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Shipping Section</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ route('update-shipping-section-content') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="img">Shipping Image <span class="required">*</span></label>
                                <input
                                    name="img"
                                    type="file"
                                    id="img"
                                    accept="image/*"
                                    class="dropify"
                                    data-height="150"
                                    data-default-file="{{ URL::to($shippingSection ? $shippingSection->img : '') }}"
                                />
                                @error('img')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Title <span class="required">*</span></label>
                                <textarea
                                    name="title"
                                    class="form-control description"
                                    id="title"
                                    placeholder="Title"
                                >
                                    {!! old('title', $shippingSection ? $shippingSection->title : "") !!}
                                </textarea>
                                @error('title')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description">Description <span class="required">*</span></label>
                                <textarea
                                    name="description"
                                    class="form-control description"
                                    id="description"
                                    placeholder="Description"
                                >
                                    {!! old('description', $shippingSection ? $shippingSection->description : "") !!}
                                </textarea>
                                @error('description')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="img2">Shipping Image 2 <span class="required">*</span></label>
                                <input
                                    name="img2"
                                    type="file"
                                    id="img2"
                                    accept="image/*"
                                    class="dropify"
                                    data-height="150"
                                    data-default-file="{{ URL::to($shippingSection ? $shippingSection->img2 : '') }}"
                                />
                                @error('img2')
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
