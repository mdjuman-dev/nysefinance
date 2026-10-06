<div class="chatboard-chat-area">
    <div class="chat-box">
        <div class="chat-box__header">
            <div class="chat-box__author-content">
                <h5 class="title mb-0 text--base">
                    {{ __($trader->full_name) }}
                </h5>
                <div class="chat-box__author-info">
                    <span  class="text--success">
                        <i class="las la-thumbs-up"></i>
                        {{@$feedback->positive}}
                    </span>
                    <span  class="text--danger">
                        <i class="las la-thumbs-down"></i>
                        {{@$feedback->negative}}
                    </span>
                </div>
            </div>
        </div>
        <div class="chat-box__thread" id="message">
            @foreach ($messages as $message)
                @php
                    $direction = $message->sender_id == $user->id ? 'sender' : 'receiver';
                @endphp
                @include($activeTemplate . 'user.p2p.trade.single_message', ['direction' => $direction])
            @endforeach
        </div>
        <div class="chat-box__footer">
            <div class="chat-send-area">
                <div class="chat-send-field">
                    <form action="{{ route('user.p2p.trade.message.save', $trade->id) }}" enctype="multipart/form-data" method="POST"
                        class="send__msg">
                        @csrf
                        <div class="input-group">
                            <textarea type="text" id="message" class="form--control" name="message" placeholder="@lang('Message')..."></textarea>
                            @if ($trade->status != Status::P2P_TRADE_COMPLETED && $trade->status != Status::P2P_TRADE_CANCELED )
                                <input type="file" name="attach_file" class="attach_file" accept=".jpg, .jpeg, .png, .gif, .bmp, .webp, .svg, .tiff, .ico, .PNG, .JPEG, .JPG">
                                <i class="fa fa-file icon_attach_file"></i>
                                <button type="submit" class="btn--base btn-sm chat-send-btn">
                                    <i class="las la-paper-plane"></i>
                                </button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@push('style')

    <style>
        .icon_attach_file{
            border-radius: 30px !important;
            position: absolute;
            right: 15%;
            top: 5px;
            height: 40px;
            width: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #3c3a38;
            cursor: pointer;
        }
        .attach_file{
            display: none;
        }
        .u-download,.sender-download,.receiver-download{
            padding: 10px 5px;
            color: red;
            cursor: pointer;
            font-size: 15px;
        }
    </style>

@endpush

@push('script')
    <script>
        "use strict";
        (function($) {

            $(".send__msg").on('submit', function(e) {
               e.preventDefault();
               $(".skeleton").removeClass(".skeleton");
                const token     = "{{ csrf_token() }}";
                const formData  = new FormData($(this)[0]);
                const action    = $(this).attr('action');
                const submitBtn = $(this).find(`button[type=submit]`);

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    url: action,
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        submitBtn.attr('disabled',true);
                    },
                    complete: function() {
                        submitBtn.attr('disabled',false);
                    },
                    success: function(resp) {
                        if (resp.success) {
                            $('#message').append(resp.data.html);
                            scollBottomChatBox();
                            $(".send__msg").trigger('reset');
                        } else {
                            notify("error", resp.message);
                        }
                    },
                    error: function(e) {
                        notify("error", "@lang('something went wrong')");
                    }
                });
            });

            function scollBottomChatBox () {
                const $element = $('#message');
                $element.scrollTop($element[0].scrollHeight);
             }

             function  messageReceived(data) {
                $('#message').append(data.html);
                setTimeout(() => {
                    $('#message').find(".skeleton").removeClass("skeleton");
                }, 1000);
                scollBottomChatBox();
            }

             scollBottomChatBox();

             pusherConnection(`p2p-message-{{$trade->id}}-{{$user->id}}`, messageReceived);

             $(".skeleton").removeClass("skeleton");

        })(jQuery);

        $(document).on('click', '.icon_attach_file', function (e){
            $('.attach_file').trigger('click');
        });

        function fetchMessages(){
            $.ajax({
                'type':'GET',
                url:'{{ route('user.p2p.trade.message.live', $trade->id) }}',

                success:function(res){
                    if(res.status=='success'){
                        $('#message').html(res.data);
                        const $element = $('#message');
                        $element.scrollTop($element[0].scrollHeight);
                    }
                }
            })
        }

        setInterval(() => {
            fetchMessages();
        }, 30000);


        $(document).on('click', '.click-to-download', function (e){
            const file=$(this).attr('data-url');
            if(file){
                location.href=file;
            }
        });
    </script>
@endpush


@push('script-lib')
    <script src = "{{ asset('assets/global/js/pusher.min.js') }}"></script>
    <script src = "{{ asset('assets/global/js/broadcasting.js') }}"></script>
@endpush
