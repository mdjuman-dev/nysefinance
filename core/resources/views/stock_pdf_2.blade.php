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
            background-image: url('{{asset('core/public/final_copy.png')}}'); /* Path to your uploaded image */
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
            top: 88px;
            font-weight: 700;
        }
        .stock-info.left{
            margin-left: 12px;
        }
        .stock-info.right{
            margin-right: 77px;
        }
        .stock-main-logo-section{
            position: relative;
            top: -18px;
            height: 75px;
            width: 240px;
            left:26px;
        }
        .stock-details-section{
            position: relative;
            top: 56px;
        }
        .owner-name{
            font-family: "Caveat", cursive;
            font-optical-sizing: auto;
            font-weight: 800;
            font-style: normal;
            font-size: 40px;
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
            width: 7%;
            float: right;
            top: 83px;
            right: 12%;
        }
        body{
            min-width: 1366px !important;
            max-width: 1366px !important;
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
                    <div class="stock-info left">ID: {{substr($stock_product->certificate_id, 0, 6)}}</div>
                    @if(isset($base64Image))
                        <div class="stock-main-logo-section">
                            <img src="{{$base64Image}}" alt="" style="height: 100%; width: 100%;">
                        </div>
                    @endif
                    <div class="stock-info right">{{$stock_product->invest_amount}} USD</div>
                </div>

                <div class="stock-details-section">
                    <h1>{{$product->name}}</h1>
                    <h4>THIS IS TO CERTIFY THAT</h4>
                    <h2 class="owner-name">
                        {{$user->fullname}}
                    </h2>

                    <p>
                    <div class="full-cusipid" style="font-size: 13px;font-weight: 500;text-align: center;">
                        <span>CUSIP:</span> <b> {{$stock_product->certificate_id}}</b>
                    </div>
                    </p>

                    <div class="details-info-one">
                        IS THE REGISTERED HOLDER OF ${{$stock_product->invest_amount}} USD ORDINARY
                        SHARES FULLY PAID IN THE SHARE CAPITAL OF THE COMPANY
                        SUBJECT TO THE CONSTITUTION OF THE COMPANY GIVEN ON
                        THIS ({{$stock_product->type=='fix'?'MUTUAL FUND':'LIVE MARKET'}} HOLDING UNTILL {{$stock_product->invest_date}}) Live Market Price.
                    </div>

                    <div class="sub-info-sec">
                        <h1>
                            <u>{{$product->name}}</u>
                        </h1>
                    </div>

                    <div class="details-info-two">
                        This certificate is transferable on the books of the company, in person or by a duly authorized attorney,
                        upon delivery thereof. This certificate and the shares represented hereby are issued and shall be held subject
                        to all provisions of the Company's Certificate of Incorporation, as amended and restated, and the Bylaws (copies of
                        which are with the Company and the Transfer Agent), to which each holder, by acknowledging hereof, consents. If this certificate
                        is not verified from the website, the shares will not result in being registered by the transfer agent and registrar.
                    </div>

                    @if(isset($base64Svg))
                        <div class="base-qr-code">
                            <img src="data:image/svg+xml;base64,{{ $base64Svg }}" alt="QR Code SVG" style="width: 100%; height: 100%">
                        </div>
                    @endif


                    @if($stock_product)
                        <div class="section-sold" style="width: 180px;font-size: 11px;position: relative;left: 26%;margin-top: 7%;background: #f4f4f4;padding: 10px 0px;border-radius: 5px;">

                            <div class="buy-date" style="font-size: 9px !important;">Purchase Date: {{$stock_product->created_at->format('Y-m-d H:i:s A')}}</div>
                            @if($stock_product->status=='sell')
                                <div class="sell-tag" style="font-size: 22px;font-weight: 800;color: #ff0808;">
                                    SOLD
                                </div>
                                <div class="sell-date" style="font-size: 9px !important;">
                                    Sell Date: {{$stock_product->updated_at->format('Y-m-d H:i:s A')}}
                                </div>
                            @endif

                        </div>
                    @endif

                </div>

            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>


</body>
</html>
