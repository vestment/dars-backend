@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
    <link rel="stylesheet" href="../../assets/css/course.css"/>
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
                    <p>Explore / Academy / <b class="text-white">{{$academy->full_name}}</b></p>
                </div>
                <div class="p-1">
                    <h2 class="text-white"><b>{{$academy->full_name}}</b></h2>
                </div>

                <div class="p-1">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>

                    <span class="text-white">0</span>
                </div>


                <div class="row col-lg-3 flex">
                    <a href="{{$academyData->facebook_link}}" class="btn btn-sm btn-outline-light mr-1"><i
                                class="fab fa-facebook-f"></i> </a>
                    <a href="{{$academyData->twitter_link}}" class="btn btn-sm btn-outline-light mr-1"><i
                                class="fab fa-twitter"></i> </a>
                    <a href="{{$academyData->linkedin_link}}" class=" btn btn-sm btn-outline-light mr-1"><i
                                class="fab fa-linkedin"></i> </a>
                    {{--                    <a href="{{$academyData->instegram_link}}" class="btn btn-sm btn-outline-light mr-1"><i class="fab fa-instagram"></i> </a>--}}

                </div>

                <div class="row mt-5 flex">
                    <div class="col-lg-2 col-xl-2">
                        <span class=" text-light font-weight-bold">Phone:</span> <span
                                class="text-white font-weight-light">{{$academy->phone}}</span>
                    </div>
                    <div class="col-lg-3 col-xl-4">
                        <span class=" text-light font-weight-bold">Address:</span> <span
                                class="text-white font-weight-light">{{$academyData->adress}}</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of academy logo section
        ============================================= -->
    <section id="course-page" class="course-page-section">
        <div class="container">


            <div class="m-5 col-2 d-flex shadow-lg divfixed">
                <div class="teacher-img text-center">
                    <img class="academy-logo" src="{{$academyData->logo}}" alt="{{$academy->full_name}}">
                </div>
            </div>


        </div>
    </section>
    <?php
    $courses = [];
    ?>
    <!-- End of academy logo section
        ============================================= -->
    <!-- Start of Teacher section
           ============================================= -->
    <section id="course-teacher" class="course-teacher-section p-5">
        <div class="">
            <div class="container ">
                <div class=" mb20 headline p-5 mb-5">
                    <span class=" text-uppercase font-weight-lighter">@lang('labels.frontend.home.our_professionals')</span>
                    <h1 class="text-dark font-weight-bolder">{{env('APP_NAME')}} <span>@lang('labels.frontend.home.Instructors').</span>
                    </h1>
                </div>

                @if(count($academyData->teachers)> 0)
                    <div class="owl-carousel custom-owl-theme">

                        @foreach($academyData->teachers as $teacher)
                            <?php
                            $teacherInfo = \App\Models\Auth\User::role('teacher')->with('courses')->where('id', $teacher->user_id)->get()[0];
                            //                            dd($teacherInfo);
                            //                            $courses = $teacherInfo->courses;
                            foreach ($teacherInfo->courses as $teacherCourse) {
                                array_push($courses, $teacherCourse);
                            }
                            ?>
                            <div class="item">
                                <div class="text-center ">
                                    <div class="bg-card">
                                        <div>
                                            <div class="finger-img">
                                                <img src="/assets/img/banner/01.png" alt="">
                                            </div>
                                            <div class="prof-img ">
                                                <img class="teacher-image p-3"
                                                     src="{{asset($teacherInfo->avatar_location)}}"
                                                     alt="">
                                            </div>
                                        </div>
                                        <div class="teacher-social-name ul-li-block pt-3">
                                            <div class="teacher-name text-dark font-weight-bold">
                                                <h5>{{$teacher->title}}.{{$teacherInfo->full_name}}</h5>
                                            </div>
                                            <hr>
                                            <div class="teacher-name text-dark  justify-content-center">
                                                <span>{{$teacher->description}}</span>
                                            </div>
                                            <ul>
                                                <li><a href="{{'mailto:'.$teacherInfo->email}}"><i
                                                                class="fa fa-envelope"></i></a></li>
                                                <li>
                                                    <a href="{{route('admin.messages',['teacher_id'=>$teacherInfo->id])}}"><i
                                                                class="fa fa-comments"></i></a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- end of Teachers section -->

    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs custom-tabs" id="myTab" role="tablist">
                        @foreach($categories as $key=>$category)
                            <li class="nav-item navv" role="presentation">
                                <a class=" nav-link navl @if ($key == 0) active @endif" id="{{$category->id}}"
                                   data-toggle="tab" href="#content-{{$category->id}}" role="tab"
                                   aria-controls="{{$category->id}}" aria-selected="true">{{$category->name}}</a>
                            </li>

                        @endforeach
                    </ul>
                    <div class="tab-content">
                        @foreach($categories as $key=>$category)
                            <div class="tab-pane fade in @if ($key == 0) show active @endif"
                                 id="content-{{$category->id}}" aria-labelledby="content-{{$category->id}}">
                                <div class="owl-carousel default-owl-theme p-3 ">
                                    @if(count($courses) > 0)

                                        @foreach($courses as $course)
                                            @if($course->category_id == $category->id)
                                                <div class="item">

                                                    <div class="">
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
                                                                        <i class="fa fa-star text-warning"></i>
                                                                        <i class="fa fa-star text-warning"></i>
                                                                        <i class="fa fa-star text-warning"></i>
                                                                        <i class="fa fa-star text-warning"></i>
                                                                        <i class="fa fa-star text-warning"></i>
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
                                                                        <img src="../../assets/img/course/c-3.jpg"
                                                                             class="rounded-circle">
                                                                    </div>
                                                                    <div class="col-9">
                                                                        <div class="row">
                                                                            @foreach($course->teachers as $key=>$teacher)
                                                                                @php $key++ @endphp
                                                                                <a href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                                                                   target="_blank">
                                                                                    {{$teacher->full_name}}@if($key < count($course->teachers ))
                                                                                        , @endif
                                                                                </a>
                                                                            @endforeach
                                                                            @foreach($course->teachers as $key=>$teacher)
                                                                                @php $key++ @endphp
                                                                                <a href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                                                                   target="_blank">
                                                                                    {{$teacher->description}}
                                                                                </a>
                                                                            @endforeach

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="row justify-content-around">
                                                                    <div class="">
                                                                        @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                                                            <button type="submit"
                                                                                    class="btn btn-info btnAddCard">   @lang('labels.frontend.course.add_to_cart')
                                                                                <i class="fa fa-shopping-bag ml-1"></i>
                                                                            </button>

                                                                        @elseif(!auth()->check())
                                                                            @if($course->free == 1)
                                                                                <a id="openLoginModal"
                                                                                   class="btn btn-info btnAddCard"
                                                                                   data-target="#myModal"
                                                                                   href="#"><span
                                                                                            class="d-lg-inline-block d-sm-none">@lang('labels.frontend.course.get_now') </span><i
                                                                                            class="fas fa-caret-right"></i></a>
                                                                            @else

                                                                                <a id="openLoginModal"
                                                                                   class="btn btn-info btnAddCard w-100"
                                                                                   data-target="#myModal"
                                                                                   href="#"><span
                                                                                            class="d-lg-inline-block d-sm-none">@lang('labels.frontend.course.add_to_cart')</span>
                                                                                    <i class="fa fa-shopping-bag"></i>
                                                                                </a>
                                                                            @endif
                                                                        @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                                                                            @if($course->free == 1)
                                                                                <form action="{{ route('cart.getnow') }}"
                                                                                      method="POST">
                                                                                    @csrf
                                                                                    <input type="hidden"
                                                                                           name="course_id"
                                                                                           value="{{ $course->id }}"/>
                                                                                    <input type="hidden" name="amount"
                                                                                           value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                                    <button class="btn btn-info btnAddCard w-100"
                                                                                            href="#"><span
                                                                                                class="d-lg-inline-block d-sm-none">@lang('labels.frontend.course.get_now')</span>
                                                                                        <i
                                                                                                class="fas fa-caret-right"></i>
                                                                                    </button>
                                                                                </form>
                                                                            @else
                                                                                <form action="{{ route('cart.addToCart') }}"
                                                                                      method="POST">
                                                                                    @csrf
                                                                                    <input type="hidden"
                                                                                           name="course_id"
                                                                                           value="{{ $course->id }}"/>
                                                                                    <input type="hidden" name="amount"
                                                                                           value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                                    <button type="submit"
                                                                                            class="btn btn-info btnAddCard w-100">
                                                                                        <span class="d-lg-inline-block d-sm-none"> @lang('labels.frontend.course.add_to_cart')</span>
                                                                                        <i
                                                                                                class="fa fa-shopping-bag"></i>
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
                                            @endif
                                        @endforeach

                                    @else
                                        <h3>@lang('labels.general.no_data_available')</h3>
                                    @endif

                                </div>


                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


    </section>

@endsection

@push('after-scripts')
    <script>
        $(document).ready(function () {
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
            })
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


