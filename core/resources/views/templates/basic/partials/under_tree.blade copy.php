<ul @if($isFirst) class="firstList" @endif>
    @foreach($user->allReferrals as $under)
        @if($loop->first)
        @php $layer++ @endphp
        @endif

        @php
        $hasStockMemberShip=\App\Models\StockMember::where('user_id', $under->id)->first();
        $grossStockAmount=\App\Models\UserStock::where('user_id', $under->id)->where('status', 'buy')->sum('invest_amount');
        $className='';
        @endphp
    <li>
        <div>
            {{ $under->fullname }} @if($hasStockMemberShip)
            @php $className=''
            <!-- &nbsp;&nbsp;&nbsp;&nbsp;
            <i style=" color: #00ff0b !important;padding: 4px 4px 2px 4px; border-radius: 5px;font-size: 13px;"
                class="fa fa-certificate"></i> -->
            @endif
            &nbsp;&nbsp; ({{ $under->username }}) | @if($under->allReferrals) #{{$under->allReferrals->count()}} @else 0 @endif

            &nbsp;&nbsp;&nbsp;&nbsp; {{$grossStockAmount}}&nbsp;USD
        </div>



        {{-- @if(($under->referrals->count()) > 0 && ($layer < $maxLevel))--}}
        {{-- @include($activeTemplate.'partials.under_tree',['user'=>$under,'layer'=>$layer,'isFirst'=>false])--}}
        {{-- @endif--}}
    </li>
    @endforeach
</ul>
