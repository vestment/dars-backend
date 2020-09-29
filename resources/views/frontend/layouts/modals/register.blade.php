<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{app()->getLocale() == 'ar' ? 'dir="rtl"': ''}}>
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>E-Council</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Cairo;
        {{app()->getLocale() == 'ar' ? 'direction:rtl;': ''}}


        }

        .imgDivReg {
            background-image: url("{{ asset('img/frontend/course/02.svg') }}");
            background-repeat: no-repeat;
            background-size: 100% 100%;
            margin- {{app()->getLocale() == 'ar' ? 'right': 'left'}}: -3.5%;

        }

        .text-md-left {
            text-align: {{app()->getLocale() == 'ar' ? 'right': 'left'}}  !important;
        }

        .formDiv {

            height: 100vh;
            margin- {{app()->getLocale() == 'ar' ? 'right': 'left'}}: 3%;
        }

        .form-check-label {
            margin-right: 1.25rem;
        }

        @media (max-width: 991px) {

            .imgDivReg {
                display: none;
            }
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6 imgDivReg">
        </div>
        <div class="formDiv col-lg-6 text-md-left text-center">
            <div class="row col-lg-8">
                <div class="col-md-10 offset-md-1">
                    <a href="{{url('/')}}"> <img class="py-5" src="{{asset("storage/logos/".config('logo_b_image'))}}"
                                                 alt="{{env('APP_NAME')}}"></a>
                </div>

                <div class="col-md-10 offset-md-1">
                    @if (count($errors) > 0)
                        @foreach ($errors->all() as $error)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">{{ $error }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endforeach
                    @endif

                    @if (session()->has('message'))
                        <p class="alert alert-success alert-dismissible fade show" role="alert">{{ session('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </p>
                    @endif
                    <form id="registerForm" action="{{route('frontend.auth.register.post')}}"
                          method="POST">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="first_name">@lang('labels.frontend.sign_up.first_name')</label>
                                <input name="first_name" value="{{old('first_name')}}" type="text" class="form-control"
                                       id="first_name"
                                       aria-describedby="emailHelp">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="last_name">@lang('labels.frontend.sign_up.last_name')</label>
                                <input name="last_name" type="text" value="{{old('last_name')}}" class="form-control"
                                       id="last_name"
                                       aria-describedby="emailHelp">
                            </div>
                        </div>

                        {{--                        <div class="form-group">--}}
                        {{--                            <label for="exampleInput">@lang('labels.frontend.sign_up.user_name')</label>--}}
                        {{--                            <input type="text" class="form-control" id="exampleInput">--}}
                        {{--                        </div>--}}

                        <div class="form-group">
                            <label for="email">@lang('labels.frontend.sign_up.email')</label>
                            <input name="email" value="{{old('email')}}" type="email" class="form-control" id="email">
                        </div>

                        <div class="form-group">
                            <label for="password">@lang('labels.frontend.sign_up.password')</label>
                            <input name="password" type="password" class="form-control" id="password">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">@lang('labels.frontend.sign_up.confirm_password')</label>
                            <input name="password_confirmation" type="password" class="form-control"
                                   id="password_confirmation">
                        </div>
                        @foreach($registerFields as $field)
                            <div class="form-group">
                                <label for="{{$field->type}}">{{$field->name}}</label>
                                <input name="{{$field->name}}" type="{{$field->type}}" class="form-control"
                                       id="{{$field->type}}">
                            </div>
                        @endforeach
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label"
                                           for="exampleCheck1"> @lang('labels.frontend.sign_up.agreement')</label>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" value="0" name="parentRole" id="parent-role">


                        <div class="row justify-content-center">
                            <button type="submit"
                                    class="btn btn-primary btn-lg text-white col-10 mt-5 ">@lang('labels.frontend.sign_up.sign_up')</button>
                            <button type="submit" id="parent_value"
                                    class="btn btn-primary btn-lg text-white col-10 mt-5 "> @lang('labels.frontend.login.register_as_parent')</button>
                            <a href="{{ route('frontend.auth.teacher.register') }}"
                               class="btn btn-info btn-block btn-lg text-white col-10 mt-5">
                                @lang('labels.frontend.login.register_as_teacher')
                            </a>
                        </div>


                    </form>
                    <div><a href="{{ route('login.index') }}"
                            class="text-dark col-12 d-flex justify-content-center mt-5">@lang('labels.frontend.sign_up.already_have_account')</a>
                    </div>
                    <div class="mb-2"><a href="{{route('frontend.index',['page'=>'privacy-policy'])}}"
                                         class="text-dark col-12 d-flex justify-content-center mt-3">@lang('labels.frontend.sign_up.terms_of_use')</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('assets/js/jquery-2.1.4.min.js')}}"></script>
<script src="{{asset('assets/js/popper.min.js')}}"></script>
<script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
<script>
    $(document).ready(function () {
        $("#parent_value").click(function () {
            $("#parent-role").val(1);
        });
    });
</script>
</body>
</html>    
