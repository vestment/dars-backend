@extends('frontend.layouts.app')

@section('title', trans('labels.frontend.home.title').' | '.app_name())
@section('meta_description', '')
@section('meta_keywords','')


@push('after-styles')
    <link rel="stylesheet" href="../../assets/css/course.css"/>
    <style>

.cont{
    width:50%;
    /* float:right; */
}
@media screen and (max-width: 768px) {
    .cont{
        width: 100%;
        
    }
    
}
        .my-alert {
            position: absolute;
            z-index: 10;
            left: 0;
            right: 0;
            top: 25%;
            width: 50%;
            margin: auto;
            display: inline-block;
        }

        /*section {*/
        /*    overflow: unset;*/
        /*}*/


    </style>
@endpush

@section('content')

    <!-- Start of slider section
            ============================================= -->
    
    @if(session()->has('socialToken'))
        <script>
            localStorage.setItem('token','{{session('socialToken')}}')
        </script>
    @endif
    @if(session()->has('socialToken'))
        <script>
            localStorage.setItem('token','{{session('socialToken')}}')
        </script>
    @endif
    @include('frontend.layouts.partials.slider')

    @if($sections->search_section->status == 1)
        <!-- End of slider section
            ============================================= -->
        <section id="search-course" class=" border">
            <div class="container">
                <div class="row ">
                    <div class="col-12">
                        <div class="row">
                            <div class="hero-section">
                                <ul class="nav justify-content-center">
                                    <li class="nav-item"><span class="icon iconss"><img
                                                    src="assets/img/banner/260d37c0-84ad-4627-9667-26030c180189 (1).png"
                                                    alt=""> </span><span class="text">@lang('labels.frontend.layouts.partials.Expert_Teachers')</span>
                                    </li>
                                    <li class="nav-item"><span class="icon iconss"><img src="assets/img/banner/55.png" alt=""></span><span
                                                class="text">@lang('labels.frontend.layouts.partials.Learn_Anywhere')</span>
                                    </li>
                                    <li class="nav-item"><span class="icon iconss"><img
                                                    src="assets/img/banner/dfeferf9 (1).png" alt=""></span><span
                                                class="text">@lang('labels.frontend.layouts.partials.Earn a certificate or degree')</span>
                                    </li>

                                    <li class="nav-item"><span class="icon iconss"><img src="assets/img/banner/fdfvds.png"
                                                                                 alt=""></span><span class="text">@lang('labels.frontend.layouts.partials.Learn the latest skills')</span>
                                    </li>
                                    <li class="nav-item"><span class="icon iconss"><img src="assets/img/banner/fdfvds.png"
                                                                                 alt=""></span><span class="text">@lang('labels.frontend.layouts.partials.Booking center online or offline')</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>
        <!-- End of Search Courses
            ============================================= -->
    @endif


    @if($sections->popular_courses->status == 1)
        @include('frontend.layouts.partials.popular_courses')
    @endif

    @if(($sections->reasons->status != 0) || ($sections->testimonial->status != 0))
        <!-- Start of why choose us section
        ============================================= -->
        <section id="why-choose-us" class="">
            <div class=" ">
                <div class="">
                    <div class="section-title mb20 headline text-center pt-5">

                    </div>
                    <div class="row bg-grd package p-5">
                        <div class="col-lg-4 pic-hd ">
                            <div class=" position-relative p-re">
                                <div class="p-ab ">
                                    <div>
                                        <div class="text-white tex">
                                            <h3 class="pb-4">@lang('labels.frontend.layouts.partials.Bundle Course')</h3>
                                            <h4 class="pb-5 textbold">@lang('labels.frontend.layouts.partials.World-class learning for anyone, anywhere')</h4>
                                        </div>
                                        <div class=" text-center btn-p">
                                            <button class=" btn btn-outline-info">@lang('labels.frontend.layouts.partials.view_plans')</button>
                                        </div>


                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="offset-1 col-lg-7 col-sm-12 text-white">
                            <div class="p-5">
                                <span class=" pb-3"> @lang('labels.frontend.layouts.partials.OfferCourses')</span>
                                <h4 >@lang('labels.frontend.layouts.partials.packagecourse')
                                </h4>
                            </div>

                            @if($total_bundle->count() > 0)
                                <div class="owl-carousel default-owl-theme " data-items="3">

                                    @foreach($total_bundle as $course)
                                        <div class="item">
                                            <div class="">
                                                <div class="best-course-pic-text bg-white relative-position">
                                                    <a href="{{ route('bundles.show', [$course->slug]) }}"><div class="best-course-pic piclip relative-position"
                                                                                                                @if($course->image != "") style="background-image: url('{{$course->image}}')" @endif>
                                                        <div class="course-price text-center gradient-bg">
                                                            @if($course->free == 1)
                                                                <span>{{trans('labels.backend.courses.fields.free')}}</span>
                                                            @else
                                                                <span> {{$appCurrency['symbol'].' '.$course->price}}</span>
                                                            @endif
                                                        </div>

                                                    </div></a>
                                                    <div class="card-body text-dark">
                                                        <h3 class="card-title titleofcard">{{$course->title}}</h3>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="course-rate ul-li">
                                                                    <ul>
                                                                        @for ($i=0; $i<5; ++$i)
                                                                            <li><i class="fa{{($course->rating<=$i?'r':'s')}} fa-star{{($course->rating==$i+.5?'-half-alt':'')}}" aria-hidden="true"></i></li>
                                                                        @endfor
                                                                        <li><span class="text-muted">{{number_format($course->rating)}} ({{number_format($course->reviews->count())}})</span></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="course-meta my-3 vv">

                                                            <span class="course-author"><a href="#">{{ $total_bundle->count() }}
                                                                    @lang('labels.frontend.course.courses')</a></span>

                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xl-10 col-9">
                                                                @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                                                    <button type="submit"
                                                                            class="btn btn-info btnAddCard">   @lang('labels.frontend.course.add_to_cart')
                                                                        <i class="fa fa-shopping-bag ml-1"></i>
                                                                    </button>

                                                                @elseif(!auth()->check())
                                                                    @if($course->free == 1)
                                                                        <a class="btn btn-info btn-block btnAddCard"
                                                                           href="{{ route('login.index') }}">@lang('labels.frontend.course.get_now') <i
                                                                                    class="fas fa-caret-right"></i></a>
                                                                    @else

                                                                        <a class="btn btn-info btnAddCard btn-block"
                                                                           href="{{ route('login.index') }}">@lang('labels.frontend.course.add_to_cart')
                                                                            <i class="fa fa-shopping-bag"></i>
                                                                        </a>
                                                                    @endif
                                                                @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                                                                    @if($course->free == 1)
                                                                        <form action="{{ route('cart.getnow') }}" method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="bundle_id"
                                                                                   value="{{ $course->id }}"/>
                                                                            <input type="hidden" name="amount"
                                                                                   value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                            <button class="btn btn-info btnAddCard btn-block">@lang('labels.frontend.course.get_now') <i
                                                                                        class="fas fa-caret-right"></i></button>
                                                                        </form>
                                                                    @else
                                                                        <form action="{{ route('cart.addToCart') }}" method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="bundle_id"
                                                                                   value="{{ $course->id }}"/>
                                                                            <input type="hidden" name="amount"
                                                                                   value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                            <button type="submit"
                                                                                    class="btn btn-info btnAddCard btn-block">
                                                                                @lang('labels.frontend.course.add_to_cart')<i
                                                                                        class="fa fa-shopping-bag"></i></button>
                                                                        </form>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="">
                                                                <a href="{{ route('bundles.show', [$course->slug]) }}"
                                                                   class="btn btnWishList">
                                                                    <i class="far fa-bookmark"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    @endforeach
                                    @else
                                        <h3>@lang('labels.general.no_data_available')</h3>


                                </div>
                            @endif
                        </div>


                    </div>


                </div>
            </div>
        </section>
        <!-- End of why choose us section
            ============================================= -->
    @endif

    @if($sections->latest_news->status == 1)
        <!-- Start latest section
        ============================================= -->
        @include('frontend.layouts.partials.latest_news')
        <!-- End latest section
            ============================================= -->
    @endif

    @if($sections->course_by_category->status == 1)
        <!-- Start Course category
        ============================================= -->
        @include('frontend.layouts.partials.course_by_category')
        <!-- End Course category
            ============================================= -->
    @endif
    <section class="bg-static">
        <div class="row bg-static1 ">
            <div class="container">
                
                <div class="">
                    <div class="p-5 ">
                        
                                <img src="{{asset("storage/logos/".config('logo_b_image'))}}"
                                    alt="{{env('APP_NAME')}}">
                                   
                     </div>
                     @if($offline)
                    <div class="cont text-white pl-5 pb-5">
                    @if(App::getLocale() == 'en')
                        <h1>{{$offline->title}}</h1>
                        @endif
                        @if(App::getLocale() == 'ar')
                        <h1>{{$offline->title_ar}}</h1>
                        @endif
                        @if(App::getLocale() == 'en')
                        <p class="">{!!$offline->content!!}</p>
                        @endif
                        @if(App::getLocale() == 'ar')
                        <p class="">{!!$offline->content_ar!!}</p>
                        @endif
                         <div class="pl-3 pt-3 pb-5">
                    
                            <a href="{{ route('offlineBooking.index') }}"class="btn btn-outline-info">
                            @lang('labels.frontend.layouts.partials.view_offline_courses')
                            </a>
                    </div>
                    </div>
                    @endif
                   
                </div>
            </div>
        </div>
    </section>


    @if($sections->sponsors->status == 1)
        @if(count($sponsors) > 0 )
            <!-- Start of sponsor section
        ============================================= -->
            <section id="sponsor" class="sponsor-section">
                <div class="container">
                    <div class="section-title-2 mb65 headline text-left ">
                        <h4 class="title">{{env('APP_NAME')}} <span>@lang('labels.frontend.layouts.partials.sponsors')</span></h4>
                    </div>

                    <div class="sponsor-item sponsor-1 text-center">
                        @foreach($sponsors as $sponsor)
                            <div class="sponsor-pic text-center">
                                <a href="{{ ($sponsor->link != "") ? $sponsor->link : '#' }}">
                                    <img src={{asset("storage/uploads/".$sponsor->logo)}} alt="{{$sponsor->name}}">
                                </a>

                            </div>
                        @endforeach

                    </div>
                </div>
            </section>
            <!-- End of sponsor section
       ============================================= -->
        @endif
    @endif


    @if($sections->featured_courses->status == 1)
        <!-- Start of best course
        ============================================= -->
        {{--@include('frontend.layouts.partials.browse_courses')--}}
        <!-- End of best course
            ============================================= -->
    @endif


    @if($sections->teachers->status == 1)
       @include('frontend.layouts.partials.teachers')
    @endif



    @if($sections->faq->status == 1)
        <!-- Start FAQ section
        ============================================= -->
        @include('frontend.layouts.partials.faq')
        <!-- End FAQ section
            ============================================= -->
    @endif
  <!-- Start academies section
        ============================================= -->

    <section id="course-teacher" class="course-teacher-section p-5">
        <div class="">
            <div class="container ">
                <div class="section-title headline mb-5">
                    <span class=" subtitle text-uppercase font-weight-lighter">@lang('labels.frontend.home.our_professionals')</span>
                    <h4 class="text-dark font-weight-bolder title "><span>@lang('labels.frontend.home.academies').</span>
                    </h4>
                </div>

                @if(count($acadimies)> 0)
                    <div class="owl-carousel custom-owl-theme">
                        @foreach($acadimies as $academy)
                            @if($academy->academy != null)
                                <div class="item ml-lg-5">
                                    <a href="{{route('academy.show',$academy)}}">
                                        <div class="text-center ">
                                            <div class="card academy">
                                                <img src="{{$academy->academy->logo}}" alt="{{$academy->full_name}}">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>


<!-- End academies section
            ============================================= -->


@endsection

@push('after-scripts')
    <script>
        setTimeout(function () {
            $('.owl-carousel').trigger('refresh.owl.carousel');
        }, 100)
        $('ul.product-tab').find('li:first').addClass('active');
    </script>
@endpush

