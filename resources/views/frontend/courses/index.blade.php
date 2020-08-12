@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
<link rel="stylesheet" href="../../assets/css/course.css"/>

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
                <div class="col-md-12">
                    <div class="genius-post-item">
                        <div class="tab-container">
                            <div id="tab1" class="tab-content-1 pt35">
                                <div class="best-course-area best-course-v2">
                                    <div class="row">
                                        @if(@isset($category))
                                        <div class="owl-carousel owl-theme">
                                            @if($popular_course->count() > 0)
                                                @foreach($popular_course as $course)
                                                    <div class="col-lg-4 col-md-6 item" >
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
                                                                                <img src="../../assets/img/course/Cat – 1/star.svg">
                                                                                <img src="../../assets/img/course/Cat – 1/star.svg">
                                                                                <img src="../../assets/img/course/Cat – 1/star.svg">
                                                                                <img src="../../assets/img/course/Cat – 1/star.svg">
                                                                                <img src="../../assets/img/course/Cat – 1/star.svg">
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
                                                                                    <a id="openLoginModal" class="btn btn-block btnAddCard" data-target="#myModal" href="#">@lang('labels.frontend.course.get_now')
                                                                                        <i class="fas fa-caret-right"></i>
                                                                                    </a>
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
                                        @endif
                                    </div>
                                </div>
                            </div><!-- /tab-2 -->
                        </div>
                    </div>
                </div>             
            </div>
        </div>
    </section> --}}
    <section id="course-page" class="course-page-section">
       
        {{-- <div class="row">
            <div class="col-md-12">
                <div class="genius-post-item">
                    <div class="tab-container">
                        <div id="tab1" class="tab-content-1 pt35">
                            <div class="best-course-area best-course-v2"> --}}
                                <div class="row">
                                    @if(@isset($category))
                                    <div class="owl-carousel owl-theme">
                                        @if($popular_course->count() > 0)
                                            @foreach($popular_course as $course)
                                                <div class="item col-8" >
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
                                                                            <img src="../../assets/img/course/Cat – 1/star.svg">
                                                                            <img src="../../assets/img/course/Cat – 1/star.svg">
                                                                            <img src="../../assets/img/course/Cat – 1/star.svg">
                                                                            <img src="../../assets/img/course/Cat – 1/star.svg">
                                                                            <img src="../../assets/img/course/Cat – 1/star.svg">
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
                                                                                <a id="openLoginModal" class="btn btn-block btnAddCard" data-target="#myModal" href="#">@lang('labels.frontend.course.get_now')
                                                                                    <i class="fas fa-caret-right"></i>
                                                                                </a>
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
                                    @endif
                                </div>
                            {{-- </div>
                        </div><!-- /tab-2 -->
                    </div>
                </div>
            </div>             
        </div> --}}
    
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
    </section>

    <!-- End of course section
        ============================================= -->

        
 {{-- start myyy of course section  --}}
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
            
                @foreach($cour as $course)
                
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
@endif



{{-- <section>
  
    <div class="owl-carousel owl-theme">
            @if($popular_course->count() > 0)
            @foreach($popular_course as $course)
                                            
            <div class="col-lg-4 col-md-6 item" >
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
                                <img src="../../assets/img/course/Cat – 1/star.svg">
                                <img src="../../assets/img/course/Cat – 1/star.svg">
                                <img src="../../assets/img/course/Cat – 1/star.svg">
                                <img src="../../assets/img/course/Cat – 1/star.svg">
                                <img src="../../assets/img/course/Cat – 1/star.svg">
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
                                                            class="fas fa-caret-right"></div></a>
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
        @endif
    </div>
</section> --}}



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

$(document).ready(function(){
    $(".owl-carousel").owlCarousel({

        loop:true,
        margin:10,
        nav:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            },
            1000:{
                items:5
            }
        }
}) 
});
</script>
