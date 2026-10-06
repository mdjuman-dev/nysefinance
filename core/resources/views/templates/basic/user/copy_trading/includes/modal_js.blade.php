<script>
    $(document).on('click', '.buyCopyTrade', function (e){
        const id=$(this).attr('data-id');
        const name=$(this).attr('data-name');
        $('.copy_trade_id').val(id);
        $('.trade_name').text(name);



        $.ajax({
            type:'GET',
            url:'{{route('user.copy.trade.details')}}',
            data:{
                id:id,
            },

            success:function (res){

                if(res.status=='success'){

                    $('.interest_type').text(res.data.interest_type);
                    $('.interest_type_t').text(res.data.interest_type);
                    $('.cost').text('$'+res.data.cost);
                    $('.interest').text(res.data.interest+'%');
                    $('.current-balance').text(res.data.current_balance);
                    $('.final-interest').text(res.data.final_interest);
                    $('.trade-note-section').html(res.data.description);
                }

                // <div class="details-section">
                //     <h6>ROI Will Get Every <strong class="interest_type">0d</strong></h6>
                //     <h6>Trade Cost<strong class="cost">0d</strong></h6>
                //     <h6>Trade Interest<strong class="interest">0d</strong></h6>
                //     <h6>Wallet Balance: <strong class="current-balance">0.00</strong></h6>
                // </div>
                // <div class="trade-note-section">
                //
                // </div>
                //
                //

            }
        })

        $('#buyCopyTradeModal').modal('show');

    });


    $(document).on('click', '.withdrawCopyTrade', function (e){
        const id=$(this).attr('data-id');
        const name=$(this).attr('data-name');
        $('.w_copy_trade_id').val(id);
        $('.w_trade_name').text(name);
        $('#withdrawCopyTradeModal').modal('show');

    });

    function isMobileDevice() {
        return /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
    }

    window.onload = function() {
        if (!isMobileDevice()) {
            $('#mobileViewModal').modal('show');
            $('#mobileViewModal').css('background', '#000000');
        }
    }

</script>
