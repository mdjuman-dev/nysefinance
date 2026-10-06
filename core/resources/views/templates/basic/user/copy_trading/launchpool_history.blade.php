<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Launchpool</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {primary: "#F2A900", secondary: "#333333"},
                    borderRadius: {
                        none: "0px",
                        sm: "4px",
                        DEFAULT: "8px",
                        md: "12px",
                        lg: "16px",
                        xl: "20px",
                        "2xl": "24px",
                        "3xl": "32px",
                        full: "9999px",
                        button: "8px",
                    },
                },
            },
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link
        href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap"
        rel="stylesheet"
    />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css"
    />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"  />


    <style>
        :where([class^="ri-"])::before {
            content: "\f3c2";
        }

        body {
            background-color: #000000;
            color: white;
            font-family: 'Inter', sans-serif;
        }

        .vip-badge {
            background-color: #8B5A00;
            color: #F2A900;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .percentage-badge {
            background-color: #F2A900;
            color: #000000;
            font-size: 0.75rem;
            padding: 2px 6px;
            border-radius: 4px;
        }

        .btn-customize {
            padding: 7px !important;
        }
        .form-control,.form-control:focus{
            color: #ffffff; !important;
            background-color: #141414a1; !important;
        }
        .buy-now-btn{
            float: right;
            background: #17099b;
            font-size: 12px;
            padding: 1px 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body class="bg-black text-white">
<!-- Header -->
<header
    class="fixed top-0 w-full bg-black z-10 px-4 py-4 flex items-center justify-between border-b border-gray-800"
>
    <div class="w-8 h-8 flex items-center justify-center cursor-pointer">
        <a href="{{route('user.home')}}">
            <i class="ri-arrow-left-line ri-lg"></i>
        </a>
    </div>
    <h1 class="text-xl font-semibold">Launchpool History</h1>
    <div class="w-8 h-8 flex items-center justify-center cursor-pointer">
        <i class=""></i>
    </div>
</header>

<!-- Main Content -->
<main class="pt-16 pb-20 px-4">
    <section class="mt-6">

    </section>

    <!-- Project Card -->
    <section class="mt-6">
        <div class="bg-[#1C1C1C] rounded-xl p-4 relative">

            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div
                        class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center"
                    >
                        <i class="ri-cube-line ri-lg"></i>
                    </div>
                    <span class="text-1xl font-bold">My Stack</span>
                </div>
                <i class="ri-arrow-right-s-line ri-lg"></i>
            </div>

            <div class="flex justify-between mb-6">
                <div>
                    <div class="text-gray-400 text-sm">Total Stack Amount</div>
                    <div class="text-1xl font-bold">${{$total_invest}}</div>
                </div>
                <div class="text-right">
                    <div class="text-gray-400 text-sm">My Rewards</div>
                    <div class="text-1xl font-bold">0.00</div>
                </div>
            </div>


            <div>
                <!-- SOSO Pool -->

                @foreach($pools_histories as $pools_history)
                <div class="flex items-center justify-between py-4 border-t border-gray-700" >
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center">
                            <img style="border-radius: 50%;" src="{{ getImage(getFilePath('currency') .'/'.$pools_history->pool->image,getFileSize('currency')) }}" alt="">
                        </div>
                        <div>
                            <div class="font-semibold">
                                {{$pools_history->pool->name}} <small>({{$pools_history->price}})</small>
                            </div>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-sm text-gray-400">Stack Amount: {{$pools_history->invest_amount}}</span>

                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </section>
</main>



<!-- Modal -->
<div class="modal fade" id="buyPoolModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="background: #1c1c1cd1;">
        <div class="modal-content" style="background-color: #1d1d1d !important;">
            <form action="{{route('user.buy.pool.coin')}}" method="post">
                @csrf

                <input type="hidden" name="pool_id" class="pool_id">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Confirm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="">Coin Price</label>
                        <input type="text"  class="form-control coin_price">
                    </div>

                    <div class="form-group mt-4">
                        <label for="">Invest Amount</label>
                        <input type="text" name="invest_amount" class="form-control">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success btn-sm">Confirm</button>
                </div>

            </form>
        </div>
    </div>
</div>


<!-- Bottom Navigation -->
<nav
    class="fixed bottom-0 w-full bg-[#111111] border-t border-gray-800 flex justify-around py-3"
>
    <a href="{{route('user.launch.pool')}}" class="flex flex-col items-center text-gray-500">
        <div class="w-6 h-6 flex items-center justify-center">
            <i class="ri-rocket-2-line ri-lg"></i>
        </div>
        <span class="text-xs mt-1">Launchpool</span>
    </a>
    <a href="{{route('user.launch.pool.transaction')}}" class="flex flex-col items-center text-gray-500">
        <div class="w-6 h-6 flex items-center justify-center">
            <i class="ri-wallet-3-line ri-lg"></i>
        </div>
        <span class="text-xs mt-1">Staking Transaction</span>
    </a>
    <a href="{{route('user.launch.pool.history')}}" class="flex flex-col items-center text-primary">
        <div class="w-6 h-6 flex items-center justify-center">
            <i class="ri-history-line ri-lg"></i>
        </div>
        <span class="text-xs mt-1">Stack History</span>
    </a>
</nav>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" ></script>


<script id="navigation-script">
    document.addEventListener("DOMContentLoaded", function () {
        const navLinks = document.querySelectorAll("nav a");
        navLinks.forEach((link) => {
            link.addEventListener("click", function (e) {
                // e.preventDefault();
                navLinks.forEach((l) => l.classList.remove("text-primary"));
                this.classList.add("text-primary");
            });
        });
    });
</script>

<script id="share-button-script">
    document.addEventListener("DOMContentLoaded", function () {
        const shareButton = document.querySelector("button:nth-of-type(2)");
        shareButton.addEventListener("click", function () {
            if (navigator.share) {
                navigator.share({
                    title: "Launchpool",
                    text: "Check out this amazing staking opportunity!",
                    url: window.location.href,
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                const tempInput = document.createElement("input");
                tempInput.value = window.location.href;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand("copy");
                document.body.removeChild(tempInput);

                // Create a toast notification
                const toast = document.createElement("div");
                toast.textContent = "Link copied to clipboard!";
                toast.style.position = "fixed";
                toast.style.bottom = "80px";
                toast.style.left = "50%";
                toast.style.transform = "translateX(-50%)";
                toast.style.backgroundColor = "rgba(0,0,0,0.8)";
                toast.style.color = "white";
                toast.style.padding = "10px 20px";
                toast.style.borderRadius = "4px";
                toast.style.zIndex = "1000";
                document.body.appendChild(toast);

                setTimeout(() => {
                    document.body.removeChild(toast);
                }, 2000);
            }
        });
    });
</script>


<script>

    $(document).on('click', '.buyPool', function (e){

        const id=$(this).attr('data-id');
        const price=$(this).attr('data-price')

        $('.coin_price').val(price).attr('readonly')
        $('.pool_id').val(id)


        $('#buyPoolModal').modal('show');
    })
</script>

@include('templates.basic.user.copy_trading.includes.modal')


@include('templates.basic.user.copy_trading.includes.modal_js')



<script>
    @if(Session::has('success'))
    toastr.success("{{ Session::get('success') }}", "Success!", {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 5000,
        extendedTimeOut: 2000,
    });
    @endif

    @if(Session::has('error'))
    toastr.error("{{ Session::get('error') }}", "Error!", {
        closeButton: true,
        progressBar: true,
        positionClass: "toast-top-right",
        timeOut: 8000, // Longer display for errors
        extendedTimeOut: 3000,
    });
    @endif
</script>

</body>
</html>
