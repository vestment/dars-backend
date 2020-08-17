<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" {{app()->getLocale() == 'ar' ? 'dir="rtl"': ''}}>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @if(config('favicon_image') != "")
            <link rel="shortcut icon" type="image/x-icon" href="{{asset('storage/logos/'.config('favicon_image'))}}"/>
        @endif
        <title>@yield('title', app_name())</title>
        <meta name="description" content="@yield('meta_description', '')">
        <meta name="keywords" content="@yield('meta_keywords', '')">

        {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}
        @stack('before-styles')

        <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/flaticon.css')}}">
        <link rel="stylesheet" type="text/css" href="{{asset('assets/css/meanmenu.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/video.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/lightbox.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/progess.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/animate.min.css')}}">
        {{--<link rel="stylesheet" href="{{asset('assets/css/style.css')}}">--}}
        <link rel="stylesheet" href="{{ asset('css/'.$cssFile) }}">
        <link rel="stylesheet" href="{{asset('assets/css/fontawesome-all.css')}}">

        <link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}">


        <link href="https://fonts.googleapis.com/css2?family=Cairo&display=swap" rel="stylesheet">
        @yield('css')
        @stack('after-styles')

        @if(config('onesignal_status') == 1)
            {!! config('onesignal_data') !!}
        @endif

        @if(config('google_analytics_id') != "")
        <!-- Global site tag (gtag.js) - Google Analytics -->
            <script async src="https://www.googletagmanager.com/gtag/js?id={{config('google_analytics_id')}}"></script>
            <script>
                window.dataLayer = window.dataLayer || [];

                function gtag() {
                    dataLayer.push(arguments);
                }

                gtag('js', new Date());

                gtag('config', '{{config('google_analytics_id')}}');
            </script>
        @endif
        @if(!empty(config('custom_css')))
            <style>
                {!! config('custom_css')  !!}
            </style>
        @endif

    </head>
    <body class="{{config('layout_type')}}">

    <div id="app">
    {{--<div id="preloader"></div>--}}
    @include('frontend.layouts.modals.loginModal')

    <!-- Start of Header section
        ============================================= -->
        <header>
            <div id="main-menu" class="main-menu-container">
                <div class="main-menu">
                    {{-- <div class="container"> --}}


                        <div class="navbar-default">
                            <div class="navbar-header logonone float-left">
                                <a class="navbar-brand" href="{{url('/')}}">
                                    <img src="{{asset("storage/logos/".config('logo_b_image'))}}"
                                         alt="{{env('APP_NAME')}}">

                                <!-- <img src="{{asset('img/backend/brand/Council-logo-100px.png')}}" alt="logo"> -->

                                </a>
                            </div>
                            <!-- /.navbar-header -->

                        <!-- <div class="cart-search float-right ul-li">
                                <ul>
                                    <li>
                                        <a href="{{route('cart.index')}}"><i class="fas fa-shopping-bag"></i>
                                            @if(auth()->check() && Cart::session(auth()->user()->id)->getTotalQuantity() != 0)
                            <span class="badge badge-danger position-absolute">{{Cart::session(auth()->user()->id)->getTotalQuantity()}}</span>
                                            @endif
                                </a>
                            </li>
                        </ul>
                    </div> -->


                            <!-- Collect the nav links, forms, and other content for toggling -->
                            <nav class="navbar-menu float-left">
                                <div class="nav-menu ul-li">
                                    <ul>

                                        <div class="btn-group dropright">
                                            <button type="button" class="btn btn-secondary dropdown-toggle btndropdown"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                @lang('navs.general.courses')
                                            </button>
                                            <div class="dropdown-menu droplist">
                                                {{-- <a class="linkdrop" href="">hsdfghs</a> --}}
                                                <ul>
                                                    @if(count($categories) > 0)
                                                        @foreach($categories as $category)
                                                            <li><a class="linkdrop" href="{{$category->id}}"><i
                                                                            class="{{$category->icon}} p-2"></i> {{$category->name}}
                                                                </a></li>
                                                        @endforeach
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>


                                        @if(count($custom_menus) > 0 )
                                            @foreach($custom_menus as $menu)
                                                {{--@if(is_array($menu['id']) && $menu['id'] == $menu['parent'])--}}
                                                {{--@if($menu->subs && (count($menu->subs) > 0))--}}
                                                @if($menu['id'] == $menu['parent'])
                                                    @if(count($menu->subs) == 0)


                                                        <li class="">
                                                            <a href="{{asset($menu->link)}}"
                                                               class="nav-link {{ active_class(Active::checkRoute('frontend.user.dashboard')) }}"
                                                               id="menu-{{$menu->id}}">{{trans('custom-menu.'.$menu_name.'.'.str_slug($menu->label))}}</a>
                                                        </li>

                                                    @else
                                                        <li class="menu-item-has-children ul-li-block">
                                                            <a href="#!">{{trans('custom-menu.'.$menu_name.'.'.str_slug($menu->label))}}</a>
                                                            <ul class="sub-menu">
                                                                @foreach($menu->subs as $item)
                                                                    @include('frontend.layouts.partials.dropdown', $item)
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif


                                    </ul>
                                </div>
                            </nav>


                            <nav class="navbar-menu float-right divloginsearch">
                                <div class="nav-menu ul-li hoverpink">
                                    <ul>
                                        <li>

                                            <a href="">@lang('navs.general.offers')</a>

                                        </li>
                                        <li>
                                            @if(!auth()->check())
                                                <a class="sign-up"
                                                   href="{{ route('register.index') }}">@lang('navs.general.signup')</a>
                                            @endif
                                        </li>
                                        <li>
                                            <a href="{{route('cart.index')}}"><i class="fas fa-shopping-bag"></i>
                                                @if(auth()->check() && Cart::session(auth()->user()->id)->getTotalQuantity() != 0)
                                                    <span class="badge badge-danger position-absolute">{{Cart::session(auth()->user()->id)->getTotalQuantity()}}</span>
                                                @endif
                                            </a>
                                        </li>

                                        @if(auth()->check())
                                            <li class="menu-item-has-children ul-li-block">
                                                <a href="#!">{{ $logged_in_user->name }}</a>
                                                <ul class="sub-menu">
                                                    @can('view backend')
                                                        <li>
                                                            <a href="{{ route('admin.dashboard') }}">@lang('navs.frontend.dashboard')</a>
                                                        </li>
                                                    @endcan

                                                    <li>
                                                        <a href="{{ route('frontend.auth.logout') }}">@lang('navs.general.logout')</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        @else
                                            <li>
                                                <div class="log-in mt-0">
                                                    <a
                                                            href="{{ route('login.index') }}">@lang('navs.general.login')</a>


                                                </div>
                                            </li>
                                        @endif


                                        <li>
                                            <form action="{{route('search')}}" method="get">

                                                <div class="search-bar">
                                                    <input class="input-text course" name="q" type="text"
                                                           placeholder="search here">
                                                    <i class="icon fa fa-search"></i>

                                                </div>
                                            </form>
                                            {{-- <form action="{{route('search')}}" method="get">

                                                <div class="input-group search-group">

                                                    <input class="course" name="q" type="text"
                                                           placeholder="@lang('labels.frontend.home.search_course_placeholder')">
                                                </div>
                                            </form> --}}
                                        </li>


                                        @if(count($locales) > 1)
                                            <li class="menu-item-has-children ul-li-block">
                                                <a href="#">
                                                    <span class="d-md-down-none">@lang('menus.language-picker.language')
                                                        ({{ strtoupper(app()->getLocale()) }})</span>
                                                </a>
                                                <ul class="sub-menu">
                                                    @foreach($locales as $lang)
                                                        @if($lang != app()->getLocale())
                                                            <li>
                                                                <a href="{{ '/lang/'.$lang }}"
                                                                   class=""> @lang('menus.language-picker.langs.'.$lang)</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endif


                                    </ul>
                                </div>
                            </nav>


                            <div class="mobile-menu">
                                <div class="logo">
                                    <a href="{{url('/')}}">
                                        <img src={{asset("storage/logos/".config('logo_w_image'))}} alt="Logo">
                                    </a>
                                </div>
                                <nav>
                                    <ul>
                                        @if(count($custom_menus) > 0 )
                                            @foreach($custom_menus as $menu)
                                                @if($menu['id'] == $menu['parent'])
                                                    @if(count($menu->subs) > 0)
                                                        <li class="">
                                                            <a href="#!">{{trans('custom-menu.'.$menu_name.'.'.str_slug($menu->label))}}</a>
                                                            <ul class="">
                                                                @foreach($menu->subs as $item)
                                                                    @include('frontend.layouts.partials.dropdown', $item)
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                    @else
                                                        <li class="">
                                                            <a href="{{asset($menu->link)}}"
                                                               class="nav-link {{ active_class(Active::checkRoute('frontend.user.dashboard')) }}"
                                                               id="menu-{{$menu->id}}">{{trans('custom-menu.'.$menu_name.'.'.str_slug($menu->label))}}</a>
                                                        </li>
                                                    @endif

                                                @endif
                                            @endforeach
                                        @endif
                                        @if(auth()->check())
                                            <li class="">
                                                <a href="#!">{{ $logged_in_user->name}}</a>
                                                <ul class="">
                                                    @can('view backend')
                                                        <li>
                                                            <a href="{{ route('admin.dashboard') }}">@lang('navs.frontend.dashboard')</a>
                                                        </li>
                                                    @endcan


                                                    <li>
                                                        <a href="{{ route('frontend.auth.logout') }}">@lang('navs.general.logout')</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        @else
                                            <li>
                                                <div class=" ">
                                                    <a id="openLoginModal" data-target="#myModal"
                                                       href="#">@lang('navs.general.login')</a>
                                                    <!-- The Modal -->
                                                </div>
                                            </li>
                                        @endif
                                        @if(count($locales) > 1)
                                            <li class="menu-item-has-children ul-li-block">
                                                <a href="#">
                                                    <span class="d-md-down-none">@lang('menus.language-picker.language')
                                                        ({{ strtoupper(app()->getLocale()) }})</span>
                                                </a>
                                                <ul class="">
                                                    @foreach($locales as $lang)
                                                        @if($lang != app()->getLocale())
                                                            <li>
                                                                <a href="{{ '/lang/'.$lang }}"
                                                                   class=""> @lang('menus.language-picker.langs.'.$lang)</a>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @endif
                                    </ul>
                                </nav>

                            </div>
                        </div>

                </div>
            </div>
        </header>
        <!-- Start of Header section
            ============================================= -->


        @yield('content')
        @include('cookieConsent::index')


        @include('frontend.layouts.partials.footer')

    </div><!-- #app -->

    <!-- Scripts -->

    @stack('before-scripts')

    <!-- For Js Library -->
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


    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
    <!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/> -->


    <script>
        $('.search-bar .icon').on('click', function () {
            $(this).parent().toggleClass('active');
        });
    </script>


    <script>
        @if(request()->has('user')  && (request('user') == 'admin'))

        $('#myModal').modal('show');
        $('#loginForm').find('#email').val('admin@lms.com')
        $('#loginForm').find('#password').val('secret')

        @elseif(request()->has('user')  && (request('user') == 'student'))

        $('#myModal').modal('show');
        $('#loginForm').find('#email').val('student@lms.com')
        $('#loginForm').find('#password').val('secret')

        @elseif(request()->has('user')  && (request('user') == 'teacher'))

        $('#myModal').modal('show');
        $('#loginForm').find('#email').val('teacher@lms.com')
        $('#loginForm').find('#password').val('secret')

        @endif
    </script>


    <script src="{{asset('assets/js/script.js')}}"></script>
    <script>
        @if((session()->has('show_login')) && (session('show_login') == true))
        window.location.href = '{{route('login.index')}}';
        @endif
        var font_color = "{{config('font_color')}}"
        setActiveStyleSheet(font_color);
        $(window).on('load', function () {
            $(".owl-carousel").owlCarousel({
                rewind: true,
                padding: 2,
                margin: 10,
                nav: true,
                navText: ["<i class='fas fa-chevron-left'></i>",
                    "<i class='fas fa-chevron-right'></i>"],
                responsive: {
                    0: {
                        items: 1
                    },
                    576: {
                        items: 1
                    },
                    768: {
                        items: 3
                    },
                    991: {
                        items: 5
                    }
                }
            });

        });
        $(document).ready(function () {
            $('.owl-carousel').on('changed.owl.carousel', function (event) {
                var items = event.target.dataset.items;
                if (items) {
                    if ($(window).width() > 768) {
                        event.relatedTarget.settings.items = items
                    }
                }
            })
        })

        function setOwlItems(element, noItems) {
            $(element).owlCarousel({
                items: noItems
            })
        }

        function showTab(element, button) {
            var elem = element[0];
            $('.course-container.show').addClass('hide');
            $('button.active').removeClass('active');
            $('button.active').removeClass('active');
            $('.course-container.show').removeClass('show');
            $('.course-container.hide').css('display', 'none');
            $(elem).removeClass('hide');
            $(elem).addClass('show acive');
            $(elem).css('display', 'block');
            button.addClass('active');
            // console.log(elem.classList)
            window.dispatchEvent(new Event('resize'));
        }
    </script>

    @yield('js')

    @stack('after-scripts')

    @include('includes.partials.ga')

    @if(!empty(config('custom_js')))
        <script>
            {!! config('custom_js') !!}
        </script>
    @endif

    </body>
    </html>
