@extends('backend.layouts.app')

@section('title', __('labels.backend.teachers.title').' | '.app_name())
@push('after-styles')
<style>
    table th {
        width: 20%;
    }
</style>
@endpush
@section('content')

    <div class="card">

        <div class="card-header">
            <h3 class="page-title d-inline mb-0">@lang('labels.backend.teachers.title')</h3>
            <div class="float-right">
                <a href="{{ route('admin.teachers.index') }}"
                   class="btn btn-pink">@lang('labels.backend.teachers.view')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table ">
                    @if($teacher->picture == null)
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.avatar')</th>
                            <td><img src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg" style="width:9%" /></td>
                        </tr>
                    @else
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.avatar')</th>
                            <td><img src="{{asset($teacher->picture)}}" height="100px" class="img-rounded user-profile-image p-2" /></td>
                        </tr>
                        @endif

                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.name')</th>
                            <td>{{ $teacher->full_name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.type')</th>
                            <td>{{ $teacher->teacherProfile->type }}</td>
                        </tr>
                        
                      

                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.email')</th>
                            <td>{{ $teacher->email }}</td>
                        </tr>
                       
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.phone')</th>
                            <td>{{ $teacher->phone }}</td>
                        </tr>
                       
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.status')</th>
                            <td>{!! $teacher->status_label !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.general_settings.user_registration_settings.fields.gender')</th>
                            <td>{!! $teacher->gender !!}</td>
                        </tr>
                        @php
                            $teacherProfile = $teacher->teacherProfile?:'';
                            $payment_details = $teacher->teacherProfile?json_decode($teacher->teacherProfile->payment_details):new stdClass();
                        @endphp
                       
                     
                     
                       
                    </table>
                </div>
            </div><!-- Nav tabs -->
        </div>
    </div>
@stop
