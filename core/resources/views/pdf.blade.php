<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"  />


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>

</head>
<body>
<div style="background-image: url('{{$bgImage}}'); background-position: center;background-repeat: no-repeat;
background-size: cover;min-height: 800px;padding: 100px;">

    <div class="container" >
        <div class="row ">
            <div class="col-md-2 mx-auto">
                @if(isset($base64Image))
                    <div class="image-section" style="height: 100px;">
                        <img src="{{$base64Image}}" alt="" style="height: 100%; width: 100%;">
                    </div>
                @endif
            </div>
            <div class="col-md-12 mt-3 text-center">
                <h4>
                    {{$product->name}}
                </h4>
            </div>

            <div class="col-md-10 mx-auto mt-2 text-center">
                {!! $product->short_description !!}
            </div>

            <div class="col-md-12 text-center mt-4 mb-2">
                <h4>THIS CERTIFIES THAT</h4>
                <p>____________________________________________________</p>
                <h4>IS THE OWNER OF</h4>
            </div>

            <div class="col-md-11 mx-auto mt-3">
                {!! $product->description !!}
            </div>
        </div>
    </div>
</div>
</body>
</html>
