@if ($message->admin_id)
    <div class="single-message message--left">
        <div class="message-content-outer">
            <div class="message-content-outer-wrapper">
                <div class="chat-author" style="background: transparent;">
                    <img src="{{ siteFavicon() }}"/>
                </div>
                <div class="message-content">
                    <p class="message-text skeleton">
                        @php echo $message->message; @endphp
                        @if($message->attachment)
                            <a target="_blank" href="{{route('user.p2p.trade.message.download.file',['f'=>$message->attachment])}}">
                                <i class="fa fa-download u-download"></i>
                            </a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="single-message @if ($direction == 'sender') message--right @else  message--left @endif">
        <div class="message-content-outer">
            <div class="message-content-outer-wrapper">
                @if ($direction == 'sender')
                    @if($message->attachment)
                        <a target="_blank" href="{{route('user.p2p.trade.message.download.file',['f'=>$message->attachment])}}">
                        <i class="fa fa-download sender-download"></i>
                        </a>
                    @endif

                    <div class="message-content">
                        <p class="message-text">
                            @php echo $message->message; @endphp
                        </p>
                    </div>
                    <div class="chat-author">
                        <p>{{ __(firstTwoCharacter($message->sender->full_name)) }}</p>
                    </div>
                @else
                    <div class="chat-author">
                        <p>{{ __(firstTwoCharacter(@$message->sender->full_name)) }}</p>
                    </div>
                    <div class="message-content">
                        <p class="message-text skeleton">
                            @php echo $message->message; @endphp
                        </p>
                    </div>
                    @if($message->attachment)
                        <a target="_blank" href="{{route('user.p2p.trade.message.download.file',['f'=>$message->attachment])}}">
                            <i class="fa fa-download receiver-download"></i>
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>

@endif
