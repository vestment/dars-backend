@extends('backend.layouts.app')
@section('title', __('labels.backend.lessons.title').' | '.app_name())

@push('after-styles')
    <link rel="stylesheet" type="text/css" href="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.css')}}">
    <style>
        .select2-container--default .select2-selection--single {
            height: 35px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 35px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 35px;
        }

        .bootstrap-tagsinput {
            width: 100% !important;
            display: inline-block;
        }

        .bootstrap-tagsinput .tag {
            line-height: 1;
            margin-right: 2px;
            background-color: #2f353a;
            color: white;
            padding: 3px;
            border-radius: 3px;
        }

    </style>

@endpush

@section('content')

    {!! Form::open(['method' => 'POST', 'route' => ['admin.lessons.store'], 'files' => true,]) !!}
    {!! Form::hidden('course_id',$course_id,['id'=>'lesson_id']) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.lessons.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.lessons.index') }}"
                   class="btn btn-success">@lang('labels.backend.lessons.view')</a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-12 col-lg-12 form-group">
                    {!! Form::label('chapter_id', trans('labels.backend.lessons.fields.chapter'), ['class' => 'control-label']) !!}
                    {!! Form::select('chapter_id', $allChapters,  (request('chapter_id')) ? request('chapter_id') : old('chapter_id'), ['class' => 'form-control select2']) !!}


                </div>
            </div>
            <div class="row">

                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title', trans('labels.backend.lessons.fields.title').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.lessons.fields.title'), 'required' => '']) !!}
                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title_ar', trans('labels.backend.lessons.fields.title_ar').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title_ar', old('title_ar'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.lessons.fields.title_ar'), 'required' => '']) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('slug',trans('labels.backend.lessons.fields.slug'), ['class' => 'control-label']) !!}
                    {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.lessons.slug_placeholder')]) !!}

                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('lesson_image', trans('labels.backend.lessons.fields.lesson_image').' '.trans('labels.backend.lessons.max_file_size'), ['class' => 'control-label']) !!}
                    {!! Form::file('lesson_image', ['class' => 'form-control' , 'accept' => 'image/jpeg,image/gif,image/png']) !!}
                    {!! Form::hidden('lesson_image_max_size', 8) !!}
                    {!! Form::hidden('lesson_image_max_width', 4000) !!}
                    {!! Form::hidden('lesson_image_max_height', 4000) !!}

                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('short_text', trans('labels.backend.lessons.fields.short_text'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('short_text', old('short_text'), ['class' => 'form-control ', 'placeholder' => trans('labels.backend.lessons.short_description_placeholder')]) !!}

                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('short-text-ar', trans('labels.backend.lessons.fields.short-text-ar'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('short-text-ar', old('short-text-ar'), ['class' => 'form-control ', 'placeholder' => trans('labels.backend.lessons.short-text-ar')]) !!}

                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('full_text', trans('labels.backend.lessons.fields.full_text'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('full_text', old('full_text'), ['class' => 'form-control editor', 'placeholder' => '','id' => 'editor']) !!}

                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('full_text_ar', trans('labels.backend.lessons.fields.full_text_ar'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('full_text_ar', old('full_text_ar'), ['class' => 'form-control editor', 'placeholder' => '','id' => 'editor_ar']) !!}

                </div>
            </div>
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('downloadable_files', trans('labels.backend.lessons.fields.downloadable_files').' '.trans('labels.backend.lessons.max_file_size'), ['class' => 'control-label']) !!}
                    {!! Form::file('downloadable_files[]', [
                        'multiple',
                        'class' => 'form-control file-upload',
                        'id' => 'downloadable_files',
                        'accept' => "image/jpeg,image/gif,image/png,application/msword,audio/mpeg,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-powerpoint,application/pdf,video/mp4"
                        ]) !!}
                    <div class="photo-block">
                        <div class="files-list"></div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('pdf_files', trans('labels.backend.lessons.fields.add_pdf'), ['class' => 'control-label']) !!}
                    {!! Form::file('add_pdf', [
                        'class' => 'form-control file-upload',
                         'id' => 'add_pdf',
                        'accept' => "application/pdf"

                        ]) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('audio_files', trans('labels.backend.lessons.fields.add_audio'), ['class' => 'control-label']) !!}
                    {!! Form::file('add_audio', [
                        'class' => 'form-control file-upload',
                         'id' => 'add_audio',
                        'accept' => "audio/mpeg3"

                        ]) !!}
                </div>
            </div>


            <div class="row">
                <div class="col-md-12 form-group">
                    {!! Form::label('add_video', trans('labels.backend.lessons.fields.add_video'), ['class' => 'control-label']) !!}

                    {!! Form::select('media_type', ['youtube' => 'Youtube','vimeo' => 'Vimeo','upload' => 'Upload','embed' => 'Embed'],null,['class' => 'form-control', 'placeholder' => 'Select One','id'=>'media_type' ]) !!}

                    {!! Form::text('video', old('video'), ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video'  ]) !!}

                        {!! Form::select('video', $videos, old('video'), ['class' => 'form-control mt-3 d-none ','id'=>'video_file']) !!}

                        @lang('labels.backend.lessons.video_guide')

                </div>
            </div>
            <div class="col-12 col-lg-6 form-group d-none" id="duration">
                {!! Form::label('duration',  trans('labels.backend.courses.duration'), ['class' => 'control-label']) !!}
                {!! Form::text('duration', old('duration'), ['class' => 'form-control ', 'placeholder' =>  trans('labels.backend.courses.video_format')]) !!}

            </div>

            <div class="row">

                <div class="col-12 col-lg-3 form-group">
                    <div class="checkbox">
                        {!! Form::hidden('published', 0) !!}
                        {!! Form::checkbox('published', 1, false, []) !!}
                        {!! Form::label('published', trans('labels.backend.lessons.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div>
                </div>
                <div class="col-12  text-left form-group">
                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn  btn-danger']) !!}
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}



@stop

@push('after-scripts')
    <script src="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>
    <script type="text/javascript" src="{{asset('/vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript" src="{{asset('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js')}}"></script>
    <script src="{{asset('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script>
        $('.editor').each(function () {

            CKEDITOR.replace($(this).attr('id'), {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}',
                extraPlugins: 'smiley,lineutils,widget,codesnippet,prism,flash',
            });

        });

        var uploadField = $('input[type="file"]');

        $(document).on('change', 'input[type="file"]', function () {
            var $this = $(this);
            $(this.files).each(function (key, value) {
                console.log(value.size);
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

    </script>

@endpush
