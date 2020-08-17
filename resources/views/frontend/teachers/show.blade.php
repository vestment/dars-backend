@extends('frontend.layouts.app')
@push('after-styles')
    <style>
        .section-title-2 h2:after {
            background: #ffffff;
            bottom: 0px;
            position: relative;
        }
         .couse-pagination li.active {
             color: #333333!important;
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
            background-color:white;
            border:none;

        }
        ul.pagination{
            display: inline;
            text-align: center;
        }
        .bg-fr{
            color:white;

        }
        .containe{
            width:80%;
            margin:auto
        }
        .teacher-img img{
            border-radius: 50%;
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
                                        <div class="teacher-img text-center" >
                                        @if($teacher->avatar_location == "")
                                                            <img class="teacher-image p-3" src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg"
                                                                 alt="">
                                                        @else
                                                                <img class="teacher-image p-3" src="{{asset($teacher->avatar_location)}}"
                                                                 alt="">
                                                        @endif

                                         

                                        </div>
                                    </div>
                                    <div class="col-md-8 pt-5 pl-5">
                                            <div class="">
                                                <h5 class="type">{{$teacher_data->type}} </h5>


                                                <h2 class="text-white ">{{$teacher->first_name}} <span>{{$teacher->last_name}}</span></h2>
                                                <span class="text-white bold">{{$teacher->email}}</span>
 
                                                <div class="address-details ul-li-block my-3">
                                                    <ul class="d-inline w-100 ">
                                                        <li class="d-inline w-100 ">
                                                        <a href="{{$teacher_data->twitter_link}}"><i class="fab fa-twitter-square"></i></a>
                                                        </li>
                                                        <li class="d-inline w-100 text-white    ">
                                                        <a href="{{$teacher_data->facebook_link}}"><i class="fab fa-facebook-square"></i></a>
                                                            
                                                        </li>
                                                        <li class="d-inline w-100 text-white    ">
                                                        <a href="{{$teacher_data->linkedin_link}}"><i class="fab fa-linkedin"></i></a>
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
                <div  class="col-md-4 col-sm-6 bg-fr">
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
        <div class="containe ">
            <h4 class="about">About Me</h4>
            <p>{{$teacher_data->description}} </p>

                   

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
                                        @foreach($courses as $item)
                                            <div class="col-md-4">
                                                <div class="best-course-pic-text relative-position ">
                                                    <div class="best-course-pic relative-position"
                                                        @if($item->course_image) style="background-image:url({{asset('storage/uploads/'.$item->course_image)}}) " @endif >

                                                        @if($item->trending == 1)
                                                            <div class="trend-badge-2 text-center text-uppercase">
                                                                <i class="fas fa-bolt"></i>
                                                                <span>@lang('labels.frontend.badges.trending')</span>
                                                            </div>
                                                        @endif
                                                            @if($item->free == 1)
                                                                <div class="trend-badge-3 text-center text-uppercase">
                                                                    <i class="fas fa-bolt"></i>
                                                                    <span>@lang('labels.backend.courses.fields.free')</span>
                                                                </div>
                                                            @endif
                                                        <div class="course-price text-center gradient-bg">
                                                            @if($item->free == 1)
                                                                <span> {{trans('labels.backend.courses.fields.free')}}</span>
                                                            @else
                                                            <span>{{$appCurrency['symbol'].' '.$item->price}}</span>
                                                            @endif
                                                        </div>
                                                        <div class="course-rate ul-li">
                                                            <ul>
                                                                @for($i=1; $i<=(int)$item->rating; $i++)
                                                                    <li><i class="fas fa-star"></i></li>
                                                                @endfor
                                                            </ul>
                                                        </div>
                                                        <div class="course-details-btn">
                                                            <a class="text-uppercase"
                                                            href="{{ route('courses.show', [$item->slug]) }}">@lang('labels.frontend.teacher.course_detail')
                                                                <i
                                                                        class="fas fa-arrow-right"></i></a>
                                                        </div>
                                                        <div class="blakish-overlay"></div>
                                                    </div>
                                                    <div class="best-course-text">
                                                        <div class="course-title mb20 headline relative-position">
                                                            <h3>
                                                                <a href="{{ route('courses.show', [$item->slug]) }}">{{$item->title}}</a>
                                                            </h3>
                                                        </div>
                                                        <div class="course-meta">
                                                    <span class="course-category"><a
                                                                href="#">{{$item->category->name}}</a></span>
                                                            <span class="course-author">
                                                        <a href="#">
                                                            {{ $item->students()->count() }}
                                                            @lang('labels.frontend.teacher.students')</a>
                                                    </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach


                                    </div>
                                    <div class="couse-pagination text-center ul-li">
                                        {{ $courses->links() }}
                                    </div>

                                @else
                                    <p>@lang('labels.general.no_data_available')</p>
                                @endif
                            </div>

        </div>
    </section>

@endsection
