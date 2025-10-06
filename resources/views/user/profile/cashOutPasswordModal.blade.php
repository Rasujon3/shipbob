<!-- withdraw/cash-out password modal start -->
<div
    class="modal fade"
    id="cashout-modal"
    tabindex="-1"
    aria-labelledby="cashoutModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="cashoutModalLabel">Cash Out</h1>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <div>
                    <form id="cashoutForm">
                        <div class="mb-3">
                            <label for="withdraw_password" class="form-label"
                            >Withdraw Password</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="withdraw_password"
                                placeholder="Enter withdraw password"
                            />
                        </div>
                        <button
                            type="submit"
                            class="btn btn-dark py-2 w-100 d-flex gap-2 align-items-center justify-content-center"
                        >
                            <span>Submit</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- withdraw/cash-out password modal end -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
   $(document).ready(function(){
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }
       });
      $(document).on('submit','#cashoutForm',function(e){
        e.preventDefault();
        let withdraw_pass = $('#withdraw_password').val();
        let user_id = "{{user()->id}}";
        let redirectUrl = "{{url('/cashout')}}?user_id="+user_id;
        $.ajax({

            url: "{{url('/check-withdraw-password')}}",

                 type:"POST",
                 data:{'user_id':user_id, 'withdraw_pass':withdraw_pass},
                 success:function(data) {

                    if(data.status === true)
                    {
                        toastr.success(data.message);
                        window.location.href=redirectUrl;
                    }else{
                        toastr.error(data.message);
                    }

            },

        });
      });
   });
</script>
