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
            background-image: url('{{asset('core/public/options.png')}}'); /* Path to your uploaded image */
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
            top: 20px;
            height: 70px;
            width: 145px;
            left: 17px;
            border-radius: 5px;
        }
        .stock-details-section .section-interest-price{
            display: flex;
        }
        .stock-details-section{
            position: relative;
            top: 300px;
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
            width: 56px;
            float: right;
            top: 204px;
            right: 24%;
            border: 4px solid white;
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
        .section-ins{
            width: 30%;
            font-size: 30px;
            text-align: right;
            position: relative;
            top: 40px;
            right: 64px;
            font-weight: 700;
        }
        .section-inss{
            width: 30%;
            font-size: 30px;
            text-align: center;
            position: relative;
            top: 40px;
            right: 90px;
            font-weight: 700;
        }
        .section-p{
            width: 40%;
            font-size: 55px;
            font-weight: 900;
            text-align: center;
            padding-left: 10px;
        }
        .section-name-ips{
            display: flex;
            position: relative;
            top: 126px;
            justify-content: space-around;
        }
        .sec-isp{
            font-weight: 700;
        }
        .sec-user-name{
            font-size: 28px;
            position: relative;
            font-weight: 900;
            font-family: cursive;
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



                <div class="stock-details-section">

                    <div class="section-interest-price">
                        <div class="section-ins">
                            {{$stock_product->interest}}%
                        </div>
                        <div class="section-p">
                            ${{$stock_product->invest_amount}}
                        </div>
                        <div class="section-inss">
                            {{$stock_product->interest}}%
                        </div>
                    </div>


                    <div class="section-name-ips">
                        <div class="sec-isp">
                            CUSIPID: {{$stock_product->certificate_id}}
                        </div>

                        <div class="sec-user-name">
                            {{$user->fullname}}
                        </div>
                    </div>

                    <div class="sec-ft">

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
