
@extends('frontend.layouts.appCourse')
@section('content')
<script>
        var lang = '{{app()->getLocale()}}';
    </script>
        <style>
        .main-menu-container.menu-bg-overlay, .main-menu-container {
            background-color: #0C0C3F;
        }

        .course-details-section {
            padding: 0px;
        }

        .bg-lgh {
            background-color: #f1f1f1;

        }


        /*.bg-active a:active {*/
        /*    background-color: yellow;*/
        /*}*/

        .shad {
            box-shadow: 0px 1px 15px #dad4d4;
        }

        .play {
            font-size: 10px !important;
            display: inline !important;
        }

        .play i {
            font-size: 10px;
        }

        .subtitle2 a:
        .main-menu-container {
            background-color: #0C0C3F;
        }

        .test-form {
            color: #333333;
        }

        .bollder {
            font-weight: bolder;

        }

        .test {

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

        .sidebar.is_stuck {
            top: 16% !important;
            margin-top: 0;

        }

        .sidebar {
            margin-top: 10%;
            margin-left: 1%;

            -webkit-transition: .3s all ease-in-out;
            transition: .3s all ease-in-out;
        }

        .m-note {
            margin-top: 11%;
        }

        .course-timeline-list {
            max-height: 300px;
            overflow: scroll;
        }

        .options-list li {
            list-style-type: none;
        }

        .shadow-c {
            box-shadow: 2px 3px #ebebeb;
        }

        .options-list li.correct {
            color: green;

        }

        .options-list li.incorrect {
            color: red;

        }

        .options-list li.correct:before {
            content: "\f058"; /* FontAwesome Unicode */
            font-family: 'Font Awesome\ 5 Free';
            display: inline-block;
            color: green;
            margin-left: -1.3em; /* same as padding-left set on li */
            width: 1.3em; /* same as padding-left set on li */
        }

        .options-list li.incorrect:before {
            content: "\f057"; /* FontAwesome Unicode */
            font-family: 'Font Awesome\ 5 Free';
            display: inline-block;
            color: red;
            margin-left: -1.3em; /* same as padding-left set on li */
            width: 1.3em; /* same as padding-left set on li */
        }

        .options-list li:before {
            content: "\f111"; /* FontAwesome Unicode */
            font-family: 'Font Awesome\ 5 Free';
            display: inline-block;
            color: black;
            margin-left: -1.3em; /* same as padding-left set on li */
            width: 1.3em; /* same as padding-left set on li */
        }

        .touchPDF {
            border: 1px solid #e3e3e3;
        }

        .touchPDF > .pdf-outerdiv > .pdf-toolbar {
            height: 0;
            color: black;
            padding: 5px 0;
            text-align: right;
        }

        .pdf-tabs {
            width: 100% !important;
        }

        .pdf-outerdiv {
            width: 100% !important;
            left: 0 !important;
            padding: 0px !important;
            transform: scale(1) !important;
        }

        .pdf-viewer {
            left: 0px;
            width: 100% !important;
        }

        .pdf-drag {
            width: 100% !important;
        }

        .pdf-outerdiv {
            left: 0px !important;
        }

        .pdf-outerdiv {
            padding-left: 0px !important;
            left: 0px;
        }

        .pdf-toolbar {
            left: 0px !important;
            width: 99% !important;
            height: 30px;
        }

        .pdf-viewer {
            box-sizing: border-box;
            left: 0 !important;
            margin-top: 10px;
        }

        .pdf-title {
            display: none !important;
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

        @media screen  and  (min-width: 768px) {
            .txt-ara {
                display: block;
            }

            .txt-ara2 {
                display: none;
            }

        }

        .breadcrumb-sectionn {
            background-color: #0C0C3F !important;

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

        .plyr--video {
            width: 93.2%;
            margin-left: 6%;
            margin-top: -1.3%;
        }

        .course-details-item {
            width: 100%;

        }

        .subtitle2 {
            font-weight: light;
            font-size: 14px;


        }

        .bg-active {
            border-bottom: solid 1px #e4e4e4;
        }

        .fr-quick-insert {
            display: none !important;
        }

        .svg-embedded {
            position: absolute;
            top: 0;
            left: 0;
            background-color: #000;
            color: #fff;
            width: auto;
            z-index: 1000000000;
            height: auto;
            pointer-events: none;
            overflow: hidden;
        }
        .content{
            
        }
    </style>
    <div id="app" class="content">

        <player></player>

    </div>  
    @endsection

@push('before-scripts')
<script  src="{{ asset(mix('js/manifest.js')) }}"></script>
<script  src="{{ asset(mix('js/vendor.js')) }}"></script>

<script  src="{{ asset(mix('js/frontend.js')) }}"></script>
@endpush