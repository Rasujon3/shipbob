@extends('user.layout.master')
@section('content')
    @include('user.profile.profileBackBtn')
    <!-- hero section start -->
    <section class="problem-help bg-white mt-1">
    <div class="p-3">
      <div class="mb-3 border border-secondary-subtle rounded p-3">
        <form>
          <div>
            <label for="exampleFormControlTextarea1" class="form-label"
              >Message:</label
            >
            <textarea
              class="form-control"
              id="exampleFormControlTextarea1"
              rows="5"
              placeholder="Please enter your message"
            ></textarea>
          </div>
          <button class="btn btn-dark px-3 py-1 w-100 mt-3">Submit</button>
        </form>
      </div>
    </div>
    </section>
    <!-- hero section end -->
@endsection
