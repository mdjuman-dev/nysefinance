@extends($activeTemplate.'layouts.master')
@section('content')
    {{--    <div class="container">--}}
    {{--        <div class="row justify-content-center">--}}
    {{--            <div class="col-lg-8">--}}
    {{--                <div class="card custom--card">--}}
    {{--                    <div class="card-header">--}}
    {{--                        <h5 class="card-title">@lang('KYC Form')</h5>--}}
    {{--                    </div>--}}
    {{--                    <div class="card-body">--}}
    {{--                        <form action="{{route('user.kyc.submit')}}" method="post" enctype="multipart/form-data">--}}
    {{--                            @csrf--}}

    {{--                                <x-viser-form identifier="act" identifierValue="kyc" />--}}

    {{--                            <div class="form-group">--}}
    {{--                                <button type="submit" class="btn btn--base w-100">@lang('Submit')</button>--}}
    {{--                            </div>--}}
    {{--                        </form>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}












    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <section class="wizard-section">
                    <div class="row no-gutters">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-wizard">
                                <form action="{{route('user.kyc.submit')}}" method="post" id="kycForm" role="form" enctype="multipart/form-data">
                                    @csrf


                                    <div class="form-wizard-header">
                                        <div class="wizard-title-section">
                                            <h2 class="wizard-main-title">Time To Verify Your Account</h2>
                                            <p class="wizard-subtitle">Complete your KYC verification in 5 simple steps</p>
                                        </div>
                                        <div class="wizard-progress-container">
                                            <ul class="list-unstyled form-wizard-steps clearfix">
                                                <li class="active" data-step="1">
                                                    <span class="step-number">1</span>
                                                    <span class="step-label">Personal Info</span>
                                                </li>
                                                <li data-step="2">
                                                    <span class="step-number">2</span>
                                                    <span class="step-label">Financial Info</span>
                                                </li>
                                                <li data-step="3">
                                                    <span class="step-number">3</span>
                                                    <span class="step-label">ID Verification</span>
                                                </li>
                                                <li data-step="4">
                                                    <span class="step-number">4</span>
                                                    <span class="step-label">US Stock Info</span>
                                                </li>
                                                <li data-step="5">
                                                    <span class="step-number">5</span>
                                                    <span class="step-label">Emergency Contacts</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <fieldset class="wizard-fieldset show">
                                        <div class="fieldset-header">
                                            <h5 class="fieldset-title">Personal Information</h5>
                                            <p class="fieldset-description">Tell us about yourself to get started</p>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control wizard-required" name="first_name" id="fname">
                                            <label for="fname" class="wizard-form-text-label">First Name*</label>
                                            <div class="wizard-form-error"></div>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control wizard-required" name="last_name" id="lname">
                                            <label for="lname" class="wizard-form-text-label">Last Name*</label>
                                            <div class="wizard-form-error"></div>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control wizard-required" name="maiden_name" id="maiden_name">
                                            <label for="maiden_name" class="wizard-form-text-label">Mother's Maiden Name*</label>
                                            <div class="wizard-form-error"></div>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control wizard-required" name="phone" id="pnumber">
                                            <label for="pnumber" class="wizard-form-text-label">Enter Number With Country Code*</label>
                                            <div class="wizard-form-error"></div>
                                        </div>


                                        <div class="form-group">
                                            <select name="home_owner" class="form-control">
                                                <option selected></option>
                                                <option value="Own Home">Own Home</option>
                                                <option value="Family Home">Family Home</option>
                                                <option value="Renting Home">Renting Home</option>
                                                <option value="Others">Others</option>
                                            </select>
                                            <label class="wizard-form-text-label">Home Ownership Status*</label>
                                        </div>

                                        <div class="form-group">
                                            Marital Status
                                            <br>
                                            <div class="wizard-form-radio">
                                                <input name="marital_status" id="married" type="radio">
                                                <label for="married">Married</label>
                                            </div>
                                            <div class="wizard-form-radio">
                                                <input name="marital_status" checked id="single" type="radio">
                                                <label for="single">Single</label>
                                            </div>
                                            <div class="wizard-form-radio">
                                                <input name="marital_status" id="divorced" type="radio">
                                                <label for="divorced">Divorced</label>
                                            </div>
                                        </div>

                                        <div class="form-group clearfix">
                                            <a href="javascript:;" class="form-wizard-next-btn float-right">Next</a>
                                        </div>
                                    </fieldset>

                                    <fieldset class="wizard-fieldset">
                                        <div class="fieldset-header">
                                            <h5 class="fieldset-title">Financial Information</h5>
                                            <p class="fieldset-description">Help us understand your financial background</p>
                                        </div>

                                        <div class="form-group">
                                            <select name="occupation" class="form-control">
                                                <option selected></option>
                                                <option value="Student">Student</option>
                                                <option value="Employee">Employee</option>
                                                <option value="Government Employee">Government Employee</option>
                                                <option value="Freelancer">Freelancer</option>
                                                <option value="Others">Others</option>
                                            </select>
                                            <label class="wizard-form-text-label">Occupation*</label>
                                        </div>

                                        <div class="form-group">
                                            <select name="income_source" class="form-control">
                                                <option selected></option>
                                                <option value="Salary">Salary</option>
                                                <option value="Savings">Savings</option>
                                                <option value="Others">Others</option>
                                            </select>
                                            <label class="wizard-form-text-label">Source Of Income*</label>
                                        </div>

                                        <div class="form-group">
                                            <select name="annual_income" class="form-control">
                                                <option selected></option>
                                                <option value="<50 million"> <50 million </option>
                                                <option value="50-200 million"> 50-200 million </option>
                                                <option value="250-500 million"> 250-500 million </option>
                                                <option value="500-1.5 billion"> 500-1.5 billion </option>
                                                <option value="250-500 billion"> >1.5 billion </option>
                                            </select>
                                            <label class="wizard-form-text-label">Annual Income*</label>
                                        </div>

                                        <div class="form-group">
                                            <select name="account_purpose" class="form-control">
                                                <option selected></option>
                                                <option value="Hedging"> Hedging </option>
                                                <option value="Investment"> Investment </option>
                                                <option value="Speculation"> Speculation </option>
                                                <option value="Others"> Others </option>
                                            </select>
                                            <label class="wizard-form-text-label">Purpose Of Account*</label>
                                        </div>


                                        <div class="form-group clearfix">
                                            <a href="javascript:;" class="form-wizard-previous-btn float-left">Previous</a>
                                            <a href="javascript:;" class="form-wizard-next-btn float-right">Next</a>
                                        </div>
                                    </fieldset>

                                    <fieldset class="wizard-fieldset">
                                        <div class="fieldset-header">
                                            <h5 class="fieldset-title">ID Verification</h5>
                                            <p class="fieldset-description">Upload your identification documents</p>
                                        </div>

                                        <div class="form-group">
                                            <select name="document_type"  class="form-control document_type">
                                                <option selected></option>
                                                <option value="ID Card">ID Card</option>
                                                <option value="Passport">Passport</option>
                                                <option value="Driving Licence">Driving Licence</option>
                                            </select>
                                            <label class="wizard-form-text-label">Document Type*</label>
                                        </div>

                                        <div>
                                            <div class="form-group id-card-section doc-type d-none">
                                                <input type="text" placeholder="Enter ID-Card Number" name="id_number" class="form-control id_number">
                                            </div>
                                        </div>

                                        <div>
                                            <div class="form-group passport-card-section doc-type d-none">
                                                <input type="text" placeholder="Enter Passport Number" name="passport_number" class="form-control passport_number">
                                            </div>
                                        </div>

                                        <div>
                                            <div class="form-group dl-card-section doc-type d-none">
                                                <input type="text" placeholder="Enter Driving Licence Number" name="dl_number" class="form-control dl_number">
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <label>Front Page* (Max Size: 2MB)</label>
                                            <input type="file" name="front_page" class="form-control front_page pt-3">
                                            <div class="wizard-form-error"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Back Page* (Max Size: 2MB)</label>
                                            <input type="file" name="back_page" class="form-control back_page pt-3">
                                            <div class="wizard-form-error"></div>
                                        </div>
                                        <div class="form-group">
                                            <label>Selfie* (Max Size: 2MB)</label>
                                            <input type="file" name="selfie" class="form-control selfie pt-3">
                                            <div class="wizard-form-error"></div>
                                        </div>


                                        <div class="form-group clearfix">
                                            <a href="javascript:;" class="form-wizard-previous-btn float-left">Previous</a>
                                            <a href="javascript:;" class="form-wizard-next-btn float-right">Next</a>
                                        </div>
                                    </fieldset>

                                    <fieldset class="wizard-fieldset">
                                        <div class="fieldset-header">
                                            <h5 class="fieldset-title">US Stock Financial Information</h5>
                                            <p class="fieldset-description">Additional information for US stock trading</p>
                                        </div>

                                        <div class="form-group">
                                            <select name="net_worth_estimate" class="form-control">
                                                <option selected></option>
                                                <option value="<500 million"> < 500 million</option>
                                                <option value="500-1.0 billion"> 500-1 billion </option>
                                                <option value="1-5 billion"> 1-5 billion </option>
                                                <option value="5-10 billion"> 5-10 billion </option>
                                                <option value="10 billion"> > 10 billion </option>
                                            </select>
                                            <label class="wizard-form-text-label">Net Wroth Estimate*</label>
                                        </div>

                                        <div class="form-group">
                                            Have you had any experienced in investment?
                                            <br>
                                            <div class="wizard-form-radio">
                                                <input name="experienced_in_investment" id="experienced_yes" type="radio">
                                                <label for="experienced_yes">Yes</label>
                                            </div>
                                            <div class="wizard-form-radio">
                                                <input name="experienced_in_investment" checked id="experienced_no" type="radio">
                                                <label for="experienced_no">No</label>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            Have you been declared bankrupt by court?
                                            <br>
                                            <div class="wizard-form-radio">
                                                <input name="bankrupt_by_court" id="bankrupt_yes" type="radio">
                                                <label for="bankrupt_yes">Yes</label>
                                            </div>
                                            <div class="wizard-form-radio">
                                                <input name="bankrupt_by_court" checked id="bankrupt_no" type="radio">
                                                <label for="bankrupt_no">No</label>
                                            </div>
                                        </div>

                                        <div class="form-group clearfix">
                                            <a href="javascript:;" class="form-wizard-previous-btn float-left">Previous</a>
                                            <a href="javascript:;" class="form-wizard-next-btn float-right">Next</a>
                                        </div>
                                    </fieldset>

                                    <fieldset class="wizard-fieldset">
                                        <div class="fieldset-header">
                                            <h5 class="fieldset-title">Emergency Contacts</h5>
                                            <p class="fieldset-description">Provide emergency contact information</p>
                                        </div>

                                        <div class="form-group">
                                            <select name="relationship" class="form-control">
                                                <option selected></option>
                                                <option value="father">Father</option>
                                                <option value="brother">Brother</option>
                                                <option value="wife">Wife</option>
                                                <option value="others">Other</option>
                                            </select>
                                            <label class="wizard-form-text-label">Relationship</label>
                                        </div>


                                        <div class="form-group">
                                            <input type="text" class="form-control wizard-required" name="relation_full_name" id="honame">
                                            <label for="honame" class="wizard-form-text-label">Full Name*</label>
                                            <div class="wizard-form-error"></div>
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control wizard-required"
                                                   name="emergency_phone" id="emergency_phone">
                                            <label for="emergency_phone" class="wizard-form-text-label">Phone
                                                Number*</label>
                                            <div class="wizard-form-error"></div>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control wizard-required" name="address" id="address">
                                            <label for="address" class="wizard-form-text-label">Address*</label>
                                            <div class="wizard-form-error"></div>
                                        </div>


                                        <div class="form-group clearfix">
                                            <a href="javascript:;" class="form-wizard-previous-btn float-left">Previous</a>

                                            <button class="form-wizard-submit float-right kycFormSubmit" type="button">Submit</button>
                                        </div>
                                    </fieldset>

                                </form>
                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>

@endsection



@push('style')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"  />

    <style>
        /* Modern Background and Layout */
        .wizard-section {
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            min-height: 100vh;
            /* padding: 40px 0; */
        }

        .wizard-content-left {
            background-blend-mode: darken;
            background-color: rgba(0, 0, 0, 0.45);
            background-image: url("https://i.ibb.co/X292hJF/form-wizard-bg-2.jpg");
            background-position: center center;
            background-size: cover;
            height: 100vh;
            padding: 30px;
        }
        .wizard-content-left h1 {
            color: #ffffff;
            font-size: 38px;
            font-weight: 600;
            padding: 12px 20px;
            text-align: center;
        }

        .form-wizard {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            color: #333;
            padding: 50px;
            margin: 20px 0;
            position: relative;
            overflow: hidden;
        }

        .form-wizard::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2, #f093fb, #f5576c);
        }
        .form-wizard .wizard-form-radio {
            display: inline-flex;
            align-items: center;
            margin: 0 15px 15px 0;
            position: relative;
            cursor: pointer;
        }

        .form-wizard .wizard-form-radio input[type="radio"] {
            -webkit-appearance: none;
            -moz-appearance: none;
            -ms-appearance: none;
            -o-appearance: none;
            appearance: none;
            background-color: #f7fafc;
            height: 24px;
            width: 24px;
            display: inline-block;
            vertical-align: middle;
            border-radius: 50%;
            position: relative;
            cursor: pointer;
            border: 2px solid #e2e8f0;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .form-wizard .wizard-form-radio input[type="radio"]:focus {
            outline: 0;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-wizard .wizard-form-radio input[type="radio"]:hover {
            border-color: #cbd5e0;
            background-color: #ffffff;
        }

        .form-wizard .wizard-form-radio input[type="radio"]:checked {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-color: #667eea;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }

        .form-wizard .wizard-form-radio input[type="radio"]:checked::before {
            content: "";
            position: absolute;
            width: 8px;
            height: 8px;
            display: inline-block;
            background-color: #ffffff;
            border-radius: 50%;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .form-wizard .wizard-form-radio input[type="radio"]:checked::after {
            content: "";
            display: inline-block;
            webkit-animation: click-radio-wave 0.65s;
            -moz-animation: click-radio-wave 0.65s;
            animation: click-radio-wave 0.65s;
            background: #667eea;
            content: '';
            display: block;
            position: absolute;
            z-index: 100;
            border-radius: 50%;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }

        .form-wizard .wizard-form-radio input[type="radio"] ~ label {
            padding-left: 12px;
            cursor: pointer;
            font-weight: 500;
            color: #4a5568;
            transition: color 0.3s ease;
        }

        .form-wizard .wizard-form-radio:hover label {
            color: #2d3748;
        }

        .form-wizard .wizard-form-radio input[type="radio"]:checked ~ label {
            color: #667eea;
            font-weight: 600;
        }
        .form-wizard .form-wizard-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .wizard-title-section {
            margin-bottom: 30px;
        }

        .wizard-main-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .wizard-subtitle {
            color: #718096;
            font-size: 1.1rem;
            margin: 0;
        }

        .wizard-progress-container {
            position: relative;
            margin: 30px 0;
        }
        /* Select Dropdown Styling */
        .form-wizard select.form-control {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 16px;
            padding-right: 40px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
        }

        .form-wizard select.form-control:focus {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23667eea' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        }

        /* File Input Styling */
        .form-wizard input[type="file"] {
            padding: 12px 15px;
            border: 2px dashed #cbd5e0;
            background: #f7fafc;
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .form-wizard input[type="file"]:hover {
            border-color: #667eea;
            background: #ffffff;
        }

        .form-wizard input[type="file"]:focus {
            border-color: #667eea;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        /* Navigation Buttons */
        .form-wizard .form-wizard-next-btn,
        .form-wizard .form-wizard-previous-btn,
        .form-wizard .form-wizard-submit {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #ffffff;
            display: inline-block;
            min-width: 140px;
            padding: 15px 30px;
            text-align: center;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            text-decoration: none;
        }

        .form-wizard .form-wizard-previous-btn {
            background: linear-gradient(135deg, #718096, #4a5568);
            box-shadow: 0 4px 12px rgba(113, 128, 150, 0.3);
        }

        .form-wizard .form-wizard-next-btn:hover,
        .form-wizard .form-wizard-next-btn:focus,
        .form-wizard .form-wizard-previous-btn:hover,
        .form-wizard .form-wizard-previous-btn:focus,
        .form-wizard .form-wizard-submit:hover,
        .form-wizard .form-wizard-submit:focus {
            color: #ffffff;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            text-decoration: none;
        }

        .form-wizard .form-wizard-previous-btn:hover,
        .form-wizard .form-wizard-previous-btn:focus {
            box-shadow: 0 6px 20px rgba(113, 128, 150, 0.4);
        }
        .form-wizard .wizard-fieldset {
            display: none;
            animation: fadeInUp 0.5s ease;
        }
        .form-wizard .wizard-fieldset.show {
            display: block;
        }

        .fieldset-header {
            margin-bottom: 30px;
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #f1f5f9;
        }

        .fieldset-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .fieldset-description {
            color: #718096;
            font-size: 1rem;
            margin: 0;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .form-wizard .wizard-form-error {
            display: none;
            background-color: #d70b0b;
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 2px;
            width: 100%;
        }
        .form-wizard .form-wizard-previous-btn {
            background-color: #0066ff;
        }
        .form-wizard .form-control {
            font-weight: 400;
            height: 60px !important;
            padding: 20px 15px 10px 15px;
            color: #2d3748 !important;
            background: #f7fafc !important;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-size: 16px;
            -webkit-text-fill-color: #2d3748 !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .form-wizard .form-control:focus {
            border-color: #667eea;
            background: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-wizard .form-control:hover {
            border-color: #cbd5e0;
            background: #ffffff !important;
        }

        .form-wizard .form-group {
            position: relative;
            margin: 30px 0;
        }

        .form-wizard .wizard-form-text-label {
            position: absolute;
            left: 15px;
            top: 20px;
            color: #718096;
            font-size: 16px;
            font-weight: 400;
            transition: all 0.3s ease;
            pointer-events: none;
            background: transparent;
            padding: 0 5px;
        }

        .form-wizard .focus-input .wizard-form-text-label,
        .form-wizard .form-control:not(:placeholder-shown) + .wizard-form-text-label {
            color: #667eea;
            top: -8px;
            font-size: 12px;
            font-weight: 600;
            background: #ffffff;
            transform: translateY(0);
        }

        .form-wizard .form-control:focus + .wizard-form-text-label {
            color: #667eea;
            top: -8px;
            font-size: 12px;
            font-weight: 600;
            background: #ffffff;
        }
        .form-wizard .form-wizard-steps {
            margin: 30px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
        }

        .form-wizard .form-wizard-steps::before {
            content: '';
            position: absolute;
            top: 25px;
            left: 50px;
            right: 50px;
            height: 3px;
            background: linear-gradient(90deg, #e2e8f0, #e2e8f0);
            border-radius: 2px;
            z-index: 1;
        }

        .form-wizard .form-wizard-steps li {
            width: 20%;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            z-index: 2;
        }

        .form-wizard .form-wizard-steps li::after {
            content: '';
            position: absolute;
            top: 25px;
            left: 50%;
            right: -50%;
            height: 3px;
            background: #e2e8f0;
            border-radius: 2px;
            z-index: 1;
            transition: all 0.3s ease;
        }

        .form-wizard .form-wizard-steps li:last-child::after {
            display: none;
        }

        .step-number {
            background: #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 50px;
            width: 50px;
            position: relative;
            text-align: center;
            font-weight: 600;
            font-size: 16px;
            color: #718096;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border: 3px solid #fff;
        }

        .step-label {
            margin-top: 10px;
            font-size: 12px;
            font-weight: 500;
            color: #718096;
            text-align: center;
            transition: all 0.3s ease;
        }

        .form-wizard .form-wizard-steps li.active .step-number,
        .form-wizard .form-wizard-steps li.activated .step-number {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #ffffff;
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .form-wizard .form-wizard-steps li.active .step-label,
        .form-wizard .form-wizard-steps li.activated .step-label {
            color: #667eea;
            font-weight: 600;
        }

        .form-wizard .form-wizard-steps li.active::after,
        .form-wizard .form-wizard-steps li.activated::after {
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .form-wizard .form-wizard-steps li.activated::after {
            background: linear-gradient(90deg, #667eea, #764ba2);
        }
        .form-wizard .wizard-password-eye {
            position: absolute;
            right: 32px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }
        @keyframes click-radio-wave {
            0% {
                width: 25px;
                height: 25px;
                opacity: 0.35;
                position: relative;
            }
            100% {
                width: 60px;
                height: 60px;
                margin-left: -15px;
                margin-top: -15px;
                opacity: 0.0;
            }
        }
        /* Error Styling */
        .form-wizard .wizard-form-error {
            display: none;
            background: linear-gradient(135deg, #f56565, #e53e3e);
            position: absolute;
            left: 0;
            right: 0;
            bottom: 0;
            height: 3px;
            width: 100%;
            border-radius: 0 0 12px 12px;
        }

        /* Responsive Design */
        @media screen and (max-width: 767px) {
            .wizard-content-left {
                height: auto;
            }
            .form-wizard {
                padding: 30px 20px !important;
                margin: 10px 0;
            }

            .wizard-main-title {
                font-size: 2rem;
            }

            .wizard-subtitle {
                font-size: 1rem;
            }

            .form-wizard .form-wizard-steps {
                flex-direction: column;
                gap: 20px;
            }

            .form-wizard .form-wizard-steps li {
                width: 100%;
                flex-direction: row;
                justify-content: flex-start;
            }

            .form-wizard .form-wizard-steps::before {
                display: none;
            }

            .form-wizard .form-wizard-steps li::after {
                display: none;
            }

            .step-number {
                margin-right: 15px;
                height: 40px;
                width: 40px;
                font-size: 14px;
            }

            .step-label {
                margin-top: 0;
                font-size: 14px;
            }

            .form-wizard .form-wizard-next-btn,
            .form-wizard .form-wizard-previous-btn,
            .form-wizard .form-wizard-submit {
                min-width: 120px;
                padding: 12px 20px;
                font-size: 14px;
            }
        }

        @media screen and (max-width: 480px) {
            .form-wizard {
                padding: 20px 15px !important;
            }

            .wizard-main-title {
                font-size: 1.75rem;
            }

            .fieldset-title {
                font-size: 1.5rem;
            }
        }

    </style>

@endpush

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        jQuery(document).ready(function() {
            // click on next button
            jQuery('.form-wizard-next-btn').click(function() {
                var parentFieldset = jQuery(this).parents('.wizard-fieldset');
                var currentActiveStep = jQuery(this).parents('.form-wizard').find('.form-wizard-steps .active');
                var next = jQuery(this);
                var nextWizardStep = true;
                parentFieldset.find('.wizard-required').each(function(){
                    var thisValue = jQuery(this).val();

                    if( thisValue == "") {
                        jQuery(this).siblings(".wizard-form-error").slideDown();
                        nextWizardStep = false;
                    }
                    else {
                        jQuery(this).siblings(".wizard-form-error").slideUp();
                    }
                });
                if( nextWizardStep) {
                    next.parents('.wizard-fieldset').removeClass("show","400");
                    currentActiveStep.removeClass('active').addClass('activated').next().addClass('active',"400");
                    next.parents('.wizard-fieldset').next('.wizard-fieldset').addClass("show","400");
                    jQuery(document).find('.wizard-fieldset').each(function(){
                        if(jQuery(this).hasClass('show')){
                            var formAtrr = jQuery(this).attr('data-tab-content');
                            jQuery(document).find('.form-wizard-steps .form-wizard-step-item').each(function(){
                                if(jQuery(this).attr('data-attr') == formAtrr){
                                    jQuery(this).addClass('active');
                                    var innerWidth = jQuery(this).innerWidth();
                                    var position = jQuery(this).position();
                                    jQuery(document).find('.form-wizard-step-move').css({"left": position.left, "width": innerWidth});
                                }else{
                                    jQuery(this).removeClass('active');
                                }
                            });
                        }
                    });
                }
            });
            //click on previous button
            jQuery('.form-wizard-previous-btn').click(function() {
                var counter = parseInt(jQuery(".wizard-counter").text());;
                var prev =jQuery(this);
                var currentActiveStep = jQuery(this).parents('.form-wizard').find('.form-wizard-steps .active');
                prev.parents('.wizard-fieldset').removeClass("show","400");
                prev.parents('.wizard-fieldset').prev('.wizard-fieldset').addClass("show","400");
                currentActiveStep.removeClass('active').prev().removeClass('activated').addClass('active',"400");
                jQuery(document).find('.wizard-fieldset').each(function(){
                    if(jQuery(this).hasClass('show')){
                        var formAtrr = jQuery(this).attr('data-tab-content');
                        jQuery(document).find('.form-wizard-steps .form-wizard-step-item').each(function(){
                            if(jQuery(this).attr('data-attr') == formAtrr){
                                jQuery(this).addClass('active');
                                var innerWidth = jQuery(this).innerWidth();
                                var position = jQuery(this).position();
                                jQuery(document).find('.form-wizard-step-move').css({"left": position.left, "width": innerWidth});
                            }else{
                                jQuery(this).removeClass('active');
                            }
                        });
                    }
                });
            });
            //click on form submit button
            jQuery(document).on("click",".form-wizard .form-wizard-submit" , function(){
                var parentFieldset = jQuery(this).parents('.wizard-fieldset');
                var currentActiveStep = jQuery(this).parents('.form-wizard').find('.form-wizard-steps .active');
                parentFieldset.find('.wizard-required').each(function() {
                    var thisValue = jQuery(this).val();
                    if( thisValue == "" ) {
                        jQuery(this).siblings(".wizard-form-error").slideDown();
                    }
                    else {
                        jQuery(this).siblings(".wizard-form-error").slideUp();
                    }
                });
            });
            // focus on input field check empty or not
            jQuery(".form-control").on('focus', function(){
                var tmpThis = jQuery(this).val();
                var thisName = jQuery(this).attr('name');

                if(thisName=='id_number' || thisName=='passport_number' || thisName=='dl_number'){
                    return;
                }

                if(tmpThis == '' ) {
                    jQuery(this).parent().addClass("focus-input");
                }
                else if(tmpThis !='' ){
                    jQuery(this).parent().addClass("focus-input");
                }

            }).on('blur', function(){
                var tmpThis = jQuery(this).val();
                var thisName = jQuery(this).attr('name');

                if(thisName=='id_number' || thisName=='passport_number' || thisName=='dl_number'){
                    return;
                }


                if(tmpThis == '' ) {
                    jQuery(this).parent().removeClass("focus-input");
                    jQuery(this).siblings('.wizard-form-error').slideDown("3000");
                }
                else if(tmpThis !='' ){
                    jQuery(this).parent().addClass("focus-input");
                    jQuery(this).siblings('.wizard-form-error').slideUp("3000");
                }
            });
        });


        $(document).on('change', '.document_type', function (e){
            const type=$(this).val();

            $('.doc-type').addClass('d-none');

            if(type=='ID Card'){
                $('.id-card-section').removeClass('d-none');
            }else if(type=='Passport'){
                $('.passport-card-section').removeClass('d-none')
            }else if(type=='Driving Licence'){
                $('.dl-card-section').removeClass('d-none')
            }

        });


        // $(document).ready(function () {
        //     const maxFileSize = 2 * 1024 * 1024; // 2MB in bytes

        //     // Function to validate file size
        //     function validateFileSize(input, type) {
        //         const file = input.files[0]; // Get the selected file
        //          if (file) {
        //             if (file.size > maxFileSize) {
        //                 if(type=='front_page') {
        //                     toastr.error('Please upload front page less than  2MB. Please upload a smaller file.');
        //                     $('.front_page').val('');

        //                 }else if(type='back_page') {
        //                     toastr.error('Please upload back page less than  2MB. Please upload a smaller file.');
        //                     $('.back_page').val('');

        //                 }else if(type=='selfie') {
        //                     toastr.error('Please upload selfie less than  2MB. Please upload a smaller file.');
        //                     $('.selfie').val('');

        //                 }
        //             } else {
        //             }
        //         } else {
        //         }
        //     }



        //     // Attach the change event listener to each file input
        //     $(".front_page").on("change", function () {
        //         validateFileSize(this,'front_page');
        //     });
        //     $(".back_page").on("change", function () {
        //         validateFileSize(this,'back_page');
        //     });
        //     $(".selfie").on("change", function () {
        //         validateFileSize(this,'selfie');
        //     });
        // });


        $(document).on('click', '.kycFormSubmit', function (e){

            const document_type=$('.document_type').val();

            let doc_number=null;

            if(document_type=='ID Card'){
                doc_number=$('.id_number').val();

                if(!doc_number){
                    toastr.error('Please Enter ID-Card Number');
                    return;
                }
            }

            if(document_type=='Passport'){
                doc_number=$('.passport_number').val();

                if(!doc_number){
                    toastr.error('Please Enter Passport Number');
                    return;
                }
            }

            if(document_type=='Driving Licence'){
                doc_number=$('.dl_number').val();

                if(!doc_number){
                    toastr.error('Please Enter Driving Licence Number');
                    return;
                }
            }



            $.ajax({
                type:'POST',
                url:'{{route('user.kyc.data.check')}}',
                data:{
                    doc_number:doc_number,document_type:document_type,'_token':'{{csrf_token()}}'
                },

                success:function (res){
                    if(res.status=='success'){

                        $('#kycForm').submit();
                    }else{
                        toastr.error(res.message);
                    }
                }
            })







        })


    </script>

@endpush
