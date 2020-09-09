@extends('frontend.layouts.appCourse')

@push('after-styles')
    {{--<link rel="stylesheet" href="{{asset('plugins/YouTube-iFrame-API-Wrapper/css/main.css')}}">--}}
    <link rel="stylesheet" href="https://cdn.plyr.io/3.5.3/plyr.css"/>
    <link href="{{asset('plugins/touchpdf-master/jquery.touchPDF.css')}}" rel="stylesheet">
    <link href="{{asset('Lexxus-jq-timeTo-f2c4b67/timeTo.css')}}" rel="stylesheet">
    <link href="{{asset('froala_editor_3.2.1/css/froala_style.min.css')}}" rel="stylesheet">
    <link href="{{asset('froala_editor_3.2.1/css/froala_editor.pkgd.min.css')}}" rel="stylesheet">
    <link href="{{asset('froala_editor_3.2.1/css/plugins.pkgd.min.css')}}" rel="stylesheet">




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


        .bg-active a:active {

            background-color: yellow;

        }

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
            background-color:#000;
            color:#fff;
            width: auto;
            z-index:1000000000;
            height: auto;
            pointer-events: none;
            overflow: hidden;
        }
    </style>
@endpush
@section('lesson-title')
    <span class="course-title-header ml-5">{{$lesson->course->getDataFromColumn('title')}}</span>
@endsection
@section('course_route')
    <a href="{{route('courses.show',['slug'=>$lesson->course->slug])}}"><i class="fa fa-times" aria-hidden="true"></i>
    </a>
@endsection
@section('progress_bar')
    <div class="row prog ">
        <div class="text-right text-white mr-2">
            {{ $lesson->course->progress()}} %
        </div>
        <div class="progress">
            <div class="progress-bar"
                 style="width:{{ $lesson->course->progress() }}%">
            </div>
        </div>
    </div>                                        </a>
@endsection

@section('content')
    <div class="svg-embedded" style="display:none;">{{auth()->user()->full_name}} - {{auth()->user()->id}}</div>
    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="breadcrumb-sectionn relative-position d-none">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="page-breadcrumb-content text-center">
                <div class="page-breadcrumb-title">
                    <h2 class="breadcrumb-head black bold">
                        <span class="course-title-header">{{$lesson->course->getDataFromColumn('title')}}</span><br>
                        <br></h2>

                </div>
            </div>
        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of course details section
        ============================================= -->
    <section id="course-details" class="course-details-section pt-5">
        <div class="container-fluid">
            <div class="row main-content">
                <div class="col-md-9 p-0 pt-5">
                    @if(session()->has('success'))
                        <div class="alert alert-dismissable alert-success mt-4 mb-4 ml-5 fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{session('success')}}
                        </div>
                    @endif
                    @include('includes.partials.messages')

                    <div class="course-details-item border-bottom-0 mb-0">


                        @if ($lesson->available == 1)
                            @if ($test_exists)
                                @if ((session()->get('test_attempts')  < 3 && session()->get('reTest')) || is_null($latestTest))
                                    <div data-attempts="{{session()->get('test_attempts')}}"
                                         class="course-single-text row">

                                        <div class="col-6">
                                            <div class="course-title mt10 headline relative-position">
                                                <h3>
                                                    <b>@lang('labels.frontend.course.test')
                                                        : {{$lesson->getDataFromColumn('title')}}</b>

                                                </h3>
                                            </div>
                                            <div class="course-details-content">
                                                <p> {!! $lesson->full_text !!} </p>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div id="countdown" class="timeTo timeTo-white"
                                                 style="font-family: Verdana, sans-serif;">
                                                <div class="first" style="">
                                                    <ul style="left:3px; top:-30px">
                                                        <li>0</li>
                                                        <li>0</li>
                                                    </ul>
                                                </div>
                                                <div style="">
                                                    <ul style="left:3px; top:-30px">
                                                        <li>0</li>
                                                        <li>0</li>
                                                    </ul>
                                                </div>
                                                <span>:</span>
                                                <div class="first" style="">
                                                    <ul style="left:3px; top:-30px">
                                                        <li>0</li>
                                                        <li>0</li>
                                                    </ul>
                                                </div>
                                                <div style="">
                                                    <ul style="left: 3px; top: -30px;" class="">
                                                        <li>1</li>
                                                        <li>1</li>
                                                    </ul>
                                                </div>
                                                <span>:</span>
                                                <div class="first" style="">
                                                    <ul style="left: 3px; top: -30px;" class="">
                                                        <li>2</li>
                                                        <li>2</li>
                                                    </ul>
                                                </div>
                                                <div style="">
                                                    <ul style="left: 3px; top: 0px;" class="transition">
                                                        <li>0</li>
                                                        <li>1</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <hr/>
                                @if (!is_null($latestTest))
                                    <div class="alert alert-info">
                                        <p>@lang('labels.frontend.course.your_test_score')</p>
                                        @foreach($prevTests as $prevTest)
                                            <p>Attempt ({{$prevTest->attempts}}) : {{ $prevTest->test_result }}
                                                / {{$questionsToAnswer->sum('score')}}
                                                @if ($prevTest->test_result < $prevTest->test->min_grade)
                                                    <span class="ml-2 text-danger">
                                                    You need min ({{$prevTest->test->min_grade}}) points to pass this test
                                                </span>
                                                @endif
                                            </p>

                                        @endforeach
                                    </div>
                                    @if(config('retest') && $canReTest)
                                        @if( $latestTest->attempts <3)
                                            @if (!session()->get('reTest'))
                                                <form action="{{route('lessons.retest',[$latestTest->test->slug])}}"
                                                      method="post">
                                                    @csrf
                                                    <input type="hidden" name="result_id"
                                                           value="{{$latestTest->id}}">
                                                    <button type="submit"
                                                            class="btn gradient-bg font-weight-bold text-white"
                                                            href="">
                                                        @lang('labels.frontend.course.give_test_again')
                                                    </button>
                                                </form>
                                            @endif
                                        @else
                                            <div class="alert alert-danger">You already attended this test 3 times
                                            </div>
                                        @endif
                                    @endif

                                    @if(count($lesson->questions) > 0 )
                                        @if (count($prevTests) >= 3)
                                            <hr>
                                            @foreach ($lesson->questions as $question)

                                                <h4 class="mb-0">{{ $loop->iteration }}
                                                    . {!! $question->question !!}   @if(!$question->isAttempted($latestTest->id))
                                                        <small class="badge badge-danger"> @lang('labels.frontend.course.not_attempted')</small> @endif
                                                </h4>
                                                <br/>
                                                <ul class="options-list pl-4">
                                                    @foreach ($question->options as $option)

                                                        <li class="@if(($option->answered($latestTest->id) != null && $option->answered($latestTest->id) == 1) || ($option->correct == true)) correct @elseif($option->answered($latestTest->id) != null && $option->answered($latestTest->id) == 2) incorrect  @endif"> {{ $option->option_text }}

                                                            @if($option->correct == 1 && $option->explanation != null)
                                                                <p class="text-dark">
                                                                    <b>@lang('labels.frontend.course.explanation')</b><br>
                                                                    {{$option->explanation}}
                                                                </p>
                                                            @endif
                                                        </li>

                                                    @endforeach
                                                </ul>
                                                <br/>
                                            @endforeach
                                        @endif
                                    @else
                                        <h3>@lang('labels.general.no_data_available')</h3>
                                    @endif
                                @endif
                                @if ((session()->get('test_attempts')  < 3 && session()->get('reTest')) || is_null($latestTest))
                                    <div class="test-form">
                                        @if(count($questionsToAnswer) > 0  )
                                            <script>
                                                var slug = '{{$lesson->slug}}';
                                                var id = '{{$lesson->id}}';
                                                var timeoutorg = parseInt('{{$lesson->timer*60}}');
                                                var start = parseInt('{{$start_time}}');
                                                var endtime = parseInt(start + timeoutorg);
                                                var now = Date.now() / 1000;

                                                var timecomp = (endtime - now);
                                                setInterval(() => {
                                                    var endtime = parseInt(start + timeoutorg);
                                                    var now = Date.now() / 1000;
                                                    var timecomp = (endtime - now);

                                                }, 1000);


                                            </script>
                                            <form action="{{ route('lessons.test', [$lesson->slug]) }}"
                                                  method="post">
                                                {{ csrf_field() }}
                                                @foreach ($questionsToAnswer as $key => $question)
                                                    @if($loop->iteration <= $lesson->no_questions)
                                                        <h4 class="mb-0">{{ $loop->iteration }}
                                                            . {!! $question->question !!}  </h4>
                                                        <br/>
                                                        @foreach ($question->options as $option)
                                                            <div class="radio">
                                                                <label>
                                                                    <input type="radio"
                                                                           name="questions[{{ $question->id }}]"
                                                                           value="{{ $option->id }}"/>
                                                                    <span class="cr"><i
                                                                                class="cr-icon fa fa-circle"></i></span>
                                                                    {{ $option->option_text }}<br/>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                        <br/>
                                                    @endif
                                                @endforeach
                                                <input class="btn gradient-bg text-white font-weight-bold"
                                                       type="submit"
                                                       value=" @lang('labels.frontend.course.submit_results') "/>
                                            </form>
                                        @else
                                            <h3>@lang('labels.general.no_data_available')</h3>

                                        @endif
                                    </div>
                                @endif
                            @else
                                <h3>@lang('labels.general.no_data_available')</h3>

                            @endif
                            <hr/>
                        @else

                        @endif


                        @if($lesson->mediaVideo && $lesson->mediavideo->count() > 0)

                            <div class="course-single-text mb-4">
                                @if($lesson->mediavideo != "")
                                    <div class="course-details-content mt-5">
                                        <div class="video-container mb-5" data-id="{{$lesson->mediavideo->id}}">
                                            @if($lesson->mediavideo->type == 'youtube')
                                                <div id="player" class="js-player" data-plyr-provider="youtube"
                                                     data-plyr-embed-id="{{$lesson->mediavideo->file_name}}"></div>
                                            @elseif($lesson->mediavideo->type == 'vimeo')
                                                <div id="player" class="js-player" data-plyr-provider="vimeo"
                                                     data-plyr-embed-id="{{$lesson->mediavideo->file_name}}"></div>
                                            @elseif($lesson->mediavideo->type == 'upload')
                                                <video poster="" id="player" class="js-player" playsinline controls>
                                                    <source src="{{route('videos.stream',['encryptedId'=>\Illuminate\Support\Facades\Crypt::encryptString($lesson->mediavideo->id)])}}"
                                                            type="video/mp4"/>
                                                </video>
                                            @elseif($lesson->mediavideo->type == 'embed')
                                                {!! $lesson->mediavideo->url !!}

                                            @endif
                                        </div>
                                    </div>
                                @endif


                            </div>
                            <section class="m-note">
                                <div class="container my-5 txt-ara">
                                    <h2 class="m-3">Write Your Notes</h2>
                                    <div class="row">
                                        <div class="col-12">

                                            <form action="{{route('save.note')}}" method="POST">
                                                <input type="hidden" name="lesson_slug" value="{{$lesson->slug}}">
                                                @csrf
                                                <textarea class='edit-froala' name="contentText"
                                                          style="margin-top: 30px;">

                                                </textarea>

                                                <button type="submit" class=" float-right btn btn-success my-5">
                                                    save
                                                </button>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @if(count($notes) > 0)
                                    <div class="container my-5 d-none">
                                        <h3 class="my-3 mx-4">Notes</h3>
                                        <div class="card shadow-c">
                                            <div class="container">
                                                @foreach($notes as $note)
                                                    <div class="card shadow-c my-5 ">
                                                        <div class="card-body">
                                                            {{$note->contentText}}
                                                            <a class="float-right text-pink "
                                                               onclick="editNote({{$note->id}})" data-toggle="modal"
                                                               data-target="#edit-note-modal"><i
                                                                        class="far fa-edit"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endif

                            </section>
                        @endif


                    </div>
                </div>

                <div class="col-md-3 p-0">
                    <div id="sidebar" class="sidebar p-0 pt-4">
                        <div class="course-details-category ul-li">
                            @if ($previous_lesson)
                                <p><a class="btn btn-block gradient-bg font-weight-bold text-white"
                                      href="{{ route('lessons.show', [$previous_lesson->course_id, $previous_lesson->model->slug]) }}"><i
                                                class="fa fa-angle-double-left"></i>
                                        @lang('labels.frontend.course.prev')</a></p>
                            @endif

                            <p id="nextButton">
                                @if($next_lesson)
                                    @if((int)config('lesson_timer') == 1 && $lesson->isCompleted() && $canEnterNextChapter)
                                        <a class="btn btn-block gradient-bg font-weight-bold text-white"
                                           href="{{ route('lessons.show', [$next_lesson->course_id, $next_lesson->model->slug]) }}">@lang('labels.frontend.course.next')
                                            <i class='fa fa-angle-double-right'></i> </a>
                                    @else
                                        <a class="btn btn-block gradient-bg font-weight-bold text-white"
                                           href="{{ route('lessons.show', [$next_lesson->course_id, $next_lesson->model->slug]) }}">@lang('labels.frontend.course.next')
                                            <i class='fa fa-angle-double-right'></i> </a>
                                    @endif
                                @endif
                            </p>


                            @if($lesson->course->progress() == 100)
                                @if(!$lesson->course->isUserCertified())
                                    <form method="post" action="{{route('admin.certificates.generate')}}">
                                        @csrf
                                        <input type="hidden" value="{{$lesson->course->id}}" name="course_id">
                                        <button class="btn btn-success btn-block text-white mb-3 text-uppercase font-weight-bold"
                                                id="finish">@lang('labels.frontend.course.finish_course')</button>
                                    </form>
                                @else
                                <!-- <div class="alert alert-success">
                                        @lang('labels.frontend.course.certified')
                                        </div> -->
                                @endif
                            @endif
                            @foreach($chapters as $chapter)
                                <div class="row m-2 shadow ml-3">
                                    <div class="accordion" id="accordionExample">
                                        <div class="card shad">
                                            <div class="card-header" id="headingOne">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link btn-block text-left bollder"
                                                            type="button"
                                                            data-toggle="collapse"
                                                            data-target="#chapter-{{ $chapter->id}}"
                                                            aria-expanded="true"
                                                            aria-controls="chapter-{{ $chapter->id}}">
                                                        {{ $chapter->getDataFromColumn('title')}} <i
                                                                class="fa fa-angle-down float-right"
                                                                aria-hidden="true"></i>
                                                    </button>
                                                </h2>
                                            </div>

                                            <div id="chapter-{{ $chapter->id}}" class="collapse show"
                                                 aria-labelledby="headingOne" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    <div class="bordered" id="start_test">
                                                        @foreach($lesson->course->courseTimeline()->where('chapter_id',$chapter->id)->orderBy('sequence')->get() as $key=>$item)
                                                            @if($item->model && $item->model->published == 1)
                                                                @if($item->model_type == 'App\Models\Lesson')
                                                                    <div class="bg-active px-2 pt-2">
                                                                        <p class="mb-0 subtitle2 test"><a
                                                                                    @if($canEnterNextChapter)
                                                                                    data-test-id="{{$item->model->id}}"
                                                                                    href="{{route('lessons.show',['id' => $lesson->course->id,'slug'=>$item->model->slug])}}"
                                                                                    @endif>
                                                                        {{$item->model->getDataFromColumn('title')}}
                                                                        @if ($lesson->mediavideo)  <p class="play p-0"><i
                                                                                    class="far fa-play-circle"></i> {{$lesson->mediavideo->duration}}  @lang('labels.frontend.course.minutes')
                                                                        </p>
                                                                        @endif
                                                                        </a>

                                                                        <b class="float-right">
                                                                            @if($item->model->mediaPDF)
                                                                                <a href="{{asset('storage/uploads/'.$item->model->mediaPDF->name)}}"
                                                                                   target="_blank" data-toggle="tooltip"
                                                                                   data-placement="top"
                                                                                   title="Open PDF">
                                                                                    <i class="far fa-file-pdf ml-2"></i>
                                                                                </a>
                                                                            @endif
                                                                            @if(count($notes) > 0)
                                                                                <a href="#notesModal"
                                                                                   onclick="getLessonNotes('{{$item->model->slug}}')"
                                                                                   data-toggle="modal"
                                                                                   data-target="#notesModal"><i
                                                                                            class="far fa-sticky-note ml-2"></i></a>
                                                                            @endif
                                                                            @if(($item->model->downloadableMedia != "") && ($item->model->downloadableMedia->count() > 0))
                                                                                @foreach($item->model->downloadableMedia as $media)
                                                                                    <a data-toggle="tooltip"
                                                                                       data-placement="top"
                                                                                       title="Download {{ $media->name }}"
                                                                                       href="{{ route('download',['filename'=>$media->name,'lesson'=>$lesson->id]) }}">
                                                                                        <i class="fas fa-download ml-2"></i>
                                                                                    </a>
                                                                                @endforeach
                                                                            @endif
                                                                            @if($item->model->mediaAudio)
                                                                                <a id="audioPlayer" controls
                                                                                   href="{{$item->model->mediaAudio->url}}"
                                                                                   target="_blank"> <i
                                                                                            class="fas fa-volume-up "></i>
                                                                                </a>
                                                                            @endif

                                                                            @endif
                                                                            @if($item->model_type == 'App\Models\Test')
                                                                                <div class="p-1">

                                                                                    <p class="mb-0 mt-1 text-pink test "
                                                                                       style="cursor: pointer;"
                                                                                       onclick="startTest(this)"
                                                                                       data-test-id="{{$item->model->id}}"
                                                                                       data-href="{{route('lessons.show',['id' => $lesson->course->id,'slug'=>$item->model->slug])}}">
                                                                                        &nbsp;&nbsp;@lang('labels.frontend.course.test')
                                                                                        On {{$item->model->getDataFromColumn('title')}}
                                                                                        <b class="float-right">


                                                                                            @endif
                                                                                            @if(!in_array($item->model->id,$completed_lessons))
                                                                                                <i class="fas fa-unlock-alt"></i>
                                                                                            @else
                                                                                                <i class="fas fa-unlock-alt"></i>
                                                                                            @endif
                                                                                        </b>

                                                                                    </p>



                                                                                </div>
                                                                        @endif
                                                                        @endforeach

                                                                    </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>


                                    </div>
                                </div>

                            @endforeach
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="container my-5 txt-ara2">
            <h2 class="m-3">Write Your Notes</h2>
            <div class="row">
                <div class="col-12">

                    <form action="{{route('save.note')}}" method="POST">
                        <input type="hidden" name="lesson_slug" value="{{$lesson->slug}}">
                        @csrf
                        <textarea class='edit-froala' name="contentText"
                                  style="margin-top: 30px;">

                                                </textarea>

                        <button type="submit" class=" float-right btn btn-success my-5">
                            save
                        </button>

                    </form>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="notesModal" tabindex="-2" aria-labelledby="notesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notesModalLabel">Lesson Notes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="notes-container">

                </div>

            </div>
        </div>
    </div>
    <!-- Button trigger modal -->
    <!-- Modal -->
    <div class="modal fade" id="edit-note-modal" tabindex="-1" role="dialog"
         aria-labelledby="edit-note-modallLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content my-4">
                <div class="modal-header m-3 " style="background: unset;">
                    <h5 class="modal-title" id="edit-note-modallLabel">Edit note</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('update.note')}}" method="POST">
                    <div class="modal-body" id="note_content_body">

                        <input type="hidden" name="note_id" id="note_id">
                        @csrf
                        <textarea id="edit-note" class='edit-froala' name="contentText"
                                  style="margin-top: 30px;">
                                                </textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">Close
                        </button>
                        <button type="submit" class="btn btn-primary">Save changes
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End of course details section
    ============================================= -->

@endsection

@push('after-scripts')
    {{--<script src="//www.youtube.com/iframe_api"></script>--}}
    <script src="{{asset('Lexxus-jq-timeTo-f2c4b67/jquery.time-to.min.js')}}"></script>

    <script src="{{asset('plugins/sticky-kit/sticky-kit.js')}}"></script>

    <script src="https://cdn.plyr.io/3.5.3/plyr.polyfilled.js"></script>
    <script src="{{asset('plugins/touchpdf-master/pdf.compatibility.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/pdf.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/jquery.touchSwipe.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/jquery.touchPDF.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/jquery.panzoom.js')}}"></script>
    <script src="{{asset('plugins/touchpdf-master/jquery.mousewheel.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/js-cookie@2/src/js.cookie.min.js"></script>
    <script src="{{asset('froala_editor_3.2.1/js/froala_editor.min.js')}}"></script>
    <script src="{{asset('froala_editor_3.2.1/js/froala_editor.pkgd.min.js')}}"></script>
    <script src="{{asset('froala_editor_3.2.1/js/plugins.pkgd.min.js')}}"></script>



    <link href="{{asset('froala_editor_3.2.1/css/froala_style.min.css')}}" rel="stylesheet">

    <script>
        $(document).ready(function () {

            new FroalaEditor(".edit-froala", {
                enter: FroalaEditor.ENTER_BR,
                fileUpload: false,
                fileInsertButtons: [],
                imageUpload: false
            }, function () {
                // Call the method inside the initialized event.
                $('#insertFile-1').remove();
                $('#insertFiles-1').remove();

                $('#insertLink-1').remove();
                $('#insertImage-1').remove();
                $('#insertVideo-1').remove();
                $('#getPDF-1').remove();
                $('#print-1').remove();
                $('#insertFile-2').remove();
                $('#insertFiles-2').remove();

                $('#insertLink-2').remove();
                $('#insertImage-2').remove();
                $('#insertVideo-2').remove();
                $('#getPDF-2').remove();
                $('#print-2').remove();
                $('#logo').remove();
                $('a[href="https://www.froala.com/wysiwyg-editor?k=u"]').parent().remove()


            })


        })
    </script>
    <script>
        $('.bg-active').on('click', function () {

            $('.bg-active').addClass('bg-lgh')

        });


    </script>
    <script>
        $('#edit-note-modal').on('show.bs.modal', function (e) {
            $('#notesModal').modal('hide');
        })
        $('#edit-note-modal').on('hidden.bs.modal', function (e) {
            $('#notesModal').modal('show');
        })

        function editNote(id) {
            $.ajax({
                url: "{{route('editnote')}}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id,
                },
                success: function (result) {
                    $('#note_content_body .fr-element').html(result.contentText);
                    $('#note_content_body .fr-placeholder').hide();
                    $('#note_id').val(id);
                }
            });
        }

        function getLessonNotes(lessonSlug) {
            $.ajax({
                url: "{{route('notes.index')}}",
                method: "get",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'lesson_slug': lessonSlug,
                },
                success: function (resp) {
                    if (resp.status == 'success') {
                        $('#notes-container').html('')
                        $(resp.notes).each(function (key, note) {
                            var cardElement = '<div class="card shadow-c my-5 ">\n' +
                                '                            <div class="card-body">\n' +
                                '                                ' + note.contentText + '\n' +
                                '                                <a class="float-right text-pink "\n' +
                                '                                   onclick=\"editNote(' + note.id + ')\" data-toggle="modal"\n' +
                                '                                   data-target="#edit-note-modal"><i\n' +
                                '                                            class="far fa-edit"></i>\n' +
                                '                                </a>\n' +
                                '                            </div>\n' +
                                '                        </div>';
                            $('#notes-container').append(cardElement)
                        })

                    }

                }
            });
        }

    </script>



    <script>
        function startTest(element) {
            $.ajax({
                url: "{{route('update.test.start_time')}}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': $(element).data('test-id'),
                },
                success: function (result) {
                    console.log(result);
                    window.location.href = $(element).data('href');
                }
            });
        }

        @if($questionsToAnswer != null )

        {{--        @if(count($questionsToAnswer) > 0 && ((session()->get('test_attempts')  < 3 && session()->get('reTest')) || is_null($latestTest)) )--}}
        $(document).ready(function () {
            if (typeof timecomp !== 'undefined') {
                var $countdown = $('#countdown');
                if (timecomp > 0) {
                    $countdown.show().timeTo(parseInt(timecomp));
                }
                if (timecomp == 0) {
                    $countdown.timeTo(parseInt(timecomp),
                        function () {
                            $countdown.hide();
                            $.ajax({
                                url: "{{route('update.test.available')}}",
                                method: "POST",
                                data: {
                                    "_token": "{{ csrf_token() }}",
                                    'lesson_slug': slug,
                                },
                                success: function (result) {
                                    console.log(result)
                                    window.location.href = "{{route('courses.show', [$lesson->course->slug])}}"
                                }
                            });
                        }
                    )
                }
            }
        });

        @endif





        var storedDuration = 0;
        var storedLesson;
        storedDuration = Cookies.get("duration_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}");
        storedLesson = Cookies.get("lesson" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}");
        var user_lesson;

        if (parseInt(storedLesson) != parseInt("{{$lesson->id}}")) {
            Cookies.set('lesson', parseInt('{{$lesson->id}}'));
        }


        @if($lesson->mediaVideo && $lesson->mediaVideo->type != 'embed')
        var current_progress = 0;


        @if($lesson->mediaVideo->getProgress(auth()->user()->id) != "")
            current_progress = "{{$lesson->mediaVideo->getProgress(auth()->user()->id)->progress}}";
        @endif



        const player2 = new Plyr('#audioPlayer');
        const player = new Plyr('#player');
        let plyrVideoWrapper = document.getElementsByClassName('plyr__video-wrapper')
        if (plyrVideoWrapper) {
            let myPluginCollection = document.getElementsByClassName('svg-embedded')
            if (myPluginCollection) {
                plyrVideoWrapper[0].appendChild(myPluginCollection[0])
            }
            $('.svg-embedded').show();
            setInterval(function () {
                var $div = $('.svg-embedded'),
                    docHeight = $div.parent().height(),
                    docWidth = $div.parent().width(),
                    divHeight = $div.height(),
                    divWidth = $div.width(),
                    heightMax = docHeight - divHeight,
                    widthMax = docWidth - divWidth;

                $div.css({
                    left: Math.floor(Math.random() * widthMax),
                    top: Math.floor(Math.random()*-10 * heightMax)
                });
                console.log(widthMax,heightMax)
            }, 2000);
        }
        $('.js-player source').remove();
        duration = 10;
        var progress = 0;
        var video_id = $('#player').parents('.video-container').data('id');
        player.on('ready', event => {
            player.currentTime = parseInt(current_progress);
            duration = event.detail.plyr.duration;


            if (!storedDuration || (parseInt(storedDuration) === 0)) {
                Cookies.set("duration_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}", duration);
            }

        });

        {{--if (!storedDuration || (parseInt(storedDuration) === 0)) {--}}
        {{--Cookies.set("duration_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}", player.duration);--}}
        {{--}--}}


        setInterval(function () {
            player.on('timeupdate', event => {
                if ((parseInt(current_progress) > 0) && (parseInt(current_progress) < parseInt(event.detail.plyr.currentTime))) {
                    progress = current_progress;
                } else {
                    progress = parseInt(event.detail.plyr.currentTime);
                }
            });
            if (duration !== 0 || parseInt(progress) !== 0) {
                saveProgress(video_id, duration, parseInt(progress));
            }
        }, 3000);


        function saveProgress(id, duration, progress) {
            $.ajax({
                url: "{{route('update.videos.progress')}}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'video': parseInt(id),
                    'duration': parseInt(duration),
                    'progress': parseInt(progress)
                },
                success: function (result) {
                    if (progress === duration) {
                        location.reload();
                    }
                }
            });
        }


        $('#notice').on('hidden.bs.modal', function () {
            location.reload();
        });

        @endif

        // $("#sidebar").stick_in_parent();


        @if((int)config('lesson_timer') != 0)
        //Next Button enables/disable according to time

        var readTime, totalQuestions, testTime;
        user_lesson = Cookies.get("user_lesson_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}");

        @if ($test_exists )
            totalQuestions = '{{count($lesson->questions)}}'
        readTime = parseInt(totalQuestions) * 30;
        @else
            readTime = parseInt("{{$lesson->readTime()}}") * 60;
        @endif

                @if(!$lesson->isCompleted())
            storedDuration = Cookies.get("duration_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}");
        storedLesson = Cookies.get("lesson" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}");


        var totalLessonTime = readTime + (parseInt(storedDuration) ? parseInt(storedDuration) : 0);
        var storedCounter = (Cookies.get("storedCounter_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}")) ? Cookies.get("storedCounter_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}") : 0;
        var counter;
        if (user_lesson) {
            if (user_lesson === 'true') {
                counter = 1;
            }
        } else {
            if ((storedCounter != 0) && storedCounter < totalLessonTime) {
                counter = storedCounter;
            } else {
                counter = totalLessonTime;
            }
        }
        var interval = setInterval(function () {
            counter--;
            // Display 'counter' wherever you want to display it.
            if (counter >= 0) {
                // Display a next button box
                $('#nextButton').html("<a class='btn btn-block bg-danger font-weight-bold text-white' href='#'>@lang('labels.frontend.course.next') (in " + counter + " seconds)</a>")
                Cookies.set("duration_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}", counter);

            }
            if (counter === 0) {
                Cookies.set("user_lesson_" + "{{auth()->user()->id}}" + "_" + "{{$lesson->id}}" + "_" + "{{$lesson->course->id}}", 'true');
                Cookies.remove('duration');

                @if ($test_exists && (is_null($latestTest)))
                $('#nextButton').html("<a class='btn btn-block bg-danger font-weight-bold text-white' href='#'>@lang('labels.frontend.course.complete_test')</a>")
                @else
                @if($next_lesson)
                $('#nextButton').html("<a class='btn btn-block gradient-bg font-weight-bold text-white'" +
                    " href='{{ route('lessons.show', [$next_lesson->course_id, $next_lesson->model->slug]) }}'>@lang('labels.frontend.course.next')<i class='fa fa-angle-double-right'></i> </a>");
                @else
                $('#nextButton').html("<form method='post' action='{{route("admin.certificates.generate")}}'>" +
                    "<input type='hidden' name='_token' id='csrf-token' value='{{ Session::token() }}' />" +
                    "<input type='hidden' value='{{$lesson->course->id}}' name='course_id'> " +
                    "<button class='btn btn-success btn-block text-white mb-3 text-uppercase font-weight-bold' id='finish'>@lang('labels.frontend.course.finish_course')</button></form>");

                @endif

                @if(!$lesson->isCompleted())
                courseCompleted("{{$lesson->id}}", "{{get_class($lesson)}}");
                @endif
                @endif
                clearInterval(counter);
            }
        }, 1000);

        @endif
        @endif

        function courseCompleted(id, type) {
            $.ajax({
                url: "{{route('update.course.progress')}}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'model_id': parseInt(id),
                    'model_type': type,
                },
            });
        }

    </script>
@endpush
