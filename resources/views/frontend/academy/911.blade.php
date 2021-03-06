@extends('frontend.layouts.app')
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
    @if($academy)
        <!-- Start of breadcrumb section
        ============================================= -->
        <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style  bgimage">

            <div class="blakish-overlay"></div>
            <div class="container d-none">
                <div class="col m-sm-5 m-5 m-xl-0 paragraph1 academy-info">

                    <div class="m-1">
                        <p> @lang('labels.frontend.layouts.partials.explore') / @lang('labels.frontend.home.academies')
                            / <b class="text-white">{{$academy->user->full_name}}</b></p>

                    </div>
                    <div class="p-1">
                        <h2 class="text-white"><b>{{$academy->user->full_name}}</b></h2>
                    </div>
                    <div class="row col-lg-3 flex">
                        @if($academy->facebook_link) <a href="{{$academy->facebook_link}}"
                                                        class="btn btn-sm btn-outline-light mr-1"><i
                                    class="fab fa-facebook-f"></i> </a> @endif
                        @if($academy->twitter_link)<a href="{{$academy->twitter_link}}"
                                                      class="btn btn-sm btn-outline-light mr-1"><i
                                    class="fab fa-twitter"></i> </a>@endif
                        @if($academy->linkedin_link)<a href="{{$academy->linkedin_link}}"
                                                       class=" btn btn-sm btn-outline-light mr-1"><i
                                    class="fab fa-linkedin"></i> </a>@endif

                    </div>

                    <div class="row mt-5 flex">
                        <div class="col-lg-2 col-xl-2">
                            <span class=" text-light font-weight-bold">Phone:</span> <span
                                    class="text-white font-weight-light">{{$academy->user->phone}}</span>
                        </div>
                        <div class="col-lg-3 col-xl-4">
                            <span class=" text-light font-weight-bold">Address:</span> <span
                                    class="text-white font-weight-light">{{$academy->adress}}</span>
                        </div>
                    </div>
                </div>
            </div>

        </section>
        <!-- End of breadcrumb section
            ============================================= -->

        <!-- End of academy logo section
            ============================================= -->
        <!-- Start of Teacher section
               ============================================= -->
        @if(session()->has('alert'))
            <div class="alert alert-light alert-dismissible fade my-alert show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>{{session('alert')}}</strong>
            </div>
        @endif
        @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible fade my-alert show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>{{session('success')}}</strong>
            </div>
        @endif
        <section id="course-teacher" class="course-teacher-section mt-5">
            <div class="container ">
                <div class=" mb20 headline pl-3">
                    <span class=" text-uppercase font-weight-lighter">@lang('labels.frontend.home.our_professionals')</span>
                    <h1 class="text-dark font-weight-bolder"><span>@lang('labels.frontend.home.Instructors').</span>
                    </h1>
                </div>
                @if(count($academyTeachers)> 0)
                    <div class="owl-carousel custom-owl-theme">
                        @foreach($academyTeachers as $teacher)
                            @if ($teacher->teacher)
                                <div class="item">
                                    <div class="text-center ">
                                        <div class="bg-card">
                                            <div>
                                                <div class="finger-img">
                                                    <img src="/assets/img/banner/01.png" alt="">
                                                </div>
                                                <div class="prof-img ">

                                                    <a href="{{route('teachers.show',['id'=>$teacher->teacher['id']])}}"><img
                                                                class="teacher-image shadow-lg p-3"
                                                                src="{{$teacher->teacher['picture']}}"
                                                                alt=""></a>
                                                </div>
                                            </div>
                                            <div class="teacher-social-name ul-li-block pt-3">
                                                <div class="teacher-name text-dark font-weight-bold">
                                                    <h5>{{$teacher->teacher['full_name']}}</h5>
                                                </div>
                                                <div class="teacher-title text-muted font-weight-light">
                                                    {{$teacher->getDataFromColumn('title')}}
                                                </div>
                                                <hr>
                                                <div class="teacher-name text-dark  justify-content-center">


                                                    <span>{{Illuminate\Support\Str::words($teacher->getDataFromColumn('description'),10,'...') }}</span>

                                                </div>
                                                <ul>
                                                    <li><a href="{{'mailto:'.$teacher->teacher['email']}}"><i
                                                                    class="fa fa-envelope"></i></a></li>
                                                    <li>
                                                        <a href="{{route('admin.messages',['teacher_id'=>$teacher->teacher['id']])}}">
                                                            <i class="fa fa-comments"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
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
                        <span>@lang('labels.frontend.academy.courses_by_teacher')</span>
                    </h1>
                </div>
                @if(count($courses) > 0)
                    <div class="col-md-12">
                        <div class="col-xl-12 courses-container">
                            <div class="owl-carousel default-owl-theme p-3 ">
                                @foreach($courses as $course)

                                    <div class="item">

                                        <div class="">
                                            @include('frontend.layouts.partials.coursesTemp')
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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
                    <h1 class="text-dark font-weight-bolder"><span>@lang('labels.frontend.academy.Gallery').</span>
                    </h1>
                </div>
                @if ($academy->gallery != null && $academy->gallery != 'null')
                    <div class="col-md-12">
                        <div class="gallery">

                        @foreach(json_decode($academy->gallery) as $key=>$image)
                            <!-- Grid column -->
                                <div class="mb-3 pics 2">
                                    <img class="img-fluid"
                                         src="{{asset($image)}}"
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
    @endif
@endsection

@push('after-scripts')
    <script>

    </script>
@endpush


