@props([
    'isCustom' => false
])
<div id="confirmationModal" class="modal fade @if($isCustom) custom--modal  @endif" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="question"></p>

                    <div class="ex-section mt-3 d-none">
                        <div class="form-group mb-1">
                            <strong>Requested Amount:</strong> <span class="requested-amount"></span>
                        </div>
                        <div class="form-group">
                            <lable>Enter Deposit Amount</lable>
                            <input type="text" placeholder="Enter deposit amount" class="form-control" name="custom_amount">
                        </div>
                    </div>

                    <div class="form-group release-section mt-3 d-none">
                        <label for="">Security Pin</label>
                        <input type="text" class="form-control" name="security_pin" placeholder="Enter Security PIN">
                    </div>


                    <div class="form-group release-section mt-3 d-none">
                        <label for="">Verification OTP</label>
                        <div class="input-group mb-3 sec-send-otp">
                            <input type="text" class="form-control" name="p2p_otp" placeholder="Enter OTP">
                            <div class="input-group-append">
                                <span class="input-group-text sendOtp" id="sendOtpBtn">
                                        Send Otp
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn {{ $isCustom ? 'btn-dark btn--dark btn--sm' :  'btn--dark' }}" data-bs-dismiss="modal">@lang('No')</button>
                    <button type="submit" class="btn {{ $isCustom ? 'btn--base btn--sm' :  'btn--primary' }}  ">@lang('Yes')</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')

<script>
    (function ($) {
        "use strict";
        $(document).on('click','.confirmationBtn', function (e) {
            var modal   = $('#confirmationModal');
            let data    = $(this).data();

            const data_method_code=$(this).attr('data-method-code');

            if(data_method_code && data_method_code=='1000'){
                $('.ex-section').removeClass('d-none');

                const amount=$(this).attr('data-amount');
                const data_currency=$(this).attr('data-currency');
                $('.requested-amount').text(amount+' '+data_currency)

            }

            const cus_date=$(this).attr('data-type');

            if(cus_date && cus_date=='release'){
                $('.release-section').removeClass('d-none');
            }else{
                $('.release-section').addClass('d-none');
            }

            modal.find('.question').text(`${data.question}`);
            modal.find('form').attr('action', `${data.action}`);
            modal.modal('show');
        });
    })(jQuery);
</script>
@endpush
