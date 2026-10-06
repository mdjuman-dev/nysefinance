@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-end gy-3">
        <div class="col-12">


            <form class="card-form" method="post" action="{{route('user.store.application')}}" enctype="multipart/form-data">
                @csrf
                <h4 class="text-center mb-4">Apply for Visa / MasterCard</h4>

                <!-- Card Preview -->
                <div class="card-preview hidden" id="cardPreview">
                    <img id="cardLogo" class="card-type-logo" src="" alt="">
                    <div class="card-number" id="previewNumber">**** **** **** ****</div>
                    <div class="card-name" id="previewName">CARDHOLDER NAME</div>
                </div>


                <div class="mt-2 mb-2">
                    <div class="d-flex justify-content-end">
                        <a href="/privacy" class="text-danger">Privacy</a> &nbsp;&nbsp;&nbsp; <a class="text-danger" href="/terms">Terms</a>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" id="fullName" placeholder="Enter your full name" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="example@email.com" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Phone Number</label>
                    <input type="tel" class="form-control" name="phone_number" placeholder="+1 555 123 4567" required>
                </div>

                <div class="mb-3">
                    <div class="form-group">
                        <label for="">Address</label>
                        <textarea name="address" id="" cols="3" rows="3" class="form-control" placeholder="Enter your full address......."></textarea>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Card Type</label>
                    <select class="form-select" name="card_type" id="cardType" required>
                        <option value="">Choose...</option>
                        <option value="Visa">Visa</option>
                        <option value="MasterCard">MasterCard</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Verification Type</label>
                    <select class="form-select" name="doc_type" id="verificationType">
                        <option value="">Choose...</option>
                        <option value="passport">Passport</option>
                        <option value="nid">NID</option>
                        <option value="license">Driving Licence</option>
                        <option value="bank">Bank Statement</option>
                    </select>
                </div>



                <div class="mb-3 verification-sec d-none" id="passport-type">

                    <div class="form-group">
                        <label class="form-label">Passport Number</label>
                        <input type="text" class="form-control" name="passport_number">
                    </div>

                   <div class="form-group">
                       <label class="form-label">Upload Passport</label>
                       <input type="file" class="form-control" name="passport" accept=".jpg,.png,.pdf" required>
                   </div>
                </div>

                <div class="mb-3 verification-sec d-none" id="nid-type">

                    <div class="form-group">
                        <label class="form-label">NID Number</label>
                        <input type="number" class="form-control" name="nid_number" accept=".jpg,.png,.pdf" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Front Part</label>
                        <input type="file" class="form-control" name="nid_front" accept=".jpg,.png,.pdf" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Back Part</label>
                        <input type="file" class="form-control" name="nid_back" accept=".jpg,.png,.pdf" required>
                    </div>
                </div>

                <div class="mb-3 verification-sec d-none" id="license-type">

                    <div class="form-group">
                        <label class="form-label">License Number</label>
                        <input type="number" class="form-control" name="license"  required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Front Part</label>
                        <input type="file" class="form-control" name="license_front" accept=".jpg,.png,.pdf" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Back Part</label>
                        <input type="file" class="form-control" name="license_back" accept=".jpg,.png,.pdf" required>
                    </div>

                </div>

                <div class="mb-3 verification-sec d-none" id="bank-type">
                    <label class="form-label">Bank Statement</label>
                    <input type="file" class="form-control" name="bank_statement" accept=".jpg,.png,.pdf" required>
                </div>



                <button type="submit" class="btn btn-primary w-100 mt-3">Submit Application</button>
            </form>


        </div>
    </div>
    <x-confirmation-modal isCustom="true"/>
@endsection

@push('topContent')
    <h4 class="mb-4">{{ __($pageTitle) }}</h4>
@endpush

@push('script-lib')

@endpush
@push('style-lib')

    <style>

        .card-form {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.25);
            width: 100%;
            max-width: 520px;
            padding: 30px;
            backdrop-filter: blur(4px);
        }

        /* Card Preview */
        .card-preview {
            position: relative;
            border-radius: 15px;
            color: #fff;
            padding: 25px;
            margin-bottom: 25px;
            height: 200px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            overflow: hidden;
            transition: all 0.5s ease;
            background: linear-gradient(110deg, #240307e3 5%, #e96110 60%, #ff9800 80%);
        }

        .card-preview.hidden {
            display: none;
        }

        .card-number {
            font-size: 16px;
            letter-spacing: 3px;
        }

        .card-name {
            font-size: 1rem;
            text-transform: uppercase;
            margin-top: 5px;
            letter-spacing: 1px;
        }

        .card-type-logo {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 70px;
            height: auto;
            filter: drop-shadow(1px 1px 3px rgba(0,0,0,0.4));
        }

        /* Different background colors for card types */
        .visa-bg {
            background: linear-gradient(135deg, #1a1f71, #009cde);
        }

        .mastercard-bg {
            background: linear-gradient(135deg, #ff5f00, #eb001b);
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff, #6610f2);
            border: none;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #4b0ecf);
        }

        .form-label {
            font-weight: 500;
        }
    </style>

@endpush


@push('script')

    <script>
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
