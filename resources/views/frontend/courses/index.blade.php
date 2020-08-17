@extends('frontend.layouts.app')
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
    <link rel="stylesheet" href="../../assets/css/course.css"/>

@endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="breadcrumb-section relative-position pb-5 backgroud-style bg-header-cat">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="page-breadcrumb-content text-center">
                <div class=row>
                    <div class="page-breadcrumb-title text-left col-7 col-xl-7 col-md-7 col-lg-7">
                        <h1 class="breadcrumb-head black bold">
                            <span>@if(isset($category)) {{$category->name}} @else @lang('labels.frontend.course.courses') @endif </span>
                        </h1>
                        <h3>
                            Courses to get you started
                        </h3>
                    </div>
                    <div class="col-xl-5 col-md-5 col-lg-5 col-5">
                        <img class="breadcrumb-image" src="/assets/img/Learn Online.svg">

                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of course section
        ============================================= -->
    <!-- Check if there is any courses in trending or popular -->
    @if(count($popular_course) > 0 || count($trending_courses) > 0)
        <section id="course-page" class="course-page-section pt-5">
            <div class="container">
                <div class="row">
                    @if(@isset($category))
                        <div class="col-xl-12 categories-container border-bottom">
                            @if(count($popular_course) > 0)
                                <button onclick="showTab($('#popular-course'),$(this))"
                                        class="tab-button btn active btn-light">Most Popular
                                </button>
                            @endif
                            @if(count($trending_courses) > 0)
                                <button onclick="showTab($('#trending'),$(this))"
                                        class="tab-button btn btn-light">Trending
                                </button>
                            @endif
                        </div>
                        <div class="col-xl-12 courses-container">
                            <div class="course-container fade in show active "
                                 id="popular-course" aria-labelledby="popular-course">
                                <div class="owl-carousel default-owl-theme">
                                    @if(count($popular_course) > 0)
                                        @foreach($popular_course as $course)
                                            <div class="item ">
                                                @include('frontend.layouts.partials.coursesTemp')
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="course-container fade in "
                                 id="trending" aria-labelledby="trending">
                                <div class="owl-carousel default-owl-theme">
                                    @if($trending_courses->count() > 0)
                                        @foreach($trending_courses as $course)
                                            <div class="item ">
                                                @include('frontend.layouts.partials.coursesTemp')
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                        </div>
                    @else
                        <div class="col-12 col-md-9 col-xl-9">
                            @if($chapters->count() > 0)
                                <div class="row">
                                    @foreach($chapters as $course)

                                        <div class="col-12 col-xl-3 col-md-3 col-sm-6">
                                            @include('frontend.layouts.partials.coursesTemp')
                                        </div>

                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="side-bar">

                                <div class="side-bar-widget  first-widget">
                                    <h2 class="widget-title text-capitalize">@lang('labels.frontend.course.find_your_course')</h2>
                                    <div class="listing-filter-form pb30">
                                        <form action="{{route('search-course')}}" method="get">

                                            <div class="filter-search mb20">
                                                <label class="text-uppercase">@lang('labels.frontend.course.category')</label>
                                                <select name="category"
                                                        class="form-control listing-filter-form select">
                                                    <option value="">@lang('labels.frontend.course.select_category')</option>
                                                    @if(count($categories) > 0)
                                                        @foreach($categories as $category)
                                                            <option value="{{$category->id}}">{{$category->name}}</option>

                                                        @endforeach
                                                    @endif

                                                </select>
                                            </div>


                                            <div class="filter-search mb20">
                                                <label>@lang('labels.frontend.course.full_text')</label>
                                                <input type="text" class="" name="q"
                                                       placeholder="{{trans('labels.frontend.course.looking_for')}}">
                                            </div>
                                            <button class="genius-btn gradient-bg text-center text-uppercase btn-block text-white font-weight-bold"
                                                    type="submit">@lang('labels.frontend.course.find_courses')
                                                <i
                                                        class="fas fa-caret-right"></i></button>
                                        </form>

                                    </div>
                                </div>

                                @if($recent_news->count() > 0)
                                    <div class="side-bar-widget">
                                        <h2 class="widget-title text-capitalize">@lang('labels.frontend.course.recent_news')</h2>
                                        <div class="latest-news-posts">
                                            @foreach($recent_news as $item)
                                                <div class="latest-news-area">

                                                    @if($item->image != "")
                                                        <div class="latest-news-thumbnile relative-position"
                                                             style="background-image: url({{asset('storage/uploads/'.$item->image)}})">
                                                            <div class="blakish-overlay"></div>
                                                        </div>
                                                    @endif
                                                    <div class="date-meta">
                                                        <i class="fas fa-calendar-alt"></i> {{$item->created_at->format('d M Y')}}
                                                    </div>
                                                    <h3 class="latest-title bold-font"><a
                                                                href="{{route('blogs.index',['slug'=>$item->slug.'-'.$item->id])}}">{{$item->title}}</a>
                                                    </h3>
                                                </div>
                                                <!-- /post -->
                                            @endforeach


                                            <div class="view-all-btn bold-font">
                                                <a href="{{route('blogs.index')}}">@lang('labels.frontend.course.view_all_news')
                                                    <i class="fas fa-chevron-circle-right"></i></a>
                                            </div>
                                        </div>
                                    </div>

                                @endif


                                @if($global_featured_course != "")
                                    <div class="side-bar-widget">
                                        <h2 class="widget-title text-capitalize">@lang('labels.frontend.course.featured_course')</h2>
                                        <div class="featured-course">
                                            <div class="best-course-pic-text relative-position pt-0">
                                                <div class="best-course-pic relative-position "
                                                     @if($global_featured_course->course_image != "") style="background-image: url({{asset('storage/uploads/'.$global_featured_course->course_image)}})" @endif>

                                                    @if($global_featured_course->trending == 1)
                                                        <div class="trend-badge-2 text-center text-uppercase">
                                                            <i class="fas fa-bolt"></i>
                                                            <span>@lang('labels.frontend.badges.trending')</span>
                                                        </div>
                                                    @endif
                                                    @if($global_featured_course->free == 1)
                                                        <div class="trend-badge-3 text-center text-uppercase">
                                                            <i class="fas fa-bolt"></i>
                                                            <span>@lang('labels.backend.courses.fields.free')</span>
                                                        </div>
                                                    @endif

                                                </div>
                                                <div class="best-course-text" style="left: 0;right: 0;">
                                                    <div class="course-title mb20 headline relative-position">
                                                        <h3>
                                                            <a href="{{ route('courses.show', [$global_featured_course->slug]) }}">{{$global_featured_course->title}}</a>
                                                        </h3>
                                                    </div>
                                                    <div class="course-meta">
                                            <span class="course-category"><a
                                                        href="{{route('courses.category',['category'=>$global_featured_course->category->slug])}}">{{$global_featured_course->category->name}}</a></span>
                                                        <span class="course-author">{{ $global_featured_course->students()->count() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif
    @if(@isset($category))
        <section id="search-course" class=" border ">
            <div class="container">
                <div class="row ">
                    <div class="col-12">
                        <div class="row">
                            <div class="hero-section">
                                <ul class="nav justify-content-center">
                                    <li class="nav-item"><span class="icon"><img
                                                    src="{{asset('/')}}assets/img/banner/260d37c0-84ad-4627-9667-26030c180189 (1).png"
                                                    alt=""> </span><span class="text">Expert Teachers</span>
                                    </li>
                                    <li class="nav-item"><span class="icon"><img
                                                    src="{{asset('/')}}assets/img/banner/55.png" alt=""></span><span
                                                class="text">Learn Anywhere</span>
                                    </li>
                                    <li class="nav-item"><span class="icon"><img
                                                    src="{{asset('/')}}assets/img/banner/dfeferf9 (1).png"
                                                    alt=""></span><span class="text">Earn a certificate or degree</span>
                                    </li>

                                    <li class="nav-item"><span class="icon"><img
                                                    src="{{asset('/')}}assets/img/banner/fdfvds.png" alt=""></span><span
                                                class="text">Learn the latest skills</span>
                                    </li>
                                    <li class="nav-item"><span class="icon"><img
                                                    src="{{asset('/')}}assets/img/banner/fdfvds.png" alt=""></span><span
                                                class="text">Booking center online or offline</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </section>
    @endif

    @if(@isset($category))

        <section class="course-page-section">
            <div class="container" id="featured-courses">
                <div class="section-title mb20 headline mb-5">

                    <h3 class="text-dark font-weight-bolder "><span>Featured courses</span>
                    </h3>
                </div>
                <div class="owl-carousel custom-owl default-owl-theme" data-items="1">
                    @if(count($featured_courses) > 0)
                        @foreach($featured_courses as $course)
                            <div class="card p-3">
                                <div class="row no-gutters">
                                    <div class="col-md-6 ">
                                        <div class="best-course-pic relative-position ">
                                            <div class="course-list-img-text course-page-sec">
                                                <div class="course-li-img"
                                                     @if($course->course_image != "") style="background-image: url({{asset('storage/uploads/'.$course->course_image)}})" @endif >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card-body noborder">
                                            <h3 class=" mt-3 display-6">{{$course->title}}</h3>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="course-rate ul-li">
                                                        <ul>
                                                            @for ($i=0; $i<5; ++$i)
                                                                <li>
                                                                    <i class="fa{{($course->rating<=$i?'r':'s')}} fa-star{{($course->rating==$i+.5?'-half-alt':'')}}"
                                                                       aria-hidden="true"></i></li>
                                                            @endfor
                                                            <li><span class="text-muted">{{number_format($course->rating)}} ({{number_format($course->reviews->count())}})</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="course-meta my-2">
                                                <small><i class="far fa-clock"></i> {{ $course->course_hours }}
                                                    hours |
                                                </small>
                                                <small><i
                                                            class="fab fa-youtube"></i> {{ $course->chapters()->count() }}
                                                    lecture
                                                </small>
                                            </div>
                                            <div class="row my-3">
                                                <div class="col-xl-2 col-3">
                                                    @foreach($course->teachers as $key=>$teacher)
                                                        @php $key++ @endphp
                                                        {{-- <img src="{{asset($teacher->avatar_location)}}"
                                                             class="rounded-circle teach_img"> --}}

                                                        @if($teacher->avatar_location == "")
                                                            <img class="rounded-circle teach_img"
                                                                 src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg"
                                                                 alt="">
                                                        @else
                                                            <img class="rounded-circle teach_img"
                                                                 src="{{asset($teacher->avatar_location)}}"
                                                                 alt="">
                                                        @endif
                                                    @endforeach
                                                </div>
                                                <div class="col-9">
                                                    <div class="row pt-2">
                                                        @foreach($course->teachers as $key=>$teacher)
                                                            @php
                                                                $teacherProfile = \App\Models\TeacherProfile::where('user_id',$teacher->id)->first();
                                                            @endphp
                                                            @php $key++ @endphp

                                                            <a class="col-12"
                                                               href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                                               target="_blank">
                                                                {{$teacher->full_name}}@if($key < count($course->teachers ))
                                                                    , @endif
                                                            </a>
                                                        @endforeach
                                                        @foreach($course->teachers as $key=>$teacher)
                                                            @php $key++ @endphp
                                                            <a class="col-12"
                                                               href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                                               target="_blank">
                                                                {{$teacherProfile->description}}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xl-5 col-9">
                                                    @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                                        <button type="submit"
                                                                class="btn btn-block btn-info ">   @lang('labels.frontend.course.add_to_cart')
                                                            <i class="fa fa-shopping-bag ml-1"></i>
                                                        </button>

                                                    @elseif(!auth()->check())
                                                        @if($course->free == 1)
                                                            <a class="btn btn-block btn-info"
                                                               href="{{route('login.index')}}">@lang('labels.frontend.course.get_now')
                                                                <i class="fas fa-caret-right"></i>
                                                            </a>
                                                        @else
                                                            <a class="btn btn-block btn-info"
                                                               href="{{route('login.index')}}">@lang('labels.frontend.course.add_to_cart')
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
                                                                <button class="btn btn-block btn-info"
                                                                        href="#">@lang('labels.frontend.course.get_now')
                                                                    <i class="fas fa-caret-right"></i>
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
                                                                        class="btn btn-block btn-info">
                                                                    @lang('labels.frontend.course.add_to_cart')
                                                                    <i class="fa fa-shopping-bag"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="">
                                                    <a href="{{ route('courses.show', [$course->slug]) }}"
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
                    @endif
                </div>
            </div>
        </section>
    @endif
    <!-- End of course section
        ============================================= -->

    <!-- Start of course teacher
    ============================================= -->
    <section id="course-teacher" class="course-teacher-section p-5">
        <div class="">
            <div class="container ">
                <div class=" section-title mb20 headline p-5 mb-5">
                    <span class=" subtitle text-uppercase font-weight-lighter">@lang('labels.frontend.home.our_professionals')</span>
                    <h2 class="text-dark font-weight-bolder "><span>@lang('labels.frontend.home.Instructors').</span>
                    </h2>
                </div>
                <div class="owl-carousel custom-owl-theme">
                    @if(count($teachers)> 0)
                        @foreach($teachers as $key=>$item)
                            @foreach($teacher_data as $teacher)
                                @if($item->id == $teacher->user_id)
                                    <div class="item">
                                        <div class="text-center ">
                                            <div class="bg-card">
                                                <div>
                                                    <div class="finger-img">
                                                        <img src="/assets/img/banner/01.png" alt="">
                                                    </div>

                                                    <div class="prof-img ">
                                                        @if($item->avatar_location == "")
                                                            <a href="{{route('teachers.show',['id'=>$item->id])}}"><img
                                                                        class="teacher-image shadow-lg p-3"
                                                                        src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg"
                                                                        alt=""></a>
                                                        @else
                                                            <a href="{{route('teachers.show',['id'=>$item->id])}}"><img
                                                                        class="teacher-image shadow-lg p-3"
                                                                        src="{{asset($item->avatar_location)}}"
                                                                        alt=""></a>
                                                        @endif


                                                    </div>
                                                </div>
                                                <div class="teacher-social-name ul-li-block pt-3">
                                                    <div class="teacher-name text-dark font-weight-bold">
                                                        <h5>{{$item->full_name}}</h5>
                                                    </div>
                                                    <div class="teacher-title text-muted font-weight-light">
                                                        {{$teacher->title}}
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



    {{-- start myyy of course section --}}
    @if(@isset($category))
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-lg-2 col-xl-2 col-md-4 filters-section">
                        <button type="button"
                                class="btn btn-block btn-primary btn-toggler mb-xl-0 mb-lg-0 mb-3">@lang('labels.frontend.course.filters.filters')
                            <i class="fas fa-filter"></i></button>

                        <!-- Section: Filters -->
                        <section class="p-2 filters-side-bar">

                            <!-- Section: Average -->
                            <section class="p-3 pb-0 border-bottom rating-filter">

                                <h5 class="font-weight-bold mb-3">@lang('labels.frontend.course.filters.rating') </h5>
                                <div class="form-group form-check">
                                    <input name="rate" type="radio" data-value="4" class="form-check-input" id="4+">
                                    <label class="form-check-label" for="4+">
                                        <ul class="small rating-list">
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <p class=" px-2">& Up</p>
                                            </li>
                                        </ul>
                                    </label>

                                </div>
                                <div class="form-group form-check">
                                    <input name="rate" type="radio" data-value="3" class="form-check-input" id="3+">
                                    <label class="form-check-label" for="3+">
                                        <ul class="small rating-list">
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <p class=" px-2">& Up</p>
                                            </li>
                                        </ul>
                                    </label>
                                </div>
                                <div class="form-group form-check">
                                    <input name="rate" type="radio" data-value="2" class="form-check-input" id="2+">
                                    <label class="form-check-label" for="2+">
                                        <ul class="small rating-list">
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <p class=" px-2">& Up</p>
                                            </li>
                                        </ul>
                                    </label>
                                </div>
                                <div class="form-group form-check">
                                    <input name="rate" type="radio" data-value="1" class="form-check-input" id="1+">
                                    <label class="form-check-label" for="1+">
                                        <ul class="small rating-list">
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <p class=" px-2">& Up</p>
                                            </li>
                                        </ul>
                                    </label>
                                </div>


                            </section>
                            <!-- Section: Average -->

                            <!-- Section: Price -->
                            <section class="p-3 pb-0 border-bottom duration-filter">

                                <h5 class="font-weight-bold mb-3">@lang('labels.frontend.course.filters.duration') </h5>

                                <div class="form-check pl-0 mb-3">
                                    <input type="radio" class="form-check-input" id="under2" data-value="0-2"
                                           name="duration">
                                    <label class="form-check-label small font-weight-bold" for="under2">0-2
                                        Hours</label>
                                </div>
                                <div class="form-check pl-0 mb-3">
                                    <input type="radio" class="form-check-input" data-value="3-6" id="3-6"
                                           name="duration">
                                    <label class="form-check-label small font-weight-bold" for="3-6">3-6
                                        Hours</label>
                                </div>
                                <div class="form-check pl-0 mb-3">
                                    <input type="radio" class="form-check-input" data-value="7-16" id="7-16"
                                           name="duration">
                                    <label class="form-check-label small font-weight-bold" for="7-16">7-16
                                        Hours</label>
                                </div>
                                <div class="form-check pl-0 mb-3">
                                    <input type="radio" class="form-check-input" data-value="20-26" id="20-26"
                                           name="duration">
                                    <label class="form-check-label small font-weight-bold" for="20-26">20-26
                                        Hours</label>
                                </div>
                            </section>
                            <!-- Section: Price -->

                            <!-- Section: Price  -->
                            <section class="pb-0 p-3 border-bottom price-filter">
                                <h5 class="font-weight-bold mb-3">@lang('labels.frontend.course.filters.price') </h5>
                                <div class="form-check pl-0 mb-3">
                                    <input type="checkbox" class="form-check-input" id="isFree">
                                    <label class="form-check-label small font-weight-bold" for="isFree">Free</label>
                                </div>
                                <input class="price-filter-input" type="range" name="price" id="price" value="0"
                                       step="10"
                                       min="0"
                                       max="10000">
                                <span class="text-muted font-weight-light float-right"><span id="current-price">0</span>  EGP</span>
                                {{--                                <span class="text-muted font-weight-light float-right">10000 EGP</span>--}}
                            </section>
                            <!-- Section: Price -->
                            <section class="filters-controler">
                                <button type="button" style="display: none;"
                                        class="btn btn-block btn-primary btn-apply"><i
                                            class="fas fa-check"></i> @lang('labels.frontend.course.filters.apply')
                                </button>
                                <button type="button" style="display: none;"
                                        class="btn btn-block btn-primary btn-reset"><i
                                            class="fas fa-recycle"></i> @lang('labels.frontend.course.filters.reset')
                                </button>
                            </section>
                        </section>
                        <!-- Section: Filters -->
                    </div>
                    <div class="col-12 col-lg-9 col-xl-9 col-md-8">
                        <div class="form-group row filters-category">
                            <label class="col-sm-2 col-form-label col-form-label-sm " for="sort"><h3
                                        class="font-weight-bold text-dark">SORT BY</h3></label>
                            <div class="col">
                                <select id="sortFilter" class="form-control">
                                    <option selected value="All">All</option>
                                    <option value="popular">Most Popular</option>
                                    <option value="trending">Trending</option>
                                    <option value="featured">Featured</option>
                                </select>
                            </div>
                        </div>
                        <div class="row all-courses">
                            @if($courses->count() > 0)

                                @foreach($courses as $course)

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-2">
                                        @include('frontend.layouts.partials.coursesTemp')
                                    </div>

                                @endforeach
                            @else
                                <h3>@lang('labels.general.no_data_available')</h3>
                            @endif
                        </div>
                        <div class="row filtered-items" style="display: none">

                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    {{-- end myyy of course section --}}
    <!-- End of course section
        ============================================= -->

    <!-- Start of best course
   =============================================  -->
    @include('frontend.layouts.partials.browse_courses')
    <!-- End of best course
            ============================================= -->


@endsection

@push('after-scripts')
    <script>
        $(document).ready(function () {
            if ($(window).width() <= 768) {
                $('.filters-section .btn-toggler').click(function () {
                    $('.filters-side-bar').toggle(500);
                    $('.filters-category').toggle(500);
                });
            }
            var rating = $('.rating-filter input:checked').data('value');
            var duration = $('.duration-filter input:checked').data('value');
            var maxPrice = $('.price-filter-input').val();
            var isFree = $('#isFree').prop('checked');
            var sortBy = $('#sortFilter').val();
            $('.filters-section .btn-apply').on('click', function (e) {
                e.preventDefault();
                var rating = $('.rating-filter input:checked').data('value') ? $('.rating-filter input:checked').data('value') : '';
                var duration = $('.duration-filter input:checked').data('value');
                var maxPrice = $('.price-filter-input').val();
                var isFree = $('#isFree').prop('checked');
                var sortBy = $('#sortFilter').val();
                $.ajax({
                    url: "{{route('courses.filterCategory')}}",
                    method: "GET",
                    data: {
                        'rating': rating,
                        'duration': duration,
                        'maxPrice': maxPrice,
                        'isFree': isFree,
                        'type': sortBy
                    },
                    beforeSend: function () {
                        $('.all-courses').hide();
                        $('.filtered-items').html('');
                        $(".filtered-items").show();
                        $(".filtered-items").css('justify-content', 'center').append('<div class="ajax-loader"></div>');
                    },
                    success: function (resp) {
                        console.log(resp);
                        $(".filtered-items").css('justify-content', 'unset');
                        $('.filtered-items').show();
                        $('.filtered-items').html(resp);
                    }
                });
            });
            $('input[type=range]').on('change', function () {
                $('#current-price').text($('input[type=range]').val())
            });
            $('.filters-section input , #sortFilter').on('click', function () {
                if (rating || duration || maxPrice !== '0' || isFree || sortBy) {
                    $('.btn-apply').show();
                    $('.btn-reset').show();
                } else {
                    $('.btn-apply').fadeOut(500);
                    $('.btn-reset').fadeOut(500);
                }
            });
            $('.btn-reset').click(function () {
                $('.rating-filter input:checked').prop("checked", false);
                $('.duration-filter input:checked').prop("checked", false);
                $('.price-filter-input').val('0');
                $('#isFree').prop("checked", false);
                $('#sortFilter').val('All');
                $('#current-price').text($('input[type=range]').val());
                $('.btn-reset').fadeOut(500);
                $('.btn-apply').fadeOut(500);
            });
            $(document).on('change', '#sortBy', function () {
                if ($(this).val() != "") {
                    location.href = '{{url()->current()}}?type=' + $(this).val();
                } else {
                    location.href = '{{route('courses.all')}}';
                }
            })

            @if(request('type') != "")
            $('#sortBy').find('option[value="' + "{{request('type')}}" + '"]').attr('selected', true);
            @endif

        });


    </script>
@endpush

