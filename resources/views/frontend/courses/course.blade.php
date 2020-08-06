@extends('frontend.layouts.app'.config('theme_layout'))
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
     .listing-filter-form select{
            height:50px!important;
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
    <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="col m-5 p-3 paragraph1">
                <div class="m-3">    
                    <p >Explore / Business / <span style="color: white">An Entire MBA in 1 Course:Award Winning Business School Prof </span></p>
                </div>
                <div class="p-3">
                    <h2 class="text-white"><b>{{$course->title}}</b></h2>
                </div>

                <div class="p-3">            
                    <img src="img/frontend/course/urn_aaid_sc_US_260d37c0-84ad-4627-9667-26030c180189 (1).png">
                    <img src="img/frontend/course/urn_aaid_sc_US_260d37c0-84ad-4627-9667-26030c180189 (1).png">
                    <img src="img/frontend/course/urn_aaid_sc_US_260d37c0-84ad-4627-9667-26030c180189 (1).png">
                    <img src="img/frontend/course/urn_aaid_sc_US_260d37c0-84ad-4627-9667-26030c180189 (1).png">
                    <img src="img/frontend/course/urn_aaid_sc_US_260d37c0-84ad-4627-9667-26030c180189 (5).png">
                    <span class="text-white">{{$course_rating}}</span>
                </div>



                
                <div class="row col-6 flex">
                    <div class="row col-lg-2  flex">


                    <li> @lang('labels.frontend.course.author') <span>

@foreach($course->teachers as $key=>$teacher)
    @php $key++ @endphp
    <a href="{{route('teachers.show',['id'=>$teacher->id])}}" target="_blank">
        {{$teacher->full_name}}@if($key < count($course->teachers )), @endif
    </a>
@endforeach

</span>
</li>



                            <img src="img/frontend/course/bc-1.jpg" style=" height:100%; width:50%; border-radius:50%;">
                    </div>                   
                    <div class="row">
                  
                        <button type="submit" class="btn btn-outline-light m-1"> <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                        @lang('labels.frontend.course.add_to_cart')
                        </button>
                        <button type="button" class="btn btn-outline-light m-1"> <i class="fa fa-bookmark" aria-hidden="true"></i> Wishlist</button>
                        <button type="button" class="btn btn-outline-light m-1"> <i class="fa fa-share-alt" aria-hidden="true"></i> Share</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of course section
        ============================================= -->
    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="row col-lg-8 col-sm-12 coursesec d-block m-2">
                    <h2 > What you will learn</h2>
                <div class="row subtitle2">
                    <div class="col-lg-6 col-sm-12">
                        <p> <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Over 350,000 students in 195 countries!</p>
                        <p> <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
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
                        <p> <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Superb reviews!</p>
                        <p>  <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Get any job the easy way.</p>
                        <p>  <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Raise a lot of money quickly.</p>
                        <p>  <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Analyze company financials with ease!</p>
                        <p>  <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Over 350,000 students in 195 countries!</p>
                        <p>  <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Over 350,000 students in 195 countries!</p>
                        <p>  <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Change careers easily.</p>
                    </div>
                </div>
            </div>
            <div class="row  coursesec d-block m-3">
                <h2 > Requirements</h2>
            </div>
            <div class="row d-block m-3">
                <p > <i class="fa fa-angle-down p-2" aria-hidden="true"></i>
                    Nothing except a positive attitude!</p>
            </div>

            
            <div class="col-4 m-5 p-3 shadow-lg divfixed">
            <div class="col divpoly">
                    <h3>hello</h3>
                </div>
                <div class="col">
                    <h3 class="font49">
                                     @if($course->free == 1)
                                        <span> {{trans('labels.backend.courses.fields.free')}}</span>
                                        @else
                                        <span>   {{$appCurrency['symbol'].' '.$course->price}}</span>
                                        @endif</h3>
                    <h6 class="font20">This course includes: </h6>
                    <p class="smpara"> <i class="fa fa-play-circle" aria-hidden="true"></i> 8 hours on-demand video</p>
                    <p class="smpara"> <i class="fa fa-file" aria-hidden="true"></i> 32 articles</p>
                    <p class="smpara"> <i class="fa fa-download" aria-hidden="true"></i> 65 downloadable resources</p>
                    <p class="smpara"> <i class="fa fa-film" aria-hidden="true"></i> Access on mobile and TV</p>
                    <p class="smpara"> <i class="fa fa-certificate" aria-hidden="true"></i> Certificate of completion</p>

                    <button type="button" class="btn btncolor btn-sm btn-block text-white"><i class="fa fa-shopping-bag" aria-hidden="true"></i> Add To Cart</button>

                </div>
            </div>


        </div>
    </section>
    <!-- End of course content section
        ============================================= -->


 <!-- Start of course section
        ============================================= -->
<section id="course-page" class="course-page-section">
    <div class="container">
        <div class="row  coursecontent d-block m-2">
            <h2> Course content </h2>
        </div>
        <div class="row smpara d-block m-2">
            <p>16 sections • 83 lectures • 8h 0m total length</p>
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
</section>
 <!-- end of course content section -->



<section id="course-page" class="course-page-section">
    <div class="container">
        <div class="row  coursecontent d-block m-2">
            <h2> Instructor </h2>
        </div>
        <div class="row m-2">
            <div class="col-lg-2 col-md-2 col-sm-3">
                <img src="{{asset('img/backend/brand/logo.png')}}" alt="logo">
            </div>
            <div class="col-lg-3 col-md-5 col-sm-3">
                <p style="font-size:30px;">Mohsen hassan </p>
                <p> Founder and President of Montreal Trading Group </p>
            </div>
        </div>
        <div class="row m-2">
            <p> Chris has sold more than 1,000,000 of his online business & self improvement courses 
                in 12 languages in 196 countries and his courses have been profiled in Business Insider, NBC, Inc, Forbes,
                CNN, Entrepreneur & on other business news websites. Chris is the author of the #1 best selling online 
                business course called "An Entire MBA in 1 Course®” & many other courses.</p> 
                
            <p> He’s the author of the book "101 Crucial Lessons They Don't Teach You in Business School®,"​ which 
            Business Insider wrote is "the most popular book of 2016."​ Forbes called this book "1 of 6 books that all 
            entrepreneurs must read right now. </p>
            
            <p>"​He is the founder & CEO of Haroun Education Ventures, an award winning business school professor, 
                MBA graduate from Columbia University & former Goldman Sachs employee. He has raised/managed over $1bn 
                in his career.</p>
        </div>
     
    </div>
</section>


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