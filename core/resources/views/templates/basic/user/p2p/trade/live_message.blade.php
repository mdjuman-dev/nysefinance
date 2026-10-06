@foreach ($messages as $message)
    @php
        $direction = $message->sender_id == $user->id ? 'sender' : 'receiver';
    @endphp
    @include($activeTemplate . 'user.p2p.trade.single_message', ['direction' => $direction])
@endforeach
