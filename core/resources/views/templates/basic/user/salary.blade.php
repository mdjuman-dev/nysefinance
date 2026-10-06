@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row gy-4">
        <div class="col-12">
            <h4 class="mb-0">{{ __($pageTitle) }}

            </h4>
        </div>

        <div class="col-md-12">
            <form action="{{route('user.salary.submit')}}" method="post">
                @csrf

                <div class="card">
                    <div class="card-body">
                        <div class="row each-team-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Choose Team (Take 50% Invest From This Team)</label>
                                    <select name="team_one" class="form--control select2">
                                        <option value="">--Choose Team--</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->username}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="first-team-details">
                                    <div class="text-center">
                                        <small class="text-white">
                                            Choose Team To See Details
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row each-team-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Choose Team (Take 30% Invest From This Team)</label>
                                    <select name="team_two" class="form--control select1">
                                        <option value="">--Choose Team--</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->username}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="second-team-details">
                                    <div class="text-center">
                                        <small class="text-white">
                                            Choose Team To See Details
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="row each-team-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Choose Team (Take 15% Invest From This Team)</label>
                                    <select name="team_three" class="form--control select3">
                                        <option value="">--Choose Team--</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->username}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="three-team-details">
                                    <div class="text-center">
                                        <small class="text-white">
                                            Choose Team To See Details
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <div class="row each-team-row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Choose Team (Take 5% Invest From This Team)</label>
                                    <select name="team_four" class="form--control select4">
                                        <option value="">--Choose Team--</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->username}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="four-team-details">
                                    <div class="text-center">
                                        <small class="text-white">
                                            Choose Team To See Details
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12 text-right">
                                <button style="float: right" class="btn btn-primary" type="submit">
                                    Submit
                                </button>
                            </div>
                        </div>

                    </div>
                </div>


            </form>
        </div>
    </div>
@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'dashboard/css/jquery.treeView.css') }}">

    <style>

        .select2-dropdown{
            background-color: #161616 !important;
        }
        .each-team-row{
            background: #030404;
            border-radius: 5px;
            padding: 20px;
            margin-top: 15px;
            box-shadow: 0 14px 28px rgb(56 25 210 / 25%), 0 10px 10px rgb(11 46 158 / 22%);
        }
        .all-class-table{
            width: 100%;
            /*margin-top: 22px;*/
            color: white !important;
        }
        .user-ck-valid{
            color: #07c307;
            background: white;
            border-radius: 50%;
            font-size: 21px;
        }
        .user-ck-invalid{
            color: #ffffff;
            background: #ff0000;
            border-radius: 50%;
            font-size: 21px;
        }
        .first-team-details,.second-team-details,.three-team-details,.four-team-details{
            background: #505268;
            padding: 14px;
            margin-top: 21px;
            border-radius: 5px;
        }
    </style>
@endpush

@push('script-lib')
    <script src="{{ asset($activeTemplateTrue . 'dashboard/js/jquery.treeView.js') }}"></script>
@endpush

@push('script')
    <script>

        $(document).ready(function (){

            $('.select1').select2({
                multiple:false,
                placeholder:'--Choose Team--'
            });
            $('.select3').select2({
                multiple:false
            });
            $('.select4').select2({
                multiple:false
            });
        });

        $(document).on('change', 'select[name=team_one]', function(e){

            const user_id=$(this).val();


            $.ajax({
                type:'GET',
                url:'{{route('user.salary.invest',['type'=>'one'])}}',
                data:{
                    user_id:user_id
                },

                success:function(res){
                    if(res.status=='success' && res.data){
                        let available=3000 - res.data;

                        let icon='';
                        if(available < 0){
                            icon=` <i class="fa fa-check-circle user-ck-valid"></i>`;
                        }else{
                            icon=`<i class="fa-regular fa-circle-xmark user-ck-invalid"></i>`;
                        }

                        let html=`<table class="all-class-table">
                                    <tbody>
                                    <tr>
                                        <td>$${res.data}</td>
                                        <td>$${available.toFixed(4)}</td>
                                        <td>
                                            ${icon}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`;

                        $('.first-team-details').html(html);
                    }else{
                        let html=`<table class="all-class-table">
                                    <tbody>
                                    <tr class="text-center">
                                        <td colspan="3">No Data Available</td>
                                    </tr>
                                    </tbody>
                                </table>`;

                        $('.first-team-details').html(html);
                    }
                }



            })



            $('.first-team-details').html();
        });





        $(document).on('change', 'select[name=team_two]', function(e){
            const user_id=$(this).val();
            $.ajax({
                type:'GET',
                url:'{{route('user.salary.invest',['type'=>'two'])}}',
                data:{
                    user_id:user_id
                },
                success:function(res){
                    if(res.status=='success' && res.data){
                        let available=1800 - res.data;

                        let icon='';
                        if(available < 0){
                            icon=` <i class="fa fa-check-circle user-ck-valid"></i>`;
                        }else{
                            icon=`<i class="fa-regular fa-circle-xmark user-ck-invalid"></i>`;
                        }
                        let html=`<table class="all-class-table">
                                    <tbody>
                                    <tr>
                                        <td>$${res.data}</td>
                                        <td>$${available.toFixed(4)}</td>
                                        <td>
                                            ${icon}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`;

                        $('.second-team-details').html(html);
                    }else{
                        let html=`<table class="all-class-table">
                                    <tbody>
                                    <tr class="text-center">
                                        <td colspan="3">No Data Available</td>
                                    </tr>
                                    </tbody>
                                </table>`;

                        $('.second-team-details').html(html);
                    }
                }
            })
        });



        $(document).on('change', 'select[name=team_three]', function(e){
            const user_id=$(this).val();
            $.ajax({
                type:'GET',
                url:'{{route('user.salary.invest',['type'=>'three'])}}',
                data:{
                    user_id:user_id
                },
                success:function(res){
                    if(res.status=='success' && res.data){
                        let available=900 - res.data;

                        let icon='';
                        if(available < 0){
                            icon=` <i class="fa fa-check-circle user-ck-valid"></i>`;
                        }else{
                            icon=`<i class="fa-regular fa-circle-xmark user-ck-invalid"></i>`;
                        }
                        let html=`<table class="all-class-table">
                                    <tbody>
                                    <tr>
                                        <td>$${res.data}</td>
                                        <td>$${available.toFixed(4)}</td>
                                        <td>
                                            ${icon}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`;

                        $('.three-team-details').html(html);
                    }else{
                        let html=`<table class="all-class-table">
                                    <tbody>
                                    <tr class="text-center">
                                        <td colspan="3">No Data Available</td>
                                    </tr>
                                    </tbody>
                                </table>`;

                        $('.three-team-details').html(html);
                    }
                }
            })
        });



        $(document).on('change', 'select[name=team_four]', function(e){
            const user_id=$(this).val();
            $.ajax({
                type:'GET',
                url:'{{route('user.salary.invest',['type'=>'four'])}}',
                data:{
                    user_id:user_id
                },
                success:function(res){
                    if(res.status=='success' && res.data){
                        let available=300 - res.data;

                        let icon='';
                        if(available > 0){
                            icon=` <i class="fa fa-check-circle user-ck-valid"></i>`;
                        }else{
                            icon=`<i class="fa-regular fa-circle-xmark user-ck-invalid"></i>`;
                        }
                        let html=`<table class="all-class-table">
                                    <tbody>
                                    <tr>
                                        <td>$${res.data}</td>
                                        <td>$${available.toFixed(4)}</td>
                                        <td>
                                            ${icon}
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>`;

                        $('.four-team-details').html(html);
                    }else{
                        let html=`<table class="all-class-table">
                                    <tbody>
                                    <tr class="text-center">
                                        <td colspan="3">No Data Available</td>
                                    </tr>
                                    </tbody>
                                </table>`;

                        $('.four-team-details').html(html);
                    }
                }
            })
        });



    </script>
@endpush

