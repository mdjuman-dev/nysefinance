<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$product->name}} Stock Certificate</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&display=swap" rel="stylesheet">


    <style>
        /* Container for the entire certificate */
        .certificate-container {
            background-image: url('{{asset('core/public/economy.png')}}'); /* Path to your uploaded image */
            background-size: cover;
            background-position: center;
            width: 100%;
            margin: auto;
            padding: 2rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            text-align: center;
            color: #333;
            min-height: 870px !important;
        }
        .id-section{
            display: flex;
            width: 70%;
            margin: 0 auto;
            justify-content: space-between;
            font-size: 23px;
            color: #000000;
            position: relative;
            top: 30px;
            font-weight: 700;
        }
        .stock-info.left{
            margin-left: 12px;
        }
        .stock-info.right{
            margin-right: 77px;
        }
        .stock-main-logo-section img{
            border-radius: 5px;
        }
        .stock-main-logo-section{
            position: relative;
            top: 10px;
            height: 100px;
            width: 100px;
            left: 45px;
            border-radius: 5px;
        }
        .stock-details-section{
            position: relative;
            top: 36px;
        }
        .owner-name{
            /*font-family: "Caveat", cursive;*/
            font-optical-sizing: auto;
            font-weight: 800;
            font-style: normal;
            font-size: 35px;
            margin-top: 5px;
        }
        .details-info-one{
            width: 60%;
            margin: 0 auto;
            position: relative;
            top: 20px;
            font-size: 18px;
            font-weight: 400;
            font-family: math;
        }
        .details-info-two{
            width: 68%;
            margin: 0 auto;
            position: relative;
            top: 63px;
            font-size: 17px;
            font-weight: 600;
            left: 5%;
            line-height: 25px;
        }
        .sub-info-sec{
            width: 40%;
            margin: 0 auto;
            position: relative;
            top: 55px;
        }
        .base-qr-code{
            position: relative;
            top: 8px;
            right: 10%;
            border: 5px solid #efefef;
            border-radius: 5px;
        }
        body{
            min-width: 1366px !important;
            max-width: 1366px !important;
        }
        .main-top-title .t-two{
            font-weight: 900;
            font-size: 14px;
        }
        .main-top-title .t-one{
            font-weight: 500;
            font-size: 14px;
        }
        .main-top-title{
            position: relative;
            top: 28px;
        }
        .th-part-section{
            display: flex;
            position: relative;
            top: 80px;
        }
        .th-one-sec{
            width: 27%;
            padding: 10px 20px;
            text-align: center;
        }
        .th-two-sec{
            width: 46%;
            padding: 10px 30px;
            line-height: 32px;
            font-weight: 300;
        }
        .th-three-sec{
            width: 27%;
            padding: 10px 20px;
            text-align: center;
        }
        .section-top-one{
            display: flex;
            justify-content: space-around;
        }
        .nnmbr{
            font-optical-sizing: auto;
            font-weight: 800;
            font-style: normal;
            font-size: 25px;
            margin-top: 5px;
            position: relative;
            right: 55px;
        }
        .economy-price{
            font-optical-sizing: auto;
            font-weight: 800;
            font-style: normal;
            font-size: 25px;
            margin-top: 5px;
            position: relative;
            left: 55px;
        }
    </style>



</head>
<body>

<div class="container cer-desk-mode d-none">
    <div class="row">
        <div class="col-9 mx-auto text-center pt-5 pb-5" style="margin-top: 30%;">
            <h4>To See Certificate Please View As Desktop Mode</h4>
        </div>
    </div>
</div>

@php $user=$stock_product->user; @endphp


<div class="container-fluid main-cer-section pt-5 pb-5">
    <div class="row">
        <div class="col-md-12">
            <div class="certificate-container">

                <div class="id-section">
                    <div class="stock-info left"></div>
                    @if(isset($base64Image))
                        <div class="stock-main-logo-section">
                            <img src="{{$base64Image}}" alt="" style="height: 100%; width: 100%;">
                        </div>
                    @endif
                    <div class="stock-info right"></div>
                </div>

                <div class="stock-details-section">
                    <div class="section-top-one">
                        <div class="sec-nmbr">
                            <h2 class="nnmbr">
                                @php
                                 $strId=\Illuminate\Support\Str::random(7);
                                    @endphp
                                {{strtoupper($strId)}}
                            </h2>
                        </div>

                        <div class="sec-nmbr">
                            <h2 class="economy-price">
                                {{number_format($stock_product->invest_amount, 1)}} USD
                            </h2>
                        </div>
                    </div>


                    <div class="sectiondt-one" style="font-weight: 600;">

                        <h3 style="font-weight: 900;" class="ttl">
                            PGI ENERGY FUND I SERIES 2010
                        </h3>
                        <div class="ttl-two">
                            INCORPORATED UNDER THE LAWS OF THE STATE OF TEXAS
                        </div>
                        <div>
                            AUTHORIZED: 500 USD PAR VALUE PER SHARE
                            <br>
                            CUSIP ID: {{$stock_product->certificate_id}}
                        </div>
                    </div>

                    <div class="section-own-name" style="position: relative;top: 84px;">
                        <div style="    font-weight: 600;font-size: 13px;">
                            Fully Paid and Non-Assessable Common INDEX, {{$stock_product->invest_amount}} USD Par Value of
                        </div>
                        <h2 style="margin-top: 4px;font-weight: 700;">
                            MD ALI AHAMED

                        </h2>
                    </div>



                    <div class="sec-ft" style="display: flex;justify-content: space-between; position: relative;top: 300px;">

                        <div class="section-date" style="    position: relative;left: 16%;top: -25px;font-weight: 700;">
                            Date: {{$stock_product->created_at->format('Y-m-d')}}
                        </div>


                        @if(isset($base64Svg))
                            <div class="base-qr-code">
                                <img src="data:image/svg+xml;base64,{{ $base64Svg }}" alt="QR Code SVG" style="width: 100%; height: 100%">
                            </div>
                        @endif




{{--                        @if($stock_product)--}}
{{--                            <div class="section-sold" style="width: 180px;top: 90px;font-size: 11px;position: relative;left: 13%;margin-top: 7%;background: #f4f4f4;padding: 10px 0px;border-radius: 5px;">--}}

{{--                                <div class="buy-date" style="font-size: 9px !important;">Purchase Date: {{$stock_product->created_at->format('Y-m-d H:i:s A')}}</div>--}}
{{--                                @if($stock_product->status=='sell')--}}
{{--                                    <div class="sell-tag" style="font-size: 22px;font-weight: 800;color: #ff0808;">--}}
{{--                                        SOLD--}}
{{--                                    </div>--}}
{{--                                    <div class="sell-date" style="font-size: 9px !important;">--}}
{{--                                        Sell Date: {{$stock_product->updated_at->format('Y-m-d H:i:s A')}}--}}
{{--                                    </div>--}}
{{--                                @endif--}}

{{--                            </div>--}}
{{--                        @endif--}}

                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>


</body>
</html>
