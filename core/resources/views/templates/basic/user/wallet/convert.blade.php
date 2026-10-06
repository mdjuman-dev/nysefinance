<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Converter</title>
    <script src="https://cdn.tailwindcss.com/3.4.16">
    </script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: "#FFA500", secondary: "#FFD700" },
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"  />

    <style>
        :where([class^="ri-"])::before { content: "\f3c2"; }
        body {
            background-color: #1E1E1E;
            color: #fff;
            font-family: 'Inter', sans-serif;
        }
        .currency-card {
            background-color: #2A2A2A;
            border-radius: 12px;
        }
        .warning-banner {
            background-color: rgba(255, 174, 0, 0.2);
            border-left: 4px solid #FFAE00;
        }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }
        .exchange-icon {
            background-color: #FFA500;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
        }
        .zero-fees {
            background-color: rgba(0, 128, 0, 0.2);
            color: #00FF00;
            border-radius: 4px;
            padding: 2px 8px;
        }
        .dropdown-icon {
            transition: transform 0.3s ease;
        }
        .dropdown-open .dropdown-icon {
            transform: rotate(180deg);
        }
        .h-screen{
            height: 95vh !important;
        }
        /* Select2 Custom Styles */
        .select2-container--default .select2-selection--single {
            background-color: transparent;
            border: none;
            height: auto;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: white;
            padding: 0;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            display: none;
        }
        .select2-dropdown {
            background-color: #2A2A2A;
            border: 1px solid #3A3A3A;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #1E1E1E;
            color: white;
            border: 1px solid #3A3A3A;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #3A3A3A;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #4A4A4A;
        }
        .select2-results__option {
            color: white;
            padding: 8px;
        }
        .select2-container {
            width: 100% !important;
        }
        .select2-container--open .select2-dropdown--below{
            width: 150px !important;
        }



        .select2-results__options::-webkit-scrollbar-track
        {
            -webkit-box-shadow: inset 0 0 3px rgba(0,0,0,0.3);
            background-color: #F5F5F5;
        }

        .select2-results__options::-webkit-scrollbar
        {
            width: 2px;
            background-color: #F5F5F5;
        }

        .select2-results__options::-webkit-scrollbar-thumb
        {
            background-color: #000000;
            border: 1px solid #555555;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
<div class="max-w-md mx-auto w-full flex flex-col h-screen bg-[#1E1E1E]">
    <!-- Header -->
    <header class="flex items-center justify-between p-4 border-b border-gray-700">
        <a href="{{route('user.wallet.overview')}}" class="w-8 h-8 flex items-center justify-center text-gray-300">
            <i class="ri-arrow-left-line ri-lg"></i>
        </a>
        <h1 class="text-xl font-medium">Convert</h1>
        <button class="w-8 h-8 flex items-center justify-center text-gray-300">
            <i class="ri-settings-3-line ri-lg"></i>
        </button>
    </header>
    <!-- Warning Banner -->
    <div class="warning-banner p-3 flex items-center space-x-2 text-sm text-amber-400">
        <div class="w-5 h-5 flex items-center justify-center">
            <i class="ri-alert-line"></i>
        </div>
        <p>Conversion is currently only available via ...</p>
        <div class="ml-auto w-5 h-5 flex items-center justify-center">
            <i class="ri-arrow-down-s-line dropdown-icon"></i>
        </div>
    </div>
    <!-- Main Content -->
    <main class="flex-1 p-4 flex flex-col">
        <form action="{{route('user.convert.amount')}}" method="POST" class="flex-1 flex flex-col">
            @csrf

            <!-- Account Selection -->
            <div class="flex justify-between items-center mb-4">
                <span class="text-gray-400">Account</span>
                <button type="button" class="flex items-center space-x-2 text-white">
                    <span>Funding Account</span>
                    <div class="w-5 h-5 flex items-center justify-center">
                        <i class="ri-arrow-down-s-line"></i>
                    </div>
                </button>
            </div>
            <!-- Conversion Form -->
            <div class="relative mb-6">
                <!-- From Currency -->
                <div class="currency-card p-4 mb-2">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-400">From</span>
                        <span class="text-sm text-gray-400"><b class="available-bal"></b>
                            <span style="cursor: pointer" class="text-amber-400 cursor-pointer hover:text-amber-300 cick-all-bal">All</span>
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="relative">
                            <button id="fromCurrencyBtn" type="button" class="flex items-center space-x-2 py-1 hover:bg-gray-700 rounded-lg pr-2">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">$</span>
                                </div>
                                <div>
                                    <select id="fromCurrencySelect" class="currency-select">

                                    </select>
                                </div>
                            </button>
                        </div>
                        <input type="hidden" class="cv_rate">
                        <div class="flex-1 ml-4">
                            <input type="number" name="fromAmount" value="" placeholder="Enter Amount" class="bg-transparent text-right text-xl font-medium focus:outline-none w-full" required />
                            <input type="hidden" name="fromCurrency" id="fromCurrencyInput" value="USDC" />
                            <input type="hidden" name="toCurrency" id="toCurrencyInput" value="USDT" />
                        </div>
                    </div>
                </div>
                <!-- Exchange Icon -->
                <div class="exchange-icon flex items-center justify-center">
                    <i class="ri-arrow-up-down-line text-white"></i>
                </div>
                <!-- To Currency -->
                <div class="currency-card p-4">
                    <div class="flex justify-between items-center mb-1">
                        <span class="text-sm text-gray-400">To</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="relative">
                            <button id="toCurrencyBtn" type="button" class="flex items-center space-x-2 py-1 hover:bg-gray-700 rounded-lg pr-2">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <span class="text-white font-bold">T</span>
                                </div>
                                <div>
                                    <select id="toCurrencySelect" class="currency-select">

                                    </select>
                                </div>
                            </button>
                        </div>
                        <div class="flex-1 ml-4">
                            <span class="block text-right text-xl font-medium to_coin_rate">0.00</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Conversion Details -->
            <div class="space-y-3 mb-6">
                <div class="flex justify-center text-sm text-gray-400">
                    <span>1 USDC = 0.99181659 USDT</span>
                </div>
{{--                <div class="flex justify-between items-center">--}}
{{--                    <span class="text-gray-400">Single Limit</span>--}}
{{--                    <span>10-100000 USDC</span>--}}
{{--                </div>--}}
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Fee Rate</span>
                    <span class="zero-fees text-xs">Zero Fees</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-400">Receive</span>
                    <span class="receive_amount">0.00 <small class="receive_currency"></small> </span>
                </div>
            </div>

            <input type="hidden" name="from_coin" class="input_from_coin">
            <input type="hidden" name="to_coin" class="input_to_coin">

            <!-- Footer -->
            <footer class="p-4 mt-auto">
                <button type="submit" class="w-full bg-primary text-white py-4 font-medium !rounded-button">Convert</button>
            </footer>

        </form>
    </main>

</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" ></script>
<script>


    $(document).ready(function () {
        let toData = '';
        let fromData = '';

        $('#fromCurrencySelect').html(`<option>Loading.......</option>`);
        $('#toCurrencySelect').html(`<option>Loading.......</option>`)


        $.ajax({
            type: 'GET',
            url: '{{route('user.get.convert.pairs')}}',

            success: function (res) {

                if (res.status == 'success') {

                    $.each(res.to, function (index, value) {
                        toData += `<option value="${value}" data-icon="$" data-subtext="${value}">${value}</option>`;
                    });

                    $.each(res.from, function (index, value) {
                        fromData += `<option value="${value}" data-icon="$" data-subtext="${value}">${value}</option>`;
                    });


                    $('#fromCurrencySelect').html(fromData);
                    $('#toCurrencySelect').html(toData);

                    $('#fromCurrencySelect').trigger('change');

                }
            }
        })



    })

    $(document).on('click', '.cick-all-bal', function (e){

        const amnt=$('.available-bal').text();

        $('input[name=fromAmount]').val(amnt).trigger('keyup');

    });

    $(document).on('keyup or change', 'input[name=fromAmount]', function(e){
        const cv_rate=$('.cv_rate').val();
        const cv_amount=$(this).val();
        const to_coin = $('#toCurrencySelect').val();

        let grandAmount= cv_amount * cv_rate;

        if(!grandAmount || grandAmount <= 0){
            grandAmount=0.00;
        }


        $('.to_coin_rate').text(cv_rate)
        $('.receive_amount').text(grandAmount.toFixed());
        $('.receive_currency').text(to_coin);
    })



    $(document).ready(function() {
        // Initialize Select2 dropdowns
        $('#fromCurrencySelect, #toCurrencySelect').select2({
            dropdownParent: $('body'),
            minimumResultsForSearch: 0,
            templateResult: formatCurrencyOption,
            templateSelection: formatCurrencySelection
        });

        // Format dropdown options
        function formatCurrencyOption(currency) {
            if (!currency.id) return currency.text;
            return $(`
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background-color: ${currency.id === 'USDC' ? '#2775CA' : '#26A17B'}">
                        <span class="text-white font-bold">${$(currency.element).data('icon')}</span>
                    </div>
                    <div>
                        <div class="font-medium">${currency.text}</div>
                        <div class="text-xs text-gray-400">${$(currency.element).data('subtext')}</div>
                    </div>
                </div>
            `);
        }

        // Format selected option
        function formatCurrencySelection(currency) {
            if (!currency.id) return currency.text;
            return $(`
                <div class="flex items-center space-x-1">
                    <span class="font-medium">${currency.text}</span>
                    <i class="ri-arrow-down-s-line"></i>
                </div>
            `);
        }

        // Trigger getConvertRate() when either dropdown changes
        $('#fromCurrencySelect, #toCurrencySelect').on('change', function() {
            getConvertRate();
        });

        // Also trigger when "From Amount" changes
        $('input[name="fromAmount"]').on('input', function() {
            getConvertRate();
        });

        // Fetch conversion rate and update UI
        function getConvertRate() {
            const from_coin = $('#fromCurrencySelect').val(); // Get selected "From" coin
            const to_coin = $('#toCurrencySelect').val();     // Get selected "To" coin
            const from_amount = $('input[name="fromAmount"]').val(); // Get amount

            if (!from_coin || !to_coin) return; // Skip if no selection

            $.ajax({
                type: "GET",
                url: '{{ route("user.convert.rate") }}',
                data: {
                    from_coin: from_coin,
                    to_coin: to_coin,
                    amount: from_amount || 1 // Default to 1 if empty
                },
                success: function(res) {
                    if (res.status == 'success') {
                        console.log(res);
                        // Update the conversion rate display
                        $('.flex.justify-center.text-sm.text-gray-400 span').text(
                            `1 ${from_coin} = ${res.convert_rate} ${to_coin}`
                        );




                        $('.input_from_coin').val(from_coin);
                        $('.input_to_coin').val(to_coin);
                        $('.to_coin_rate').text(res.convert_rate)
                        $('.available-bal').text(res.available_amount)
                        $('.cv_rate').val(res.convert_rate)
                        // Update the "To Amount" field
                        $('.currency-card:eq(1) .text-right.text-xl.font-medium').text(res.converted_amount);
                    }else{
                        toastr.error('Something went wrong')
                    }
                },
                error: function(xhr) {
                    toastr.error('Something went wrong')
                }
            });
        }
    });
</script>


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
