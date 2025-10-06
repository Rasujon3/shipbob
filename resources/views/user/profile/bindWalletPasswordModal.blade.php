<!-- bind wallet account password modal start -->
<div
    class="modal fade"
    id="bind-wallet-modal"
    tabindex="-1"
    aria-labelledby="bindWalletModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="bindWalletModalLabel">
                    Bind Wallet Account
                </h1>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close"
                ></button>
            </div>
            <div class="modal-body">
                <div>
                    <form id="withdrowForm">
                        <div class="mb-3">
                            <label for="withdraw_pass" class="form-label"
                            >Withdraw Password</label
                            >
                            <input
                                type="text"
                                class="form-control"
                                id="withdraw_pass"
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

        $(document).on('submit','#withdrowForm',function(e){
            e.preventDefault();

            let withdraw_pass = $('#withdraw_pass').val();
            let user_id = "{{ auth()->user()->id }}";

            let redirectUrl = "{{ url('/add-payment-method') }}?user_id=" + user_id;

            $.ajax({
                url: "{{ url('/check-withdraw-password') }}",
                type: "POST",
                data: {
                    user_id: user_id,
                    withdraw_pass: withdraw_pass
                },
                success:function(data) {
                    if(data.status === true) {
                        toastr.success(data.message);
                        window.location.href = redirectUrl;
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr) {
                    toastr.error("Something went wrong! " + xhr.statusText);
                }
            });
        });
    });
</script>

