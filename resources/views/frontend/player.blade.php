@extends('frontend.layouts.appCourse')

@section('lesson-title')
    <span class="course-title-header ml-5"></span>
@endsection
@section('course_route')
    <a class="close-lesson" href="#"><i class="fa fa-times" aria-hidden="true"></i>
    </a>
@endsection
@section('progress_bar')
    <div class="row prog ">
        <div class="text-right text-white mr-2 course-progress">
            0 %
        </div>
        <div class="progress">
            <div class="progress-bar"
                 style="width:0%">
            </div>
        </div>
    </div>
@endsection
@section('content')
<script>
    var lang = '{{app()->getLocale()}}';
</script>
<style>
    .main-menu-container.menu-bg-overlay, .main-menu-container {
        background-color: #0C0C3F;
    }

    .course-details-section {
        padding-top: 8%;
    }


    .play {
        font-size: 10px !important;
        display: inline !important;
    }

    .play i {
        font-size: 10px;
    }


    .progress {
        background-color: #b6b9bb;
        height: 3px;
        font-weight: bold;
        font-size: 0.8rem;
        padding: 0px !important;
    }

    .progress-bar {
        height: 3px !important;
        background-color: #D2498B;
    }

    .course-details-category ul li {
        width: 100%;
    }


    .m-note {
        margin-top: 11%;
    }


    @media screen  and  (max-width: 768px) {
        .txt-ara {
            display: none;
        }

        .txt-ara2 {
            display: block;
        }

        .prog {
            display: none;
        }

    }

    .course-title-header {
        color: white;
        font-weight: bold;
        font-size: 24px;

    }

    .progress {
        color: #D2498B;
        margin-top: 10px;
        width: 80%;
    }

    .video-container iframe {
        width: 100%;
        height: 553px;
        margin-top: -0.2%;

    }


    .svg-embedded {
        position: absolute;
        top: 0;
        left: 0;
        background-color: #000;
        color: #fff;
        width: auto;
        z-index: 500;
        height: auto;
        pointer-events: none;
        overflow: hidden;
    }

    .content {

    }
</style>
<div class="svg-embedded">{{auth()->user()->full_name}} - {{auth()->user()->id}}</div>

<section id="breadcrumb" class="breadcrumb-section relative-position d-none">
    <div class="blakish-overlay"></div>
    <div class="container">
        <div class="page-breadcrumb-content text-center">
            <div class="page-breadcrumb-title">
                <h2 class="breadcrumb-head black bold">
                    <span class="course-title-header"></span><br>
                    <br></h2>

            </div>
        </div>
    </div>
</section>
<section id="course-details" class="course-details-section">
    <div class="container-fluid">
        <div class="main-content">
            <div id="app" class="content">

            </div>
        </div>
    </div>
</section>
@endsection

@push('before-scripts')
    <script src="{{ asset(mix('js/manifest.js')) }}"></script>
    <script src="{{ asset(mix('js/vendor.js')) }}"></script>

    <script src="{{ asset(mix('js/frontend.js')) }}"></script>
@endpush
