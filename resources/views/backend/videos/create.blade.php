@extends('backend.layouts.app')

@section('title', __('labels.backend.videos.title').' | '.app_name())

@section('content')
    {{ html()->form('POST', route('admin.video-bank.store'))->acceptsFiles()->class('form-horizontal videoBank-form')->open() }}
    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">@lang('labels.backend.videos.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.video-bank.index') }}"
                   class="btn btn-success">@lang('labels.backend.videos.view')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.videos.fields.videos_input'))->class('col-md-2 form-control-label')->for('image') }}

                        <div class="col-md-10">
                            {!! Form::file('video_file[]', ['class' => 'form-control d-inline-block', 'placeholder' => '','multiple' => 'multiple']) !!}
                        </div><!--col-->
                    </div>

                    <div class="form-group row justify-content-center">
                        <div class="col-4">
                            {{ form_cancel(route('admin.video-bank.index'), __('buttons.general.cancel')) }}
                            {{ form_submit(__('validation.attributes.frontend.upload')) }}
                        </div>
                    </div><!--col-->
                </div>
            </div>
        </div>
        <div class="card-footer">
            <!-- Progress bar -->
            <div class="progress">
                <div class="progress-bar"></div>
            </div>
            <!-- Display upload status -->
            <div id="uploadStatus"></div>
        </div>
    </div>
    {{ html()->form()->close() }}

@endsection
@push('after-scripts')
    <script>
        $(document).ready(function () {
            // File upload via Ajax
            $(".videoBank-form").on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    xhr: function () {
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (evt) {
                            if (evt.lengthComputable) {
                                var percentComplete = Math.round((evt.loaded / evt.total) * 100);
                                $(".progress-bar").width(percentComplete + '%');
                                $(".progress-bar").html(percentComplete + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    type: 'POST',
                    url: $('.videoBank-form').attr('action'),
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    beforeSend: function () {
                        $(".progress-bar").width('0%');
                        $('#uploadStatus').html('<i class="fa fa-spin fa-spinner"></i>');
                    },
                    error: function () {
                        $('#uploadStatus').html('<p style="color:#EA4335;">File upload failed, please try again.</p>');
                    },
                    success: function (resp) {
                        console.log(resp);
                        if (resp.status == 'success') {
                            $('.videoBank-form')[0].reset();
                            $('#uploadStatus').html('<p style="color:#28A74B;">File has uploaded successfully!</p>');
                        } else if (resp == 'error') {
                            $('#uploadStatus').html('<p style="color:#EA4335;">Please select a valid file to upload.</p>');
                        }
                        setTimeout(() => {
                            $(".progress-bar").width('0%').fadeOut();
                            $('#uploadStatus').html('').fadeOut();
                        },5000)
                    }
                });
            });
        });
    </script>
@endpush
