@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
<link rel="stylesheet" href="../../assets/css/course.css"/>

@endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="breadcrumb-section relative-position pb-5 backgroud-style">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="page-breadcrumb-content text-center">
                <div class="page-breadcrumb-title">
                    <h2 class="breadcrumb-head black bold">
                        <span>@if(isset($category)) {{$category->name}} @else @lang('labels.frontend.course.courses') @endif </span>
                    </h2>
                </div>
            </div>
        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of course section
        ============================================= -->
    <section id="course-page" class="course-page-section pt-5">
        <div class="container">
            <div class="row">
                @if(@isset($category))
                    <div class="col-xl-12 categories-container border-bottom">
                        <button onclick="showTab($('#popular-course'),$(this))"
                                class="tab-button btn active btn-light">Most Popular
                        </button>
                        <button onclick="showTab($('#trending'),$(this))"
                                class="tab-button btn btn-light">Trending
                        </button>
                    </div>
                    <div class="col-xl-12 courses-container">
                        <div class="course-container fade in show active "
                             id="popular-course" aria-labelledby="popular-course">
                            <div class="owl-carousel default-owl-theme">
                                @if(count($popular_course) > 0)
                                    @foreach($popular_course as $course)
                                        <div class="item " >
                                            <div class="best-course-pic-text relative-position">
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
                                                <div class="card-body back-im">
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
                                                                <small> <i class="far fa-clock"></i> 10 hours |</small><small> <i class="fab fa-youtube"></i> 10 lecture </small>
                                                            {{-- <span class="course-category">
                                                                <a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a>
                                                            </span>
                                                            <span class="course-author"><a href="#">{{ $course->students()->count() }}
                                                                    @lang('labels.frontend.course.students')</a>
                                                            </span>
                                                            <span class="course-author">
                                                                    {{ $course->lessons()->count() }} @lang('labels.frontend.course.lessons') 
                                                            </span> --}}
                                                        </div>
                                                        <div class="row my-2">
                                                            <div class="col-3">
                                                                <img src="../../assets/img/teacher/tb-2.png"
                                                                        class="rounded-circle py-1">
                                                            </div>
                                                            <div class="col-9">
                                                                <div class="row">
                                                                    @foreach($course->teachers as $key=>$teacher)
                                                                        @php $key++ @endphp
                                                                        <div class="instructor-name">
                                                                        <a href="{{route('teachers.show',['id'=>$teacher->id])}}" target="_blank" >
                                                                                Instructor : {{$teacher->full_name}}@if($key < count($course->teachers )), @endif
                                                                        </a>
                                                                        </div>
                                                                    @endforeach
                                                                    @foreach($course->teachers as $key=>$teacher)
                                                                        @php $key++ @endphp
                                                                        <a href="{{route('teachers.show',['id'=>$teacher->id])}}" target="_blank">
                                                                            {{-- {{$teacher->description}} --}}Adobe Certified Instructor
                                                                        </a>
                                                                    @endforeach
                                                                    <!-- <div class="col-12 metatitle"></div>
                                                                    <div class="col-12 metadescr"></div> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="row justify-content-around">
                                                                @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                                                    <button type="submit" class="btn btn-block btn-info">   @lang('labels.frontend.course.add_to_cart') 
                                                                        <i class="fa fa-shopping-bag ml-1"></i>
                                                                    </button> 
                                                                    
                                                                @elseif(!auth()->check())
                                                                @if($course->free == 1)
                                                                        <a id="openLoginModal" class="btn btn-block btn-info" data-target="#myModal" href="#">@lang('labels.frontend.course.get_now')
                                                                            <i class="fas fa-caret-right"></i>
                                                                        </a>
                                                                @else
                                                                        <a id="openLoginModal"
                                                                            class="btn btn-block btn-info"
                                                                            data-target="#myModal"
                                                                            href="#">@lang('labels.frontend.course.add_to_cart')
                                                                            <i class="fa fa-shopping-bag"></i>
                                                                        </a>
                                                                        @endif
                                                                @elseif(auth()->check() && (auth()->user()->hasRole('student')))
                                
                                                                    @if($course->free == 1)
                                                                            <form action="{{ route('cart.getnow') }}" method="POST">
                                                                                @csrf
                                                                                <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                                                                <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                                <button class="btn btn-block btn-info"
                                                                                        href="#">@lang('labels.frontend.course.get_now') <i
                                                                                            class="fas fa-caret-right"></i></button>
                                                                            </form>
                                                                    @else
                                                                            <form action="{{ route('cart.addToCart') }}" method="POST">
                                                                                @csrf
                                                                                <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                                                                <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                                <button type="submit"
                                                                                        class="btn btn-block btn-info">
                                                                                    @lang('labels.frontend.course.add_to_cart') <i
                                                                                            class="fa fa-shopping-bag"></i></button>
                                                                            </form>
                                                                    @endif
                                                                @endif
                                                                <a href="{{ route('courses.show', [$course->slug]) }}" class="btn btnWishList">
                                                                    <i class="far fa-bookmark"></i>
                                                                </a>
                                                        </div>
                                                </div>
                                            </div> 
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
                                                <div class="best-course-pic-text relative-position">
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
                                                    <div class="card-body back-im">
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
                                                            <small> <i class="far fa-clock"></i> 10 hours
                                                                |</small><small> <i class="fab fa-youtube"></i> 10
                                                                lecture </small>
                                                            {{-- <span class="course-category">
                                                                <a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a>
                                                            </span>
                                                            <span class="course-author"><a href="#">{{ $course->students()->count() }}
                                                                    @lang('labels.frontend.course.students')</a>
                                                            </span>
                                                            <span class="course-author">
                                                                    {{ $course->lessons()->count() }} @lang('labels.frontend.course.lessons')
                                                            </span> --}}
                                                        </div>
                                                        <div class="row my-2">
                                                            <div class="col-3">
                                                                <img src="../../assets/img/teacher/tb-2.png"
                                                                     class="rounded-circle py-1">
                                                            </div>
                                                            <div class="col-9">
                                                                <div class="row">
                                                                    @foreach($course->teachers as $key=>$teacher)
                                                                        @php $key++ @endphp
                                                                        <div class="instructor-name">
                                                                            <a href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                                                               target="_blank">
                                                                                Instructor
                                                                                : {{$teacher->full_name}}@if($key < count($course->teachers ))
                                                                                    , @endif
                                                                            </a>
                                                                        </div>
                                                                    @endforeach
                                                                    @foreach($course->teachers as $key=>$teacher)
                                                                        @php $key++ @endphp
                                                                        <a href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                                                           target="_blank">
                                                                            {{-- {{$teacher->description}} --}}Adobe
                                                                            Certified Instructor
                                                                        </a>
                                                                @endforeach
                                                                <!-- <div class="col-12 metatitle"></div>
                                                                    <div class="col-12 metadescr"></div> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                                <div class="row">
                                                                    <div class="col-10">
                                                                        @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                                                            <button type="submit" class="btn btn-block btn-info">   @lang('labels.frontend.course.add_to_cart') 
                                                                                <i class="fa fa-shopping-bag ml-1"></i>
                                                                            </button> 
                                                                            
                                                                        @elseif(!auth()->check())
                                                                        @if($course->free == 1)
                                                                                <a id="openLoginModal" class="btn btn-block btn-info" data-target="#myModal" href="#">@lang('labels.frontend.course.get_now')
                                                                                    <i class="fas fa-caret-right"></i>
                                                                                </a>
                                                                        @else
                                                                                <a id="openLoginModal"
                                                                                    class="btn btn-block btn-info"
                                                                                    data-target="#myModal"
                                                                                    href="#">@lang('labels.frontend.course.add_to_cart')
                                                                                    <i class="fa fa-shopping-bag"></i>
                                                                                </a>
                                                                                @endif
                                                                        @elseif(auth()->check() && (auth()->user()->hasRole('student')))
                                        
                                                                            @if($course->free == 1)
                                                                                    <form action="{{ route('cart.getnow') }}" method="POST">
                                                                                        @csrf
                                                                                        <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                                                                        <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                                        <button class="btn btn-block btn-info"
                                                                                                href="#">@lang('labels.frontend.course.get_now') <i
                                                                                                    class="fas fa-caret-right"></i></button>
                                                                                    </form>
                                                                            @else
                                                                                    <form action="{{ route('cart.addToCart') }}" method="POST">
                                                                                        @csrf
                                                                                        <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                                                                        <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                                        <button type="submit"
                                                                                                class="btn btn-block btn-info">
                                                                                            @lang('labels.frontend.course.add_to_cart') <i
                                                                                                    class="fa fa-shopping-bag"></i></button>
                                                                                    </form>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-2 " style="margin-left: -9%;">
                                                                        <a href="{{ route('courses.show', [$course->slug]) }}" class="btn btnWishList">
                                                                            <i class="far fa-bookmark"></i>
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                    </div> 

                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                        </div>

                      </div>
           @else
           <div class="col-9">
                @if($chapters->count() > 0)
                <div class="row">
                    @foreach($chapters as $course)

                        <div class="col-4" >
                            <div class="best-course-pic-text relative-position">
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
                                <div class="card-body">
                                    <h3 class="card-title titleofcard">{{$course->title}}</h3>
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="course-rate ul-li">
                                                    <ul>
                                                        @for($i=1; $i<=(int)$course->rating; $i++)
                                                            <li><i class="fas fa-star"></i></li>
                                                        @endfor
                                                    </ul>
                                                </div>
                                                    <span class="ml-1  rate">0</span>
                                            </div>
                                        </div>
                                        <div class="course-meta my-1 vv">
                                            {{-- <span class="course-category">
                                                <a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a>
                                            </span>
                                            <span class="course-author"><a href="#">{{ $course->students()->count() }}
                                                    @lang('labels.frontend.course.students')</a>
                                            </span>
                                            <span class="course-author">
                                                    {{ $course->lessons()->count() }} @lang('labels.backend.courses.lessons')
                                            </span> --}}

                                            <small> <i class="far fa-clock"></i>  {{ $course->course_hours }} hours |</small><small> <i class="fab fa-youtube"></i> {{ $course->chapters()->count() }} lecture </small>


                                        </div>
                                        <div class="row my-2">
                                                <div class="col-3">
                                                    @foreach($course->teachers as $key=>$teacher)
                                                    @php $key++ @endphp
                                                        <img src="{{asset($teacher->avatar_location)}}"
                                                                class="rounded-circle">


                                                @endforeach
                                            </div>
                                            <div class="col-9">
                                                <div class="row">
                                                    @foreach($course->teachers as $key=>$teacher)
                                                        @php $key++ @endphp

                                                        <a class="col-12" href="{{route('teachers.show',['id'=>$teacher->id])}}" target="_blank">
                                                            {{$teacher->full_name}}@if($key < count($course->teachers )), @endif
                                                        </a>
                                                    @endforeach
                                                    @foreach($course->teachers as $key=>$teacher)
                                                        @php $key++ @endphp
                                                        <a class="col-12" href="{{route('teachers.show',['id'=>$teacher->id])}}" target="_blank">
                                                            @foreach($teacher_data as $data)
                                                            {{$data->description}}
                                                            @endforeach
                                                        </a>
                                        @endforeach
                                                    <!-- <div class="col-12 metatitle"></div>
                                                    <div class="col-12 metadescr"></div> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-10">
                                                @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                                    <button type="submit" class="btn  btn-info">   @lang('labels.frontend.course.add_to_cart')
                                                        <i class="fa fa-shopping-bag ml-1"></i>
                                                    </button>

                                                @elseif(!auth()->check())
                                                @if($course->free == 1)
                                                        <a id="openLoginModal" class="btn  btn-info" data-target="#myModal" href="#">@lang('labels.frontend.course.get_now')
                                                            <i class="fas fa-caret-right"></i>
                                                        </a>
                                                @else
                                                        <a id="openLoginModal"
                                                            class="btn  btn-info"
                                                            data-target="#myModal"
                                                            href="#">@lang('labels.frontend.course.add_to_cart')
                                                            <i class="fa fa-shopping-bag"></i>
                                                        </a>
                                                            @endif
                                                        @endif
                                                @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                                                    @if($course->free == 1)
                                                            <form action="{{ route('cart.getnow') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                                                <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                <button class="btn  btn-info"
                                                                        href="#">@lang('labels.frontend.course.get_now') <i
                                                                            class="fas fa-caret-right"></i></button>
                                                            </form>
                                                    @else
                                                            <form action="{{ route('cart.addToCart') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                                                <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                <button type="submit"
                                                                        class="btn  btn-info">
                                                                    @lang('labels.frontend.course.add_to_cart') <i
                                                                            class="fa fa-shopping-bag"></i></button>
                                                            </form>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="col-2 " style="margin-left: -9%;">
                                                <a href="{{ route('courses.show', [$course->slug]) }}" class="btn btnWishList">
                                                    <i class="far fa-bookmark"></i>
                                                </a>
                                </div>
                            </div>

                        </div>
                    </div>

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
                                    <select name="category" class="form-control listing-filter-form select">
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
                                    <input type="text" class="" name="q" placeholder="{{trans('labels.frontend.course.looking_for')}}">
                                </div>
                                <button class="genius-btn gradient-bg text-center text-uppercase btn-block text-white font-weight-bold"
                                        type="submit">@lang('labels.frontend.course.find_courses') <i
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
    <section>
        <div class="container">
            <div class="card mb-3 " >
                <div class="row no-gutters hei-sec">
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
                                                        @for($i=1; $i<=(int)$course->rating; $i++)
                                                            <li><i class="fas fa-star"></i></li>
                                                        @endfor
                                                    </ul>
                                                </div>
                                                    <span class="ml-1  rate">0</span>
                                            </div>
                                        </div>
                                        <div class="course-meta my-1 vv">
                                            <span class="course-category">
                                                <a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a>
                                            </span>
                                            <span class="course-author"><a href="#">{{ $course->students()->count() }}
                                                    @lang('labels.frontend.course.students')</a>
                                            </span>
                                            <span class="course-author">
                                                    {{ $course->lessons()->count() }} @lang('labels.backend.courses.lessons') 
                                            </span>
                                        </div>
                                        <div class="row my-2">
                                            <div class="col-3">
                                                <img src="../../assets/img/course/c-3.jpg"
                                                        class="rounded-circle">
                                            </div>
                                            <div class="col-9">
                                    <div class="row pt-2">
                                        <div class="col-12 x">{{$course->meta_title}}</div>
                                        <div class="col-12 y">{{$course->meta_description}}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                <div class="col-5">
                                    <a href="#" class="btn  btn-info">Add To Cart <i class="fa fa-shopping-bag"></i>
                                                        </a>
                                            </div>
                                    <div class="col-2 " style="margin-left:-3%">
                                        <a href="{{ route('courses.show', [$course->slug]) }}" class="btn" style="background: #D2498B;color:#fff;opacity:0.5;">
                                                    <i class="far fa-bookmark"></i>
                                                </a>
                                            </div>
                                        </div>
                                </div>
                            </div> 
                        </div>
            </div>
        </div>
    </section>

{{--    <section>--}}
{{--        <div class="container">--}}
{{--            <div class="card mb-3 " >--}}
{{--                <div class="row no-gutters hei-sec">--}}
{{--                    <div class="col-md-6 ">--}}
{{--                        <div class="best-course-pic relative-position ">--}}
{{--                                <div class="course-list-img-text course-page-sec">--}}
{{--                                        <div class="course-li-img"--}}
{{--                                        @if($course->course_image != "") style="background-image: url({{asset('storage/uploads/'.$course->course_image)}})" @endif >--}}
{{--                                   </div>--}}
{{--                                </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-md-6">--}}
{{--                        <div class="card-body noborder">--}}
{{--                            <h3 class=" mt-3 display-6">{{$course->title}}</h3>--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-12">--}}
{{--                                    <div class="course-rate ul-li">--}}
{{--                                        <ul>--}}
{{--                                            @for($i=1; $i<=(int)$course->rating; $i++)--}}
{{--                                                <li><i class="fas fa-star"></i></li>--}}
{{--                                            @endfor--}}
{{--                                        </ul>--}}
{{--                                    </div>--}}
{{--                                  <i class="fa fa-star"></i>--}}
{{--                                  <i class="fa fa-star"></i>--}}
{{--                                  <i class="fa fa-star"></i>--}}
{{--                                  <i class="fa fa-star"></i>--}}
{{--                                  <i class="fa fa-star"></i>--}}
{{--                                    <span class="ml-1  rate">4.4 (222)</span>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="course-meta my-2">--}}
{{--                                <span class="course-category">--}}
{{--                                    <a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a>--}}
{{--                                </span>--}}
{{--                                <span class="course-author"><a href="#">{{ $course->students()->count() }}--}}
{{--                                        @lang('labels.frontend.course.students')</a></span>--}}
{{--                                <span class="course-author">--}}
{{--                                        {{ $course->lessons()->count() }} Lessons--}}
{{--                                </span>--}}
{{--                            </div>--}}
{{--                            <div class="row my-3">--}}
{{--                                <div class="col-2">--}}
{{--                                    <img src="../../assets/img/course/c-3.jpg" class="rounded-circle">--}}
{{--                                </div>--}}
{{--                                <div class="col-9">--}}
{{--                                    <div class="row pt-2">--}}
{{--                                        <div class="col-12 x">{{$course->meta_title}}</div>--}}
{{--                                        <div class="col-12 y">{{$course->meta_description}}</div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <div class="row">--}}
{{--                                <div class="col-5">--}}
{{--                                    <a href="#" class="btn btn-block btn-info">Add To Cart <i class="fa fa-shopping-bag"></i>--}}
{{--                                    </a> --}}
{{--                                    </div>--}}
{{--                                    <div class="col-2 " style="margin-left:-3%">--}}
{{--                                        <a href="{{ route('courses.show', [$course->slug]) }}" class="btn" style="background: #D2498B;color:#fff;opacity:0.5;">--}}
{{--                                            <i class="far fa-bookmark"></i>--}}
{{--                                        </a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div> --}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </section>--}}

    <!-- End of course section
        ============================================= -->

 {{-- start myyy of course section --}}
 @if(@isset($category))
<section>
        <div class="container-fluid">
    <div class="row">
<div class="col-2">
nn
</div>
<div  class="col-9">
<div class="row">
        @if($courses->count() > 0)
       
        @foreach($courses as $course)
        
    <div  class="col-6">
            <div class="card " >
                    <div class="row no-gutters">
                        <div class="col-md-6 ">
                                <div class="best-course-pic relative-position ">
                                        <div class="course-list-img-text course-page-sec">
                                                <div class="course-l-img"
                                                @if($course->course_image != "") style="background-image: url({{asset('storage/uploads/'.$course->course_image)}})" @endif >
                                           </div>
                                        </div>
                                </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body" style="border: none">
                                <h3 class="display-6">{{$course->title}}</h3>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="course-rate ul-li">
                                            <ul>
                                                @for($i=1; $i<=(int)$course->rating; $i++)
                                                    <li><i class="fas fa-star"></i></li>
                                                @endfor
                                            </ul>
                                        </div>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <i class="fa fa-star"></i>
                                        <span class="ml-1  rate">4.4 (222)</span>
                                    </div>
                                </div>
                                <div class="course-meta ">
                                    <span class="course-category">
                                        <a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a>
                                    </span>
                                    <span class="course-author"><a href="#">{{ $course->students()->count() }}
                                            @lang('labels.frontend.course.students')</a></span>
                                    <span class="course-author">
                                            {{ $course->lessons()->count() }} Lessons
                                    </span>
                                </div>
                                <div class="row ">
                                    <div class="col-2">
                                        <img src="../../assets/img/course/c-3.jpg" class="rounded-circle">
                                    </div>
                                    <div class="col-9">
                                        <div class="row pt-2">
                                            @foreach($course->teachers as $teacher)
                                            <div class="col-12 x">{{$teacher->first_name}}</div>
                                            @endforeach
                                            <div class="col-12 y">{{$course->meta_description}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-7">
                                        <a href="#" class="btn btn-block" style="background: #52ADE1 ;color:#fff;">Add To Cart
                                            <i class="fa fa-shopping-bag"></i>
                                        </a> 
                                        </div>
                                        <div class="col-2 " style="margin-left:-9%">
                                            <a href="{{ route('courses.show', [$course->slug]) }}" class="btn" style="background: #D2498B;color:#fff;opacity:0.5;">
                                                <i class="fas fa-bookmark"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div> 
                        </div>
                    </div>
                </div>


    </div>
 
     @endforeach
                                        @else
                                            <h3>@lang('labels.general.no_data_available')</h3>
                                    @endif
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

