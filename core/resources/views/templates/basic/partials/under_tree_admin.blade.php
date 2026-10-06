{{-- <ul @if($isFirst) class="firstList" @endif>
    @foreach($user->allReferrals as $under)
        @if($loop->first)
            @php $layer++ @endphp
        @endif

    @php
    $hasStockMemberShip=\App\Models\StockMember::where('user_id', $under->id)->first();
    $grossStockAmount=\App\Models\UserStock::where('user_id', $under->id)->where('status', 'buy')->sum('invest_amount');
    @endphp
        <li>
            <div>
                {{ $under->fullname }}  @if($hasStockMemberShip)
                    &nbsp;&nbsp;&nbsp;&nbsp;<i style=" color: #00ff0b !important;padding: 4px 4px 2px 4px; border-radius: 5px;font-size: 13px;" class="fa fa-certificate"></i>
                @endif
                 &nbsp;&nbsp; ({{ $under->username }}) | @if($under->allReferrals) #{{$under->allReferrals->count()}} @else 0 @endif

                &nbsp;&nbsp;&nbsp;&nbsp; {{$grossStockAmount}}&nbsp;USD
            </div>
        </li>
    @endforeach
</ul> --}}


@if($user->allReferrals && $user->allReferrals->count() > 0)

<ul class="sub-child-list step-2">
    @foreach ($user->allReferrals as $user_one)
        <li class="single-child">
            @php
                    $hasStockMemberShipOne=\App\Models\StockMember::where('user_id', $user_one->id)->first();
                    $grossStockAmountOne=\App\Models\UserStock::where('user_id', $user_one->id)->where('status', 'buy')->sum('invest_amount');
            @endphp
            <p>
                <div  class="mb-0">
                    {{ $user_one->full_name }}
                @if($hasStockMemberShipOne)
                    &nbsp;&nbsp;&nbsp;&nbsp;<i style=" color: #00ff0b !important;padding: 4px 4px 2px 4px; border-radius: 5px;font-size: 13px;" class="fa fa-certificate"></i>
                @endif
                &nbsp;&nbsp;
                <a href="#" class="redirect_child"
                    style="cursor: pointer; display: unset !important;">
                    ({{ $user_one->username }})
            </a>
             | @if($user_one->allReferrals) #{{$user_one->allReferrals->count()}} @else 0 @endif
                &nbsp;&nbsp;&nbsp;&nbsp; {{$grossStockAmountOne}}&nbsp;USD

                </div>
            </p>

            @if($user_one->allReferrals && $user_one->allReferrals->count() > 0)
            <ul class="sub-child-list step-3">
                @foreach ($user_one->allReferrals as $ref)
                    <li class="single-child">
                        @php
                                $hasStockMemberShipTwo=\App\Models\StockMember::where('user_id', $ref->id)->first();
                                $grossStockAmountTwo=\App\Models\UserStock::where('user_id', $ref->id)->where('status', 'buy')->sum('invest_amount');
                        @endphp

                        <p>
                            <div  class="mb-0">
                                {{ $ref->full_name }}

                                @if($hasStockMemberShipTwo)
                                    &nbsp;&nbsp;&nbsp;&nbsp;<i style=" color: #00ff0b !important;padding: 4px 4px 2px 4px; border-radius: 5px;font-size: 13px;" class="fa fa-certificate"></i>
                                @endif
                                &nbsp;&nbsp;
                                <a href="#" class="redirect_child"
                                    style="cursor: pointer; display: unset !important;">
                                    ({{ $ref->username }})
                            </a>
                                | @if($ref->allReferrals) #{{$ref->allReferrals->count()}} @else 0 @endif
                                &nbsp;&nbsp;&nbsp;&nbsp; {{$grossStockAmountTwo}}&nbsp;USD

                            </div>
                        </p>

                        @if($ref->allReferrals && $ref->allReferrals->count()> 0 )
                        <ul class="sub-child-list step-4">
                            @foreach ($ref->allReferrals as $ref2)
                                <li class="single-child">
                                    @php
                                            $hasStockMemberShipThree=\App\Models\StockMember::where('user_id', $ref2->id)->first();
                                            $grossStockAmountThree=\App\Models\UserStock::where('user_id', $ref2->id)->where('status', 'buy')->sum('invest_amount');
                                    @endphp

                                    <p>
                                        <div class="mb-0">
                                            {{ $ref2->full_name }}
                                            @if($hasStockMemberShipThree)
                                                &nbsp;&nbsp;&nbsp;&nbsp;<i style=" color: #00ff0b !important;padding: 4px 4px 2px 4px; border-radius: 5px;font-size: 13px;" class="fa fa-certificate"></i>
                                            @endif
                                            &nbsp;&nbsp;
                                            <a href="#" class="redirect_child"
                                                style="cursor: pointer; display: unset !important;">
                                                ({{ $ref2->username }})
                                            </a>
                                 | @if($ref2->allReferrals) #{{$ref2->allReferrals->count()}} @else 0 @endif
                                            &nbsp;&nbsp;&nbsp;&nbsp; {{$grossStockAmountThree}}&nbsp;USD

                                        </div>
                                    </p>

                                    @if($ref2->allReferrals && $ref2->allReferrals->count() > 0)
                                    <ul class="sub-child-list step-5">
                                        @foreach ($ref2->allReferrals as $ref3)
                                            <li class="single-child">

                                            @php
                                                    $hasStockMemberShipFour=\App\Models\StockMember::where('user_id', $ref3->id)->first();
                                                    $grossStockAmountFour=\App\Models\UserStock::where('user_id', $ref3->id)->where('status', 'buy')->sum('invest_amount');
                                            @endphp

                                                    <div class="mb-0">
                                                        {{ $ref3->full_name }}

                                                    @if($hasStockMemberShipFour)
                                                        &nbsp;&nbsp;&nbsp;&nbsp;<i style=" color: #00ff0b !important;padding: 4px 4px 2px 4px; border-radius: 5px;font-size: 13px;" class="fa fa-certificate"></i>
                                                    @endif
                                                    &nbsp;&nbsp;
                                                    <a href="#" class="redirect_child"
                                                        style="cursor: pointer; display: unset !important;">
                                                        ({{ $ref3->username }})
                                                    </a>
                                             | @if($ref3->allReferrals) #{{$ref3->allReferrals->count()}} @else 0 @endif
                                                    &nbsp;&nbsp;&nbsp;&nbsp; {{$grossStockAmountFour}}&nbsp;USD
                                                    </div>


                                                @if($ref3->allReferrals && $ref3->allReferrals->count() > 0)
                                                <ul class="sub-child-list step-6">
                                                    @foreach ($ref3->allReferrals as $ref4)
                                                        <li class="single-child">
                                                         @php
                                                                $hasStockMemberShipFive=\App\Models\StockMember::where('user_id', $ref4->id)->first();
                                                                $grossStockAmountFive=\App\Models\UserStock::where('user_id', $ref4->id)->where('status', 'buy')->sum('invest_amount');
                                                        @endphp

                                                                <div class="mb-0">
                                                                    {{ $ref4->full_name }}

                                                                    @if($hasStockMemberShipFive)
                                                                        &nbsp;&nbsp;&nbsp;&nbsp;<i style=" color: #00ff0b !important;padding: 4px 4px 2px 4px; border-radius: 5px;font-size: 13px;" class="fa fa-certificate"></i>
                                                                    @endif
                                                                    &nbsp;&nbsp;
                                                                    <a href="#" class="redirect_child"
                                                                        style="cursor: pointer; display: unset !important;">
                                                                        ({{ $ref4->username }})
                                                                    </a>
                                                     | @if($ref4->allReferrals) #{{$ref4->allReferrals->count()}} @else 0 @endif
                                                                    &nbsp;&nbsp;&nbsp;&nbsp; {{$grossStockAmountFive}}&nbsp;USD

                                                            </div>

                                                            @if($ref4->allReferrals && $ref4->allReferrals->count() > 0)
                                                            <ul class="sub-child-list step-7">
                                                                @foreach ($ref4->allReferrals as $ref5)
                                                                    <li class="single-child">
                                                                    @php
                                                                            $hasStockMemberShipSix=\App\Models\StockMember::where('user_id', $ref5->id)->first();
                                                                            $grossStockAmountSix=\App\Models\UserStock::where('user_id', $ref5->id)->where('status', 'buy')->sum('invest_amount');
                                                                    @endphp


                                                                            <div class="mb-0">
                                                                                {{ $ref5->full_name }}
                                                                                @if($hasStockMemberShipSix)
                                                                                &nbsp;&nbsp;&nbsp;&nbsp;<i style=" color: #00ff0b !important;padding: 4px 4px 2px 4px; border-radius: 5px;font-size: 13px;" class="fa fa-certificate"></i>
                                                                                @endif
                                                                                &nbsp;&nbsp; ({{ $ref5->username }}) | @if($ref5->allReferrals) #{{$ref5->allReferrals->count()}} @else 0 @endif
                                                                                &nbsp;&nbsp;&nbsp;&nbsp; {{$grossStockAmountSix}}&nbsp;USD

                                                                        </div>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                            @endif

                                                        </li>
                                                    @endforeach
                                                </ul>
                                                @endif


                                            </li>
                                        @endforeach
                                    </ul>
                                    @endif

                                </li>
                            @endforeach
                        </ul>
                        @endif


                    </li>
                @endforeach
            </ul>
            @endif

        </li>
    @endforeach

</ul>
@else

<div>
    No Data Available
</div>
@endif

