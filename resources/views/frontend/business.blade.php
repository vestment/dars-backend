@extends('frontend.layouts.app')

@section('title', trans('labels.frontend.home.title').' | '.app_name())
@section('meta_description', '')
@section('meta_keywords','')


@push('after-styles')
    <link rel="stylesheet" href="../../assets/css/course.css"/>
    <style>


        .my-alert {
            position: absolute;
            z-index: 10;
            left: 0;
            right: 0;
            top: 25%;
            width: 50%;
            margin: auto;
            display: inline-block;
        }

        /*section {*/
        /*    overflow: unset;*/
        /*}*/


    </style>
@endpush

@section('content')


    <!-- Start of breadcrumb section
        ============================================= -->
<section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style bg-business">
    <div class="blakish-overlay"></div>
        <div class="container">
            <div class="row col-lg-6 col-sm-6 textbusiness">
                    <h1 class="text-white display-3 texthbusiness">
                    JOIN THE <span class="display-4 texthbusiness"> SKILLS </span> REVOLUTION
                    </h1>
            </div>
        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->


<section id="course-page" class="course-page-section m-5">
    <div class="container text-center">
    <h2 class="hbusiness">For a price quotation, reach us at ecouncilacademy.com</h2>


    </div>
</section>

@endsection

@push('after-scripts')
    <script>
        setTimeout(function () {
            $('.owl-carousel').trigger('refresh.owl.carousel');
        }, 100)
        $('ul.product-tab').find('li:first').addClass('active');
    </script>
@endpush
