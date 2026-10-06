@extends($activeTemplate.'layouts.frontend')



@section('new_css')

    <style>

        .nav-item.hide-m-view-btn{
            display: none !important;
        }
        #navbarSupportedContent{
            display: none !important;
        }
        .header-right{
            display: none !important;
        }
    </style>

@endsection



@section('content')

    <style>

        .nav-item.hide-m-view-btn{
            display: none !important;
        }
        #navbarSupportedContent{
            display: none !important;
        }
    </style>


    <div class="container">
        <div class="row">
            <div class="col-md-12 bg-white">


                @if(isset($page) && $page->details)

                    <div>
                        {!! json_decode($page->details) !!}
                    </div>

                @else

                <h4 class="text-danger text-center">
                    No Data Available
                </h4>

                @endif


            </div>
        </div>
    </div>
@endsection
