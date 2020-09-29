@extends('frontend.layouts.app')
@push('after-styles')
<link rel="stylesheet" href="../../assets/css/course.css"/>

    <style>
        .section-title-2 h2:after {
            background: #ffffff;
            bottom: 0px;
            position: relative;
        }

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

        ul.pagination {
            display: inline;
            text-align: center;
        }

        .bg-fr {
            color: white;

        }

        .containe {
            width: 80%;
            margin: auto
        }

        .teacher-img img {
            border-radius: 50%;
            height: 208px;
        }


    </style>
@endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="bg-header ">

        <div class="containe row ">
            <div class="col-md-8 col-sm-12">
                <div class="row ">
                    <div class="col-md-9">
                        <div class="teacher-details-content mb45">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="teacher-img text-center">
                                        <img class=" p-3" src="{{$teacher->picture}}" alt="">
                                    </div>
                                </div>
                                <div class="col-md-8 pt-5 pl-5">

                                    <h2 class="text-white "><span>{{$teacher->full_name}}</span></h2>
                                    <h5 class="text-white font-weight-lighter "><span>{{$teacher_data->title}}</span>
                                    </h5>

                                    <div class="">
                                        @if($teacher_data->type == 'academy')
                                        @if($academy)
                                            <h5 class="type">{{$academy->full_name}}</h5>
                                            @endif
                                        @else
                                            <h5 class="type">{{$teacher_data->type}}</h5>
                                          

                                        @endif
                                        <div class="address-details ul-li-block my-3">
                                            <ul class="d-inline w-100 ">
                                                <li class="d-inline w-100 ">
                                                    @if($teacher_data->twitter_link == null)
                                                    @else
                                                        <a href="{{$teacher_data->twitter_link}}"><i
                                                                    class="fab fa-twitter-square"></i></a>

                                                    @endif
                                                </li>
                                                <li class="d-inline w-100 text-white    ">
                                                    @if($teacher_data->facebook_link == null)
                                                    @else
                                                        <a href="{{$teacher_data->facebook_link}}"><i
                                                                    class="fab fa-facebook-square"></i></a>
                                                    @endif
                                                </li>
                                                <li class="d-inline w-100 text-white    ">
                                                    @if($teacher_data->linkedin_link == null)
                                                    @else
                                                        <a href="{{$teacher_data->linkedin_link}}"><i
                                                                    class="fab fa-linkedin"></i></a>
                                                    @endif
                                                </li>

                                            </ul>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>


                </div>
            </div>
            <div class="col-md-4 col-sm-6 bg-fr">
                <div class="row pb-5 text-center">
                    <div class=" col-6 offset-3 bord voticon p-3">
                        <span><i class="fas fa-graduation-cap"></i></span>
                        <h3>{{count($courses)}}</h3>
                        <h2 class="fon">@lang('labels.frontend.teacher.courses')</h2>

                    </div>


                </div>
            </div>


        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of teacher details area
        ============================================= -->
    <section id="teacher-details" class="teacher-details-area ">
        <div class="container">
            <h4 class="about">@lang('labels.frontend.teacher.about_me')</h4>
            <p>{{$teacher_data->getDataFromColumn('description')}} </p>


        </div>
    </section>
    <!-- End  of teacher details area
        ============================================= -->
    <section id="teacher-details" class="teacher-details-area ">
        <div class="containe ">
            <div class="about-teacher mb45">
                <div class="section-title-2  mb-0 headline text-left">
                    <h2>@lang('labels.frontend.teacher.courses_by_teacher')</h2>
                </div>
                @if(count($courses) > 0)
                    <div class="row">
                        @foreach($courses as $course)
                        @if($course->published == 1)

                            <div class="col-md-3">
                            @include('frontend.layouts.partials.coursesTemp')
                            </div>
                            @endif
                        @endforeach


                    </div>


                @else
                    <p>@lang('labels.general.no_data_available')</p>
                @endif
            </div>

        </div>
    </section>

@endsection
