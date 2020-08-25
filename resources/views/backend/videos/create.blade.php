@extends('backend.layouts.app')

@section('title', __('labels.backend.videos.title').' | '.app_name())

@section('content')
    {{ html()->form('POST', route('admin.video-bank.store'))->acceptsFiles()->class('form-horizontal')->open() }}
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
    </div>
    {{ html()->form()->close() }}
@endsection
