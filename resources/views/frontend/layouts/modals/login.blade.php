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
    <!-- <script src="https://kit.fontawesome.com/a076d05399.js"></script> -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: Cairo;
        {{app()->getLocale() == 'ar' ? 'direction:rtl;': ''}}

        }

        .imgDiv {
            background-image: url("{{ asset('img/frontend/course/01.svg') }}");
            background-repeat: no-repeat;
            background-size: 100% 100%;
            margin- {{app()->getLocale() == 'ar' ? 'right': 'left'}}: -3.5%;

        }

        .text-md-left {
            text-align: {{app()->getLocale() == 'ar' ? 'right': 'left'}} !important;
        }

        .formDiv {
            height: 100vh;
            margin- {{app()->getLocale() == 'ar' ? 'right': 'left'}}: 3%;

        }

        .form-check-label {
            margin-right: 1.25rem;
        }

        @media (max-width: 991px) {
            .imgDiv {
                display: none;
            }
        }
    </style>
</head>

<body>
@if(!auth()->check())

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 imgDiv">
            </div>
            <div class="formDiv col-lg-6 text-md-left text-center">
                <div class="row col-lg-8">

                    <div class="col-md-10 offset-md-1">
                        <a href="{{url('/')}}"> <img class="img-fluid" src="{{ asset('img/frontend/course/E-Council.png') }}"></a>
                    </div>
                    <div class="col-md-10 offset-md-1 mb-3">
                        <h2>@lang('labels.frontend.login.welcome')</h2>
                        <h6 class="text-muted">@lang('labels.frontend.login.please_login')</h6>
                    </div>
                    <div class="col-md-10 offset-md-1">
                        <div class="alert alert-success response-success" style="display: none">
                            <h5><span class="response-success">{{(session()->get('flash_success'))}}</span></h5>
                        </div>
                        <div class="alert alert-danger response-error" style="display: none">
                            <h5><span class="response-error">{{(session()->get('flash_error'))}}</span></h5>
                        </div>

                        <form id="loginForm" action="{{route('frontend.auth.login.post')}}"
                              method="POST">
                            @csrf
                            <div class="form-group ">
                                <label for="exampleInputEmail1">@lang('labels.frontend.login.user_name')</label>
                                <input name="email" type="text" class="form-control" id="exampleInputEmail1"
                                       aria-describedby="emailHelp">
                                <span id="login-email-error" class="text-danger"></span>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputPassword1">@lang('labels.frontend.login.password')</label>
                                <input name="password" type="password" class="form-control" id="exampleInputPassword1">
                                <span id="login-password-error" class="text-danger"></span>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                                        <label class="form-check-label"
                                               for="exampleCheck1">@lang('labels.frontend.login.remember')</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <a href="{{ route('frontend.auth.password.reset') }}"
                                       class="text-muted">@lang('labels.frontend.login.forgot')</a>
                                </div>
                                @if(config('access.captcha.registration'))
                                    <div class="contact-info mb-2 text-center">
                                        {!! Captcha::display() !!}
                                        {{ html()->hidden('captcha_status', 'true') }}
                                        <span id="login-captcha-error" class="text-danger"></span>

                                    </div><!--col-->
                                @endif
                            </div>
                            <button type="submit" value="Submit"
                                    class="btn btn-info btn-lg text-white col-12 mt-5">@lang('labels.frontend.login.login')</button>

                        </form>
                        <h5 class="mt-5"> @lang('labels.frontend.login.login_with') </h5>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <a href="{{ route('frontend.auth.social.login',['provider'=> 'facebook']) }}"
                                   class="btn btn-block btn-primary btn-lg text-white"><i
                                            class="fa fa-facebook-f mr-2"></i> @lang('labels.frontend.login.facebook')
                                </a>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <a href="{{ route('frontend.auth.social.login',['provider'=> 'google']) }}" class="btn btn-danger btn-block btn-lg text-white"><i
                                            class="fa fa-google mr-2"></i> @lang('labels.frontend.login.google')</a>
                            </div>
                        </div>
                        <div><a href="{{ route('register.index') }}"
                                class="text-dark font-weight-bold col-12 d-flex justify-content-center mt-5">@lang('labels.frontend.login.sign_up')</a>
                        </div>
                        <div class="mb-2"><a href="{{route('frontend.index',['page'=>'privacy-policy'])}}"
                                             class="text-dark col-12 d-flex justify-content-center mt-3">@lang('labels.frontend.login.terms_of_use')</a>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

@endif
@push('after-scripts')




    <script src="{{asset('assets/js/jquery-2.1.4.min.js')}}"></script>
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('assets/js/jarallax.js')}}"></script>
    <script src="{{asset('assets/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('assets/js/lightbox.js')}}"></script>
    <script src="{{asset('assets/js/jquery.meanmenu.js')}}"></script>
    <script src="{{asset('assets/js/scrollreveal.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery.counterup.min.js')}}"></script>
    <script src="{{asset('assets/js/waypoints.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery-ui.js')}}"></script>
    <script src="{{asset('assets/js/gmap3.min.js')}}"></script>

    <script src="{{asset('assets/js/switch.js')}}"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#loginForm').on('submit', function (e) {
                e.preventDefault();

                var $this = $(this);
                $('.response-success').empty();
                $('.response-success').show().html('Checking credentials...');
                $('.response-error').hide().empty();
                $.ajax({
                    type: $this.attr('method'),
                    url: $this.attr('action'),
                    data: $this.serializeArray(),
                    dataType: $this.data('type'),
                    success: function (response) {
                        $('#login-email-error').empty();
                        $('#login-password-error').empty();
                        $('#login-captcha-error').empty();
                        $('.response-error').hide().html('');
                        if (response.errors) {
                            $('.response-success').hide().html('');
                            if (response.errors.email) {
                                $('#login-email-error').html(response.errors.email[0]);
                            }
                            if (response.errors.password) {
                                $('#login-password-error').html(response.errors.password[0]);
                            }

                            var captcha = "g-recaptcha-response";
                            if (response.errors[captcha]) {
                                $('#login-captcha-error').html(response.errors[captcha][0]);
                            }
                        }
                        if (response.success) {
                            $('#loginForm')[0].reset();
                            if (response.redirect == 'back') {
                                window.location.href = "{{url('/')}}"
                            } else {
                                window.location.href = "{{route('admin.dashboard')}}"
                            }
                            $('.response-error').hide().html('');
                            $('.response-success').show().html(response.message);

                        }
                    },
                    error: function (jqXHR) {
                        var response = $.parseJSON(jqXHR.responseText);
                        console.log(jqXHR)
                        if (response.message) {
                            $('.response-success').hide().html('');
                            $('.response-error').show().html(response.message);
                        }
                    }
                });
            });

            $(document).on('submit', '#registerForm', function (e) {
                e.preventDefault();
                console.log('he')
                var $this = $(this);

                $.ajax({
                    type: $this.attr('method'),
                    url: "{{  route('frontend.auth.register.post')}}",
                    data: $this.serializeArray(),
                    dataType: $this.data('type'),
                    success: function (data) {
                        $('#first-name-error').empty()
                        $('#last-name-error').empty()
                        $('#email-error').empty()
                        $('#password-error').empty()
                        $('#captcha-error').empty()
                        if (data.errors) {
                            if (data.errors.first_name) {
                                $('#first-name-error').html(data.errors.first_name[0]);
                            }
                            if (data.errors.last_name) {
                                $('#last-name-error').html(data.errors.last_name[0]);
                            }
                            if (data.errors.email) {
                                $('#email-error').html(data.errors.email[0]);
                            }
                            if (data.errors.password) {
                                $('#password-error').html(data.errors.password[0]);
                            }

                            var captcha = "g-recaptcha-response";
                            if (data.errors[captcha]) {
                                $('#captcha-error').html(data.errors[captcha][0]);
                            }
                        }
                        if (data.success) {
                            $('#registerForm')[0].reset();
                            $('#register').removeClass('active').addClass('fade')
                            $('.error-response').empty();
                            $('#login').addClass('active').removeClass('fade')
                            $('.success-response').empty().html("@lang('labels.frontend.modal.registration_message')");
                        }
                    }
                });
            });

        });
    </script>


</body>
</html>    
