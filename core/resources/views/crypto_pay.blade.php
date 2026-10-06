<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{isset($currency_code)?$currency_code:'USDT'}} Deposit Page</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-1/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>







    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
        }
        #qr-code img{
            margin: 0 auto;
        }
        #qr-code {
            margin: 20px auto;
        }
        /*#deposit-address {*/
        /*    font-size: 16px;*/
        /*    margin: 20px 0;*/
        /*    padding: 10px;*/
        /*    border: 1px solid #ddd;*/
        /*    border-radius: 8px;*/
        /*    background: #f9f9f9;*/
        /*    word-break: break-all;*/
        /*}*/


        #timer {
            font-size: 20px;
            margin-top: 20px;
            color: #333;
        }
        .timeout-message {
            display: none;
            font-size: 18px;
            color: #ff4d4d;
            margin-top: 20px;
        }
        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
            h1 {
                font-size: 20px;
            }
            #deposit-address {
                font-size: 14px;
            }
            #timer {
                font-size: 18px;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <h1>USDT Deposit</h1>
    <div id="deposit-section">
        <div id="qr-code"></div>

        <div class="input-group mb-3">
            <input id="deposit-address" class="form-control" disabled value="Loading deposit address...">

            <div class="input-group-prepend">
                <span class="input-group-text" id="basic-addon1">
                     <button id="copy-button" class="btn btn-sm btn-success"><i class="fa fa-copy"></i></button>
                    </span>
            </div>

        </div>


        <div class="form-group mb-2">
            <label for="">Payable Amount</label>
            <div class="input-group">
                <input class="form-control" disabled value="{{$amount}}">

                <div class="input-group-prepend">
                <span class="input-group-text">
                     <button id="copy-amount" class="btn btn-sm btn-success"><i class="fa fa-copy"></i></button>
                    </span>
                </div>
            </div>
        </div>


        <div class="alert alert-danger mb-3" role="alert" style="font-size: 12px;padding: 10px;">
            Make sure you paid the same amount. Otherwise payment will not approved!
        </div>





        <div id="timer">01:00:00</div>
    </div>
    <div class="timeout-message" id="timeout-message">
        Payment Timeout! Please refresh the page to generate a new deposit address.
    </div>

    <p style="text-align: center; margin-top: 50px; color: #0c3c85; font-style: italic;">
        <small>2025 © <b>NyseFinance</b> All Rights Reserved</small>
    </p>

</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>



<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" ></script>



<script>
    // Simulated USDT deposit address (replace with server-side logic)
    const depositAddress = '{{$crypto_address}}';

    // Display the deposit address
    document.getElementById("deposit-address").value = depositAddress;

    // Generate QR code
    new QRCode(document.getElementById("qr-code"), {
        text: depositAddress,
        width: 200,
        height: 200,
    });

    $(document).on('click', '#copy-button', function(e){

        navigator.clipboard.writeText(depositAddress);

        toastr.success('Address successfully copied!')
    });

    $(document).on('click', '#copy-amount', function(e){

        navigator.clipboard.writeText('{{$amount}}');

        toastr.success('Amount successfully copied!')
    });


    // Timer logic
    const timerDisplay = document.getElementById("timer");
    const depositSection = document.getElementById("deposit-section");
    const timeoutMessage = document.getElementById("timeout-message");

    let startTime = localStorage.getItem("startTime");
    if (!startTime) {
        startTime = Date.now();
        localStorage.setItem("startTime", startTime);
    }

    const countdownDuration = 60 * 60 * 1000; // 1 hour in milliseconds

    function updateTimer() {
        const currentTime = Date.now();
        const elapsedTime = currentTime - startTime;
        const remainingTime = countdownDuration - elapsedTime;

        if (remainingTime <= 0) {
            // Timer has expired
            clearInterval(timerInterval);
            depositSection.style.display = "none";
            timeoutMessage.style.display = "block";
            localStorage.removeItem("startTime"); // Clear the timer
            return;
        }

        const hours = Math.floor(remainingTime / 3600000);
        const minutes = Math.floor((remainingTime % 3600000) / 60000);
        const seconds = Math.floor((remainingTime % 60000) / 1000);
        timerDisplay.textContent =
            `${String(hours).padStart(2, "0")}:${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
    }

    const timerInterval = setInterval(updateTimer, 1000);
    updateTimer(); // Initial call
</script>


<script>


    $(document).ready(function() {
        // Define the function that contains the AJAX request
        function fetchData() {
            $.ajax({
                type: 'GET',
                url: '{{route('check.crypto.transaction')}}',
                data: {
                    request_id: '{{$deposit_id}}'
                },
                success: function(res) {
                    if (res.status == 'success') {
                        toastr.success('Thanks for your payment!')
                        location.href = '{{route('user.wallet.overview')}}';
                    }

                    if (res.status == 'rejected') {
                        toastr.success(res.message)
                        location.href = '{{route('user.wallet.overview')}}';
                    }
                }
            });
        }

        // Run the fetchData function every 15 seconds (15000 milliseconds)
        setInterval(fetchData, 15000);
    });



    {{--$(document).ready(function(){--}}


    {{--    $.ajax({--}}
    {{--        type:'GET',--}}
    {{--        url:'',--}}
    {{--        data:{--}}
    {{--            request_id:'{{$deposit_id}}'--}}
    {{--        },--}}

    {{--        success:function (res){--}}
    {{--            if(res.status=='success'){--}}
    {{--                location.href='{{route('user.wallet.overview')}}'--}}
    {{--            }--}}
    {{--        }--}}
    {{--    })--}}

    {{--});--}}
</script>


</body>
</html>
