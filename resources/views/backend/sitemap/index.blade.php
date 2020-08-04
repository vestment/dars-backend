@extends('backend.layouts.app')
@section('title', __('labels.backend.sitemap.title').' | '.app_name())

@push('after-styles')
    <style>
        .form-control-label {
            line-height: 35px;
        }
    </style>
@endpush

@section('content')
    {{ html()->form('POST', route('admin.sitemap.config'))->id('general-settings-form')->class('form-horizontal')->acceptsFiles()->open() }}
    <div class="title my-3 mx-5">
      <h3 class="page-title d-inline mb-4">@lang('labels.backend.sitemap.title')</h3>
</div>
<div class="shadow-lg p-3 mb-5 bg-white rounded">
        <div class="">
            <div class="row">
                <div class="col-12">
                    <a class="btn btn-primary pull-right" href="{{route('admin.sitemap.generate')}}">@lang('labels.backend.sitemap.generate')</a>

                </div>
            </div>
        </div>

        <div class="card-body" id="newsletter">
            <h5>@lang('labels.backend.sitemap.sitemap_note')</h5>
            <a class="mb-2 d-block" target="_blank"
               href="{{asset('sitemap-'.str_slug(config('app.name')).'/sitemap-index.xml')}}"><h6>Click here to see
                    Sitemap Index File</h6></a>

            <div class="form-group row">
                {{ html()->label(__('labels.backend.sitemap.records_per_file'))->class('col-md-2 form-control-label')->for('short_description') }}
                <div class="col-md-10">
                    {{ html()->input('number','sitemap__chunk')
                  ->id('list_id')
                  ->class('form-control')
                  ->value(config('sitemap.chunk'))
                  ->placeholder('Ex. 100 ')
                  }}
                </div>
            </div>
            <div class="form-group row">
                <label class="col-md-2 col-form-label">{{__('labels.backend.sitemap.generate')}}</label>
                <div class="col-md-10 col-form-label">
                    {{ html()->select('sitemap__schedule',['1' => __('labels.backend.sitemap.daily'),'2' => __('labels.backend.sitemap.weekly'),'3' => __('labels.backend.sitemap.monthly')])
         ->id('sitemap_schedule')
         ->class('form-control ')
         }}
                    <span>@lang('labels.backend.backup.backup_note')</span>
                </div>
            </div>
            <div class="form-group text-center row">
                <div class="col text-center">
                    <button type="submit" class="btn btn-pink ">{{__('buttons.general.crud.update')}}</button>
                </div><!--col-->
            </div><!--row-->
        </div>

    </div>
    {{ html()->form()->close() }}

@endsection

@push('after-scripts')
    <script>
                @if(config('sitemap.schedule') != "")
        var schedule = "{{config('sitemap.schedule')}}";
        $('#sitemap_schedule option[value="' + schedule + '"]').attr('selected', true);
        @endif


    </script>
@endpush