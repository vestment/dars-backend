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
            margin-{{app()->getLocale() == 'ar' ? 'right': 'left'}}: -3.5%;

        }

        .text-md-left {
            text-align: {{app()->getLocale() == 'ar' ? 'right': 'left'}} !important;
        }

        .formDiv {

            height: 100vh;
            margin-{{app()->getLocale() == 'ar' ? 'right': 'left'}}: 3%;
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
                    <a href="{{url('/')}}"> <img class="img-fluid" src="{{ asset('img/frontend/course/E-Council.png') }}"></a>
                </div>

                <div class="col-md-10 offset-md-1">
                    <form action="{{route('frontend.auth.register.post')}}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">@lang('labels.frontend.sign_up.first_name')</label>
                                <input name="first_name" type="text" class="form-control" id="exampleInputEmail1"
                                       aria-describedby="emailHelp">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="exampleInputEmail1">@lang('labels.frontend.sign_up.last_name')</label>
                                <input name="last_name" type="text" class="form-control" id="exampleInputEmail1"
                                       aria-describedby="emailHelp">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="exampleInput">@lang('labels.frontend.sign_up.user_name')</label>
                            <input type="text" class="form-control" id="exampleInput">
                        </div>

                        <div class="form-group">
                            <label for="exampleInput">@lang('labels.frontend.sign_up.email')</label>
                            <input name="email" type="email" class="form-control" id="exampleInput">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">@lang('labels.frontend.sign_up.password')</label>
                            <input name="password" type="password" class="form-control" id="exampleInputPassword1">
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">@lang('labels.frontend.sign_up.confirm_password')</label>
                            <input name="password_confirmation" type="password" class="form-control"
                                   id="exampleInputPassword1">
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                    <label class="form-check-label"
                                           for="exampleCheck1"> @lang('labels.frontend.sign_up.agreement')</label>
                                </div>
                            </div>
                        </div>
                        <button type="submit"
                                class="btn btn-primary btn-lg text-white col-12 mt-5">@lang('labels.frontend.sign_up.sign_up')</button>

                        <a href="{{ route('frontend.auth.teacher.register') }}" class="btn btn-info btn-lg text-white col-12 mt-5">
                            @lang('labels.frontend.login.register_as_teacher')
                        </a>


                    </form>
                    <div><a href="{{ route('login.index') }}"
                            class="text-dark col-12 d-flex justify-content-center mt-5">@lang('labels.frontend.sign_up.already_have_account')</a>
                    </div>
                    <div class="mb-2"><a href="#"
                                         class="text-dark col-12 d-flex justify-content-center mt-3">@lang('labels.frontend.sign_up.terms_of_use')</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</body>
</html>    
