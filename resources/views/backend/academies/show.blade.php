@extends('backend.layouts.app')

@section('title', __('labels.backend.academies.title').' | '.app_name())
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
            <h3 class="page-title d-inline mb-0">@lang('labels.backend.academies.title')</h3>
            <div class="float-right">
                <a href="{{ route('admin.academies.index') }}"
                   class="btn btn-success">@lang('labels.backend.academies.view')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered table-striped">
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.avatar')</th>
                            <td><img src="{{ $academy->picture }}" class="user-profile-image" /></td>
                        </tr>

                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.name')</th>
                            <td>{{ $academy->name }}</td>
                        </tr>

                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.email')</th>
                            <td>{{ $academy->email }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.status')</th>
                            <td>{!! $academy->status_label !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.general_settings.user_registration_settings.fields.gender')</th>
                            <td>{!! $academy->gender !!}</td>
                        </tr>
                        <!-- @php
                            $teacherProfile = $academy->teacherProfile?:'';
                            $payment_details = $academy->teacherProfile?json_decode($academy->teacherProfile->payment_details):new stdClass();
                        @endphp -->
                        <tr>
                            <th>@lang('labels.academy.facebook_link')</th>
                            <td>{!! $academy->facebook_link !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.academy.twitter_link')</th>
                            <td>{!! $academy->twitter_link !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.academy.linkedin_link')</th>
                            <td>{!! $academy->linkedin_link !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.academy.payment_details')</th>
                            <td>{!! $academy->payment_method !!}</td>
                        </tr>
                        @if($academy->payment_method == 'bank')
                        <tr>
                            <th>@lang('labels.academy.bank_details.name')</th>
                            <td>{!! $payment_details->bank_name !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.academy.bank_details.bank_code')</th>
                            <td>{!! $payment_details->ifsc_code !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.academy.bank_details.account')</th>
                            <td>{!! $payment_details->account_number !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.academy.bank_details.holder_name')</th>
                            <td>{!! $payment_details->account_name !!}</td>
                        </tr>
                        @else
                       
                        @endif
                    </table>
                </div>
            </div><!-- Nav tabs -->
        </div>
    </div>
@stop
