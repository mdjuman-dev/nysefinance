@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-5">


                    <div class="row">
                        <div class="col-md-12">
                            <div>
                                <strong>Name:</strong> {{$application->name}}
                            </div>
                            <div>
                                <strong>Email:</strong> {{$application->email}}
                            </div>
                            <div>
                                <strong>Phone:</strong> {{$application->phone}}
                            </div>
                            <div>
                                <strong>Address:</strong> {!! $application->address !!}
                            </div>
                            <div>
                                <strong>Card Type:</strong>
                                @if($application->card_type=='visa')
                                    <smap class="badge badge--success">Visa</smap>
                                @else
                                    <smap class="badge badge--danger">Mastercard</smap>
                                @endif
                            </div>
                            <div>
                                <strong>Doc Type:</strong> {{strtoupper($application->doc_type)}}
                            </div>
                            <div>
                                <strong>Doc Number:</strong> {{$application->doc_number}}
                            </div>
                        </div>

                    </div>

                    <div class="row mt-3">

                        @php
                            $details=$application->document?json_decode($application->document):'';
                        @endphp

                        @if($details)

                            @if($application->doc_type=='bank' && isset($details->bank_statement))
                                <div class="col-md-4">
                                    <div class="sec-image">
                                        <label for="">Bank Statement</label>
                                        <br>
                                        <a href="{{ getImage(getFilePath('currency') .'/'.$details->bank_statement,getFileSize('currency')) }}"
                                           download>
                                            <img
                                                src="{{ getImage(getFilePath('currency') .'/'.$details->bank_statement,getFileSize('currency')) }}"
                                                alt=""
                                                style="cursor: pointer;">
                                        </a>
                                    </div>
                                </div>
                            @endif

                            @if($application->doc_type=='license')

                                @if(isset($details->license_front))
                                    <div class="col-md-4">
                                        <div class="sec-image">
                                            <label for="">License Front</label>
                                            <br>
                                            <a href="{{ getImage(getFilePath('currency') .'/'.$details->license_front,getFileSize('currency')) }}"
                                               download>
                                                <img
                                                    src="{{ getImage(getFilePath('currency') .'/'.$details->license_front,getFileSize('currency')) }}"
                                                    alt="">
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if(isset($details->license_back))
                                    <div class="col-md-4">
                                        <div class="sec-image">
                                            <label for="">License Back</label>
                                            <br>
                                            <a href="{{ getImage(getFilePath('currency') .'/'.$details->license_front,getFileSize('currency')) }}"
                                               download>
                                                <img
                                                    src="{{ getImage(getFilePath('currency') .'/'.$details->license_back,getFileSize('currency')) }}"
                                                    alt="">
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endif


                            @if($application->doc_type=='nid')

                                @if(isset($details->nid_front))
                                    <div class="col-md-4">
                                        <div class="sec-image">
                                            <label for="">NID Front</label>
                                            <br>
                                            <a href="{{ getImage(getFilePath('currency') .'/'.$details->nid_front,getFileSize('currency')) }}"
                                               download>
                                                <img
                                                    src="{{ getImage(getFilePath('currency') .'/'.$details->nid_front,getFileSize('currency')) }}"
                                                    alt="">
                                            </a>
                                        </div>
                                    </div>
                                @endif

                                @if(isset($details->nid_back))
                                    <div class="col-md-4">
                                        <div class="sec-image">
                                            <label for="">NID Back</label>
                                            <br>
                                            <a href="{{ getImage(getFilePath('currency') .'/'.$details->nid_back,getFileSize('currency')) }}"
                                               download>
                                                <img
                                                    src="{{ getImage(getFilePath('currency') .'/'.$details->nid_back,getFileSize('currency')) }}"
                                                    alt="">
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endif

                            @if($application->doc_type=='passport' && isset($details->passport))
                                <div class="col-md-4">
                                    <div class="sec-image">
                                        <label for="">Bank Statement</label>
                                        <br>
                                        <a href="{{ getImage(getFilePath('currency') .'/'.$details->passport,getFileSize('currency')) }}"
                                           download>
                                            <img
                                                src="{{ getImage(getFilePath('currency') .'/'.$details->passport,getFileSize('currency')) }}"
                                                alt="">
                                        </a>
                                    </div>
                                </div>
                            @endif

                        @endif

                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')


@endpush
