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
            font-size: 10px;
            font-weight: 500;
            color: #fff;
        }
        .services-title{
            font-size: 11px;
            margin: 15px 0;
            color: #636060;
        }
        .other-data i{
            font-size: 13px;
            color: #fff;
        }
        .todays-title{
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            margin-top: 20px;
        }
        .todays-content{
            border: 1px solid gray;
            /* padding: 10px 8px;/ */
            border-radius: 5px;
        }
        .todays-content .header .header-titles img{
            width: 25px;
            height: 25px;
            border-radius: 50%;
        }
        .todays-content .header .header-titles h6{
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }
        .todays-content .header .header-titles span{
            color: gray;
            font-size: 9px;
        }
        .todays-content .header .header-titles{
            display: flex;
            align-items: flex-end;
            gap: .4rem;
        }
        .todays-content .header{
            padding: 10px 8px;
            border-bottom: 1px solid gray;
            background-color: #ffffff14;

        }
        .header-data{
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
        }
        .header-data span{
            font-size: 11px;
            color: gray;
        }
        .header-data .aIScore{
            font-size: 13px;
            color: #fff;
            text-align: end;
        }
        .header-data .positive{
            font-size: 13px;
            color: #28a745;
            background-color: #1d3222;
            text-align: center;
            border-radius: 3px;
        }
        .todays-content .main{
            padding: 10px 8px;
            border-bottom: 1px solid gray;

        }
        .todays-content .footer{
            padding: 5px 8px;
            text-align: center;
        }
        .todays-content .footer span{
            font-size: 11px;
            color: #fff;
        }
        .main {
            position: relative;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        canvas {
            max-width: 100%;
            position: relative;
            /* z-index: 1; */
        }
        .chart-box{
            margin-top: 20px;
            padding: 30px 10px;
            background-color: #ffffff14;
        }
        .chart-box input{
            background-color: #c5c5c530;
            border-color: #c5c5c530;
            color: #636060;
        }
        .chart-box-sent{
            display: flex;
            justify-content: center;
            gap: .5rem;
        }
        .other-part .one .one-image img{
            width: 22px;
            height: 22px;
            border-radius: 50%;
        }
        .other-part .one ul{
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .other-part .one ul li{
            background-color: #c5c5c530;
            padding: 4px 10px;
            border-radius: 5px;
            margin-right: 3px;
        }
        .other-part .one .one-image span{
            font-size: 14px;
            color: #fff;
        }
        .other-part {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .other-part .two{
            font-size: 11px;
            color: #fff;
        }
        .other-part .two .main-text{
            color: #f9a856;
        }
        @media (min-width: 992px) {
            .right-back-action {
                font-size: 20px;
            }
            .header-title h6{
                font-size: 20px;
            }
            .other-data i {
                font-size: 20px;
            }
            .todays-title {
                font-size: 25px;
                margin-top: 30px;
            }
            .todays-content .header {
                padding: 15px 20px;
            }
            .todays-content .header .header-titles img {
                width: 50px;
                height: 50px;
            }
            .todays-content .header .header-titles h6 {
                font-size: 20px;
            }
            .header-data {
                margin-top: 20px;
            }
            .header-data span {
                font-size: 16px;
            }
            .header-data .aIScore {
                font-size: 20px;
            }
            .header-data .positive {
                font-size: 20px;
                background-color: #1d3222;
            }
            .moblie-bigsize{
                display: flex;
                width: 100%;
                justify-content: center;
            }
            .chart-box{
                width: 70%;
                margin-top: 50px;
            }
            .other-part .one .one-image img {
                width: 30px;
                height: 30px;
            }
            .other-part .one ul li {
                padding: 5px 5px;
                margin-right: 5px;
            }
            .other-part .one .one-image span {
                font-size: 13px;
            }
            .other-part .two {
                font-size: 16px;
                color: #fff;
            }
        }
        @media (min-width: 768px) {
            .right-back-action {
                font-size: 20px;
            }
            .header-title h6{
                font-size: 20px;
            }
            .other-data i {
                font-size: 20px;
            }
            .todays-title {
                font-size: 25px;
                margin-top: 30px;
            }
            .todays-content .header {
                padding: 15px 20px;
            }
            .todays-content .header .header-titles img {
                width: 50px;
                height: 50px;
            }
            .todays-content .header .header-titles h6 {
                font-size: 20px;
            }
            .header-data {
                margin-top: 20px;
            }
            .header-data span {
                font-size: 16px;
            }
            .header-data .aIScore {
                font-size: 20px;
            }
            .header-data .positive {
                font-size: 20px;
                background-color: #1d3222;
            }
            .other-part .one .one-image img {
                width: 30px;
                height: 30px;
            }
            .other-part .one ul li {
                padding: 5px 5px;
                margin-right: 5px;
            }
            .other-part .one .one-image span {
                font-size: 13px;
            }
            .other-part .two {
                font-size: 16px;
                color: #fff;
            }

        }

        .moblie-bigsize{
            position: fixed;
            width: 91%;
            bottom: 5%;
        }
        .suggestions-list {
            background: #27272714;
    border-radius: 5px;
    padding: 10px;
    background-color: #23292b;
        }
        .suggestion-item {
            padding: 8px 12px;
            cursor: pointer;
            color: #fff;
            border-bottom: 1px solid #ffffff30;
            font-size: 13px;
        }
        .suggestion-item:hover {
            background: #ffffff20;
        }
        .chat-container {
            position: absolute;
            top: 60px;
            left: 20px;
            right: 20px;
            max-height: calc(100% - 80px);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: #ffffff30 #ffffff14;
            /* z-index: 2; */
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
            border-radius: 10px;
        }
        .chat-container::-webkit-scrollbar {
            width: 3px;
        }
        .chat-container::-webkit-scrollbar-track {
            background: #ffffff14;
        }
        .chat-container::-webkit-scrollbar-thumb {
            background-color: #ffffff30;
            border-radius: 3px;
        }
        .chat-messages {
            padding: 10px;
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            word-wrap: break-word;
            max-width: 80%;
        }
        .user-message {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            margin-left: auto;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .bot-message {
            background: rgba(29, 50, 34, 0.8);
            color: #fff;
            margin-right: auto;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .todays-content .main{
            min-height:480px;
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
                        <h6 class="white">Trade ZPT</h6>
                    </div>
                    <div class="other-data"></div>
                </div>
            </div>
        </div>
    </header>


    <section class="main-section mb-0">
        <div class="container">
            <h1 class="todays-title">Todays Project pick</h1>

            <div class="todays-content">
                <div class="header">
                    <div class="header-titles">
                        <img src="{{asset('core/public/img/image-e8.webp')}}" alt="">
                        <h6>ROAM</h6>
                        <span>ROAM</span>
                    </div>
                    <div class="header-data">
                        <div class="one">
                            <span>Assessment</span>
                            <p class="positive">Positive</p>
                        </div>
                        <div class="two">
                            <span>AI Score</span>
                            <p class="aIScore">7.4</p>
                        </div>
                    </div>
                </div>
                <div class="main">
                    <h1 class="todays-title m-0 p-2">AI Assessment</h1>
                    <canvas id="radarChart"></canvas>
                    <div class="chat-container">
                        <div class="chat-messages"></div>
                    </div>
                </div>
                <div class="footer">
                    <span>Show more <i class="fa-solid fa-angle-down"></i></span>
                </div>
            </div>

            <div class="main-chart d-none">

            </div>

        </div>
        <div class="moblie-bigsize">
            <div class="chart-box">
                <div class="suggestions-container" style="display: none; margin-bottom: 10px;">
                    <div class="suggestions-list"></div>
                </div>

                <div class="chart-box-sent demo-chat">
                    <input type="text" placeholder="Ask me somthing about...." class="form-control search-faq">
                    <button type="button" class="btn btn-warning"><i class="fa-solid fa-paper-plane"></i></button>
                </div>



                <div class="other-part mt-2">
                    <div class="one">
                        <ul>
                            <li>
                                <div class="one-image">
                                    <img src="{{asset('core/public/img/pool-2.png')}}" alt="">
                                    <span>SOL</span>
                                </div>
                            </li>
                            <li>
                                <div class="one-image">
                                    <img src="{{asset('core/public/img/pool-2.png')}}" alt="">
                                    <span>BTC</span>
                                </div>
                            </li>
                            <li>
                                <div class="one-image">
                                    <img src="{{asset('core/public/img/pool-2.png')}}" alt="">
                                    <span>XRP</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="two">
                        <span class="main-text">20</span>
                        /
                        <span>20</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection


@push('script')

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
            integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
            integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
            crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('radarChart').getContext('2d');
        const radarChart = new Chart(ctx, {
            type: 'radar',
            data: {
                labels: ['Accuracy', 'Speed', 'Efficiency', 'Consistency', 'Adaptability', 'Creativity'],
                datasets: [{
                    data: [85, 90, 75, 95, 80, 70],
                    backgroundColor: 'rgba(128, 128, 128, 0.2)',
                    borderColor: 'rgba(128, 128, 128, 1)',
                    pointBackgroundColor: 'rgba(100, 100, 100, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(100, 100, 100, 1)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        angleLines: { display: true, color: 'rgba(200, 200, 200, 0.5)' },
                        grid: { color: 'rgba(220, 220, 220, 0.3)' },
                        suggestedMin: 0,
                        suggestedMax: 100,
                        ticks: {
                            display: false
                        },
                        pointLabels: {
                            color: 'gray'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: false
                    }
                }
            }
        });
    </script>

    <script>
        $(document).on('keyup', '.search-faq', function (e){
            const name = $(this).val();
            const suggestionsContainer = $('.suggestions-container');
            const suggestionsList = $('.suggestions-list');

            if(name.length > 0) {
                $.ajax({
                    type:'GET',
                    url:'{{route('user.get.faqs')}}',
                    data:{
                        name:name
                    },
                    success:function(res){
                        if(res.status=='success'){
                            suggestionsList.empty();
                            if(res.data.length > 0) {
                                res.data.forEach(function(item) {
                                    suggestionsList.append(`
                                        <div class="suggestion-item" data-question="${item.question}" data-answer="${item.answer}">
                                            ${item.question}
                                        </div>
                                    `);
                                });
                                suggestionsContainer.show();
                            } else {
                                suggestionsContainer.hide();
                            }
                        }
                    }
                });
            } else {
                suggestionsContainer.hide();
            }
        });

        $(document).on('click', '.suggestion-item', function() {
            const question = $(this).data('question');
            const answer = $(this).data('answer');
            const chatMessages = $('.chat-messages');
            
            // Add user message
            chatMessages.append(`
                <div class="message user-message">
                    ${question}
                </div>
            `);
            
            // Add bot message
            chatMessages.append(`
                <div class="message bot-message">
                    ${answer}
                </div>
            `);

            // Clear input and hide suggestions
            $('.search-faq').val('');
            $('.suggestions-container').hide();
            
            // Scroll to bottom of chat
            const chatContainer = $('.chat-container');
            chatContainer.scrollTop(chatContainer[0].scrollHeight);
        });

        // Close suggestions when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.suggestions-container, .search-faq').length) {
                $('.suggestions-container').hide();
            }
        });
    </script>


@endpush
