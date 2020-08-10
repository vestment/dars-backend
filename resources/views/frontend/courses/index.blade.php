@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
<link rel="stylesheet" href="../../assets/css/course.css"/>
<!-- <style>
        .couse-pagination li.active {
            color: #333333 !important;
            font-weight: 700;
        }

        .page-link {
            position: relative;
            display: block;
            padding: .5rem .75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #c7c7c7;
            background-color: white;
            border: none;
        }

        .page-item.active .page-link {
            z-index: 1;
            color: #333333;
            background-color: white;
            border: none;

        }
     .listing-filter-form select{
            height:50px!important;
        }

        ul.pagination {
            display: inline;
            text-align: center;
        }
        /* .best-course-pic-text .best-course-pic {
            width: 100%;
        } */
       .titleofcard{
        text-align: left;
        font: Bold 15px Ubuntu;
        letter-spacing: -0.2px;
        color: #000000CC;
        margin-top: -36px;
        }
        
        .rate{
                text-align: left;
                font-size:0.9rem;
                letter-spacing: 0.01px;
                color: #00000099;
        }
        .styleicon{
            text-align: left;
        font: Regular 12px Open Sans;
        letter-spacing: 0px;
        color: #00000099;
        }
        .metatitle{
            text-align: left;
            font-size: 0.7rem;
        letter-spacing: 0px;
        color: #D2498B;
        }
        .metadescr{
            text-align: left;
            font-size: 0.7rem;
        letter-spacing: 0.01px;
        color: #00000099;
        }
        .course-author{
            font-size: 0.7rem;
        }
    .course-meta span {
        font-size: 71%;
        margin-right: 10%;
    }
    .piclip{
        clip-path: polygon(0 0, 100% 0, 100% 70%, 0 94%); width: 100%;
    }

    .gradient-bg{
        background: #D2498B 
    }
    .gradient-bg:hover{
        background: #D2498B 
    }
    .best-course-pic-text {
        background-image: url('../../assets/img/card/card.png');
        background-size:cover;
        padding-top: 0;
        margin-bottom: 10%;
        box-shadow: 2px 2px 10px #eee;
    }
    .btnAddCard{
        background: #52ADE1 ;
        color:#fff!important;
    }
    .btnWishList{
        background: #D2498B;
        color:#fff!important;
        opacity:0.5;
    }
</style> -->
@endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style">
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
    {{-- <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    @if(session()->has('success'))
                        <div class="alert alert-dismissable alert-success fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{session('success')}}
                        </div>
                    @endif
                    <div class="short-filter-tab">
                        <div class="shorting-filter w-50 d-inline float-left mr-3">
                            <span>@lang('labels.frontend.course.sort_by')</span>
                            <select id="sortBy" class="form-control d-inline w-50">
                                <option value="">@lang('labels.frontend.course.none')</option>
                                <option value="popular">@lang('labels.frontend.course.popular')</option>
                                <option value="trending">@lang('labels.frontend.course.trending')</option>
                                <option value="featured">@lang('labels.frontend.course.featured')</option>
                            </select>
                        </div>

                        <div class="tab-button blog-button ul-li text-center float-right">
                            <ul class="product-tab">
                                <li class="active" rel="tab1"><i class="fas fa-th"></i></li>
                                <li rel="tab2"><i class="fas fa-list"></i></li>
                            </ul>
                        </div>

                    </div>

                    <div class="genius-post-item">
                        <div class="tab-container">
                            <div id="tab1" class="tab-content-1 pt35">
                                <div class="best-course-area best-course-v2">
                                    <div class="row">

                                        @if(@isset($category))
                                            @if($popular_courses->count() > 0)
                                            @foreach($popular_courses as $course)
                                            <div class="col-lg-4 col-md-6" >
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
                                                                <img src="../../assets/img/course/Cat – 1/star.png">
                                                                <img src="../../assets/img/course/Cat – 1/star.png">
                                                                <img src="../../assets/img/course/Cat – 1/star.png">
                                                                <img src="../../assets/img/course/Cat – 1/star.png">
                                                                <img src="../../assets/img/course/Cat – 1/star.png">
                                                                <span class="ml-1  rate">0</span>
                                                            </div>
                                                        </div>
                                                        <div class="course-meta my-1 vv">
                                                            <span class="course-category">
                                                                <a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a>
                                                            </span>
                                                            <span class="course-author"><a href="#">{{ $course->students()->count() }}
                                                                    @lang('labels.frontend.course.students')</a></span>
                                                            <span class="course-author">
                                                                    {{ $course->lessons()->count() }} @lang('labels.backend.courses.lessons') 
                                                            </span>
                                                        </div>
                                                        <div class="row my-2">
                                                                <div class="col-3">
                                                                    <img src="../../assets/img/course/c-3.jpg" class="rounded-circle">
                                                                </div>
                                                                <div class="col-9">
                                                                    <div class="row">
                                                                    @foreach($course->teachers as $key=>$teacher)
                                                                        @php $key++ @endphp
                                                                        <a href="{{route('teachers.show',['id'=>$teacher->id])}}" target="_blank">
                                                                            {{$teacher->full_name}}@if($key < count($course->teachers )), @endif
                                                                        </a>
                                                                    @endforeach
                                                                    @foreach($course->teachers as $key=>$teacher)
                                                                        @php $key++ @endphp
                                                                        <a href="{{route('teachers.show',['id'=>$teacher->id])}}" target="_blank">
                                                                            {{$teacher->description}}
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
                                                                        <button type="submit" class="btn btn-block btnAddCard">   @lang('labels.frontend.course.add_to_cart') 
                                                                            <i class="fa fa-shopping-bag ml-1"></i>
                                                                        </button> 
                                                                    
                                                                @elseif(!auth()->check())
                                                                    @if($course->free == 1)
                                                                                <a id="openLoginModal"
                                                                                class="btn btn-block btnAddCard"
                                                                                data-target="#myModal" href="#">@lang('labels.frontend.course.get_now') <i
                                                                                            class="fas fa-caret-right"></i></a>
                                                                        @else
               
                                                                            <a id="openLoginModal"
                                                                                class="btn btn-block btnAddCard"
                                                                                data-target="#myModal" href="#">@lang('labels.frontend.course.add_to_cart') 
                                                                                <i class="fa fa-shopping-bag"></i>
                                                                            </a>
                                                                        @endif
                                                                @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                                                                    @if($course->free == 1)
                                                                            <form action="{{ route('cart.getnow') }}" method="POST">
                                                                                @csrf
                                                                                <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                                                                <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                                <button class="btn btn-block btnAddCard"
                                                                                        href="#">@lang('labels.frontend.course.get_now') <i
                                                                                            class="fas fa-caret-right"></i></button>
                                                                            </form>
                                                                    @else
                                                                            <form action="{{ route('cart.addToCart') }}" method="POST">
                                                                                @csrf
                                                                                <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                                                                <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                                <button type="submit"
                                                                                        class="btn btn-block btnAddCard">
                                                                                    @lang('labels.frontend.course.add_to_cart') <i
                                                                                            class="fa fa-shopping-bag"></i></button>
                                                                            </form>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="col-2 " style="margin-left: -10%;">
                                                                <a href="{{ route('courses.show', [$course->slug]) }}" class="btn btnWishList">
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
                                    @endif
                                      
                                        @else    
                                        @if($courses->count() > 0)
                                            @foreach($courses as $course)
                                                <div class="col-lg-4 col-md-6" >
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
                                                                <img src="../../assets/img/course/Cat – 1/star.png">
                                                                <img src="../../assets/img/course/Cat – 1/star.png">
                                                                <img src="../../assets/img/course/Cat – 1/star.png">
                                                                <img src="../../assets/img/course/Cat – 1/star.png">
                                                                <img src="../../assets/img/course/Cat – 1/star.png">
                                                                <span class="ml-1  rate">0</span>
                                                            </div>
                                                        </div>
                                                        <div class="course-meta my-1 vv">
                                                            <span class="course-category">
                                                                <a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a>
                                                            </span>
                                                            <span class="course-author"><a href="#">{{ $course->students()->count() }}
                                                                    @lang('labels.frontend.course.students')</a></span>
                                                            <span class="course-author">
                                                                    {{ $course->lessons()->count() }} @lang('labels.backend.courses.lessons') 
                                                            </span>
                                                        </div>
                                                        <div class="row my-2">
                                                                <div class="col-3">
                                                                    <img src="../../assets/img/course/c-3.jpg" class="rounded-circle">
                                                                </div>
                                                                <div class="col-9">
                                                                    <div class="row">
                                                                    @foreach($course->teachers as $key=>$teacher)
                                                                        @php $key++ @endphp
                                                                        <a href="{{route('teachers.show',['id'=>$teacher->id])}}" target="_blank">
                                                                            {{$teacher->full_name}}@if($key < count($course->teachers )), @endif
                                                                        </a>
                                                                    @endforeach
                                                                    @foreach($course->teachers as $key=>$teacher)
                                                                        @php $key++ @endphp
                                                                        <a href="{{route('teachers.show',['id'=>$teacher->id])}}" target="_blank">
                                                                            {{$teacher->description}}
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
                                                                        <button type="submit" class="btn btn-block btnAddCard">   @lang('labels.frontend.course.add_to_cart') 
                                                                            <i class="fa fa-shopping-bag ml-1"></i>
                                                                        </button> 
                                                                    
                                                                @elseif(!auth()->check())
                                                                    @if($course->free == 1)
                                                                                <a id="openLoginModal"
                                                                                class="btn btn-block btnAddCard"
                                                                                data-target="#myModal" href="#">@lang('labels.frontend.course.get_now') <i
                                                                                            class="fas fa-caret-right"></i></a>
                                                                        @else
               
                                                                            <a id="openLoginModal"
                                                                                class="btn btn-block btnAddCard"
                                                                                data-target="#myModal" href="#">@lang('labels.frontend.course.add_to_cart') 
                                                                                <i class="fa fa-shopping-bag"></i>
                                                                            </a>
                                                                        @endif
                                                                @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                                                                    @if($course->free == 1)
                                                                            <form action="{{ route('cart.getnow') }}" method="POST">
                                                                                @csrf
                                                                                <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                                                                <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                                <button class="btn btn-block btnAddCard"
                                                                                        href="#">@lang('labels.frontend.course.get_now') <i
                                                                                            class="fas fa-caret-right"></i></button>
                                                                            </form>
                                                                    @else
                                                                            <form action="{{ route('cart.addToCart') }}" method="POST">
                                                                                @csrf
                                                                                <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                                                                <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                                <button type="submit"
                                                                                        class="btn btn-block btnAddCard">
                                                                                    @lang('labels.frontend.course.add_to_cart') <i
                                                                                            class="fa fa-shopping-bag"></i></button>
                                                                            </form>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="col-2 " style="margin-left: -10%;">
                                                                <a href="{{ route('courses.show', [$course->slug]) }}" class="btn btnWishList">
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
                                    @endif

                                    <!-- /course -->

                                    </div>
                                </div>
                            </div><!-- /tab-1 -->

                            <div id="tab2" class="tab-content-1">
                                <div class="course-list-view">
                                    <table>
                                        <tr class="list-head">
                                            <th>@lang('labels.frontend.course.course_name')</th>
                                            <th>@lang('labels.frontend.course.course_type')</th>
                                               <th>@lang('labels.frontend.course.starts')</th>
                                        </tr>
                                        @if($courses->count() > 0)
                                            @foreach($courses as $course)

                                                <tr>
                                                    <td>
                                                        <div class="course-list-img-text">
                                                            <div class="course-list-img"
                                                                 @if($course->course_image != "") style="background-image: url({{asset('storage/uploads/'.$course->course_image)}})" @endif >
                                                            </div>
                                                            <div class="course-list-text">
                                                                <h3>
                                                                    <a href="{{ route('courses.show', [$course->slug]) }}">{{$course->title}}</a>
                                                                </h3>
                                                                <div class="course-meta">
                                                                <span class="course-category bold-font"><a
                                                                            href="{{ route('courses.show', [$course->slug]) }}">
                                                                        @if($course->free == 1)
                                                                            {{trans('labels.backend.courses.fields.free')}}
                                                                        @else
                                                                            {{$appCurrency['symbol'].' '.$course->price}}
                                                                        @endif
                                                                    </a></span>

                                                                    <div class="course-rate ul-li">
                                                                        <ul>
                                                                            @for($i=1; $i<=(int)$course->rating; $i++)
                                                                                <li><i class="fas fa-star"></i></li>
                                                                            @endfor
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="course-type-list">
                                                            <span><a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a></span>
                                                        </div>
                                                    </td>
                                                    <td>{{\Carbon\Carbon::parse($course->start_date)->format('d M Y')}}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">
                                                    <h3>@lang('labels.general.no_data_available')</h3>

                                                </td>
                                            </tr>
                                        @endif

                                    </table>
                                </div>
                            </div><!-- /tab-2 -->
                        </div>
                        <div class="couse-pagination text-center ul-li">
                            {{ $courses->links() }}
                        </div>
                    </div>


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
            </div>
        </div>
    </section> --}}
    {{-- <section>
        <div class="container">
            <div class="card mb-3 " >
                <div class="row no-gutters">
                    <div class="col-md-6 ">
                        <div class="best-course-pic relative-position">
                            <img src="../../assets/img/course/bc-8.jpg" class="card-img" alt="...">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card-body">
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
                                    <img src="../../assets/img/course/Cat – 1/star.svg">
                                    <img src="../../assets/img/course/Cat – 1/star.svg">
                                    <img src="../../assets/img/course/Cat – 1/star.svg">
                                    <img src="../../assets/img/course/Cat – 1/star.svg">
                                    <img src="../../assets/img/course/Cat – 1/star.svg">
                                    <span class="ml-1  rate">4.4 (222)</span>
                                </div>
                            </div>
                            <div class="course-meta my-2">
                                <span class="course-category">
                                    <a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a>
                                </span>
                                <span class="course-author"><a href="#">{{ $course->students()->count() }}
                                        @lang('labels.frontend.course.students')</a></span>
                                <span class="course-author">
                                        {{ $course->lessons()->count() }} Lessons
                                </span>
                            </div>
                            <div class="row my-3">
                                <div class="col-2">
                                    <img src="../../assets/img/course/c-3.jpg" class="rounded-circle">
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
                                    <a href="#" class="btn btn-block" style="background: #52ADE1 ;color:#fff;">Add To Cart 
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bag-check" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M14 5H2v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5zM1 4v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4H1z"/>
                                        <path d="M8 1.5A2.5 2.5 0 0 0 5.5 4h-1a3.5 3.5 0 1 1 7 0h-1A2.5 2.5 0 0 0 8 1.5z"/>
                                        <path fill-rule="evenodd" d="M10.854 7.646a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 10.293l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                    </svg>
                                    </a> 
                                    </div>
                                    <div class="col-2 " style="margin-left:-3%">
                                        <a href="{{ route('courses.show', [$course->slug]) }}" class="btn" style="background: #D2498B;color:#fff;opacity:0.5;">
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bookmark" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M8 12l5 3V3a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12l5-3zm-4 1.234l4-2.4 4 2.4V3a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v10.234z"/>
                                                </svg>
                                        </a>
                                    </div>
                                </div>
                            </div> 
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    <!-- End of course section
        ============================================= -->

 {{-- start myyy of course section --}}
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
                            <div class="best-course-pic relative-position">
                                <img src="../../assets/img/course/bc-8.jpg" class="card-img" alt="...">
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
                                        <img src="../../assets/img/course/Cat – 1/star.svg">
                                        <img src="../../assets/img/course/Cat – 1/star.svg">
                                        <img src="../../assets/img/course/Cat – 1/star.svg">
                                        <img src="../../assets/img/course/Cat – 1/star.svg">
                                        <img src="../../assets/img/course/Cat – 1/star.svg">
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
                                            <div class="col-12 x">{{$course->meta_title}}</div>
                                            <div class="col-12 y">{{$course->meta_description}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-7">
                                        <a href="#" class="btn btn-block" style="background: #52ADE1 ;color:#fff;">Add To Cart 
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bag-check" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M14 5H2v9a1 1 0 0 0 1 1h10a1 1 0 0 0 1-1V5zM1 4v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4H1z"/>
                                            <path d="M8 1.5A2.5 2.5 0 0 0 5.5 4h-1a3.5 3.5 0 1 1 7 0h-1A2.5 2.5 0 0 0 8 1.5z"/>
                                            <path fill-rule="evenodd" d="M10.854 7.646a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 10.293l2.646-2.647a.5.5 0 0 1 .708 0z"/>
                                        </svg>
                                        </a> 
                                        </div>
                                        <div class="col-2 " style="margin-left:-9%">
                                            <a href="{{ route('courses.show', [$course->slug]) }}" class="btn" style="background: #D2498B;color:#fff;opacity:0.5;">
                                            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bookmark" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M8 12l5 3V3a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12l5-3zm-4 1.234l4-2.4 4 2.4V3a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v10.234z"/>
                                                    </svg>
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

 {{-- end myyy of course section --}}
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