@extends('admin.layouts.app')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs4.min.css" />
@endpush


@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body">
                    <form action="{{ route('admin.faq.update', $faq->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label>@lang('Question')</label>
                            <textarea name="question" class="form-control summernote" required>{{ old('question', $faq->question) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>@lang('Answer')</label>
                            <textarea name="answer" class="form-control summernote" required>{{ old('answer', $faq->answer) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <select name="status" class="form-control" required>
                                <option value="active" @selected(old('status', $faq->status) == 'active')>@lang('Active')</option>
                                <option value="inactive" @selected(old('status', $faq->status) == 'inactive')>@lang('Inactive')</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Update')</button>
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
