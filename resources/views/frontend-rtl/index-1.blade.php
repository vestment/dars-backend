@extends('frontend-rtl.layouts.app'.config('theme_layout'))

@section('title', trans('labels.frontend.home.title').' | '.app_name())
@section('meta_description', '')
@section('meta_keywords','')


@push('after-styles')
    <style>
        /*.address-details.ul-li-block{*/
        /*line-height: 60px;*/
        /*}*/
        .teacher-img-content .teacher-social-name{
            max-width: 67px;
        }
        .my-alert{
            position: absolute;
            z-index: 10;
            left: 0;
            right: 0;
            top: 25%;
            width: 50%;
            margin: auto;
            display: inline-block;
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

    <!-- Start of slider section
          ============================================= -->
    @include('frontend-rtl.layouts.partials.slider')
    <!-- End of slider section
          ============================================= -->

    @if($sections->search_section->status == 1)

    <section id="search-course" class=" border">
            <div class="container">
                <div class="row ">
                    <div class="col-12">
                        <div class="row">
                            <div class="hero-section">
                                <ul class="nav justify-content-center">
                                    <li class="nav-item"><span class="icon"><img
                                                    src="assets/img/banner/260d37c0-84ad-4627-9667-26030c180189 (1).png"
                                                    alt=""> </span><span class="text">Expert Teachers</span>
                                    </li>
                                    <li class="nav-item"><span class="icon"><img src="assets/img/banner/55.png" alt=""></span><span
                                                class="text">Learn Anywhere</span>
                                    </li>
                                    <li class="nav-item"><span class="icon"><img
                                                    src="assets/img/banner/dfeferf9 (1).png" alt=""></span><span
                                                class="text">Earn a certificate or degree</span>
                                    </li>

                                    <li class="nav-item"><span class="icon"><img src="assets/img/banner/fdfvds.png"
                                                                                 alt=""></span><span class="text">Learn the latest skills</span>
                                    </li>
                                    <li class="nav-item"><span class="icon"><img src="assets/img/banner/fdfvds.png"
                                                                                 alt=""></span><span class="text">Booking center online or offline</span>
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
        <section id="why-choose-us" class="why-choose-us-section">
            <div class="jarallax  backgroud-style">
                <div class="container">
                    @if($sections->reasons->status == 1)

                        <div class="section-title mb20 headline text-center ">
                            <span class="subtitle text-uppercase">{{env('APP_NAME')}} @lang('labels.frontend.layouts.partials.advantages')</span>
                            <h2>@lang('labels.frontend.layouts.partials.why_choose') <span>{{app_name()}}</span></h2>
                        </div>
                        @if($reasons->count() > 0)
                            <div id="service-slide-item" class="service-slide">
                                @foreach($reasons as $item)
                                    <div class="service-text-icon ">

                                        <div class="service-icon float-left">
                                            <i class="text-gradiant {{$item->icon}}"></i>
                                        </div>
                                        <div class="service-text">
                                            <h3 class="bold-font">{{$item->title}}</h3>
                                            <p>{{$item->content}}.</p>
                                        </div>
                                    </div>

                                @endforeach

                            </div>
                        @endif
                    @endif
                <!-- /service-slide -->
                    @if($sections->testimonial->status == 1)
                        <div class="testimonial-slide">
                            <div class="section-title-2 mb65 headline text-left ">
                                <h2>@lang('labels.frontend.layouts.partials.students_testimonial')</h2>
                            </div>
                            @if($testimonials->count() > 0)
                                <div id="testimonial-slide-item" class="testimonial-slide-area">
                                    @foreach($testimonials as $item)
                                        <div class="student-qoute ">
                                            <p>{{$item->content}}</p>
                                            <div class="student-name-designation">
                                                <span class="st-name bold-font">{{$item->name}} </span>
                                                <span class="st-designation">{{$item->occupation}}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <h4>@lang('labels.general.no_data_available')</h4>
                            @endif
                        </div>
                    @endif
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
        @include('frontend.layouts.partials.browse_courses')
        <!-- End of best course
            ============================================= -->
    @endif


   




    @if($sections->course_by_category->status == 1)
        <!-- Start Course category
        ============================================= -->
        @include('frontend-rtl.layouts.partials.course_by_category')
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
                    <h1> .مركز الحجز دون اتصال بالإنترنت</h1>
                    <p class="">نرى بأنفسنا كل يوم كيف تجعل التكنولوجيا المستحيل ممكناً. هذا هو السبب
                         مفهوم التعددية واحد موجود: لتعزيز مهمتنا في إضفاء الطابع الديمقراطي على مهارات التكنولوجيا ،
                         يتحدى
                         افتراضات حول الحلول وخلق تأثير اجتماعي كبير ودائم.</p>
                </div>
                <div class="pl-5 pb-5">
                    <button class="btn btn-outline-info ">View Plans</button>
                </div>
            </div>
        </div>
    </section>

    @if($sections->teachers->status == 1)
        <!-- Start of course teacher
        ============================================= -->
        <section id="course-teacher" class="course-teacher-section p-5">
            <div class="">
                <div class="container ">
                    <div class=" section-title mb20 headline p-5 mb-5">
                        <span class=" subtitle text-uppercase font-weight-lighter">@lang('labels.frontend.home.our_professionals')</span>
                        <h2 class="text-dark font-weight-bolder "><span>{{env('APP_NAME')}} @lang('labels.frontend.home.Instructors').<span>
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
                                                            <h5>{{$item->ar_first_name}}{{" "}}{{$item->ar_last_name}}</h5>
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
                    <h1 class="text-dark font-weight-bolder "><span>@lang('labels.frontend.home.academies').</span>
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

