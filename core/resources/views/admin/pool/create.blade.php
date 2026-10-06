@extends('admin.layouts.app')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs4.min.css" />
@endpush

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body">
                    <form action="{{ route('admin.pool.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                       <div class="row">
                           <div class="form-group col-md-6">
                               <label>@lang('Pool Name')</label>
                               <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                           </div>
                           <div class="form-group col-md-6">
                               <label>@lang('Symbol')</label>
                               <input type="text" name="symbol" class="form-control" value="{{ old('symbol') }}" required>
                           </div>
                       </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>@lang('Pool Image')</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                                <small class="text-muted">@lang('Allowed: jpeg, png, jpg, gif. Max size: 2MB')</small>
                            </div>
                            <div class="form-group col-md-6">
                                <label>@lang('APR (%)')</label>
                                <input type="number" step="0.01" name="apr" class="form-control" value="{{ old('apr') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label>@lang('VIP APR (%)')</label>
                                <input type="number" step="0.01" name="vip_apr" class="form-control" value="{{ old('vip_apr') }}" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label>@lang('Prize Pool')</label>
                                <input type="number" step="0.01" name="prize_pool" class="form-control" value="{{ old('prize_pool') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="">Price</label>
                                <input type="text" name="price" class="form-control" placeholder="Enter price">
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="">Interest</label>
                                <input type="text" name="interest" class="form-control" placeholder="Enter interest">
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Create Pool')</button>
                        </div>
                    </form>
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
