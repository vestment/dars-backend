@extends('backend.layouts.app')

@section('title', __('labels.backend.students.title').' | '.app_name())
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
            <h3 class="page-title d-inline mb-0">@lang('labels.backend.students.title')</h3>
            <div class="float-right">
                <a href="{{ route('admin.students.index') }}"
                   class="btn btn-pink">@lang('labels.backend.students.view')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    @if($student->avatar_location == null)
                        <img src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg" style="width:9%"/>
                    @else
                        <img src="{{asset($student->avatar_location)}}" class="img-rounded user-profile-image p-2"/>
                    @endif
                </div>
                <div class="col-6">
                    <table class="table ">
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.name')</th>
                            <td>{{ $student->full_name }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.city')</th>
                            <td>{{ $student->getDataFromColumn('city') }}</td>
                        </tr>

                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.email')</th>
                            <td>{{ $student->email }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.address')</th>
                            <td>{{ $student->getDataFromColumn('address') }}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.phone')</th>
                            <td>{{ $student->phone }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-6">
                    <table class="table">


                        <tr>
                            <th>@lang('labels.backend.access.users.tabs.content.overview.status')</th>
                            <td>{!! $student->status_label !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.backend.general_settings.user_registration_settings.fields.gender')</th>
                            <td>{!! $student->gender !!}</td>
                        </tr>

                        <tr>
                            <th>@lang('labels.teacher.facebook_link')</th>
                            <td>{!! $student->facebook_link !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.teacher.twitter_link')</th>
                            <td>{!! $student->twitter_link !!}</td>
                        </tr>
                        <tr>
                            <th>@lang('labels.teacher.linkedin_link')</th>
                            <td>{!! $student->linkedin_link !!}</td>
                        </tr>
                    </table>
                </div>


            </div>
        </div><!-- Nav tabs -->
    </div>
    <style>
        .best-course-pic {
            background-color: #73818f;
            background-position: center;
            background-size: cover;
            height: 150px;
            width: 100%;
            background-repeat: no-repeat;
        }
        .progress {
            background-color: #b6b9bb;
            height: 2em;
            font-weight: bold;
            font-size: 0.8rem;
            text-align: center;
        }
    </style>
    <div class="card">
        <div class="card-header"><h3 class="page-title d-inline mb-0">@lang('labels.backend.dashboard.courses')</h3>
        </div>
        <div class="card-body">
            <div class="col-12">
                @if(count($purchased_courses) > 0)
                    @foreach($purchased_courses as $item)

                        <div class="col-md-3">
                            <div class="best-course-pic-text position-relative border">
                                <div class="best-course-pic position-relative overflow-hidden"
                                     @if($item->course_image != "") style="background-image: url({{asset('storage/uploads/'.$item->course_image)}})" @endif>

                                    @if($item->trending == 1)
                                        <div class="trend-badge-2 text-center text-uppercase">
                                            <i class="fas fa-bolt"></i>
                                            <span>@lang('labels.backend.dashboard.trending') </span>
                                        </div>
                                    @endif

                                    <div class="course-rate ul-li">
                                        <ul>
                                            @for($i=1; $i<=(int)$item->rating; $i++)
                                                <li><i class="fas fa-star"></i></li>
                                            @endfor
                                        </ul>
                                    </div>
                                </div>
                                <div class="best-course-text d-inline-block w-100 p-2">
                                    <div class="course-title mb20 headline relative-position">
                                        <h5>
                                            <a href="{{ route('courses.show', [$item->slug]) }}">{{$item->title}}</a>
                                        </h5>
                                    </div>
                                    <div class="course-meta d-inline-block w-100 ">
                                        <div class="d-inline-block w-100 0 mt-2">
                                                     <span class="course-category float-left">
                                                <a href="{{route('courses.category',['category'=>$item->category->slug])}}"
                                                   class="bg-success text-decoration-none px-2 p-1">{{$item->category->name}}</a>
                                            </span>
                                            <span class="course-author float-right">
                                                 {{ $item->students()->count() }}
                                                @lang('labels.backend.dashboard.students')
                                            </span>
                                        </div>

                                        <div class="progress my-2">
                                            <div class="progress-bar text-light"
                                                 style="width:{{$item->progress() }}%">
                                                @lang('labels.backend.dashboard.completed')
                                                {{ $item->progress()  }} %
                                            </div>
                                        </div>
                                        @if($item->progress() == 100)
                                            @if(!$item->isUserCertified())
                                                <form method="post"
                                                      action="{{route('admin.certificates.generate')}}">
                                                    @csrf
                                                    <input type="hidden" value="{{$item->id}}"
                                                           name="course_id">
                                                    <button class="btn btn-success btn-block text-white mb-3 text-uppercase font-weight-bold"
                                                            id="finish">@lang('labels.frontend.course.finish_course')</button>
                                                </form>
                                            @else
                                                <div class="alert alert-success px-1 text-center mb-0">
                                                    @lang('labels.frontend.course.certified')
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <h4 class="text-center">@lang('labels.backend.dashboard.no_data')</h4>
                        <a class="btn btn-primary"
                           href="{{route('courses.all')}}">@lang('labels.backend.dashboard.buy_course_now')
                            <i class="fa fa-arrow-right"></i></a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop
