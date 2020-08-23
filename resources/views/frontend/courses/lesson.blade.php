@extends('frontend.layouts.appCourse')

@push('after-styles')
    {{--<link rel="stylesheet" href="{{asset('plugins/YouTube-iFrame-API-Wrapper/css/main.css')}}">--}}
    <link rel="stylesheet" href="https://cdn.plyr.io/3.5.3/plyr.css"/>
    <link href="{{asset('plugins/touchpdf-master/jquery.touchPDF.css')}}" rel="stylesheet">
    <link href="{{asset('Lexxus-jq-timeTo-f2c4b67/timeTo.css')}}" rel="stylesheet">

<script>
 var lang = '{{app()->getLocale()}}';
</script>
    <style>
    .main-menu-container.menu-bg-overlay{
        display:none;
        padding:34px
    }
        .test-form {
            color: #333333;
        }

        .course-details-category ul li {
            width: 100%;
        }

        .sidebar.is_stuck {
            top: 15% !important;
        }

        .course-timeline-list {
            max-height: 300px;
            overflow: scroll;
        }

        .options-list li {
            list-style-type: none;
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

        }
        .breadcrumb-sectionn{
            background-color:#0C0C3F !important;
            
        }
        .course-title-header{
            color: white;
    font-weight: bold;
    font-size: 24px;
    position: absolute;
    left: 15%;
    top: 30%;
        }
.progress{
    color:#D2498B
}
.video-container iframe{
    width: 95%;
    height: 553px;
    margin-top: -0.2%;
    margin-left: 5%;

}

.plyr--video{
    width: 93.2%;
    margin-left: 6%;
    margin-top: -1.3%;
}
.course-details-item {
    width: 93.2%;
    margin-left: 4%;
    margin-top: -1%;
}
.subtitle2 {
    font-weight: light;
    font-size: 14px;
  
    padding-bottom: 17px;
 
    border-bottom: solid 1px #e4e4e4;
}
    </style>
@endpush

@section('content')
    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="breadcrumb-sectionn relative-position ">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="page-breadcrumb-content text-center">
                <div class="page-breadcrumb-title">
                    <h2 class="breadcrumb-head black bold">
                        <span class="course-title-header">{{$lesson->course->title}}</span><br> <br> </h2>
                        
                </div>
            </div>
        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of course details section
        ============================================= -->
    <section id="course-details" class="course-details-section">
        <div class="container-fluid">
            <div class="row main-content">
                <div class="col-md-9">
                    @if(session()->has('success'))
                        <div class="alert alert-dismissable alert-success fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{session('success')}}
                        </div>
                    @endif
                    @include('includes.partials.messages')

                    <div class="course-details-item border-bottom-0 mb-0">
                       

                    @if ($lesson->available == 1)
                        @if ($test_exists)
                        

                            <div class="course-single-text row">
                           
                                <div class="col-6">
                                    <div class="course-title mt10 headline relative-position">
                                        <h3>
                                            <b>@lang('labels.frontend.course.test')
                                                : {{$lesson->title}}</b>
                                        </h3>
                                    </div>
                                    <div class="course-details-content">
                                        <p> {!! $lesson->full_text !!} </p>
                                    </div>
                                </div>
                                <div class="col-6"> 
                                    <div id="countdown" class="timeTo timeTo-white" style="font-family: Verdana, sans-serif;">
                                        <div class="first" style=""><ul style="left:3px; top:-30px">
                                        <li>0</li><li>0</li></ul>
                                        </div>
                                        <div style="">
                                        <ul style="left:3px; top:-30px">
                                        <li>0</li><li>0</li></ul>
                                        </div>
                                        <span>:</span>
                                        <div class="first" style="">
                                        <ul style="left:3px; top:-30px">
                                        <li>0</li><li>0</li></ul></div><div style="">
                                        <ul style="left: 3px; top: -30px;" class="">
                                        <li>1</li><li>1</li></ul>
                                        </div>
                                        <span>:</span>
                                        <div class="first" style="">
                                        <ul style="left: 3px; top: -30px;" class=""><li>2</li><li>2</li></ul>
                                        </div>
                                        <div style=""><ul style="left: 3px; top: 0px;" class="transition">
                                        <li>0</li><li>1</li></ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          

                            <hr/>
                            @if (!is_null($test_result))
                                <div class="alert alert-info">@lang('labels.frontend.course.your_test_score')
                                    : {{ $test_result->test_result }}</div>
                                @if(config('retest'))
                                    <form action="{{route('lessons.retest',[$test_result->test->slug])}}" method="post">
                                        @csrf
                                        <input type="hidden" name="result_id" value="{{$test_result->id}}">
                                        <button type="submit" class="btn gradient-bg font-weight-bold text-white"
                                                href="">
                                            @lang('labels.frontend.course.give_test_again')
                                        </button>
                                    </form>
                                @endif

                                @if(count($lesson->questions) > 0  )
                                    <hr>
                                  
                                    @foreach ($lesson->questions as $question)

                                        <h4 class="mb-0">{{ $loop->iteration }}
                                            . {!! $question->question !!}   @if(!$question->isAttempted($test_result->id))
                                                <small class="badge badge-danger"> @lang('labels.frontend.course.not_attempted')</small> @endif
                                        </h4>
                                        <br/>
                                        <ul class="options-list pl-4">
                                            @foreach ($question->options as $option)

                                                <li class="@if(($option->answered($test_result->id) != null && $option->answered($test_result->id) == 1) || ($option->correct == true)) correct @elseif($option->answered($test_result->id) != null && $option->answered($test_result->id) == 2) incorrect  @endif"> {{ $option->option_text }}

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

                                @else
                                    <h3>@lang('labels.general.no_data_available')</h3>

                                @endif
                            @else
                                <div class="test-form">
                                    @if(count($lesson->questions) > 0  )
                                    <script>

                                        var timeoutorg = '{{$lesson->timer}}*60';
                                        var slug = '{{$lesson->slug}}';
                                        var id = '{{$lesson->id}}';
                                        var start = '{{$lesson->start_time}}';
                                        var endtime =(start+ time())*1000;
                                        var timecomp = endtime-(start*1000);
                                        var timeout = timeoutorg - timecomp ;




                                    </script>   
                                        <form action="{{ route('lessons.test', [$lesson->slug]) }}" method="post">
                                            {{ csrf_field() }}
                                            @foreach ($lesson->questions as $question)
                                                <h4 class="mb-0">{{ $loop->iteration }}. {!! $question->question !!}  </h4>
                                                <br/>
                                                @foreach ($question->options as $option)
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="questions[{{ $question->id }}]"
                                                                   value="{{ $option->id }}"/>
                                                            <span class="cr"><i class="cr-icon fa fa-circle"></i></span>
                                                            {{ $option->option_text }}<br/>
                                                        </label>
                                                    </div>
                                                @endforeach
                                                <br/>
                                            @endforeach
                                            <input class="btn gradient-bg text-white font-weight-bold" type="submit"
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

                        @if($lesson->mediaPDF)
                            <div class="course-single-text mb-5 course_frame" style="width: 95%; height: 650px;">
                                {{--<iframe src="{{asset('storage/uploads/'.$lesson->mediaPDF->name)}}"  width="100%"--}}
                                {{--height="100%" class="iframe_style">--}}
                                {{--</iframe>--}}
                                <div id="myPDF"></div>

                            </div>
                        @endif


                        @if($lesson->mediaVideo && $lesson->mediavideo->count() > 0)
                            <div class="course-single-text">
                                @if($lesson->mediavideo != "")
                                    <div class="course-details-content mt-3">
                                        <div class="video-container mb-5" data-id="{{$lesson->mediavideo->id}}">
                                            @if($lesson->mediavideo->type == 'youtube')


                                                <div id="player" class="js-player" data-plyr-provider="youtube"
                                                     data-plyr-embed-id="{{$lesson->mediavideo->file_name}}"></div>
                                            @elseif($lesson->mediavideo->type == 'vimeo')
                                                <div id="player" class="js-player" data-plyr-provider="vimeo"
                                                     data-plyr-embed-id="{{$lesson->mediavideo->file_name}}"></div>
                                            @elseif($lesson->mediavideo->type == 'upload')
                                                <video poster="" id="player" class="js-player" playsinline controls>
                                                    <source src="{{$lesson->mediavideo->url}}" type="video/mp4"/>
                                                </video>
                                            @elseif($lesson->mediavideo->type == 'embed')
                                                {!! $lesson->mediavideo->url !!}
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if($lesson->mediaAudio)
                            <div class="course-single-text mb-5">
                                <audio id="audioPlayer" controls>
                                    <source src="{{$lesson->mediaAudio->url}}" type="audio/mp3"/>
                                </audio>
                            </div>
                        @endif


                        @if(($lesson->downloadableMedia != "") && ($lesson->downloadableMedia->count() > 0))
                            <div class="course-single-text mt-4 px-3 py-1 gradient-bg text-white">
                                <div class="course-title mt10 headline relative-position">
                                    <h4 class="text-white">
                                        @lang('labels.frontend.course.download_files')
                                    </h4>
                                </div>

                                @foreach($lesson->downloadableMedia as $media)
                                    <div class="course-details-content text-white">
                                        <p class="form-group">
                                            <a href="{{ route('download',['filename'=>$media->name,'lesson'=>$lesson->id]) }}"
                                               class="text-white font-weight-bold"><i
                                                        class="fa fa-download"></i> {{ $media->name }}
                                                ({{ number_format((float)$media->size / 1024 , 2, '.', '')}} @lang('labels.frontend.course.mb')
                                                )</a>
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <!-- /course-details -->

                    <!-- /market guide -->

                    <!-- /review overview -->
                </div>

                <div style="margin-left:-3.5%" class="col-md-3">
                    <div id="sidebar" class="sidebar">
                        <div class="course-details-category ul-li">
                            @if ($previous_lesson)
                                <p><a class="btn btn-block gradient-bg font-weight-bold text-white"
                                      href="{{ route('lessons.show', [$previous_lesson->course_id, $previous_lesson->model->slug]) }}"><i
                                                class="fa fa-angle-double-left"></i>
                                        @lang('labels.frontend.course.prev')</a></p>
                            @endif

                            <p id="nextButton">
                                @if($next_lesson)
                                    @if((int)config('lesson_timer') == 1 && $lesson->isCompleted() )
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
                                    <div class="alert alert-success">
                                        @lang('labels.frontend.course.certified')
                                    </div>
                                @endif
                            @endif



                            <!-- @foreach($chapters as $chapter)
                            <div class="accordion" id="accordionExample">
                                    <div class="card">
                                        <div class="card-header" id="{{$chapter->id}}">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                           {{ $chapter->title}}
                                            </button>
                                        </h2>
                                        </div>

                                        <div id="collapseOne" class="collapse show" aria-labelledby="{{$chapter->id}}" data-parent="#accordionExample">
                                        <div class="card-body">
                                        @foreach($lesson->course->courseTimeline()->where('chapter_id',$chapter->id)->orderBy('sequence')->get() as $key=>$item)
                                      
                                        @if($item->model && $item->model->published == 1)
                                    
                                        {{--@php $key++; @endphp--}}
                                        <li class="@if($lesson->id == $item->model->id) active @endif ">
                                            <a @if(in_array($item->model->id,$completed_lessons))href="{{route('lessons.show',['id' => $lesson->course->id,'slug'=>$item->model->slug])}}"@endif>
                                                {{$item->model->title}}
                                                @if($item->model_type == 'App\Models\Test')
                                                    <p class="mb-0 text-primary">
                                                        - @lang('labels.frontend.course.test')</p>
                                                @endif
                                                @if(in_array($item->model->id,$completed_lessons)) <i
                                                        class="fa text-success float-right fa-check-square"></i> @endif
                                            </a>
                                        </li>
                              
                                    @endif
                                    @endforeach
                                        </div>
                                        </div>
                                       
                                    </div>
 
                             </div>

                             @endforeach -->

    @foreach($chapters as $chapter)
        <div class="row m-2 shadow">
            <div class="accordion" id="accordionExample">
                <div class="card">
                    <div class="card-header" id="headingOne">
                        <h2 class="mb-0">
                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne" >
                        {{ $chapter->title}} <i class="fa fa-angle-down float-right" aria-hidden="true"></i>
                        </button>
                        </h2>
                    </div>
            
                    <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                            <div class="bordered" id="start_test">
                            @foreach($lesson->course->courseTimeline()->where('chapter_id',$chapter->id)->orderBy('sequence')->get() as $key=>$item)
                                      
                                      @if($item->model && $item->model->published == 1)
                                  
                                      {{--@php $key++; @endphp--}}
                                <p class="subtitle2"  >  <a  @if(in_array($item->model->id,$completed_lessons))href="{{route('lessons.show',['id' => $lesson->course->id,'slug'=>$item->model->slug])}}"@endif>
                                <p class="subtitle2"  >  <a  id="test" @if(in_array($item->model->id,$completed_lessons))href="{{route('lessons.show',['id' => $lesson->course->id,'slug'=>$item->model->slug])}}"@endif>
                                                {{$item->model->title}}
                                                @if($item->model_type == 'App\Models\Test')
                                                    <p class="mb-0 text-primary" >
                                                        - @lang('labels.frontend.course.test')</p>
                                                @endif
                                                @if(in_array($item->model->id,$completed_lessons)) <i  
                                                class="fa text-success float-right fa-check-square"></i> @endif
                                            </a> </p>
                                <!-- <p class="play10"> <i class="fa fa-play-circle" aria-hidden="true"></i> 10 Min </p> -->
                                @endif
                                    @endforeach
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
    </section>
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
    

    <script>
    $('#test').click(function(){
        $.ajax({
                url: "{{route('update.test.start_time')}}",
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id,
                },
                success: function (result) {
                    console.log(result)
                }
            });

    });
    @if($lesson->questions != null )

        @if(count($lesson->questions) > 0  )
    $(document).ready(function() {
        var $countdown = $('#countdown');
        if(timeout > 0){
        
        $countdown.show().timeTo(parseInt(timeout));

        $('#start_test').hide();

        }
        if(timeout ==0){
 $countdown.timeTo(parseInt(timeout),
   function() {
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
                }
            });
   }
 )
 };

});
 @endif
        @endif

   
 
        @if($lesson->mediaPDF)
        $(function () {
            $("#myPDF").pdf({
                source: "{{asset('storage/uploads/'.$lesson->mediaPDF->name)}}",
                loadingHeight: 800,
                loadingWidth: 800,
                loadingHTML: ""
            });

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
            if(duration !== 0 || parseInt(progress) !== 0 ) {
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

        $("#sidebar").stick_in_parent();


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

                @if ($test_exists && (is_null($test_result)))
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