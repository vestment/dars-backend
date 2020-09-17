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
    <link rel="stylesheet" href="{{asset('assets/css/responsive.css')}}">

    <link rel="stylesheet" href="{{ asset('css/'.$cssFile) }}">
    <link rel="stylesheet" href="{{asset('assets/css/fontawesome-all.css')}}">


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
<!--<div id="preloader">data-role="{!! auth()->check() ? auth()->user()->rolesLabel : '' !!}</div>-->

<!-- Start of Header section
        ============================================= -->
    <header>
        <div id="main-menu" class="main-menu-container bg-light ">
            <div class="main-menu">
                {{-- <div class="container"> --}}


                <div class="navbar-default">
                    <div class="navbar-header logonone">
                        <a class="navbar-brand" href="{{url('/')}}">
                            <img src="{{asset("storage/logos/".config('logo_b_image'))}}"
                                 alt="{{env('APP_NAME')}}">
                        </a>


                        <nav class="navbar-menu float-right divloginsearch ">
                            <div class="nav-menu ul-li hoverpink">
                                <ul>
                                @if(config('show_offers') == 1)
                                    <li>
                                        <a class="offersmob" href="{{route('frontend.offers')}}">@lang('navs.general.offers')</a>
                                    </li>
                                    @endif
                                    @if(!auth()->check())
                                        <li>

                                            <a class="sign-up text-white signupmob"
                                               href="{{ route('register.index') }}">@lang('navs.general.signup')</a>

                                        </li>
                                    @endif
                                    @if(auth()->check())
                                        <li>
                                            <a href="{{route('cart.index')}}"><i class="fas fa-shopping-bag"></i>
                                                @if(auth()->check() && Cart::session(auth()->user()->id)->getTotalQuantity() != 0)
                                                    <span class="badge badge-danger position-absolute">{{Cart::session(auth()->user()->id)->getTotalQuantity()}}</span>
                                                @endif
                                            </a>
                                        </li>
                                    @endif
                                    <div class="search-form">
            <form action="{{route('search')}}" method="get">
                <div class="search-bar">
                    <input autocomplete="off" class="input-text course" name="q" type="text"
                           placeholder="@lang('labels.frontend.home.search_course_placeholder')">
                </div>
            </form>
        </div>

                                    @if(auth()->check())
                                        <img src="{{ $logged_in_user->picture}}" class="img-avatar" alt="{{ $logged_in_user->full_name }}">
                                        <li class="menu-item-has-children ul-li-block">
                                            <a href="#!">{{ $logged_in_user->getDataFromColumn('first_name') }}</a>
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
                                            <a href="{{ route('login.index') }}">@lang('navs.general.login')</a>
                                        </li>
                                    @endif
                                    <li>
                                        <i class="search-icon icon fa fa-search"></i>
                                    </li>
                                    @if(count($locales) > 1)
                                        <li class="menu-item-has-children ul-li-block langmob">
                                            <a href="#">
                                                    <span class="d-md-down-none ">@lang('menus.language-picker.language')
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


                    </div>
                    <!-- /.navbar-header -->
<style>
    .dropdown-large{ padding:20px; }

@media all and (min-width: 992px) {
	.dropdown-large{ min-width:500px; }
}
</style>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <nav class="navbar-menu coursesmob">
                        <div class="nav-menu ul-li">
                            <ul >
                                <li class="nav-item dropdown">
                            	    <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">@lang('navs.general.courses')</a>
                            	    <div class="dropdown-menu dropdown-large">
                            	           <div class="row">
                            	               @php
                            	               $categories_1 = array_slice($categories->toArray(), 0, count($categories) / 2);
                                               $categories_2 = array_slice($categories->toArray(),  count($categories) / 2);
                            	               @endphp
                            	                <div class="col-md-6">
                                                <ul class="courses-menu">
                                         @foreach($categories_1 as $category)
                                         @php $category = json_decode(json_encode($category), FALSE); @endphp
                                                <li>
                                                    <a href="{{route('courses.category',['category'=>$category->slug])}}">
                                                    <i class="{{$category->icon}} p-2"></i>{{app()->getLocale() == 'ar' ? $category->ar_name : $category->name}}
                                                </a>
                                                </li>
                                        @endforeach
                                         </ul>
                                        </div>
                                          <div class="col-md-6">
                                                <ul class="courses-menu">
                                         @foreach($categories_2 as $category)
                                          @php $category = json_decode(json_encode($category), FALSE); @endphp
                                                <li>
                                                    <a href="{{route('courses.category',['category'=>$category->slug])}}">
                                                    <i class="{{$category->icon}} p-2"></i>{{app()->getLocale() == 'ar' ? $category->ar_name : $category->name}}
                                                </a>
                                                </li>
                                        @endforeach
                                         </ul>
                                        </div>
                                        </div> <!-- dropdown-large.// -->
                                         </div>
                            	</li>
                                <!--<li class="menu-item-has-children ul-li-block">-->
                                <!--    <a href="#!"> @lang('navs.general.courses') <i class="fa fa-caret-{{app()->getLocale() == 'ar' ? 'left': 'right'}}"></i> </a>-->
                                <!--    <ul class="sub-menu courses-menu">-->
                                <!--        <li>-->
                                <!--            <div class="row">-->
                                <!--        @foreach($categories as $category)-->
                                          
                                <!--                <div class="col-4 mr-5">-->
                                <!--                    <a href="{{route('courses.category',['category'=>$category->slug])}}">-->
                                <!--                    <i class="{{$category->icon}} p-2"></i>{{$category->getDataFromColumn('name')}}-->
                                <!--                </a>-->
                                <!--                </div>-->
                                            
                                <!--        @endforeach-->
                                <!--        </div>-->
                                <!--        </li>-->
                                <!--    </ul>-->
                                <!--</li>-->

                                @if(count($custom_menus) > 0 )
                                    @foreach($custom_menus as $menu)
                                        {{--@if(is_array($menu['id']) && $menu['id'] == $menu['parent'])--}}
                                        {{--@if($menu->subs && (count($menu->subs) > 0))--}}
                                        @if($menu['id'] == $menu['parent'])
                                            @if(count($menu->subs) == 0)
                                                <li class="">
                                                    <a href="{{asset($menu->link)}}" @if($menu->label == '911') style="width: 85px;margin-top: -20px;"  @endif
                                                       class="nav-link @if($menu->label == 'courses') d-lg-none @endif {{ active_class(Active::checkRoute('frontend.user.dashboard')) }}"
                                                       id="menu-{{$menu->id}}">@if($menu->label == '911') <img style="width: 60px;" src="{{asset('WhatsApp Image 2020-08-30 at 11.49.37 AM.jpeg')}}"> @else {{trans('custom-menu.'.$menu_name.'.'.str_slug($menu->label))}} @endif</a>
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
                                                    <a href="{{asset($menu->link)}}" data-label="{{$menu->label}}"
                                                       class="nav-link @if($menu->label == 'courses') d-lg-none @endif {{ active_class(Active::checkRoute('frontend.user.dashboard')) }}"
                                                       id="menu-{{$menu->id}}" data-label="{{$menu->label}}">@if($menu->label == 911) <img src="{{asset('WhatsApp Image 2020-08-30 at 11.49.37 AM.jpeg')}}"> @else {{trans('custom-menu.'.$menu_name.'.'.str_slug($menu->label))}} @endif</a>
                                                </li>
                                              
                                            @endif

                                        @endif
                                    @endforeach
                                @endif
                                @if(auth()->check())
                                    <li class="">
                                        <img src="{{ $logged_in_user->picture}}" class="img-avatar d-sm-none"
                                             alt="{{ $logged_in_user->full_name }}">
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
                                            <a href="{{route('login.index')}}">@lang('navs.general.login')</a>
                                            <!-- The Modal -->
                                        </div>
                                    </li>
                                    <li>
                                        <div class=" ">
                                            <a href="{{route('register.index')}}">@lang('navs.general.signup')</a>
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
<script type="text/javascript"
        src="https://platform-api.sharethis.com/js/sharethis.js#property=5f42db0aae128d00117aef6a&product=inline-share-buttons"
        async="async"></script>
<script src="{{asset('assets/js/switch.js')}}"></script>


<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
<!-- <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/> -->


<script>
    $('.search-icon').on('click', function () {
        $('.search-bar').toggleClass('active');
        $('.search-form').toggleClass('active');
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
    var lang = "{{app()->getLocale()}}"
    setActiveStyleSheet(font_color);
    $(window).on('load', function () {
        $(".owl-carousel").owlCarousel({
            rewind: true,
            padding: 2,
            rtl: lang == 'ar' ? true : false,
            margin: 10,
            dots: false,
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
