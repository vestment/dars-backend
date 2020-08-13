@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
    <link rel="stylesheet" href="../../assets/css/course.css"/>

@endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="breadcrumb-section relative-position pb-5 backgroud-style bg-header-cat">
        <div class="blakish-overlay"></div>
        <div class="container" style="width:85%;">
            <div class="page-breadcrumb-content text-center">
                <div class=row>
                <div class="page-breadcrumb-title text-left col-6">
                    <h2 class="breadcrumb-head black bold">
                        <span >@if(isset($category)) {{$category->name}} @else @lang('labels.frontend.course.courses') @endif </span>
                    </h2>
                    <p>
                            Courses to get you started
                    </p>
                </div>
                <div class="col-6 ">
                        <img class=" menna" src="/assets/img/Learn Online.svg">

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
                                                <div class="card-body back-im p-3">
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
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <i class="fa fa-star"></i>
                                                                    <span class="ml-1  rate">4.4 (222)</span>
                                                                </div>
                                                    </div>
                                                    <div class="course-meta my-1 vv">
                                                            <small> <i class="far fa-clock"></i> {{ $course->course_hours }}
                                                                hours |</small><small> <i
                                                                        class="fab fa-youtube"></i> {{ $course->chapters()->count() }}
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
                                                            <div class="col-4">
                                                                    @foreach($course->teachers as $key=>$teacher)
                                                                        @php $key++ @endphp
                                                                        {{-- <img src="{{asset($teacher->avatar_location)}}"
                                                                             class="rounded-circle teach_img"> --}}
            
                                                                             @if($teacher->avatar_location == "")
                                                                             <img class="rounded-circle teach_img" src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg"
                                                                                  alt="">
                                                                         @else
                                                                                 <img class="rounded-circle teach_img" src="{{$teacher->avatar_location}}"
                                                                                  alt="">
                                                                         @endif
                                                                    @endforeach
                                                                </div>
                                                            <div class="col-8">
                                                                <div class="row">
                                                                    @foreach($course->teachers as $key=>$teacher)
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
                                                                            @foreach($teacher_data as $data)
                                                                            @if($data->user_id == $teacher->id)
                                                                                {{$data->description}}
                                                                                @endif
                                                                            @endforeach
                                                                        </a>
                                                                @endforeach
                                                                <!-- <div class="col-12 metatitle"></div>
                                                            <div class="col-12 metadescr"></div> -->
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <div class="row justify-content-around">
                                                        @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                                            <button type="submit"
                                                                    class="btn btn-info w-100">   @lang('labels.frontend.course.add_to_cart')
                                                                <i class="fa fa-shopping-bag ml-1"></i>
                                                            </button>

                                                        @elseif(!auth()->check())
                                                            @if($course->free == 1)
                                                                <a id="openLoginModal" class="btn  btn-info w-75"
                                                                   data-target="#myModal"
                                                                   href="#">@lang('labels.frontend.course.get_now')
                                                                    <i class="fas fa-caret-right"></i>
                                                                </a>
                                                            @else
                                                                <a id="openLoginModal"
                                                                   class="btn  btn-info w-75"
                                                                   data-target="#myModal"
                                                                   href="#">@lang('labels.frontend.course.add_to_cart')
                                                                    <i class="fa fa-shopping-bag"></i>
                                                                </a>
                                                            @endif
                                                        @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                                                            @if($course->free == 1)
                                                                <form action="{{ route('cart.getnow') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="course_id"
                                                                           value="{{ $course->id }}"/>
                                                                    <input type="hidden" name="amount"
                                                                           value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                    <button class="btn  btn-info w-75"
                                                                            href="#">@lang('labels.frontend.course.get_now')
                                                                        <i
                                                                                class="fas fa-caret-right"></i></button>
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
                                                                            class="btn  btn-info w-75">
                                                                        @lang('labels.frontend.course.add_to_cart') <i
                                                                                class="fa fa-shopping-bag"></i></button>
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
                                                            <div class="col-4">
                                                                    @foreach($course->teachers as $key=>$teacher)
                                                                        @php $key++ @endphp
                                                                        {{-- <img src="{{asset($teacher->avatar_location)}}"
                                                                             class="rounded-circle teach_img"> --}}
            
                                                                             @if($teacher->avatar_location == "")
                                                                             <img class="rounded-circle teach_img" src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg"
                                                                                  alt="">
                                                                         @else
                                                                                 <img class="rounded-circle teach_img" src="{{$teacher->avatar_location}}"
                                                                                  alt="">
                                                                         @endif
                                                                    @endforeach
                                                                </div>
                                                            <div class="col-8">
                                                                <div class="row">
                                                                    @foreach($course->teachers as $key=>$teacher)
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
                                                                            @foreach($teacher_data as $data)
                                                                            @if($data->id == $teacher->user_id )
                                                                                {{$data->description}}
                                                                                @endif
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
                                                                <button type="submit"
                                                                        class="btn btn-block btn-info">   @lang('labels.frontend.course.add_to_cart')
                                                                    <i class="fa fa-shopping-bag ml-1"></i>
                                                                </button>

                                                            @elseif(!auth()->check())
                                                                @if($course->free == 1)
                                                                    <a id="openLoginModal"
                                                                       class="btn btn-block btn-info"
                                                                       data-target="#myModal"
                                                                       href="#">@lang('labels.frontend.course.get_now')
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
                                                                    <form action="{{ route('cart.getnow') }}"
                                                                          method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="course_id"
                                                                               value="{{ $course->id }}"/>
                                                                        <input type="hidden" name="amount"
                                                                               value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                        <button class="btn btn-block btn-info"
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
                                                                                class="btn btn-block btn-info">
                                                                            @lang('labels.frontend.course.add_to_cart')
                                                                            <i
                                                                                    class="fa fa-shopping-bag"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="col-2 " style="margin-left: -9%;">
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
                                @endif
                            </div>
                        </div>

                    </div>
                @else
                    <div class="col-9">
                        @if($chapters->count() > 0)
                            <div class="row">
                                @foreach($chapters as $course)

                                    <div class="col-4">
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
                                                            {{-- <div class="avrg-rating ul-li">
                                                                    <b>@lang('labels.frontend.course.average_rating')</b>
                                                                    <span class="avrg-rate">{{$course_rating}}</span>
                                                                    <ul>
                                                                        @for($r=1; $r<=$course_rating; $r++)
                                                                            <li><i class="fas fa-star"></i></li>
                                                                        @endfor
                                                                        @for($r=1; $r<=5-$course_rating; $r++)
                                                                        <i class="fas fa-star"></i>
                                                                        @endfor
                            
                                                                    </ul>
                                                                    <b>{{$total_ratings}} @lang('labels.frontend.course.ratings')</b>
                                                                </div> --}}
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

                                                    <small> <i class="far fa-clock"></i> {{ $course->course_hours }}
                                                        hours |</small><small> <i
                                                                class="fab fa-youtube"></i> {{ $course->chapters()->count() }}
                                                        lecture </small>


                                                </div>
                                                {{-- <div  >
                                                        
                
                                                         
                
                                                        </div> --}}
                                                <div class="row my-2">
                                                    <div class="col-4">
                                                        @foreach($course->teachers as $key=>$teacher)
                                                            @php $key++ @endphp
                                                            {{-- <img src="{{asset($teacher->avatar_location)}}"
                                                                 class="rounded-circle teach_img"> --}}

                                                                 @if($teacher->avatar_location == "")
                                                                 <img class="rounded-circle teach_img" src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg"
                                                                      alt="">
                                                             @else
                                                                     <img class="rounded-circle teach_img" src="{{$teacher->avatar_location}}"
                                                                      alt="">
                                                             @endif
                                                        @endforeach
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="row">
                                                            @foreach($course->teachers as $key=>$teacher)
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
                                                    <div class="col-8">
                                                        @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                                            <button type="submit"
                                                                    class="btn  btn-info ">   @lang('labels.frontend.course.add_to_cart')
                                                                <i class="fa fa-shopping-bag ml-1"></i>
                                                            </button>

                                                        @elseif(!auth()->check())
                                                            @if($course->free == 1)
                                                                <a id="openLoginModal" class="btn  btn-info"
                                                                   data-target="#myModal"
                                                                   href="#">@lang('labels.frontend.course.get_now')
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
                                                        @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                                                            @if($course->free == 1)
                                                                <form action="{{ route('cart.getnow') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="course_id"
                                                                           value="{{ $course->id }}"/>
                                                                    <input type="hidden" name="amount"
                                                                           value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                    <button class="btn  btn-info"
                                                                            href="#">@lang('labels.frontend.course.get_now')
                                                                        <i
                                                                                class="fas fa-caret-right"></i></button>
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
                                                                            class="btn  btn-info">
                                                                        @lang('labels.frontend.course.add_to_cart') <i
                                                                                class="fa fa-shopping-bag"></i></button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    </div>
                                                    <div class="col-4" style="margin-left: -9%;">
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
                                            <input type="text" class="" name="q"
                                                   placeholder="{{trans('labels.frontend.course.looking_for')}}">
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
      
        <section class="course-page-section" >
        <div class="container" id="featured-courses">
                <div class="section-title mb20 headline mb-5">
                        
                        <h3 fa-rotate-180 class="text-dark font-weight-bolder "> <span>Featured courses</span>
                        </h3>
                    </div>
            <div class="owl-carousel custom-owl default-owl-theme">
                @if(count($featured_courses) > 0)
                    @foreach($featured_courses as $course)
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
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <i class="fa fa-star"></i>
                                                        <span class="ml-1  rate">4.4 (222)</span>
                                                    </div>
                                            
                                        </div>
                                        <div class="course-meta my-2">
                                                <small> <i class="far fa-clock"></i> {{ $course->course_hours }}
                                                    hours |</small><small> <i
                                                            class="fab fa-youtube"></i> {{ $course->chapters()->count() }}
                                                    lecture </small>
                                        </div>
                                        <div class="row my-3">
                                            <div class="col-2">
                                                    @foreach($course->teachers as $key=>$teacher)
                                                    @php $key++ @endphp
                                                    {{-- <img src="{{asset($teacher->avatar_location)}}"
                                                         class="rounded-circle teach_img"> --}}

                                                         @if($teacher->avatar_location == "")
                                                         <img class="rounded-circle teach_img" src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg"
                                                              alt="">
                                                     @else
                                                             <img class="rounded-circle teach_img" src="{{asset($teacher->avatar_location)}}"
                                                              alt="">
                                                     @endif
                                                @endforeach
                                            </div>
                                            <div class="col-9">
                                                <div class="row pt-2">
                                                        @foreach($course->teachers as $key=>$teacher)
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
                                                           @if($data->user_id == $teacher->id)
                                                           {{$data->description}}
                                                           @endif
                                                        </a>
                                                @endforeach
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
                    @endforeach
                @endif
            </div>
        </div>
    </section>
@endif
    <!-- End of course section
        ============================================= -->
        <section id="course-teacher" class="course-teacher-section p-5">
                <div class="">
                    <div class="container ">
                            <div class=" section-title mb20 headline p-5 mb-5">
                                    <span class=" subtitle text-uppercase font-weight-lighter">OUR PROFESSIONALS</span>
                                    <h4 class="text-dark font-weight-bolder "><span>Instructors.<span>
                                    </h4>
                                </div>
                        <div class="owl-carousel custom-owl-theme" data-items=5>
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
                                                                <a href="{{route('teachers.show',['id'=>$item->id])}}"><img class="teacher-image p-3 im" src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg"
                                                                     alt=""></a>
                                                            @else
                                                                     <a href="{{route('teachers.show',['id'=>$item->id])}}"><img class="teacher-image p-3 im" src="{{asset($item->avatar_location)}}"
                                                                     alt=""></a>
                                                            @endif
    
                                                            
                                                            </div>
                                                        </div>
                                                        <div class="teacher-social-name ul-li-block pt-3">
                                                            <div class="teacher-name text-dark font-weight-bold">
                                                                <h5>{{$teacher->title}}.{{$item->full_name}}</h5>
                                                            </div>
    
                                                            <hr>
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
    {{-- start myyy of course section --}}
    @if(@isset($category))
        <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-2">
                        nn
                    </div>
                    <div class="col-9">
                        <div class="row">
                            @if($courses->count() > 0)

                                @foreach($courses as $course)

                                    <div class="col-6">
                                        <div class="card ">
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
                                                                    @foreach($course->teachers as $key=>$teacher)
                                                                    @php $key++ @endphp
                                                                    {{-- <img src="{{asset($teacher->avatar_location)}}"
                                                                         class="rounded-circle teach_img"> --}}
        
                                                                         @if($teacher->avatar_location == "")
                                                                         <img class="rounded-circle teach_img" src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg"
                                                                              alt="">
                                                                     @else
                                                                             <img class="rounded-circle teach_img" src="{{$teacher->avatar_location}}"
                                                                              alt="">
                                                                     @endif
                                                                @endforeach
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
                                                                <a href="#" class="btn btn-block"
                                                                   style="background: #52ADE1 ;color:#fff;">Add To Cart
                                                                    <i class="fa fa-shopping-bag"></i>
                                                                </a>
                                                            </div>
                                                            <div class="col-2 " style="margin-left:-9%">
                                                                <a href="{{ route('courses.show', [$course->slug]) }}"
                                                                   class="btn"
                                                                   style="background: #D2498B;color:#fff;opacity:0.5;">
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
         $(window).on('load', function () {
           

        });
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
        $("#featured-courses .owl-carousel").owlCarousel({
                rewind: true,
                margin: 5,
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
                        items: 1
                    },
                    991: {
                        items:1
                    }
                }
            });

    </script>
@endpush

