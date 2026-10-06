@extends('admin.layouts.app')


@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs4.min.css" />

@endpush

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">

                    <form action="{{route('admin.stock.store')}}" method="post" enctype="multipart/form-data">
                        @csrf

                        @include('admin.stock.form')

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')

@endpush

@push('script')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.9.1/summernote-bs4.min.js"></script>

    <script>
        $(document).ready(function (){

            $('.desc--summernote').summernote();
        });

        $(document).on('change', 'select[name=use_for]', function(e){
            const type=$(this).val();

            if(type=='bond'){
                $('.section-bond-type').removeClass('d-none');
            }else{
                $('.section-bond-type').addClass('d-none');
            }


        })
    </script>
@endpush
