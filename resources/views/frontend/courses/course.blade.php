@extends('frontend.layouts.app')
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
    <style>
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

        .listing-filter-form select {
            height: 50px !important;
        }

        ul.pagination {
            display: inline;
            text-align: center;
        }

        .divider {
            height: 2px;
        }

        .teacher-description {
            /*border-bottom: 1px solid;*/
            margin-top: 20px;
            margin-bottom: 20px;
            padding: 10px;
        }
    </style>
@endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style bgcolor">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="col m-5 p-3 paragraph1">
                <div class="m-1">
                    <p> @lang('labels.frontend.layouts.partials.explore')
                        / {{$course->category->getDataFromColumn('name')}} / <b
                                class="text-white">{{$course->getDataFromColumn('title')}}</b></p>
                </div>
                <div class="p-1">
                    <h2 class="text-white"><b>{{$course->getDataFromColumn('title')}}</b></h2>
                </div>

                <div class="p-1">
                    @for ($i=0; $i<5; ++$i)
                        <i class="fa{{($course_rating<=$i?'r':'s')}} fa-star{{($course_rating==$i+.5?'-half-alt':'')}} text-warning"
                           aria-hidden="true"></i>
                    @endfor

                    <span class="text-white">{{$course_rating}}</span>
                </div>


                <div class="row col-lg-5 col-sm-9 flex teacherdesc mt-2">
                    @foreach($course->teachers as $key=>$teacher)
                        @php
                            $teacherProfile = \App\Models\TeacherProfile::where('user_id',$teacher->id)->first();
                        @endphp
                        <img style="" class="rounded-circle" src=" {{asset($teacher->avatar_location)}}" alt="">
                        <div class="col-lg-5 col-sm-3 mt-3">
                            <p class="text-white font12">{{$teacher->full_name}}</p>
                            <p class="text-white font10">{{$teacherProfile->getDataFromColumn('description')}}</p>
                        </div>
                @endforeach

                <!-- @foreach($course->teachers as $key=>$teacher)
                    <img style="border-radius: 50%"  src=" {{asset($teacher->picture)}}" alt="">
                            @php $key++ @endphp
                            <p class="text-white mt-4 ml-2">   {{$teacher->full_name}}</p>@if($key < count($course->teachers )), @endif
                @endforeach -->

                </div>

                <div class="row mt-1 flex">

                    <div class="row col-lg-6 buttoncart">

                        @if (!$purchased_course)

                            @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                <button class="btn btn-outline-light  addcart"
                                        type="submit">@lang('labels.frontend.course.added_to_cart')
                                </button>
                            @elseif(!auth()->check())
                                @if($course->free == 1)
                                    <a href="{{route('login.index')}}" class="btn btn-outline-light addcart">
                                        @lang('labels.frontend.course.get_now')
                                        <i class="fas fa-caret-right"></i>
                                    </a>
                                @else

                                    <a href="{{route('login.index')}}" class="btn btn-outline-light addcart"> <i
                                                class="fa fa-shopping-bag" aria-hidden="true"></i>
                                        @lang('labels.frontend.course.add_to_cart')
                                    </a>
                                @endif

                            @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                                @if($course->free == 1)
                                    <form action="{{ route('cart.getnow') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                        <input type="hidden" name="amount"
                                               value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                        <button class="btn btn-outline-light addcart"
                                                href="#">@lang('labels.frontend.course.get_now') <i
                                                    class="fas fa-caret-right"></i></button>
                                    </form>
                                @else

                                    <form action="{{ route('cart.addToCart') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                        <input type="hidden" name="amount"
                                               value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                        <button type="submit" class="btn btn-outline-light addcart"><i
                                                    class="fa fa-shopping-bag" aria-hidden="true"></i>
                                            @lang('labels.frontend.course.add_to_cart')
                                        </button>
                                    </form>
                                @endif

                            @else
                                <h6 class="alert alert-danger"> @lang('labels.frontend.course.buy_note')</h6>
                            @endif
                            @else

                            @if($continue_course)
                                <a href="{{route('lessons.show',['id' => $course->id,'slug'=>$continue_course->model->slug])}}">
                                    <button class="btn btn-outline-light  addcart" type="submit">
                                        @lang('labels.frontend.course.continue_course')
                                        <i class="fa fa-arrow-right"></i>
                                    </button>
                                </a>
                                @else
                                <button class="btn btn-outline-light  addcart" type="submit">
                                    No lessons available
                                    <i class="fa fa-arrow-right"></i>
                                </button>
                            @endif
                        @endif



                        @if(auth()->check() && (auth()->user()->hasRole('student')))
                            @if(!auth()->user()->wishList->where('id',$course->id)->first())
                            <!-- wishlist -->
                                <form action="{{ route('wishlist.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                    <button type="submit" class="btn btn-outline-light ml-1 btn-sm"><i
                                                class="fa fa-heart"
                                                aria-hidden="true"></i>
                                        @lang('labels.frontend.course.wishlist')
                                    </button>
                                </form>
                            @else
                                <a href="{{route('wishlist.remove',['course'=>$course])}}"
                                   class="btn btn-outline-light ml-1"><i class="fa fa-times"></i>@lang('labels.frontend.course.remove')
                                </a>
                            @endif
                        @else
                            <a href="{{route('login.index')}}"
                               class="btn btn-outline-light ml-1"><i
                                        class="fa fa-heart"
                                        aria-hidden="true"></i> @lang('labels.frontend.course.wishlist')</a>
                        @endif
                        <button type="submit" class="btn btn-outline-light btn-sm ml-1"><i class="fa fa-share-alt"
                                                                                           aria-hidden="true"></i>
                            @lang('labels.frontend.course.Share')
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of what you will learn content section
        ============================================= -->
    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="row col-lg-8 col-sm-12 coursesec d-block m-2">
                <h2> What you will learn</h2>
                <div class="row subtitle2">
                    <div class="col-lg-6 col-sm-12">
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Over 350,000 students in 195 countries!</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Free $99 384 page book version of this course!</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Get many customers by using the best networking tool!</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Create financial models from scratch (the Professor makes it so easy to understand).</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Over 350,000 students in 195 countries!</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Understand how macro economics and micro economics works.</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Over 350,000 students in 195 countries!</p>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Superb reviews!</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Get any job the easy way.</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Raise a lot of money quickly.</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Analyze company financials with ease!</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Over 350,000 students in 195 countries!</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Over 350,000 students in 195 countries!</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Change careers easily.</p>
                    </div>
                </div>
            </div>
            <div class="row  coursesec d-block m-3">
                <h2>@lang('labels.frontend.course.requirements')</h2>
            </div>
            <div class="row m-3">
                @if(count($optional_courses) >0 || count($mandatory_courses) >0)
                    <div class="col-lg-6">
                        <p class="font-weight-bold text-dark">Optional Courses</p>

                        @foreach($optional_courses as $opt_course)
                            <a href="{{ route('courses.show', [$opt_course->slug]) }}"><p><i
                                            class="fa fa-angle-right p-2"
                                            aria-hidden="true"></i> {{$opt_course->getDataFromColumn('title')}}
                                </p></a>
                        @endforeach
                    </div>

                    <div class="col-lg-6">
                        <p class="font-weight-bold text-dark"> Mandatory Courses</p>
                        @foreach($mandatory_courses as $mand_course)
                            <a href="{{ route('courses.show', [$mand_course->slug]) }}"><p><i
                                            class="fa fa-angle-right p-2"
                                            aria-hidden="true"></i> {{$mand_course->getDataFromColumn('title')}}
                                </p></a>
                        @endforeach
                    </div>

                    <!-- <p > <i class="fa fa-angle-down p-2" aria-hidden="true"></i>
                                Nothing except a positive attitude!</p> -->
                    </ul>

                @else
                    <p><i class="fa fa-angle-down p-2" aria-hidden="true"></i>
                        Nothing except a positive attitude!</p>
                @endif
            </div>
            <!-- video modal -->
            <!--Modal: Name-->

            <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <!--Content-->
                    <!-- <div class="modal-content"> -->
                    <!--Body-->
                    <div class="modal-body mb-0 p-0 mt-5">
                    @if($course->mediaVideo && $course->mediavideo->count() > 0)
                        <!-- <div class="course-single-text"> -->
                            @if($course->mediavideo != "")
                                <div class="course-details-content">
                                    <div class="video-container mb-5" data-id="{{$course->mediavideo->id}}">
                                        @if($course->mediavideo->type == 'youtube')


                                            <div id="player" class="js-player embed-responsive embed-responsive-16by9"
                                                 data-plyr-provider="youtube"
                                                 data-plyr-embed-id="{{$course->mediavideo->file_name}}"></div>

                                        @elseif($course->mediavideo->type == 'vimeo')
                                            <div id="player" class="js-player" data-plyr-provider="vimeo"
                                                 data-plyr-embed-id="{{$course->mediavideo->file_name}}"></div>
                                        @elseif($course->mediavideo->type == 'upload')
                                            <video poster="" id="player" class="js-player" playsinline controls>
                                                <source src="{{$course->mediavideo->url}}" type="video/mp4"/>
                                            </video>
                                        @elseif($course->mediavideo->type == 'embed')
                                            {!! $course->mediavideo->url !!}
                                        @endif
                                    </div>
                                </div>
                            @endif
                    </div>
                    @else
                        <p class="text-center text-white display-4 mx-5 my-5">No Videos available </p>
                    @endif
                </div>
                <!-- </div> -->
                <!--/.Content-->
            </div>
        </div>
        <!--Modal: Name-->

        <!-- video -->
        <div class="col-4 m-5 shadow-lg divfixed paddingleft">
            <!-- Grid row -->
            <a>
                <div class="col divpoly justify-content-center d-flex" data-toggle="modal" data-target="#modal1"
                     @if($course->course_image != "")
                     style="background-image: url('{{asset('storage/uploads/'.$course->course_image)}}')" @endif>
                    <i class="far fa-play-circle iconimage"></i>

                </div>
            </a>

            <!-- Grid row -->
        {{-- <!-- @if($course->mediaVideo && $course->mediavideo->count() > 0)
                    <div class="course-single-text">
                        @if($course->mediavideo != "")
                            <div class="course-details-content">
                                <div class="video-container mb-5" data-id="{{$course->mediavideo->id}}">
                                    @if($course->mediavideo->type == 'youtube')


                                        <div id="player" class="js-player col divpoly embed-responsive embed-responsive-16by9" data-plyr-provider="youtube"
                                             data-plyr-embed-id="{{$course->mediavideo->file_name}}"></div>

                                    @elseif($course->mediavideo->type == 'vimeo')
                                        <div id="player" class="js-player" data-plyr-provider="vimeo"
                                             data-plyr-embed-id="{{$course->mediavideo->file_name}}"></div>
                                    @elseif($course->mediavideo->type == 'upload')
                                        <video poster="" id="player" class="js-player" playsinline controls>
                                            <source src="{{$course->mediavideo->url}}" type="video/mp4"/>
                                        </video>
                                    @elseif($course->mediavideo->type == 'embed')
                                        {!! $course->mediavideo->url !!}
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
        @endif --> --}}

        <!-- <div class="col divpoly embed-responsive embed-responsive-16by9">
                        <iframe  class="embed-responsive-item" src="https://www.youtube.com/embed/XHOmBV4js_E" allowfullscreen></iframe>
                </div> -->
            {{-- <h3>hello</h3> --}}
            <div class="col mr-3 pricebottom">
                <h3 class="font49">
                    @if($course->free == 1)
                        <span> {{trans('labels.backend.courses.fields.free')}}</span>
                    @else
                        <span>   {{$appCurrency['symbol'].' '.$course->price}}</span>
                    @endif</h3>
                <h6 class="font20">@lang('labels.frontend.course.This_course_includes') </h6>
                <p class="smpara"><i class="fa fa-play-circle"
                                     aria-hidden="true"></i> {{ $course->course_hours }} @lang('labels.frontend.course.hours')
                </p>
                <p class="smpara"><i class="fa fa-file" aria-hidden="true"></i>
                    <span>  {{$chaptercount}} </span> @lang('labels.frontend.course.chapters')</p>
                <p class="smpara"><i class="fa fa-download" aria-hidden="true"></i>


                    65 downloadable resources

                </p>
                <!-- <p class="smpara"> <i class="fa fa-film" aria-hidden="true"></i> Access on mobile and TV</p>
                <p class="smpara"> <i class="fa fa-certificate" aria-hidden="true"></i> Certificate of completion</p> -->

                @if (!$purchased_course)
                    @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                        <button class="btn btn-info btn-sm btn-block text-white"
                                type="submit">@lang('labels.frontend.course.added_to_cart')
                        </button>
                    @elseif(!auth()->check())
                        @if($course->free == 1)
                            <a class="btn btn-info btn-sm btn-block text-white"
                               href="{{route('login.index')}}">@lang('labels.frontend.course.get_now') <i
                                        class="fas fa-caret-right"></i></a>
                        @else

                            <a href="{{route('login.index')}}" class="btn btn-info btn-sm btn-block text-white"> <i
                                        class="fa fa-shopping-bag" aria-hidden="true"></i>
                                @lang('labels.frontend.course.add_to_cart')
                            </a>
                        @endif
                    @elseif(auth()->check() && (auth()->user()->hasRole('student')))
                        @if($course->free == 1)
                            <form action="{{ route('cart.getnow') }}" method="POST">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                <input type="hidden" name="amount"
                                       value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                <button class="btn btn-info btn-sm btn-block text-white"
                                        href="#">@lang('labels.frontend.course.get_now') <i
                                            class="fas fa-caret-right"></i></button>
                            </form>
                        @else
                            <form action="{{ route('cart.addToCart') }}" method="POST">
                                @csrf
                                <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                <input type="hidden" name="amount"
                                       value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                <button type="submit" class="btn btn-info btn-sm btn-block text-white"><i
                                            class="fa fa-shopping-bag" aria-hidden="true"></i>
                                    @lang('labels.frontend.course.add_to_cart')
                                </button>
                            </form>
                        @endif


                    @else
                        <h6 class="alert alert-danger"> @lang('labels.frontend.course.buy_note')</h6>
                    @endif
                @else

                    @if($continue_course)

                        <a href="{{route('lessons.show',['id' => $course->id,'slug'=>$continue_course->model->slug])}}"
                           class="btn btn-info btn-sm btn-block text-white">

                            @lang('labels.frontend.course.continue_course')

                            <i class="fa fa-arrow-right"></i></a>
                    @endif

                @endif
            </div>
        </div>
    </section>
    <!-- End of what you will learn  section
        ============================================= -->


    <!-- Start of course content section
        ============================================= -->
    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="row  coursecontent d-block m-2">
                <h2>@lang('labels.frontend.course.course_content') </h2>
            </div>
            <div class="row smpara d-block m-2">
                <p><span>  {{$chaptercount}} </span> @lang('labels.frontend.course.chapters') •
                    <span>  {{$lessoncount}} </span> @lang('labels.frontend.course.lessons')
                    • {{ $course->course_hours }} @lang('labels.frontend.course.hours')</p>
            </div>

            @foreach($chapters as $chapter)
                <div class="row m-2 shadow">
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left" type="button"
                                            data-toggle="collapse" data-target="#{{$chapter->id}}" aria-expanded="true"
                                            aria-controls="{{$chapter->id}}">
                                        {{ $chapter->title}} <i class="fa fa-angle-down float-right"
                                                                aria-hidden="true"></i>
                                    </button>
                                    @if($course->trending == 1)
                                        <span class="trend-badge text-uppercase bold-font"><i
                                                    class="fas fa-bolt"></i> @lang('labels.frontend.badges.trending')</span>
                                    @endif
                                </h2>
                            </div>

                            <div id="{{$chapter->id}}" class="collapse show" aria-labelledby="headingOne"
                                 data-parent="#accordionExample">
                                <div class="card-body">
                                    @foreach($lessons->get() as $key=>$item)
                                        @php $key++; @endphp
                                        <div class="bordered">
                                            @if($item->model && $item->model->published == 1)
                                                <p class="subtitle2">
                                                    <a href="{{route('lessons.show',['id' => $item->course->id,'slug'=>$item->model->slug])}}">
                                                @if($item->model->chapter_id == $chapter->id)
                                                    {{$item->model->title}}  {{$item->model->downloadableMedia}}
                                                @endif
                                                @if($item->model_type == 'App\Models\Test')
                                                    <p class="mb-0 text-primary">
                                                        - @lang('labels.frontend.course.test')</p>
                                                    @endif
                                                    </a>
                                                    </p>
                                                    <!-- <p class="play10"> <i class="fa fa-play-circle" aria-hidden="true"></i> 10 Min </p> -->
                                                @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </section>
    <!-- <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="row  coursecontent d-block m-2">
                {{-- <h2>@lang('labels.frontend.course.course_content') </h2> --}}
            </div>
            <div class="row smpara d-block m-2">
                {{-- <p></i> <span>  {{$course->chapterCount()}} </span>  @lang('labels.frontend.course.chapters') • --}}
    {{-- <span>  {{$course->chapterCount()}} </span>  @lang('labels.frontend.course.lessons') • 8h 0m total length</p> --}}
            </div>
            
            <div class="row m-2 shadow">
            

                <div class="accordion" id="accordionExample">
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h2 class="mb-0">
                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" >
                                Chapter 1 <i class="fa fa-angle-down float-right" aria-hidden="true"></i>
                            </button>
                            </h2>
                        </div>
                
                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                            <div class="card-body">
                                <div class="bordered">
                                    <p class="subtitle2"> Adding Value to Customers- Episode 1 </p>
                                    <p class="play10"> <i class="fa fa-play-circle" aria-hidden="true"></i> 10 Min </p>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="bordered">
                                    <p class="subtitle2"> Adding Value to Customers- Episode 1 </p>
                                    <p class="play10"> <i class="fa fa-play-circle" aria-hidden="true"></i> 10 Min </p>
                                </div>
                            </div>

                        </div>
                       
                    </div>

                    <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                            Chapter 2 <i class="fa fa-angle-down float-right" aria-hidden="true"></i>
                        </button>
                        </h2>
                    </div>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="bordered">
                                <p class="subtitle2"> Adding Value to Customers- Episode 1 </p>
                                <p class="play10"> <i class="fa fa-play-circle" aria-hidden="true"></i> 10 Min </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="bordered">
                                <p class="subtitle2"> Adding Value to Customers- Episode 1 </p>
                                <p class="play10"> <i class="fa fa-play-circle" aria-hidden="true"></i> 10 Min </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="bordered">
                                <p class="subtitle2"> Adding Value to Customers- Episode 1 </p>
                                <p class="play10"> <i class="fa fa-play-circle" aria-hidden="true"></i> 10 Min </p>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="bordered">
                                <p class="subtitle2"> Adding Value to Customers- Episode 1 </p>
                                <p class="play10"> <i class="fa fa-play-circle" aria-hidden="true"></i> 10 Min </p>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section> -->
    <!-- End of course content section
        ============================================= -->


    <!-- Start of Related Courses section
           ============================================= -->
    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="row  coursecontent d-block m-2">
                <h2>@lang('labels.frontend.course.related_courses') </h2>
            </div>
            <div class="row smpara d-block m-2">
                <p><span>  {{$chaptercount}} </span> @lang('labels.frontend.course.chapters') •
                    <span>  {{$lessoncount}} </span> @lang('labels.frontend.course.lessons')
                    • {{ $course->course_hours }} @lang('labels.frontend.course.hours')</p>
            </div>
            @foreach ($related_courses as $related_course)

                <div class="card col-12 col-md-6 col-lg-6 col-xl-6 mb-2">

                    <div class="row">
                        <div class="col-md-6 pl-0">
                            <div class="best-course-pic relative-position ">
                                <div class="course-list-img-text course-page-sec">

                                    <div class="col imgcard"
                                         @if($related_course->course_image != "")
                                         style="background-image: url('{{asset('storage/uploads/'.$related_course->course_image)}}')" @endif>
                                    </div>
                                <!-- <div class="col imgcard">
                                @if($related_course->course_image != "")
                                    <img src="{{asset('storage/uploads/'.$related_course->course_image)}}">
                                @endif
                                        </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card-body" style="border: none">
                                <h3 class=" font20">{{$related_course->getDataFromColumn('title')}}</h3>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="course-rate ul-li">
                                            <ul>
                                                @for($r=1; $r<=$related_course->reviews->avg('rating'); $r++)
                                                    <i class="fas fa-star" style="color: yellow; font-size:10px;"></i>
                                                @endfor
                                                @for($r=1; $r<=5-$related_course->reviews->avg('rating'); $r++)
                                                    <i class="fas fa-star" style="font-size:10px;"></i>
                                                @endfor
                                                <span class="text-white">{{$related_course->reviews->avg('rating')}}</span>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="course-meta ">
                            <span>
                            <i class="far fa-clock font12"></i> {{ $related_course->course_hours }} @lang('labels.frontend.course.hours')

                            </span>
                                    <span>
                            <i class="fa fa-play-circle font12" aria-hidden="true"></i> @lang('labels.frontend.course.lessons') 

                            </span>

                                </div>

                                <div class="row col-lg-12 flex teacherdesc mt-2">
                                    @foreach($related_course->teachers as $key=>$teacher)
                                        @php
                                            $teacherProfile = \App\Models\TeacherProfile::where('user_id',$teacher->id)->first();
                                        @endphp
                                        @php $key++ @endphp
                                        <img class="rounded-circle" src=" {{asset($teacher->picture)}}" alt="">
                                        @php $key++ @endphp
                                        <div class="col-lg-9 col-sm-3 mt-3">
                                            <p class="font12">{{$teacher->full_name}}</p>
                                            <p class="font10">{{$teacherProfile->description}}</p>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="row">
                                    <div class="col-xl-10 col-9">
                                        @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                            <button type="submit"
                                                    class="btn btn-info btn-block btnAddCard">   @lang('labels.frontend.course.add_to_cart')
                                                <i class="fa fa-shopping-bag ml-1"></i>
                                            </button>

                                        @elseif(!auth()->check())
                                            @if($related_course->free == 1)
                                                <a class="btn btn-info btn-block btnAddCard"
                                                   href="{{ route('login.index') }}">@lang('labels.frontend.course.get_now')
                                                    <i
                                                            class="fas fa-caret-right"></i></a>
                                            @else

                                                <a class="btn btn-info btnAddCard btn-block"
                                                   href="{{ route('login.index') }}">@lang('labels.frontend.course.add_to_cart')
                                                    <i class="fa fa-shopping-bag"></i>
                                                </a>
                                            @endif
                                        @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                                            @if($related_course->free == 1)
                                                <form action="{{ route('cart.getnow') }}"
                                                      method="POST">
                                                    @csrf
                                                    <input type="hidden" name="course_id"
                                                           value="{{ $related_course->id }}"/>
                                                    <input type="hidden" name="amount"
                                                           value="{{($related_course->free == 1) ? 0 : $related_course->price}}"/>
                                                    <button class="btn btn-info btnAddCard btn-block"
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
                                                           value="{{ $related_course->id }}"/>
                                                    <input type="hidden" name="amount"
                                                           value="{{($related_course->free == 1) ? 0 : $related_course->price}}"/>
                                                    <button type="submit"
                                                            class="btn btn-info btnAddCard btn-block">
                                                        @lang('labels.frontend.course.add_to_cart')
                                                        <i
                                                                class="fa fa-shopping-bag"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="">
                                        <a href="{{ route('courses.show', [$related_course->slug]) }}"
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


        </div>
    </section>

    <!-- End of Related Courses section
        ============================================= -->















    <!-- Start of course review section
        ============================================= -->
    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="course-review">
                <div class="section-title-2 mb20 headline text-left">
                    <h2>@lang('labels.frontend.course.course_reviews')</h2>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="ratting-preview">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="avrg-rating ul-li">
                                        <b>@lang('labels.frontend.course.average_rating')</b>
                                        <span class="avrg-rate">{{$course_rating}}</span>
                                        <ul>
                                            @for ($i=0; $i<5; ++$i)
                                                <i class="fa{{($course_rating<=$i?'r':'s')}} fa-star{{($course_rating==$i+.5?'-half-alt':'')}} text-warning"
                                                   aria-hidden="true"></i>
                                            @endfor

                                        </ul>
                                        <b>{{$total_ratings}} @lang('labels.frontend.course.ratings')</b>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="avrg-rating ul-li">
                                        <span><b>@lang('labels.frontend.course.details')</b></span>
                                        @for($r=5; $r>=1; $r--)
                                            <div class="rating-overview">
                                                <span class="start-item">{{$r}} @lang('labels.frontend.course.stars')</span>
                                                <span class="start-bar"></span>
                                                <span class="start-count">{{$course->reviews()->where('rating','=',$r)->get()->count()}}</span>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>
    <!-- End of course review section
        ============================================= -->

    <!-- Start of Instructor info review section
        ============================================= -->
    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="row  coursecontent d-block m-2 pb-2">
                <h2> @lang('labels.frontend.course.instructors') </h2>
            </div>

            @foreach($course->teachers as $key=>$teacher)
                @php
                    $teacherProfile = \App\Models\TeacherProfile::where('user_id',$teacher->id)->first();
                @endphp
                <div class="row" data-id="{{$teacher->id}}">

                    <div class="col-lg-1 col-md-2 col-sm-3">
                        <img style="max-width: 100px" class="rounded-circle" src=" {{asset($teacher->avatar_location)}}"
                             alt="">

                    <!-- {{-- <img src="{{asset('img/backend/brand/logo.png')}}" alt="logo"> --}} -->
                    </div>
                    <div class="col-lg-6 col-md-5 col-sm-3">
                        @php $key++ @endphp
                        <p style="font-size:30px;">{{$teacher->full_name}}</p>
                        <p class="teacher-title">{{$teacherProfile->getDataFromColumn('title')}}</p>
                        <hr class="ml-0 divider">

                    </div>
                    <div class="col-12 teacher-description">
                        <p>{{$teacherProfile->getDataFromColumn('description')}}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <!-- End of Instructor info review section
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

            $('#modal1').on('hidden.bs.modal', function (e) {
                // do something...
                $('#modal1 iframe').attr("src", $("#modal1 iframe").attr("src"));
            });
        });

    </script>
@endpush

