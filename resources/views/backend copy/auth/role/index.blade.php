@extends('backend.layouts.app')

@section('title', app_name() . ' | '. __('labels.backend.access.roles.management'))

@section('content')
<div class="title my-3 mx-5">
                <h3 class=" mb-0">
                    @lang('labels.backend.access.roles.management')
                </h3>
            </div><!--col-->
            <div class="shadow-lg p-3 mb-5 bg-white rounded">
    <div class="card-body p-5">
        <div class="mb-5">
          

            <div class="pull-right">
                @include('backend.auth.role.includes.header-buttons')
            </div><!--col-->
        </div><!--row-->

        <div class="row mt-4">
            <div class="col">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>@lang('labels.backend.access.roles.table.role')</th>
                            <th>@lang('labels.backend.access.roles.table.permissions')</th>
                            <th>@lang('labels.backend.access.roles.table.number_of_users')</th>
                            <th>@lang('labels.general.actions')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ ucwords($role->name) }}</td>
                                <td>
                                    @if($role->id == 1)
                                        @lang('labels.general.all')
                                    @else
                                        @if($role->permissions->count())
                                            @foreach($role->permissions as $permission)
                                                {{ ucwords($permission->name) }}
                                            @endforeach
                                        @else
                                            @lang('labels.general.none')
                                        @endif
                                    @endif
                                </td>
                                <td>{{ $role->users->count() }}</td>
                                <td>{!! $role->action_buttons !!}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div><!--col-->
        </div><!--row-->
        <div class="row">
            <div class="col-7">
                <div class="float-left">
                    {!! $roles->total() !!} {{ trans_choice('labels.backend.access.roles.table.total', $roles->total()) }}
                </div>
            </div><!--col-->

            <div class="col-5">
                <div class="float-right">
                    {!! $roles->render() !!}
                </div>
            </div><!--col-->
        </div><!--row-->
    </div><!--card-body-->
</div><!--card-->
@endsection
