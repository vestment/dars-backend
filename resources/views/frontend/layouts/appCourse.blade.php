<!DOCTYPE html>
@langrtl
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    @endlangrtl
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

    <!-- Check if the language is set to RTL, so apply the RTL layouts -->
        <!-- Otherwise apply the normal LTR layouts -->

        <link rel="stylesheet" href="{{asset('assets/css/owl.carousel.css')}}">
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
        <style>

            .cart-search li{
                height: 35px;
                width: 35px;
                text-align: center;
                line-height: 30px;
             
                color: #fff;
                margin-left: 10px;
                position: absolute;
                top: 27%;
                right:20px;
            }
            .cart-search li a {
                margin-top: 2px;
            }
            
         
.mean-bar{
    display: none !important;
    width:0px;
    height:0px;

}
.navbar-header{
    display: flex;
  
  justify-content: center;
  
  align-items: center;
}
@media (max-width: 768px) {
    .y-1{
        display:none;

    }
    .y-2{
        display:block;
        
    }
}
@media (min-width: 768px) {
    .y-1{
        display:inline;

    }
   
    .y-2{
        display:none;
        
    }
     .navbar-default img{

         padding-left:58px;
     }
}



        </style>
       
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
            function gtag(){dataLayer.push(arguments);}
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

    <!-- Start of Header section
        ============================================= -->
        <header>
            <div id="main-menu" class="main-menu-container">
                <div class="main-menu">
                    <div class="">
                        <div class="row navbar-default p-0 ">
                            <div class="col-11 row navbar-header float-left logo-lesson p-0 m-0">
                            <div class="col-4 p-0">
                                <a class="navbar-brand text-uppercase" href="{{url('/')}}">
                                    {{--<img src="{{asset("storage/logos/".config('logo_b_image'))}}" alt="logo">--}}
                                    <img style="" src="{{asset("storage/logos/".config('logo_b_image'))}}" alt="logo">
                                </a>
                            </div>
                            <div class="col-4 p-0">
                                <span class=" y-1 "> @yield('lesson-title')</span>
                            </div>
                            <div class="col-4 p-0">
                            @yield('progress_bar')
                            </div>`
                            </div><!-- /.navbar-header -->
                            <div class="col-12 y-2"> @yield('lesson-title')</div>

                            <div class="cart-search float-right ul-li">
                                <ul>
                                    <li>
                                    @yield('course_route')
                                    </li>
                                    
                                </ul>
                            </div>


                            <!-- Collect the nav links, forms, and other content for toggling -->
                           
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



    <script src="{{asset('assets/js/script.js')}}"></script>
    <script>
        var font_color = "{{config('font_color')}}"
        setActiveStyleSheet(font_color);
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
