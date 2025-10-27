@extends('admin_master')
@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Add Round Trial Task Product</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{URL::to('/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/rtt/rtt-products')}}">All Round Trial Task Product</a></li>
                        <li class="breadcrumb-item active">Add Round Trial Task Product</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <section class="content">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Add Round Trial Task Product</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{route('rtt-products.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Product Name <span class="required">*</span></label>
                                <input type="text" name="name" class="form-control" id="name"
                                    placeholder="Product Name" required="" value="{{old('name')}}">
                                @error('name')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price">Product Price (BDT) <span class="required">*</span></label>
                                <input type="text" name="price" class="form-control numericInput" id="price"
                                    placeholder="Product Price" required="" value="{{old('price')}}">
                                @error('price')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description">Description <span class="required">*</span></label>
                                <textarea class="form-control description" required="" name="description">{!!old('description')!!}</textarea>
                                @error('description')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="commission">Commission BDT <span class="required">*</span></label>
                                <input type="text" name="commission" class="form-control numericInput" id="commission"
                                    placeholder="Commission" required="" value="{{old('commission')}}">
                                @error('commission')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="file">Image <span class="required">*</span></label>
                                <input
                                    name="file"
                                    type="file"
                                    id="file"
                                    accept="image/*"
                                    class="dropify"
                                    data-height="150"
{{--                                    data-default-file="{{ URL::to($setting ? $setting->company_logo : '') }}"--}}
                                />
                                @error('file')
                                    <span class="alert alert-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group w-100 px-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
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

  <script src="{{asset('custom/multiple_files.js')}}"></script>

  <script>

  </script>

@endpush
