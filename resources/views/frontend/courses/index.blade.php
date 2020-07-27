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
            <div class="col m-5 paragraph1">
                <div class="m-3">    
                    <p >Explore / Business / <span style="color: white">An Entire MBA in 1 Course:Award Winning Business School Prof </span></p>
                </div>
                <div class="p-3">
                    <h2 class="text-white"><b> Award Winning Business School Prof</b></h2>
                </div>
                <div class="p-3">            
                    <img src="public/asstes/img/image course/urn_aaid_sc_US_260d37c0-84ad-4627-9667-26030c180189 (1).png">
                    <img src="public/asstes/img/image course/urn_aaid_sc_US_260d37c0-84ad-4627-9667-26030c180189 (1).png">
                    <img src="public/asstes/img/image course/urn_aaid_sc_US_260d37c0-84ad-4627-9667-26030c180189 (1).png">
                    <img src="public/asstes/img/image course/urn_aaid_sc_US_260d37c0-84ad-4627-9667-26030c180189 (1).png">
                    <img src="public/asstes/img/image course/urn_aaid_sc_US_260d37c0-84ad-4627-9667-26030c180189 (1).png">
                    <span class="text-white">4.5</span>
                </div>
            </div>
   
            <!-- <div class="page-breadcrumb-content text-center">
                <div class="page-breadcrumb-title">
                    <h2 class="breadcrumb-head black bold">
                        <span>@if(isset($category)) {{$category->name}} @else @lang('labels.frontend.course.courses') @endif </span>
                    </h2>
                </div>
            </div> -->
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
                    <div class="row col-lg-6 col-sm-12">
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
                    <div class="row col-lg-6 col-sm-12">
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
            <div class="row  coursesec d-block m-3">
                <p > <i class="fa fa-angle-down p-2" aria-hidden="true"></i>
                    Nothing except a positive attitude!</p>
            </div>
        </div>
    </section>
    <!-- End of course section
        ============================================= -->


 <!-- Start of course section
        ============================================= -->
<section id="course-page" class="course-page-section">
    <div class="container">
        <div class="row  coursecontent d-block m-3">
            <h2> Course content </h2>
        </div>
        <div class="row smpara d-block m-3">
            <p>16 sections • 83 lectures • 8h 0m total length</p>
        </div>



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
                    <p> Adding Value to Customers- Episode 1 </p>
                    <p> <i class="fa fa-play-circle-o" aria-hidden="true"></i> 10 Min </p>
                    <p> Adding Value to Customers- Episode 1 </p>
                    <p> <i class="fa fa-play-circle-o" aria-hidden="true"></i> 10 Min </p>
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
                        <p> Adding Value to Customers- Episode 1 </p>
                        <p> <i class="fa fa-play-circle-o" aria-hidden="true"></i> 10 Min </p>
                    </div>
                    <hr>
                    <div class="bordered">
                        <p> Adding Value to Customers- Episode 1 </p>
                        <p> <i class="fa fa-play-circle-o" aria-hidden="true"></i> 10 Min </p>
                    </div>
                    <div class="bordered">
                        <p> Adding Value to Customers- Episode 1 </p>
                        <p> <i class="fa fa-play-circle-o" aria-hidden="true"></i> 10 Min </p>
                    </div>
                    <div class="bordered">
                        <p> Adding Value to Customers- Episode 1 </p>
                        <p> <i class="fa fa-play-circle-o" aria-hidden="true"></i> 10 Min </p>
                    </div>
                </div>
              </div>
            </div>
          
          </div>
    </div>
</section>


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