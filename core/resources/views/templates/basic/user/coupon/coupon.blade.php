@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end gy-3">
        <div class="col-12">

            <div class="card">
                <div class="card-body">
                    <div class="row">


                        @foreach($coupons as $coupon)
                            <div class="col-md-3 col-12 main-coupon-sec mt-2" data-url="{{route('user.coupon.details', [$coupon->id])}}">
                                <div class="section-coupon">
                                    <div class="section-icon">
                                        <img src="{{ getImage(getFilePath('currency') .'/'.$coupon->icon,getFileSize('currency')) }}" >
                                    </div>
                                    <div class="section-details">
                                        <div class="sec-name">
                                            <h5 class="mb-0">{{$coupon->name}}</h5>
                                        </div>
                                        <div class="sec-name">
                                            $<b>{{$coupon->price}}</b>
                                        </div>
                                        <div class="sec-name">
                                            <small>Wining Date {{$coupon->wining_date}}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-confirmation-modal isCustom="true"/>
@endsection

@push('topContent')
    <div class="d-flex justify-content-between">
        <h4 class="mb-4">{{ __($pageTitle) }}</h4>

        <div>
            <a href="{{route('user.my.coupon')}}" class="btn btn-sm btn-info">My JackPlay</a>
        </div>
    </div>
@endpush

@push('script-lib')

@endpush
@push('style-lib')

    <style>
        .section-coupon{
            display: flex;
        }
        .section-icon img{
            border-radius: 50px;
            padding: 5px;
        }
        .section-icon{
            width: 70px;
            height: 50px;
        }
        .main-coupon-sec{
            background: #6c6c6c;
            padding: 10px;
            border-radius: 5px;
        }
        .section-details{
            color: white;
            padding-left: 10px;
        }
    </style>

@endpush


@push('script')

    <script>

        $(document).on('click', '.main-coupon-sec', function(e){
            const url=$(this).attr('data-url');

            location.href=url;
        })

        $('#verificationType').on('change', function () {
            const type=$(this).val();

            $('.verification-sec').addClass('d-none');

            $('#'+type+'-type').removeClass('d-none');
        });

        $('#cardType').on('change', function () {
            const type = $(this).val();
            const cardLogo = $('#cardLogo');
            if(type){
                $('#cardPreview').removeClass('hidden');
                let cardNumber = (type === 'Visa') ? '4123 **** **** 9876' : '5234 **** **** 6543';
                $('#previewNumber').text(cardNumber);

                // Set logo based on card type
                if(type === 'Visa'){
                    cardLogo.attr('src', 'https://upload.wikimedia.org/wikipedia/commons/4/41/Visa_Logo.png');
                } else {
                    cardLogo.attr('src', 'https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg');
                }
            } else {
                $('#cardPreview').addClass('hidden');
                cardLogo.attr('src', '');
            }


            if (type) {
                $('#cardPreview').removeClass('hidden');
                $('#previewType').text(type);
                let cardNumber = (type === 'Visa') ? '4123 **** **** 9876' : '5234 **** **** 6543';
                $('#previewNumber').text(cardNumber);
            } else {
                $('#cardPreview').addClass('hidden');
            }
        });



        // Update cardholder name live
        $('#fullName').on('input', function(){
            const name = $(this).val().toUpperCase() || 'CARDHOLDER NAME';
            $('#previewName').text(name);
        });

    </script>

@endpush
