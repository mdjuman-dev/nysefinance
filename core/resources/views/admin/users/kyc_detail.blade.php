@extends('admin.layouts.app')
@section('panel')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    @php $ignoreKey=['front_page', 'back_page', 'selfie']; @endphp
                    @if($user->kyc_data)
                        <ul class="list-group">
                            @foreach(json_decode($user->kyc_data) as $key=>$val)
                                @if($val && $key && !in_array($key, $ignoreKey))
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ucwords(str_replace('_', ' ', $key))}}
                                    <span>
                                @if($key=='front_document' && $val)
                                    <a href="{{ route('admin.download.attachment',encrypt(getFilePath('verify').'/'.$val)) }}"
                                               class="me-3"><i class="fa-regular fa-file"></i>  @lang('Attachment') </a>
                                @elseif($key=='back_document' && $val)
                                     <a href="{{ route('admin.download.attachment',encrypt(getFilePath('verify').'/'.$val)) }}"
                                               class="me-3"><i class="fa-regular fa-file"></i>  @lang('Attachment') </a>
                                @elseif($key=='selfie_document' && $val)
                                    <a href="{{ route('admin.download.attachment',encrypt(getFilePath('verify').'/'.$val)) }}"
                                               class="me-3"><i class="fa-regular fa-file"></i>  @lang('Attachment') </a>
                                @else
                                   <p>{{$val}}</p>
                                @endif
                            </span>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <h5 class="text-center">@lang('KYC data not found')</h5>
                    @endif

                    @if($user->kv == Status::KYC_UNVERIFIED)
                        <div class="my-3">
                            <h6>@lang('Rejection Reason')</h6>
                            <p>{{ $user->kyc_rejection_reason }}</p>
                        </div>
                    @endif

                    @if($user->kv == Status::KYC_PENDING)
                        <div class="d-flex flex-wrap justify-content-end mt-3">
                            <button class="btn btn-outline--danger me-3" data-bs-toggle="modal"
                                    data-bs-target="#kycRejectionModal"><i class="las la-ban"></i>@lang('Reject')
                            </button>
                            <button class="btn btn-outline--success confirmationBtn"
                                    data-question="@lang('Are you sure to approve this documents?')"
                                    data-action="{{ route('admin.users.kyc.approve', $user->id) }}"><i
                                    class="las la-check"></i>@lang('Approve')</button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div id="kycRejectionModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Reject KYC Documents')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.users.kyc.reject', $user->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-primary p-3">
                            @lang('If you reject these documents, the user will be able to re-submit new documents and these documents will be replaced by new documents.')
                        </div>

                        <div class="form-group">
                            <label>@lang('Rejection Reason')</label>
                            <textarea class="form-control" name="reason" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal/>
@endsection
