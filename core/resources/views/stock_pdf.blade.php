<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$product->name}} Stock Certificate</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />

    <style>
        /* Container for the entire certificate */
        .certificate-container {
            background-image: url('{{asset('core/public/1.jpg')}}'); /* Path to your uploaded image */
            background-size: cover;
            background-position: center;
            width: 100%;
            margin: auto;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            color: #333;
            min-height: 800px !important;
        }

        /* Title Styling */
        .certificate-title {
            font-size: 2rem;
            font-weight: bold;
            color: #3C3C3B;
            margin-bottom: 1rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .image-section img{
            height: 100%;
            width: 100%;
        }
        .image-section{
            height: 25px;
            width: 60px;
        }

        /* Stock Details Sections */
        .certificate-section {
            margin: 1rem 0;
            text-align: left;
            font-size: 1rem;
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 0.5rem;
        }

        /* Metric Styling */
        .metric {
            display: flex;
            justify-content: space-between;
            margin: 0.3rem 0;
        }

        .metric-name {
            font-weight: bold;
        }

        .inner-content{
            width: 84%;
            margin: 0 auto;
            position: relative;
            top: 83px;
        }
        .id-section{
            display: flex;
            justify-content: space-between;
            position: relative;
            bottom: 29px;
            color: #0056b3 !important;
            font-size: 12px;
            font-weight: 800;
        }
        .font-weight-bold {
            font-weight: 600 !important;
        }

        .cer-desk-mode{
            display: none;
        }
        .main-cer-section{
            display: block;
        }
        .certificate-section{
            line-height: 20px;
        }

        /* Responsive Adjustments */
        @media (max-width: 750px){
            .cer-desk-mode{
                display: block;
            }
            .main-cer-section{
                display: none;
            }
        }
        @media (max-width: 600px) {
            .certificate-container {
                padding: 1rem;
            }
            .certificate-title {
                font-size: 1.5rem;
            }
            .section-title {
                font-size: 1rem;
            }
        }
        .qr-scanner-code{
            position: absolute;
            top: 26%;
            left: 20%;
        }

        @media (max-width: 1200px) {
            .qr-scanner-code{
                position: absolute;
                top: 8%;
                left: 19%;
            }
        }
    </style>

</head>
<body>

<div class="container cer-desk-mode">
    <div class="row">
        <div class="col-9 mx-auto text-center pt-5 pb-5" style="margin-top: 30%;">
            <h4>To See Certificate Please View As Desktop Mode</h4>
        </div>
    </div>
</div>

<div class="container main-cer-section pt-5 pb-5">
    <div class="row">
        <div class="col-md-12">
            <div class="certificate-container">

                <div class="id-section">
                    <div class="stock-info left">#{{$product->stock_code}}</div>
                    <div class="stock-info right">CUSIP: {{$stock_product->certificate_id}}</div>
                </div>

                <div class="qr-scanner-code">
                    @if(isset($base64Svg))
                        <div class="base-qr-code">
                            <img src="data:image/svg+xml;base64,{{ $base64Svg }}" alt="QR Code SVG" width="40" height="40">
                        </div>
                    @endif
                </div>

                <div class="inner-content">
                    <div class="certificate-title pt-3">
                        <!--@if(isset($base64Image))-->
                        <!--    <div class="image-section">-->
                        <!--        <img src="{{$base64Image}}" alt="">-->
                        <!--    </div>-->
                        <!--@endif-->

                        {{$product->name}}
                    </div>

                    @php $user=$stock_product->user; @endphp

                    <div class="certificate-section">
                        <div class="section-title">User Information</div>
                        <div class="metric">
                            <span class="metric-name">Name:</span>
                            <span class="font-weight-bold">{{$user->fullname}}</span>
                        </div>
                        <div class="metric">
                            <span class="metric-name">Email:</span>
                            <span class="font-weight-bold">{{$user->email}}</span>
                        </div>
                        <div class="metric">
                            <span class="metric-name">Purchase Amount:</span>
                            <span class="font-weight-bold">${{$stock_product->invest_amount}}</span>
                        </div>
                        <div class="metric">
                            <span class="metric-name">Purchase Date:</span>
                            <span class="font-weight-bold">{{$stock_product->created_at->format('d-m-Y')}}</span>
                        </div>

                    </div>

                    <!-- Stock Details Section -->
                    <div class="certificate-section">
                        <div class="section-title">Stock Information</div>
                        <div class="metric">
                            <span class="metric-name">Company Name:</span>
                            <span class="font-weight-bold">NyseFinance</span>
                        </div>
                        <div class="metric">
                            <span class="metric-name">Current Price:</span>
                            <span class="font-weight-bold">${{isset($Current_Price)?$Current_Price:'0.0000000'}}</span>
                        </div>
                        <div class="metric">
                            <span class="metric-name">Market Cap:</span>
                            <span class="font-weight-bold">${{isset($Market_Cap)?$Market_Cap:'N/A'}}</span>
                        </div>
                        <div class="metric">
                            <span class="metric-name">52-Week Range:</span>
                            <span class="font-weight-bold">{{ISSET($WeekRange)?$WeekRange:'N/A'}}</span>
                        </div>
                        <div class="metric">
                            <span class="metric-name">Dividend Yield:</span>
                            <span class="font-weight-bold">{{isset($Dividend_Yield)?$Dividend_Yield:'N/A'}}</span>
                        </div>
                    </div>

                    <!-- Financial Summary Section -->
                    <div class="certificate-section">
                        <div class="section-title">Financial Summary</div>
                        <div class="metric">
                            <span class="metric-name">Revenue (TTM):</span>
                            <span class="font-weight-bold">${{isset($Revenue_TTM)?$Revenue_TTM:'N/A'}}</span>
                        </div>
                        <div class="metric">
                            <span class="metric-name">Net Income (TTM):</span>
                            <span class="font-weight-bold">${{isset($Net_Income_TTM)?$Net_Income_TTM:'0.0000'}}</span>
                        </div>
                        <div class="metric">
                            <span class="metric-name">EPS (TTM):</span>
                            <span class="font-weight-bold">${{isset($EPS_TTM)?$EPS_TTM:'0.000'}}</span>
                        </div>
                        <div class="metric">
                            <span class="metric-name">PE Ratio:</span>
                            <span class="font-weight-bold">{{isset($PE_Ratio)?$PE_Ratio:'N/A'}}</span>
                        </div>
                    </div>


                    <!-- Stock Summary Section -->
                    <div class="certificate-section">
                        <div class="section-title">Summary</div>

                        {!! $product->short_description !!}

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>


</body>
</html>
