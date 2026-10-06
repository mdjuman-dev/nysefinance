<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meta Stock Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .certificate {
            width: 900px;
            background-color: white;
            border: 2px solid #0077be;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        .border-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image:
                repeating-linear-gradient(0deg, #0077be, #0077be 2px, transparent 2px, transparent 20px),
                repeating-linear-gradient(90deg, #0077be, #0077be 2px, transparent 2px, transparent 20px),
                radial-gradient(circle at 10px 10px, #0077be 2px, transparent 2px);
            background-size: 100% 100%, 100% 100%, 20px 20px;
            pointer-events: none;
        }
        .content {
            position: relative;
            z-index: 1;
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border: 2px solid #0077be;
            margin: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 48px;
            font-weight: bold;
            color: #1877f2;
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            margin: 10px 0;
        }
        .subtitle {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .owner, .shares {
            font-size: 18px;
            margin: 10px 0;
            text-align: center;
        }
        .shares {
            color: #ff0000;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: justify;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        .signature {
            text-align: center;
        }
        .signature img {
            width: 100px;
            height: 50px;
        }
        .seal {
            position: absolute;
            bottom: 30px;
            right: 50px;
            width: 100px;
            height: 100px;
            border: 2px solid #0077be;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: bold;
            color: #0077be;
        }
        .corner-info {
            position: absolute;
            font-size: 12px;
        }
        .top-left { top: 10px; left: 10px; }
        .top-right { top: 10px; right: 10px; }
    </style>
</head>
<body>
<div class="certificate">
    <div class="border-pattern"></div>
    <div class="content">
        <div class="corner-info top-left">
            Certificate<br>Number<br>
            <span style="color: #ff0000;">{{$stock_product->certificate_id}}</span>
        </div>
        <div class="corner-info top-right">
            Shares<br>
            <span style="color: #ff0000;">{{$stock_product->invest_amount}}</span>
        </div>
        <div class="header">
            <div>CLASS A COMMON STOCK</div>

            <div class="logo">
                @if(isset($base64Svg))
                    <div class="qr-code" style=" position: absolute;top: 16px;left: 23%;">
                        <img src="data:image/svg+xml;base64,{{ $base64Svg }}" alt="QR Code SVG" width="40" height="40">
                    </div>
                @endif

                @if(isset($base64Image))
                    <div class="image-section">
                        <img src="{{$base64Image}}" alt="">
                    </div>
                @endif
            </div>
            <div class="title">
                {{$product->name}}
            </div>
        </div>

        <p style="text-align: center;">
            {{$product->short_description}}
        </p>
        <br>
        <div class="footer">
            {!! $product->description !!}
        </div>
{{--        <div class="signatures">--}}
{{--            <div class="signature">--}}
{{--                <img src="/api/placeholder/100/50" alt="President Signature">--}}
{{--                <div>President</div>--}}
{{--            </div>--}}
{{--            <div class="signature">--}}
{{--                <img src="/api/placeholder/100/50" alt="Secretary Signature">--}}
{{--                <div>Secretary</div>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="seal">
            {{$product->name}}
            <br>
            {{$stock_product->created_at->format('d/m/Y')}}
        </div>
    </div>
</div>
</body>
</html>
