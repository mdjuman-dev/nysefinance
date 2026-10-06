<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Stock Certificate</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background-color: #f0f0f0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .certificate {
            /*width: 100%;*/
            height: 620px;
            background-color: white;
            /*border: 30px solid #a7c7e7;*/
            position: relative;
            overflow: hidden;
            margin-bottom: 20px;
        }
        .border {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            border: 2px solid #4a90e2;
            margin: 10px;
        }
        .corner {
            position: absolute;
            width: 150px;
            height: 150px;
            background-color: #a7c7e7;
        }
        .top-left { top: 0; left: 0; border-bottom-right-radius: 100%; }
        .top-right { top: 0; right: 0; border-bottom-left-radius: 100%; }
        .bottom-left { bottom: 0; left: 0; border-top-right-radius: 100%; }
        .bottom-right { bottom: 0; right: 0; border-top-left-radius: 100%; }
        .logo {
            text-align: center;
            margin-top: 40px;
            font-size: 48px;
            font-weight: bold;
        }
        .logo span:nth-child(1) { color: #4285F4; }
        .logo span:nth-child(2) { color: #EA4335; }
        .logo span:nth-child(3) { color: #FBBC05; }
        .logo span:nth-child(4) { color: #4285F4; }
        .logo span:nth-child(5) { color: #34A853; }
        .logo span:nth-child(6) { color: #EA4335; }
        .company-name {
            text-align: center;
            font-size: 24px;
            margin-top: 20px;
            color: #333;
        }
        .details {
            text-align: center;
            font-size: 12px;
            margin-top: 10px;
            color: #666;
        }
        .content {
            margin: 40px;
            text-align: center;
            font-size: 14px;
            color: #333;
        }
        .stamp {
            position: absolute;
            bottom: 60px;
            right: 100px;
            width: 100px;
            height: 100px;
            border: 2px solid #4a90e2;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 10px;
            text-align: center;
            color: #4a90e2;
        }
        .signature {
            position: absolute;
            bottom: 40px;
            font-style: italic;
            color: #333;
        }
        .signature.left { left: 100px; }
        .signature.right { right: 100px; }
        .stock-info {
            position: absolute;
            top: 20px;
            font-size: 12px;
            color: #333;
            background: #e6f3ff;
            padding: 5px 10px;
            border-radius: 15px;
        }
        .stock-info.left { left: 20px; }
        .stock-info.right { right: 20px; }
        .downloadBtn {
            padding: 10px 20px;
            font-size: 16px;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin: 0 10px;
        }
        #downloadPngBtn { background-color: #4285F4; }
        #downloadPngBtn:hover { background-color: #3367D6; }
        #downloadPdfBtn { background-color: #34A853; }
        #downloadPdfBtn:hover { background-color: #2E7D32; }
    </style>
</head>
<body>
<div class="certificate" id="certificate" style="background-image: url('{{$bgImage}}'); background-position: center;background-repeat: no-repeat;background-size: cover;">
{{--    <div class="border"></div>--}}
{{--    <div class="corner top-left"></div>--}}
{{--    <div class="corner top-right"></div>--}}
{{--    <div class="corner bottom-left"></div>--}}
{{--    <div class="corner bottom-right"></div>--}}
    <div class="stock-info left">{{$product->stock_code}}</div>
    <div class="stock-info right">CUSIP {{$stock_product->certificate_id}}</div>
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
    <div class="company-name">{{$product->name}}</div>
    <div class="details">
        {!! $product->short_description !!}
    </div>
    <div class="content">
        <p>THIS CERTIFIES THAT</p>
        <p>__________________________</p>
        <p>IS THE OWNER OF</p>

        {!! $product->description !!}
    </div>
    <div class="stamp">
        {{$product->name}}<br>
        CORPORATE<br>
        SEAL<br>
        DELAWARE
    </div>
{{--    <div class="signature left">Larry Page</div>--}}
{{--    <div class="signature right">Sergey Brin</div>--}}
</div>
</body>
</html>
