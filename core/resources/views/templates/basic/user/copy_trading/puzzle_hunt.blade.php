@extends($activeTemplate . 'layouts.master')

@push('topContent')

@endpush
@push('ip-css')
    <link rel="stylesheet" href="{{asset('core/public/style.css')}}">

    <style>

        .icon{
            font-size: 13px;
            color: #fff;
        }
        .services-text{
            font-size: 14px;
            font-weight: 500;
            color: #fff;
        }
        .services-title{
            font-size: 15px;
            margin: 15px 0;
            color: #636060;
        }
        .puzzleHunt-header{
            background-image: url('{{asset('core/public/img/image-1a549c3fa5de469aab6755bc5d40f3fe.png')}}');
            background-size: cover;
            background-repeat: no-repeat;
            display: flex;
            justify-content: space-between;
            background-size: cover;
            background-position: center;
            background-color: rgb(0 0 0 / 45%);
            background-blend-mode: multiply;
        }
        .puzzleHunt-header-content{
            padding-right: 30px;
            width: 600px;
            padding-top: 30px;
            padding-bottom: 80px;
        }
        .puzzleHunt-header-content .title{
            font-size: 26px;
            color: #fff;
            margin: 0;
            font-weight: 700;
        }
        .puzzleHunt-header-content .subtitle{
            font-size: 15px;
            color: #d4d4d4;
            margin: 0;
            font-weight: 400;
        }
        .puzzleHunt-body-content{
            /* display: flex; */
            margin-top: 15px;
            position: relative;
            background: linear-gradient(to right, #67400d 0%, #000000 100%);
            border: 1px solid #323232;
            padding: 30px 10px;
            border-radius: 8px;
        }
        .votes-contents-box-complet {
            display: flex;
            position: absolute;
            width: 100%;
            top: 0;
            justify-content: flex-end;
            align-items: start;
            right: 0;
        }

        .votes-contents-box-complet-text {
            color: #20b26c;
            background: rgb(32 178 108 / 31%);
            border-radius: 0px 8px;
            font-size: 14px;
            line-height: 18px;
            padding: 3px 10px;
            text-wrap: nowrap;
        }
        .puzzleHunt-body-content-data{
            display: flex;
            align-items: center;
        }
        .puzzleHunt-body-content-data .img{
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #404347;
            display: flex;
            justify-content: center;
            align-items: center;
            border: 2px solid #404347;
        }
        .puzzleHunt-body-content-data .img img{
            width: 35px;
            height: 35px;
            border-radius: 50%;
        }
        .puzzleHunt-body-content-data .text{
            margin-left: 10px;
            font-weight: 700;
            font-size: 21px;
            color: #fff;
        }
        .ends-task-time{
            display: flex;
            align-items: center;
            gap: 2px;
        }
        .ends-task-time .number{
            border-radius: 2px;
            background: rgba(192, 210, 231, .12);
            padding: 0 4px;
            height: 24px;
            text-align: center;
            font-size: 17px;
            font-weight: 700;
            line-height: 22px;
            color: #fff;
        }
        .ends-task-time .word{
            color: #adb1b8;
            font-size: 13px;
            font-weight: 400;
            margin-right: 2px;
        }
        .text-left{
            text-align: left;
        }
        .Trading-price{
            text-align: right;
            font-size: 15px;
            color: #71757a;
        }
        .total-prize-pool{
            width: 170px;
            font-size: 13px;
            color: #bbbbbb;
            line-height: 18px;
            word-wrap: break-word;
            font-weight: 400;
        }
        .total-prize-pool-amount{
            width: 170px;
            font-size: 21px;
            color: #fff;
            line-height: 18px;
            word-wrap: break-word;
            font-weight: 700;
        }
        .btn-Subscribe{
            background: rgb(247, 166, 0) !important;
            color: rgb(16, 16, 20) !important;
            border: none !important;
            font-size: 18px;
            font-weight: 700;
            width: 100%;
            padding-top: 12px;
            padding-bottom: 12px;
            margin-top: 10px;
            border-radius: 10px;
        }
        @media (min-width: 992px) {
            .puzzleHunt-header-content .title {
                font-size: 20px;
            }
            .puzzleHunt-header-content .subtitle {
                font-size: 16px;
            }
            .puzzleHunt-header {
                padding: 50px 50px;
                background-position: center;
            }
            .owl-nav-link {
                font-size: 16px;
                padding: 10px 15px;
            }
            .ends-task-time {
                margin-top: 10px;
                margin-bottom: 10px;
                justify-content: flex-end;
            }
            .btn-Subscribe {
                width: 10%;
                font-size: 16px;
            }
            .float-right{
                float: right;
            }
            .puzzleHunt-body-content-data .text {
                font-size: 16px;
            }
            .total-prize-pool {
                font-size: 16px;
            }
            .total-prize-pool-amount {
                font-size: 22px;
                line-height: 30px;
            }
            .Trading-price {
                font-size: 16px;
            }
            .ends-task-time .number {
                height: 25px;
                font-size: 16px;
                line-height: 26px;
            }
            .ends-task-time .word {
                font-size: 16px;
                margin-right: 5px;
            }
        }
        @media (min-width: 768px) {
            .puzzleHunt-header-content .title {
                font-size: 20px;
            }
            .puzzleHunt-header-content .subtitle {
                font-size: 16px;
            }
            .puzzleHunt-header {
                padding: 50px 50px;
                background-position: center;
            }
            .owl-nav-link {
                font-size: 16px;
                padding: 10px 15px;
            }
            .ends-task-time {
                margin-top: 10px;
                margin-bottom: 10px;
                justify-content: flex-end;
            }
            .btn-Subscribe {
                width: 15%;
                font-size: 16px;
            }
            .float-right{
                float: right;
            }
            .puzzleHunt-body-content-data .text {
                font-size: 16px;
            }
            .total-prize-pool {
                font-size: 16px;
            }
            .total-prize-pool-amount {
                font-size: 22px;
                line-height: 30px;
            }
            .Trading-price {
                font-size: 16px;
            }
            .ends-task-time .number {
                height: 25px;
                font-size: 16px;
                line-height: 26px;
            }
            .ends-task-time .word {
                font-size: 16px;
                margin-right: 5px;
            }
        }
        .nav-link.owl-nav-link {
            font-size: 16px;
            font-weight: 600;
        }
        .header-title{
            font-size: 20px;
            color: white !important;
        }
    </style>
@endpush

@section('content')


    <header class="header-section">
        <div class="container">
            <div class="header-section-content">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="right-back-btn">
                        <a href="{{route('user.home')}}" class="right-back-action">
                            <i class="fa-solid fa-arrow-left"></i>
                        </a>
                    </div>
                    <div class="header-title">
                        Puzzle Hunt
                    </div>
                    <div class="other-data"></div>
                </div>
            </div>
        </div>
    </header>

    <section class="main-section">
        <div class="container">
            <div class="row justify-content-center all-other-services">
                <div class="col-12">
                    <div>
                        <div class="puzzleHunt-header">
                            <div class="puzzleHunt-header-content">
                                <p class="title">Puzzle Hunt</p>
                                <p class="subtitle">Jion the Puzzle hunt! Complete tasks collect puzzle prices and put them together to claim amazing rewords. Start ypur adventure today!</p>
                            </div>
                        </div>
                        <div class="puzzleHunt-body mt-3">
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link owl-nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                            aria-selected="true">Available Coins</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link owl-nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                            data-bs-target="#pills-profile" type="button" role="tab"
                                            aria-controls="pills-profile" aria-selected="false">Ended</button>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="pills-home" role="tabpanel"
                                     aria-labelledby="pills-home-tab">
                                    <div class="puzzleHunt-body-contents">

                                        @if($trades->isNotEmpty())
                                            @foreach($trades as $trade)
                                        <div class="puzzleHunt-body-content">
                                            <div class="votes-contents-box-complet">
                                                <div class="votes-contents-box-complet-text">Ongoing</div>
                                            </div>
                                            <div class="puzzleHunt-body-content-data">
                                                <div class="img">
                                                    <img src="{{ getImage(getFilePath('currency') .'/'.$trade->image,getFileSize('currency')) }}" alt="">
                                                </div>
                                                <div class="text">{{$trade->name}}</div>
                                            </div>
                                            <div class="row justify-content-between align-items-center mt-3">
                                                <div class="col-6 col-lg-4 col-md-4">
                                                    <div class="total-prize-pool">
                                                        Total Prize Pool (AVL)
                                                    </div>
                                                    <div class="total-prize-pool-amount">{{$trade->total_prize}}</div>
                                                </div>
                                                <div class="col-6 col-lg-4 col-md-4">
                                                    <div class="Trading-price">
                                                        Event ends in:
                                                    </div>
                                                    <div class="ends-task-time" id="countdown-{{$trade->id}}">
                                                        <div class="number">0</div>
                                                        <div class="word">D</div>
                                                        <div class="number">0</div>
                                                        <div class="word">H</div>
                                                        <div class="number">0</div>
                                                        <div class="word">M</div>
                                                        <div class="number">0</div>
                                                        <div class="word">S</div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <button class="btn btn-sm btn-Subscribe float-right buyCopyTrade"
                                                            data-name="{{strtoupper($trade->name)}}" data-id="{{$trade->id}}">Join Now</button>
                                                </div>
                                            </div>

                                        </div>

                                            @endforeach
                                        @else
                                            <div class="text-center pt-4 pb-4">
                                                <h5 class="text-center">No Trade Found</h5>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-profile" role="tabpanel"
                                     aria-labelledby="pills-profile-tab">

                                    <div class="puzzleHunt-body-contents">

                                        @if($puzzle_hunt_trades->isNotEmpty())
                                            @foreach($puzzle_hunt_trades as $puzzle_hunt_trade)
                                                <div class="puzzleHunt-body-content">
                                                    <div class="votes-contents-box-complet">
                                                        <div class="votes-contents-box-complet-text">Ongoing</div>
                                                    </div>
                                                    <div class="puzzleHunt-body-content-data">
                                                        <div class="img">
                                                            <img src="{{ getImage(getFilePath('currency') .'/'.$puzzle_hunt_trade->trade->image,getFileSize('currency')) }}" alt="">
                                                        </div>
                                                        <div class="text">{{$puzzle_hunt_trade->trade->name}}</div>
                                                    </div>
                                                    <div class="row justify-content-between align-items-center mt-3">
                                                        <div class="col-6 col-lg-4 col-md-4">
                                                            <div class="total-prize-pool">
                                                                Total Prize Pool (AVL)
                                                            </div>
                                                            <div class="total-prize-pool-amount">{{$puzzle_hunt_trade->trade->total_prize}}</div>
                                                        </div>
                                                        <div class="col-6 col-lg-4 col-md-4">
                                                            <div class="Trading-price">
                                                                Event ends in:
                                                            </div>
                                                            <div class="ends-task-time" id="countdown-{{$puzzle_hunt_trade->trade->id}}">
                                                                <div class="number">0</div>
                                                                <div class="word">D</div>
                                                                <div class="number">0</div>
                                                                <div class="word">H</div>
                                                                <div class="number">0</div>
                                                                <div class="word">M</div>
                                                                <div class="number">0</div>
                                                                <div class="word">S</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-12 ">

                                                            @php
                                                                $start_date=\Illuminate\Support\Carbon::parse($puzzle_hunt_trade->trade->start_date);
                                                            @endphp
                                                            @if($puzzle_hunt_trade->status=='buy' &&  $start_date < now())
                                                                <button class="btn btn-sm mt-3 btn-Subscribe float-right withdrawCopyTrade"
                                                                        data-name="{{strtoupper($puzzle_hunt_trade->trade->name)}}"
                                                                        data-id="{{$puzzle_hunt_trade->id}}">Withdraw</button>
                                                            @else
                                                                <button class="btn btn-sm btn-Subscribe float-right disabled mt-3" disabled >Withdraw</button>
                                                            @endif

                                                        </div>
                                                    </div>

                                                </div>

                                            @endforeach
                                        @else
                                            <div class="text-center pt-4 pb-4">
                                                <h5 class="text-center">No Trade Found</h5>
                                            </div>
                                        @endif
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    @include('templates.basic.user.copy_trading.includes.modal')
@endsection

@push('script')
    @include('templates.basic.user.copy_trading.includes.modal_js')

    <script>
        function updateCountdown(endDate, elementId) {
            const countdownElement = document.getElementById(elementId);
            const numbers = countdownElement.getElementsByClassName('number');

            function update() {
                const now = new Date().getTime();
                const distance = endDate - now;

                if (distance < 0) {
                    clearInterval(interval);
                    numbers[0].textContent = '0';
                    numbers[1].textContent = '0';
                    numbers[2].textContent = '0';
                    numbers[3].textContent = '0';
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                numbers[0].textContent = days;
                numbers[1].textContent = hours;
                numbers[2].textContent = minutes;
                numbers[3].textContent = seconds;
            }

            const interval = setInterval(update, 1000);
            update(); // Initial call
        }

        // Initialize countdowns for all trades
        @foreach($trades as $trade)
        const endDate{{$trade->id}} = new Date('{{$trade->end_date}}').getTime();
        updateCountdown(endDate{{$trade->id}}, 'countdown-{{$trade->id}}');
        @endforeach
    </script>
@endpush
