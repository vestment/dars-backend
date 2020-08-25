@extends('backend.layouts.app')
@section('title', __('labels.backend.courses.title').' | '.app_name())
<link rel="stylesheet" type="text/css" href="{{asset('plugins/amigo-sorter/css/theme-default.css')}}">
    <style>
        ul.sorter > span {
            display: inline-block;
            width: 100%;
            height: 100%;
            background: #f5f5f5;
            color: #333333;
            border: 1px solid #cccccc;
            border-radius: 6px;
            padding: 0px;
        }

        ul.sorter li > span .title {
            padding-left: 15px;
        }

        ul.sorter li > span .btn {
            width: 20%;
        }
        .margin_left{
            margin-left:50px !important
        }
        ul.sorter li{
            height: 25%;
        }


    </style>

@section('content')

<div class="row">
  <div class="col-3">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
      <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Edit Course</a>
      <a class="nav-link " id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="true">All Chapters</a>
   
      <button  type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#exampleModal">
      Create Chapter
</button>
<div class="card " id="togglecard" style="width: 18rem;">
  <div class="card-body">
    <h5 class="card-title">Card title</h5>
    <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
    <a href="#" class="card-link">Card link</a>
    <a href="#" class="card-link">Another link</a>
  </div>
</div>
    </div>
  </div>
  <div class="col-9">
    <div class="tab-content" id="v-pills-tabContent">
      <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
      
            {!! Form::model($course, ['method' => 'PUT', 'route' => ['admin.courses.update', $course->id], 'files' => true,]) !!}

                        <div class="card">
                            <div class="card-header">
                                <h3 class="page-title float-left mb-0">@lang('labels.backend.courses.edit')</h3>
                                <div class="float-right">
                                    <a href="{{ route('admin.courses.index') }}"
                                    class="btn btn-success">@lang('labels.backend.courses.view')</a>
                                </div>
                            </div>

                            <div class="card-body">

                                @if (Auth::user()->isAdmin())
                                    <div class="row">

                                        <div class="col-10 form-group">
                                            {!! Form::label('teachers',trans('labels.backend.courses.fields.teachers'), ['class' => 'control-label']) !!}
                                            {!! Form::select('teachers[]', $allTeachers, old('teachers') ? old('teachers') : $course->teachers->pluck('id')->toArray(), ['class' => 'form-control select2', 'multiple' => 'multiple','required' => true]) !!}
                                        </div>
                                        <div class="col-2 d-flex form-group flex-column">
                                            OR <a target="_blank" class="btn btn-primary mt-auto"
                                                href="{{route('admin.teachers.create')}}">{{trans('labels.backend.courses.add_teachers')}}</a>
                                        </div>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-10 form-group">
                                        {!! Form::label('category_id',trans('labels.backend.courses.fields.category'), ['class' => 'control-label']) !!}
                                        {!! Form::select('category_id', $categ_name, old('category_id'), ['class' => 'form-control select2 js-example-placeholder-single', 'multiple' => false, 'required' => true]) !!}
                                    </div>
                                    <div class="col-2 d-flex form-group flex-column">
                                        OR <a target="_blank" class="btn btn-primary mt-auto"
                                            href="{{route('admin.categories.index').'?create'}}">{{trans('labels.backend.courses.add_categories')}}</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-6 form-group">
                                        {!! Form::label('title', trans('labels.backend.courses.fields.title').' *', ['class' => 'control-label']) !!}
                                        {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => '', 'required' => '']) !!}
                                    </div>
                                    <div class="col-12 col-lg-6 form-group">
                                        {!! Form::label('slug', trans('labels.backend.courses.fields.slug'), ['class' => 'control-label']) !!}
                                        {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'placeholder' =>  trans('labels.backend.courses.slug_placeholder')]) !!}
                                    </div>
                                    <div class="col-12 col-lg-6 form-group">
                                        {!! Form::label('course_hours', trans('labels.backend.courses.course_hours'), ['class' => 'control-label']) !!}
                                        {!! Form::text('course_hours', old('course_hours'), ['class' => 'form-control', 'placeholder' =>  trans('labels.backend.courses.slug_placeholder')]) !!}
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-12 form-group">
                                        {!! Form::label('description',trans('labels.backend.courses.fields.description'), ['class' => 'control-label']) !!}
                                        {!! Form::textarea('description', old('description'), ['class' => 'form-control ', 'placeholder' => trans('labels.backend.courses.fields.description')]) !!}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-4 form-group">
                                        {!! Form::label('price', trans('labels.backend.courses.fields.price').' (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                                        {!! Form::number('price', old('price'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price') ,'step' => 'any', 'pattern' => "[0-9]"]) !!}
                                    </div>
                                    <div class="col-12 col-lg-4 form-group">

                                        {!! Form::label('course_image', trans('labels.backend.courses.fields.course_image'), ['class' => 'control-label','accept' => 'image/jpeg,image/gif,image/png']) !!}
                                        {!! Form::file('course_image', ['class' => 'form-control']) !!}
                                        {!! Form::hidden('course_image_max_size', 8) !!}
                                        {!! Form::hidden('course_image_max_width', 4000) !!}
                                        {!! Form::hidden('course_image_max_height', 4000) !!}
                                        @if ($course->course_image)
                                            <a href="{{ asset('storage/uploads/'.$course->course_image) }}" target="_blank"><img
                                                        height="50px" src="{{ asset('storage/uploads/'.$course->course_image) }}"
                                                        class="mt-1"></a>
                                        @endif
                                    </div>
                                    <div class="col-12 col-lg-4 form-group">
                                        {!! Form::label('start_date', trans('labels.backend.courses.fields.start_date').' (yyyy-mm-dd)', ['class' => 'control-label']) !!}
                                        {!! Form::text('start_date', old('start_date'), ['class' => 'form-control date', 'pattern' => '(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))', 'placeholder' => trans('labels.backend.courses.fields.start_date').' (Ex . 2019-01-01)']) !!}
                                        <p class="help-block"></p>
                                        @if($errors->has('start_date'))
                                            <p class="help-block">
                                                {{ $errors->first('start_date') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        {!! Form::label('add_video', trans('labels.backend.lessons.fields.add_video'), ['class' => 'control-label']) !!}
                                        {!! Form::select('media_type', ['youtube' => 'Youtube','vimeo' => 'Vimeo','upload' => 'Upload','embed' => 'Embed'],($course->mediavideo) ? $course->mediavideo->type : null,['class' => 'form-control', 'placeholder' => 'Select One','id'=>'media_type' ]) !!}


                                        {!! Form::text('video', ($course->mediavideo) ? $course->mediavideo->url : null, ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video'  ]) !!}

                                        {!! Form::file('video_file', ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video_file','accept' =>'video/mp4'  ]) !!}
                                        <input type="hidden" name="old_video_file"
                                            value="{{($course->mediavideo && $course->mediavideo->type == 'upload') ? $course->mediavideo->url  : ""}}">
                                        @if($course->mediavideo != null)
                                            <div class="form-group">
                                                <a href="#" data-media-id="{{$course->mediaVideo->id}}"
                                                class="btn btn-xs btn-danger my-3 delete remove-file">@lang('labels.backend.lessons.remove')</a>
                                            </div>
                                        @endif



                                        @if($course->mediavideo && ($course->mediavideo->type == 'upload'))
                                            <video width="300" class="mt-2 d-none video-player" controls>
                                                <source src="{{($course->mediavideo && $course->mediavideo->type == 'upload') ? $course->mediavideo->url  : ""}}"
                                                        type="video/mp4">
                                                Your browser does not support HTML5 video.
                                            </video>

                                        @endif

                                        @lang('labels.backend.lessons.video_guide')
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 form-group">
                                        <div class="checkbox d-inline mr-4">
                                            {!! Form::hidden('published', 0) !!}
                                            {!! Form::checkbox('published', 1, old('published'), []) !!}
                                            {!! Form::label('published', trans('labels.backend.courses.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                        </div>

                                        @if (Auth::user()->isAdmin())

                                        <div class="checkbox d-inline mr-4">
                                            {!! Form::hidden('featured', 0) !!}
                                            {!! Form::checkbox('featured', 1, old('featured'), []) !!}
                                            {!! Form::label('featured',  trans('labels.backend.courses.fields.featured'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                        </div>

                                        <div class="checkbox d-inline mr-4">
                                            {!! Form::hidden('trending', 0) !!}
                                            {!! Form::checkbox('trending', 1, old('trending'), []) !!}
                                            {!! Form::label('trending',  trans('labels.backend.courses.fields.trending'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                        </div>

                                        <div class="checkbox d-inline mr-4">
                                            {!! Form::hidden('popular', 0) !!}
                                            {!! Form::checkbox('popular', 1, old('popular'), []) !!}
                                            {!! Form::label('popular',  trans('labels.backend.courses.fields.popular'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                        </div>
                                        @endif
                                        <div class="checkbox d-inline mr-4">
                                            {!! Form::hidden('free', 0) !!}
                                            {!! Form::checkbox('free', 1, old('free'), []) !!}
                                            {!! Form::label('free',  trans('labels.backend.courses.fields.free'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                        </div>

                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12 form-group">
                                        {!! Form::label('meta_title',trans('labels.backend.courses.fields.meta_title'), ['class' => 'control-label']) !!}
                                        {!! Form::text('meta_title', old('meta_title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_title')]) !!}

                                    </div>
                                    <div class="col-12 form-group">
                                        {!! Form::label('meta_description',trans('labels.backend.courses.fields.meta_description'), ['class' => 'control-label']) !!}
                                        {!! Form::textarea('meta_description', old('meta_description'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_description')]) !!}
                                    </div>
                                    <div class="col-12 form-group">
                                        {!! Form::label('meta_keywords',trans('labels.backend.courses.fields.meta_keywords'), ['class' => 'control-label']) !!}
                                        {!! Form::textarea('meta_keywords', old('meta_keywords'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_keywords')]) !!}
                                    </div>
                                </div>


                                @if (Auth::user()->isAdmin())
                                    <div class="row">
                                        <div class="col-10 form-group">
                                            {!! Form::label('optional_courses',trans('labels.backend.courses.fields.optional_courses'), ['class' => 'control-label']) !!}
                                            {!! Form::select('opt_courses[]', $allCourses,old('opt_courses') ? old('opt_courses') : $opt_courses, ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => false]) !!}
                                        </div>
                                    </div>
                                @endif



                                @if (Auth::user()->isAdmin())
                                    <div class="row">
                                        <div class="col-10 form-group">
                                            {!! Form::label('mandatory_courses',trans('labels.backend.courses.fields.mandatory_courses'), ['class' => 'control-label']) !!}
                                            {!! Form::select('mand_courses[]', $allCourses, old('mand_courses') ? old('mand_courses') : $mand_courses, ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => false]) !!}
                                        </div>    
                                    </div>
                                @endif

                                <div class="row">
                                        <div class="col-10 form-group">
                                            {!! Form::label('learned',trans('labels.backend.courses.fields.learned'), ['class' => 'control-label']) !!}
                                            {!! Form::select('learn[]', $prevLearned, old('learn') ? old('learn') : $prevLearned, ['class' => 'form-control select2 js-input-tag', 'multiple' => 'multiple', 'required' => false]) !!}
                                        </div>    
                                    </div>



                                <div class="row">
                                    <div class="col-12  text-center form-group">
                                        {!! Form::submit(trans('strings.backend.general.app_update'), ['class' => 'btn btn-danger']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

            {!! Form::close() !!}
      
      
      </div>
      <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
      </div>
      <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
      
      <div class="title my-3 mx-5">   
             <h1 class="page-title d-inline mb-5">@lang('labels.backend.courses.content')</h1>
</div>
    <div class="card shadow-lg p-3 mb-5 bg-white rounded">
        <div class="">
           
        </div>
        <div class="card-body">
           
             <div class="card-body">
            @if(count($chapterContent) > 0)
                <div class="row justify-content-center">
                    <div class="col-6  ">
                        <!-- <h4 class="">@lang('labels.backend.hero_slider.sequence_note')</h4> -->
                        <ul class="sorter d-inline-block">
                            @foreach($chapterContent as $item)
                            @foreach ($timeline as  $singleTimeline)
                            @if($singleTimeline->model_id == $item->id)
                                <li class="@if ($singleTimeline->model_type != 'App\Models\Chapter') margin_left @endif"  >
                            <span data-id="{{$item->id}}" data-sequence="{{$singleTimeline->sequence}}">

                                <p  class="title d-inline ml-2">{{$item->title}} {{$singleTimeline->sequence}}</p>
                           </span>

                                </li>
                                @endif

                            @endforeach
                            @endforeach

                        </ul>
                        <a href="{{ route('admin.courses.index') }}"
                           class="btn btn-default border float-left">@lang('strings.backend.general.app_back_to_list')</a>

                        <a href="#" id="save_timeline"
                           class="btn btn-primary float-right">@lang('labels.backend.hero_slider.save_sequence')</a>

                    </div>

                </div>
            @endif
        </div>

        </div>
    </div>
      
      
      
      
      </div>
      <div class="tab-pane fade" id="v-pills-settings" role="tabpanel" aria-labelledby="v-pills-settings-tab">...</div>

      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Create Chapter</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      {!! Form::open(['method' => 'POST', 'route' => ['admin.chapters.store'], 'files' => true,]) !!}
    {!! Form::hidden('course_id',$course->id)!!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.chapters.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.chapters.index') }}"
                   class="btn btn-success">@lang('labels.backend.chapters.view')</a>
            </div>
        </div>

        <div class="card-body">
            
            <div class="row">
          
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title', trans('labels.backend.chapters.fields.title').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.chapters.fields.title'), 'required' => '']) !!}
                </div>
                <div class="col-12 col-lg-6 form-group">
                {!! Form::label('title_ar', trans('labels.backend.chapters.fields.title_ar').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title_ar', old('title_ar'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.chapters.fields.title_ar'), 'required' => '']) !!}
                </div>

                <div class="checkbox d-inline mr-3">
                                        {!! Form::hidden('published', 0) !!}
                                        {!! Form::checkbox('published', 1, false, []) !!}
                                        {!! Form::label('published',  trans('labels.backend.courses.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                    </div>
            </div>

          
            <div class="row">

               
                <div class="col-12  text-left form-group">
                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn  btn-danger']) !!}
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}


      </div>
     
    </div>
  </div>
</div>

    </div>
  </div>
</div>

@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {
            $('#start_date').datepicker({
                autoclose: true,
                dateFormat: "{{ config('app.date_format_js') }}"
            });

            $(".js-example-placeholder-single").select2({
                placeholder: "{{trans('labels.backend.courses.select_category')}}",
            });

            $(".js-example-placeholder-multiple").select2({
                placeholder: "{{trans('labels.backend.courses.select_teachers')}}",
            });
            $(".js-input-tag").select2({
                tags: true
            })
        });

        var uploadField = $('input[type="file"]');

        $(document).on('change', 'input[type="file"]', function () {
            var $this = $(this);
            $(this.files).each(function (key, value) {
                if (value.size > 5000000) {
                    alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
                    $this.val("");
                }
            })
        })


        $(document).on('change', '#media_type', function () {
            if ($(this).val()) {
                if ($(this).val() != 'upload') {
                    $('#video').removeClass('d-none').attr('required', true)
                    $('#video_file').addClass('d-none').attr('required', false)
                    $('#duration').removeClass('d-none').attr('required', true)
                } else if ($(this).val() == 'upload') {
                    $('#video').addClass('d-none').attr('required', false)
                    $('#video_file').removeClass('d-none').attr('required', true)
                 

                }
            } else {
                $('#video_file').addClass('d-none').attr('required', false)
                $('#video').addClass('d-none').attr('required', false)
            }
        })
        $(document).ready(function() {
            var max_fields_limit = 8; //set limit for maximum input fields
            var x = 1; //initialize counter for text box
            $('.add_more_button').click(function(e){ //click event on add more fields button having class add_more_button
            e.preventDefault();
            if(x < max_fields_limit){ //check conditions
            x++; //counter increment
            $('.input_fields_container_part').append('<div><input type="text" name="knowledge"/><a href="#" class="remove_field" style="margin-left:10px;">Remove</a></div>'); //add input field
            }
            }); 
            $('.input_fields_container_part').on("click",".remove_field", function(e){ //user click on remove text links
            e.preventDefault(); $(this).parent('div').remove(); x--;
            })
            });

    </script>
     <script>

        $(document).ready(function () {
            $('#start_date').datepicker({
                autoclose: true,
                dateFormat: "{{ config('app.date_format_js') }}"
            });

            $(".js-example-placeholder-single").select2({
                placeholder: "{{trans('labels.backend.courses.select_category')}}",
            });

            $(".js-example-placeholder-multiple").select2({
                placeholder: "{{trans('labels.backend.courses.select_teachers')}}",
            });
            $(".js-input-tag").select2({
                tags: true,
            })

            
        });
        $(document).on('change', 'input[type="file"]', function () {
            var $this = $(this);
            $(this.files).each(function (key, value) {
                if (value.size > 50000000) {
                    alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
                    $this.val("");
                }
            })
        });

        $(document).ready(function () {
            $(document).on('click', '.delete', function (e) {
                e.preventDefault();
                var parent = $(this).parent('.form-group');
                var confirmation = confirm('{{trans('strings.backend.general.are_you_sure')}}')
                if (confirmation) {
                    var media_id = $(this).data('media-id');
                    $.post('{{route('admin.media.destroy')}}', {media_id: media_id, _token: '{{csrf_token()}}'},
                        function (data, status) {
                            if (data.success) {
                                parent.remove();
                                $('#video').val('').addClass('d-none').attr('required', false);
                                $('#video_file').attr('required', false);
                                $('#media_type').val('');
                                @if($course->mediavideo && $course->mediavideo->type ==  'upload')
                                $('.video-player').addClass('d-none');
                                $('.video-player').empty();
                                @endif


                            } else {
                                alert('Something Went Wrong')
                            }
                        });
                }
            })
        });


        @if($course->mediavideo)
        @if($course->mediavideo->type !=  'upload')
        $('#video').removeClass('d-none').attr('required', true);
        $('#video_file').addClass('d-none').attr('required', false);
        $('.video-player').addClass('d-none');
        @elseif($course->mediavideo->type == 'upload')
        $('#video').addClass('d-none').attr('required', false);
        $('#video_file').removeClass('d-none').attr('required', false);
        $('.video-player').removeClass('d-none');
        @else
        $('.video-player').addClass('d-none');
        $('#video_file').addClass('d-none').attr('required', false);
        $('#video').addClass('d-none').attr('required', false);
        @endif
        @endif

        $(document).on('change', '#media_type', function () {
            if ($(this).val()) {
                if ($(this).val() != 'upload') {
                    $('#video').removeClass('d-none').attr('required', true);
                    $('#video_file').addClass('d-none').attr('required', false);
                    $('.video-player').addClass('d-none')
                } else if ($(this).val() == 'upload') {
                    $('#video').addClass('d-none').attr('required', false);
                    $('#video_file').removeClass('d-none').attr('required', true);
                    $('.video-player').removeClass('d-none')
                }
            } else {
                $('#video_file').addClass('d-none').attr('required', false);
                $('#video').addClass('d-none').attr('required', false)
            }
        })


    </script>
    <script src="{{asset('plugins/amigo-sorter/js/amigo-sorter.min.js')}}"></script>

<script>

    $('ul.sorter').amigoSorter({
        li_helper: "li_helper",
        li_empty: "empty",
        onTouchStart: function() { console.log('sdffsdsdfsdf');
        document.getElementById("togglecard").style.display = "none";},
    });
    $(document).on('click', '#save_timeline', function (e) {
        e.preventDefault();
        var list = [];
        $('ul.sorter li').each(function (key, value) {
            key++;
            var val = $(value).find('span').data('id');
            list.push({id: val, sequence: key});
        });

        $.ajax({
            method: 'POST',
            url: "{{route('admin.courses.saveSequence')}}",
            data: {
                _token: '{{csrf_token()}}',
                list: list
            }
        }).done(function () {
            location.reload();
        });
    })
    

    $(document).on('click', '.switch-input', function (e) {
        var id = $(this).data('id');
        $.ajax({
            type: "POST",
            url: "{{ route('admin.sliders.status') }}",
            data: {
                _token:'{{ csrf_token() }}',
                id: id,
            },
        }).done(function() {
            location.reload();
        });
    })
</script>



@endpush