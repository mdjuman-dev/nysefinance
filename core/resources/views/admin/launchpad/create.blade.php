@extends('admin.layouts.app')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs4.min.css" />
@endpush

@section('title', 'Create LaunchPad')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">@lang('Create New LaunchPad')</h4>
                </div>
                <div class="card-body">
                    @include('admin.launchpad.form')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')


@endpush


@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote.min.js" ></script>
    <script>
       $(document).ready(function(){
           "use strict";
           $('.summernote').summernote();
       })
    </script>
@endpush
