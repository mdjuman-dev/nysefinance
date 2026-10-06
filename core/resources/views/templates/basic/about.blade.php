@extends($activeTemplate . 'layouts.frontend')


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

    @if ($sections && $sections->secs != null)
        @foreach (json_decode($sections->secs) as $sec)
            @include($activeTemplate . 'sections.' . $sec)
        @endforeach
    @endif
@endsection
