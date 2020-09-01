@extends('backend.layouts.app')

@section('title',  __('labels.backend.update.title').' | '.app_name())

@push('after-styles')

@endpush

@section('content')
<div class="title my-3 mx-5">
      <h3 class="page-title d-inline">@lang('labels.backend.update.title')</h3>
</div>
    <div class="row">
        <div class="col">
        <div class="shadow-lg p-3 mb-5 bg-white rounded">
                <div class=" mb-5 mt-2">
                    <h3 class="float-right text-primary">@lang('labels.backend.update.current_version') {{config('app.version')}}</h3>

                </div><!--card-header-->
                <div class="card-body my-5">

                        <form method="post" action="{{route('admin.list-files')}}" enctype="multipart/form-data">
                            @csrf
                            <h2 class="text-primary mb-4">@lang('labels.backend.update.note_before_upload_title')</h2>
                            @lang('labels.backend.update.note_before_upload')
                            <h5 class="text-danger">@lang('labels.backend.update.warning')</h5>

                            <div class="row ">
                                <div class="form-group col-md-6 col-12 ">
                                    <label class="font-weight-bold  ">@lang('labels.backend.update.upload')</label>
                                    <input type="file" id="file" accept="application/zip" class="form-control" name="file">
                                </div>
                                <div class="form-group col-md-6 col-12 d-flex mt-5">
                                    <button class="btn btn-primary mt-auto" type="submit">@lang('labels.general.buttons.update')</button>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('after-scripts')
    <script>
    </script>
@endpush