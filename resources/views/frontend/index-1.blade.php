@extends('frontend.layouts.app'.config('theme_layout'))

@section('title', trans('labels.frontend.home.title').' | '.app_name())
@section('meta_description', '')
@section('meta_keywords','')


@push('after-styles')
    <link rel="stylesheet" href="../../assets/css/course.css"/>
    <style>
        .teacher-img-content .teacher-social-name {
            max-width: 67px;
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
       section{
           overflow: unset;
       }

    </style>
@endpush

@section('content')

    <!-- Start of slider section
            ============================================= -->
    @if(session()->has('alert'))
        <div class="alert alert-light alert-dismissible fade my-alert show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <strong>{{session('alert')}}</strong>
        </div>
    @endif
    @include('frontend.layouts.partials.slider')

    @if($sections->search_section->status == 1)
        <!-- End of slider section
            ============================================= -->
        <section id="search-course" class=" border ">
            <div class="container">
                <div class="row pb-3 pt-4">
                    <div class="col-12">
                        <div class="row">
                            <div class="hero-section">
                                <ul class="nav justify-content-center">
                                    <li class="nav-item"><span class="icon"><img src="assets/img/banner/260d37c0-84ad-4627-9667-26030c180189 (1).png" alt=""> </span><span class="text">Expert Teachers</span>
                                    </li>
                                    <li class="nav-item"><span class="icon"><img src="assets/img/banner/55.png" alt=""></span><span class="text">Learn Anywhere</span>
                                    </li>
                                    <li class="nav-item"><span class="icon"><img src="assets/img/banner/dfeferf9 (1).png" alt=""></span><span class="text">Earn a certificate or degree</span>
                                    </li>

                                    <li class="nav-item"><span class="icon"><img src="assets/img/banner/fdfvds.png" alt=""></span><span class="text">Learn the latest skills</span>
                                    </li>
                                    <li class="nav-item"><span class="icon"><img src="assets/img/banner/fdfvds.png" alt=""></span><span class="text">Booking center online or offline</span>
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
                    <div class="row bg-grd p-5">
                        <div class="col-lg-4 pic-hd ">
                            <div class=" position-relative p-re">
                                <div class="position-absolute p-ab ">
                                    <div>
                                        <div class="text-white tex">
                                            <h3 class="pb-4">Bundle Course</h3>
                                            <h2 class="pb-5 textbold">World-class learning for anyone, anywhere</h2>
                                        </div>
                                        <div class=" text-center btn-p">
                                            <button class=" btn btn-outline-info">view plans</button>
                                        </div>


                                    </div>
                                </div>


                            </div>
                        </div>
                        <div class="offset-1 col-lg-7 col-sm-12 text-white">
                            <div class="p-5">
                                <span class=" pb-3">{{env('APP_NAME')}} @lang('labels.frontend.layouts.partials.OfferCourses')</span>
                                <h2>@lang('labels.frontend.layouts.partials.packagecourse') <span>{{app_name()}}</span>
                                </h2>
                            </div>

                            @if($total_bundle->count() > 0)
                                <div class="owl-carousel default-owl-theme ">

                                    @foreach($total_bundle as $course)
                                        <div class="item">
                                            <div class="">
                                                <div class="best-course-pic-text bg-white relative-position">
                                                    <div class="best-course-pic piclip relative-position"
                                                         @if($course->course_image != "") style="background-image: url('{{asset('storage/uploads/'.$course->course_image)}}')" @endif>
                                                        <div class="course-price text-center gradient-bg">
                                                            @if($course->free == 1)
                                                                <span>{{trans('labels.backend.courses.fields.free')}}</span>
                                                            @else
                                                                <span> {{$appCurrency['symbol'].' '.$course->price}}</span>
                                                            @endif
                                                        </div>
                                                    <!-- <div class="course-details-btn">
                                                            <a href="{{ route('courses.show', [$course->slug]) }}">@lang('labels.frontend.course.course_detail')
                                                            <i class="fas fa-arrow-right"></i></a>
                                                    </div>
                                                    <div class="blakish-overlay"></div> -->
                                                    </div>
                                                    <div class="card-body text-dark">
                                                        <h3 class="card-title titleofcard">{{$course->title}}</h3>
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <i class="fa fa-star"></i>
                                                                <i class="fa fa-star"></i>
                                                                <i class="fa fa-star"></i>
                                                                <i class="fa fa-star"></i>
                                                                <i class="fa fa-star"></i>
                                                                <span class="ml-1  rate">0</span>
                                                            </div>
                                                        </div>
                                                        <div class="course-meta my-1 vv">
                                                        <span class="course-category">
                                                            <a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a>
                                                        </span>
                                                            <span class="course-author"><a href="#">{{ $course->students()->count() }}
                                                                    @lang('labels.frontend.course.students')</a></span>

                                                        </div>
                                                        <div class="row justify-content-around">
                                                                @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                                                    <button type="submit"
                                                                            class="btn btn-info btnAddCard">   @lang('labels.frontend.course.add_to_cart')
                                                                        <i class="fa fa-shopping-bag ml-1"></i>
                                                                    </button>

                                                                @elseif(!auth()->check())
                                                                    @if($course->free == 1)
                                                                        <a id="openLoginModal"
                                                                           class="btn btn-block btnAddCard"
                                                                           data-target="#myModal"
                                                                           href="#">@lang('labels.frontend.course.get_now')
                                                                            <i
                                                                                    class="fas fa-caret-right"></i></a>
                                                                    @else

                                                                        <a id="openLoginModal"
                                                                           class="btn btn-info btnAddCard"
                                                                           data-target="#myModal"
                                                                           href="#">@lang('labels.frontend.course.add_to_cart')
                                                                            <i class="fa fa-shopping-bag"></i>
                                                                        </a>
                                                                    @endif
                                                                @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                                                                    @if($course->free == 1)
                                                                        <form action="{{ route('cart.getnow') }}"
                                                                              method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="course_id"
                                                                                   value="{{ $course->id }}"/>
                                                                            <input type="hidden" name="amount"
                                                                                   value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                            <button class="btn btn-info btnAddCard"
                                                                                    href="#">@lang('labels.frontend.course.get_now')
                                                                                <i
                                                                                        class="fas fa-caret-right"></i>
                                                                            </button>
                                                                        </form>
                                                                    @else
                                                                        <form action="{{ route('cart.addToCart') }}"
                                                                              method="POST">
                                                                            @csrf
                                                                            <input type="hidden" name="course_id"
                                                                                   value="{{ $course->id }}"/>
                                                                            <input type="hidden" name="amount"
                                                                                   value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                            <button type="submit"
                                                                                    class="btn btn-info btnAddCard">
                                                                                @lang('labels.frontend.course.add_to_cart')
                                                                                <i
                                                                                        class="fa fa-shopping-bag"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                @endif

                                                                <a href="{{ route('courses.show', [$course->slug]) }}"
                                                                   class="btn btnWishList">
                                                                    <i class="far fa-bookmark"></i>
                                                                </a>
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
                <div class="col-xl-5 col-sm-6 p-5">
                    <div class="p-5 ">
                        <img src="/img/backend/brand/Council-logo-100px.png" alt="">
                    </div>
                    <div class="text-white pl-5 pb-5">
                        <h1>Offline Booking Center.</h1>
                        <p class="">we see first-hand every day how technology makes the impossible, possible. It’s why
                            Pluralsight One exists: To advance our mission of democratizing technology skills,
                            challenging
                            assumptions about solutions and create significant, lasting social impact.</p>
                    </div>
                    <div class="pl-5 pb-5">
                        <button class="btn btn-outline-info ">View Plans</button>
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
                        <h2>{{env('APP_NAME')}} <span>@lang('labels.frontend.layouts.partials.sponsors')</span></h2>
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
        <!-- Start of course teacher
        ============================================= -->
        <section id="course-teacher" class="course-teacher-section p-5">
            <div class="">
                <div class="container ">
                    <div class=" mb20 headline p-5 mb-5">
                        <span class=" text-uppercase font-weight-lighter">@lang('labels.frontend.home.our_professionals')</span>
                        <h1 class="text-dark font-weight-bolder ">{{env('APP_NAME')}} <span>@lang('labels.frontend.home.Instructors').</span>
                        </h1>
                    </div>

                    <div class="owl-carousel custom-owl-theme">
                        @if(count($teachers)> 0)
                            @foreach($teachers as $key=>$item)
                                @foreach($teacher_data as $teacher)
                                    @if($item->id == $teacher->user_id)
                                        <div class="item ml-lg-5">
                                            <div class="text-center ">
                                                <div class="bg-card">
                                                    <div>
                                                        <div class="finger-img">
                                                            <img src="/assets/img/banner/01.png" alt="">
                                                        </div>
                                                      
                                                        <div class="prof-img ">
                                                        @if($item->avatar_location == "")
                                                            <a href="{{route('teachers.show',['id'=>$item->id])}}"><img class="teacher-image p-3" src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg"
                                                                 alt=""></a>
                                                        @else
                                                                 <a href="{{route('teachers.show',['id'=>$item->id])}}"><img class="teacher-image p-3" src="{{asset($item->avatar_location)}}"
                                                                 alt=""></a>
                                                        @endif

                                                        
                                                        </div>
                                                    </div>
                                                    <div class="teacher-social-name ul-li-block pt-3">
                                                        <div class="teacher-name text-dark font-weight-bold">
                                                            <h5>{{$teacher->title}}.{{$item->full_name}}</h5>
                                                        </div>

                                                        <hr>
                                                        <div class="teacher-name text-dark  justify-content-center">
                                                            <span>{{$teacher->description}}</span>
                                                        </div>
                                                        <ul>
                                                            <li><a href="{{'mailto:'.$item->email}}"><i
                                                                            class="fa fa-envelope"></i></a></li>
                                                            <li>
                                                                <a href="{{route('admin.messages',['teacher_id'=>$item->id])}}"><i
                                                                            class="fa fa-comments"></i></a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                        @endif
                    </div>

                </div>
            </div>
        </section>

        <!-- End of course teacher
            ============================================= -->


    @endif



    @if($sections->faq->status == 1)
        <!-- Start FAQ section
        ============================================= -->
        @include('frontend.layouts.partials.faq')
        <!-- End FAQ section
            ============================================= -->
    @endif


    <section id="course-teacher" class="course-teacher-section p-5">
        <div class="">
            <div class="container ">
                <div class=" mb20 headline p-5 mb-5">
                    <span class=" text-uppercase font-weight-lighter">@lang('labels.frontend.home.our_professionals')</span>
                    <h1 class="text-dark font-weight-bolder ">{{env('APP_NAME')}} <span>@lang('labels.frontend.home.academies').</span>
                    </h1>
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


   


@endsection

@push('after-scripts')
<script>
                                $(document).ready(function(){
                                    $(".owl-carousel1").owlCarousel({

                                        loop:true,
                                        margin:15,
                                        nav:true,
                                        responsive:{
                                            0:{
                                                items:1
                                            },
                                            600:{
                                                items:2
                                            },
                                            1000:{
                                                items:3
                                            }
                                        }
                                }) 
                                });
                                </script>

    <script>
        $(window).on('load', function () {
            $(".owl-carousel").owlCarousel({
                rewind: true,
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
    </script>
    <script>
        setTimeout(function () {
            $('.owl-carousel').trigger('refresh.owl.carousel');
        },100)
        $('ul.product-tab').find('li:first').addClass('active');
    </script>
@endpush

