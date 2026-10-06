@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ isset($launchPad) ? route('admin.launchpad.update', $launchPad->id) : route('admin.launchpad.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if(isset($launchPad))
                            @method('PUT')
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Title') <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="title" value="{{ old('title', $launchPad->title ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Sub Title') <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="sub_title" value="{{ old('sub_title', $launchPad->sub_title ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Status') <span class="text-danger">*</span></label>
                                    <select name="status" class="form-control" required>
                                        <option value="active" {{ (old('status', $launchPad->status ?? '') == 'active') ? 'selected' : '' }}>@lang('Active')</option>
                                        <option value="upcoming" {{ (old('status', $launchPad->status ?? '') == 'upcoming') ? 'selected' : '' }}>@lang('Upcoming')</option>
                                        <option value="closed" {{ (old('status', $launchPad->status ?? '') == 'closed') ? 'selected' : '' }}>@lang('Closed')</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Price') <span class="text-danger">*</span></label>
                                    <input type="number" step="any" class="form-control" name="price" value="{{ old('price', $launchPad->price ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Price Currency') <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="price_currency" value="{{ old('price_currency', $launchPad->price_currency ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Total Allocation') <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="total_allocation" value="{{ old('total_allocation', $launchPad->total_allocation ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Cap Per Subscriber') <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="cap_per_subscriber" value="{{ old('cap_per_subscriber', $launchPad->cap_per_subscriber ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Total Committed Amount') <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="total_committed_amount" value="{{ old('total_committed_amount', $launchPad->total_committed_amount ?? '') }}" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Image')</label>
                                    <input type="file" class="form-control" name="image" accept="image/*">
                                    @if(isset($launchPad) && $launchPad->image)
                                        <div class="mt-2">
                                            <img src="{{ getImage(getFilePath('currency') . '/' . $launchPad->image) }}" alt="@lang('LaunchPad Image')" class="img-thumbnail" style="max-height: 100px;">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('Details URL')</label>
                                    <input type="url" class="form-control" name="details_url" value="{{ old('details_url', $launchPad->details_url ?? '') }}">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>@lang('Description')</label>
                                    <textarea name="description" class="form-control summernote" rows="4">{{ old('description', $launchPad->description ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.launchpad.index') }}" class="btn btn-sm btn-outline--primary">
        <i class="la la-undo"></i>@lang('Back')
    </a>
@endpush
