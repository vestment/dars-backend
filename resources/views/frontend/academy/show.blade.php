@extends('frontend.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.academy.title').' | '. app_name() )

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


        @media (max-width: 400px) {
            .btn.filter {
                padding-left: 1.1rem;
                padding-right: 1.1rem;
            }
        }
    </style>
@endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style bgcolor">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="col m-sm-5 m-5 m-xl-0 paragraph1">
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
                    @if($academy->academy->facebook_link) <a href="{{$academy->academy->facebook_link}}"
                                                        class="btn btn-sm btn-outline-light mr-1"><i
                                class="fab fa-facebook-f"></i> </a> @endif
                    @if($academy->academy->twitter_link)<a href="{{$academy->academy->twitter_link}}"
                                                      class="btn btn-sm btn-outline-light mr-1"><i
                                class="fab fa-twitter"></i> </a>@endif
                    @if($academy->academy->linkedin_link)<a href="{{$academy->academy->linkedin_link}}"
                                                       class=" btn btn-sm btn-outline-light mr-1"><i
                                class="fab fa-linkedin"></i> </a>@endif

                </div>

                <div class="row mt-5 flex">
                    <div class="col-lg-2 col-xl-2">
                        <span class=" text-light font-weight-bold">Phone:</span> <span
                                class="text-white font-weight-light">{{$academy->phone}}</span>
                    </div>
                    <div class="col-lg-3 col-xl-4">
                        <span class=" text-light font-weight-bold">Address:</span> <span
                                class="text-white font-weight-light">{{$academy->academy->adress}}</span>
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


            <div class="m-5 col-2 d-lg-flex d-lg-flex d-md-flex shadow-lg divfixed">
                <img class="academy-logo" src="{{asset($academy->academy->logo)}}" alt="{{$academy->full_name}}">
            </div>


        </div>
    </section>
    <!-- End of academy logo section
        ============================================= -->
    <!-- Start of Teacher section
           ============================================= -->

    <section id="course-teacher" class="course-teacher-section">
        <div class="container ">
            <div class=" mb20 headline pl-3">
                <span class=" text-uppercase font-weight-lighter">@lang('labels.frontend.home.our_professionals')</span>
                <h1 class="text-dark font-weight-bolder">{{env('APP_NAME')}} <span>@lang('labels.frontend.home.Instructors').</span>
                </h1>
            </div>
            @if(count($academyTeachers)> 0)
                <div class="owl-carousel custom-owl-theme">
                    @foreach($academyTeachers as $teacher)
                        <div class="item">
                            <div class="text-center ">
                                <div class="bg-card">
                                    <div>
                                        <div class="finger-img">
                                            <img src="/assets/img/banner/01.png" alt="">
                                        </div>
                                        <div class="prof-img ">
                                            <a href="{{route('teachers.show',['id'=>$teacher->teacher->id])}}"><img
                                                        class="teacher-image shadow-lg p-3"
                                                        src="{{asset($teacher->teacher->avatar_location)}}"
                                                        alt=""></a>
                                        </div>
                                    </div>
                                    <div class="teacher-social-name ul-li-block pt-3">
                                        <div class="teacher-name text-dark font-weight-bold">
                                            <h5>{{$teacher->teacher->full_name}}</h5>
                                        </div>
                                        <div class="teacher-title text-muted font-weight-light">
                                            {{$teacher->title}}
                                        </div>
                                        <hr>
                                        <div class="teacher-name text-dark  justify-content-center">
                                            <span>{{$teacher->description}}</span>
                                        </div>
                                        <ul>
                                            <li><a href="{{'mailto:'.$teacher->teacher->email}}"><i
                                                            class="fa fa-envelope"></i></a></li>
                                            <li>
                                                <a href="{{route('admin.messages',['teacher_id'=>$teacher->teacher->id])}}"><i
                                                            class="fa fa-comments"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-dark">
                    <span>@lang('labels.general.no_data_available')</span>
                </div>
            @endif
        </div>
    </section>
    <!-- end of Teachers section -->

    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class=" mb20 headline pl-3">
                <span class=" text-uppercase font-weight-lighter">@lang('labels.frontend.home.learn_new_skills')</span>
                <h1 class="text-dark font-weight-bolder">
                    <span>@lang('labels.frontend.academy.courses_by_teacher').</span>
                </h1>
            </div>
            @if(count($courses) > 0)
                <div class="col-md-12">
                    <div class="col-xl-12 categories-container border-bottom">
                        @foreach($categories as $key=>$category)
                            <button onclick="showTab($('#content-{{$category->id}}'),$(this))"
                                    class="tab-button btn @if ($key == 0) active @endif btn-light">{{$category->name}}</button>
                        @endforeach
                    </div>
                    <div class="col-xl-12 courses-container">
                        @foreach($categories as $key=>$category)
                            <div class="course-container fade in @if ($key == 0) show active @else hide @endif"
                                 id="content-{{$category->id}}" aria-labelledby="content-{{$category->id}}">
                                <div class="owl-carousel default-owl-theme p-3 ">
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
                                                            <div class="row">
                                                                <div class="col-xl-10 col-9">
                                                                    @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                                                        <button type="submit"
                                                                                class="btn btn-info btn-block btnAddCard">   @lang('labels.frontend.course.add_to_cart')
                                                                            <i class="fa fa-shopping-bag ml-1"></i>
                                                                        </button>

                                                                    @elseif(!auth()->check())
                                                                        @if($course->free == 1)
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

                                                                        @if($course->free == 1)
                                                                            <form action="{{ route('cart.getnow') }}"
                                                                                  method="POST">
                                                                                @csrf
                                                                                <input type="hidden" name="course_id"
                                                                                       value="{{ $course->id }}"/>
                                                                                <input type="hidden" name="amount"
                                                                                       value="{{($course->free == 1) ? 0 : $course->price}}"/>
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
                                                                                       value="{{ $course->id }}"/>
                                                                                <input type="hidden" name="amount"
                                                                                       value="{{($course->free == 1) ? 0 : $course->price}}"/>
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
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="alert alert-dark">
                    <span>@lang('labels.general.no_data_available')</span>
                </div>
            @endif
        </div>
    </section>


    <section class="gallery-section">
        <div class="container">
            <div class=" mb20 headline pl-3">
                <span class=" text-uppercase font-weight-lighter">@lang('labels.frontend.home.learn_new_skills')</span>
                <h1 class="text-dark font-weight-bolder">{{env('APP_NAME')}} <span>@lang('labels.frontend.academy.Gallery').</span>
                </h1>
            </div>
            @if ($academy->academy->gallery != null)
                <div class="col-md-12">
                    <div class="gallery">

                    @foreach(json_decode($academy->academy->gallery) as $key=>$image)
                        <!-- Grid column -->
                            <div class="mb-3 pics 2">
                                <img class="img-fluid"
                                     src="{{$image}}"
                                     alt="Image {{$key}}">
                            </div>
                            <!-- Grid column -->

                        @endforeach

                    </div>
                    <!-- Grid row -->
                </div>
            @else
                <div class="alert alert-dark">
                    <span>@lang('labels.general.no_data_available')</span>
                </div>
            @endif
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
            $(function () {
                var selectedClass = "";
                $(".filter").click(function () {
                    selectedClass = $(this).attr("data-rel");
                    $("#gallery").fadeTo(100, 0.1);
                    $("#gallery div").not("." + selectedClass).fadeOut().removeClass('animation');
                    setTimeout(function () {
                        $("." + selectedClass).fadeIn().addClass('animation');
                        $("#gallery").fadeTo(300, 1);
                    }, 300);
                });
            });
        });

    </script>
@endpush


