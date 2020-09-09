@extends('backend.layouts.app')
@section('title', __('strings.backend.dashboard.title').' | '.app_name())
@push('after-styles')
    <link rel="stylesheet" href="../../assets/css/course.css"/>
    <style>
        :root {
            --pink: #D2498B;
        }

        .modal-degrees th {
            width: 20%;
        }

        .btn-pink {
            background-color: var(--pink);
            color: #fff;
            border: none;

            padding: 4%;
            width: 25%;
        }

        .best-course-pic-text:before {
            background-image: url(../../images/MIX-SLP8.svg);
            background-position: center;
            -webkit-filter: grayscale(100%);
            filter: grayscale(100%);
            content: ' ';
            position: absolute;
            top: 10%;
            z-index: 0;
            transform: scale(2);
            height: 100%;
            opacity: 0.05;
            overflow: hidden;
            width: 100%;
        }

        .best-course-pic-text {
            position: relative;
        }

        .best-course-pic-text {
            border-radius: 4px;
            overflow: hidden !important;
        }

        .best-course-pic-text:hover {
            box-shadow: 0px 10px 15px #9ea3a9;
            transform: scale(1.015);
        }

        .card-body {
            z-index: 10;
        }

        .pink {
            color: var(--pink);
        }

        .bg-pink {
            background-color: var(--pink);
        }

        .round {
        }

        .icon {
            font-size: 43px;
        }

        .trend-badge-2 {
            top: -10px;
            left: -52px;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            position: absolute;
            padding: 40px 40px 12px;
            -webkit-transform: rotate(-45deg);
            transform: rotate(-45deg);
            background-color: #ff5a00;
        }

        .titleofcard {
            font-weight: bolder;
        }

        .progress {
            background-color: #b6b9bb;
            height: 3px;
            font-weight: bold;
            font-size: 0.8rem;
            padding: 0px !important;
        }

        .best-course-pic-text {
            width: 90% !important;
        }

        .progress-bar {
            height: 4px !important;
            background-color: #D2498B;
        }

        .best-course-pic {
            background-color: #333333;
            background-position: center;
            background-size: cover;
            height: 150px;
            width: 100%;
            background-repeat: no-repeat;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <h1>@lang('strings.backend.dashboard.welcome') {{ $logged_in_user->full_name }}
                        ! {{auth()->user()->id}}</h1>
                </div>
                <!--card-header-->
                <div class="card-body">
                    <div class="row">
                        @if(auth()->user()->hasRole('student'))
                            @if(count($pending_orders) > 0)
                                <div class="col-12">
                                    <h4>@lang('labels.backend.dashboard.pending_orders')</h4>
                                </div>
                                <div class="col-12 text-center shadow-lg p-3 mb-5 bg-white rounded">
                                    <table class="table table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>@lang('labels.general.sr_no')</th>
                                            <th>@lang('labels.backend.orders.fields.reference_no')</th>
                                            <th>@lang('labels.backend.orders.fields.items')</th>
                                            <th>@lang('labels.backend.orders.fields.amount')
                                                <small>(in {{$appCurrency['symbol']}})</small>
                                            </th>
                                            <th>@lang('labels.backend.orders.fields.payment_status.title')</th>
                                            <th>@lang('labels.backend.orders.fields.date')</th>
                                            <th>@lang('labels.backend.orders.fields.fawry_status')</th>
                                            <th>@lang('labels.backend.orders.fields.fawry_ref_no')</th>
                                            <th>@lang('labels.backend.orders.fields.fawry_expirationTime')</th>
                                            <th></th>


                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($pending_orders as $key=>$item)
                                            @php $key++ @endphp
                                            <tr>
                                                <td>
                                                    {{$key}}
                                                </td>
                                                <td>
                                                    {{$item->reference_no}}
                                                </td>
                                                <td>
                                                    @foreach($item->items as $key=>$subitem)
                                                        @php $key++ @endphp
                                                        @if($subitem->item != null)
                                                            {{$key.'. '.$subitem->item->title}} <br>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    {{$item->amount}}
                                                </td>
                                                <td>
                                                    @if($item->status == 0)
                                                        @lang('labels.backend.dashboard.pending')
                                                    @elseif($item->status == 1)
                                                        @lang('labels.backend.dashboard.success')
                                                    @elseif($item->status == 2)
                                                        @lang('labels.backend.dashboard.failed')
                                                    @endif
                                                </td>
                                                <td>
                                                    {{$item->created_at->format('d-m-Y h:i:s')}}
                                                </td>
                                                <td>
                                                    {{$item->fawry_status}}
                                                </td>
                                                <td>
                                                    {{$item->fawry_ref_no}}
                                                </td>
                                                <td>
                                                    {{$item->fawry_expirationTime}}
                                                </td>
                                                <td>
                                                    <button class="btn btn-danger"
                                                            onclick="cancleRequest({{$item->id}})"> Cancle Request
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                    </div>
                    <div class="col-12">
                        <h4>@lang('labels.backend.dashboard.my_courses')</h4>
                    </div>

                    <div class="row m-3">
                        @if(count($purchased_courses) > 0)
                            @foreach($purchased_courses as $item)
                                @php
                                    $orderItem = $item->orderItem()->whereHas('order',function($query) {
                                           $query->where('user_id',  auth()->user()->id)->where('status',1);
                                       })->first();
                                @endphp
                                <div class="col-md-3">
                                    <div class="best-course-pic-text card" data-ref="partials">
                                        <a href="{{ route('courses.show', [$item->slug]) }}">
                                            <div class="best-course-pic piclip relative-position"
                                                 @if($item->image != "") style="background-image: url('{{$item->image}}')" @endif>
                                            </div>
                                        </a>
                                        <div class="card-body">
                                            <a class="text-dark text-decoration-none"
                                               href="{{ route('courses.show', [$item->slug]) }}">
                                                <h3 class="card-title titleofcard"> {{$item->getDataFromColumn('title')}}</h3>
                                            </a>
                                            @if($orderItem && $orderItem->selectedDate)
                                                <div class="alert alert-info">
                                                    Booked date: {{$orderItem->selectedDate}}<br>
                                                    Booked time: {{$orderItem->selectedTime}}
                                                </div>
                                            @endif
                                            <div class="row p-4">
                                                <button type="submit" class="btn-info btn col-6"
                                                        onclick="courseChapters({{$item->id}})" data-toggle="modal"
                                                        data-target="#gradesModal"><i
                                                            class="fas fa-graduation-cap d-block"></i>
                                                    @lang('labels.backend.dashboard.grades')
                                                </button>
                                            </div>
                                            <div class="row m-1">
                                                <div class="col-3 p-0 pl-1  ">
                                                    {{ $item->progress()}} %
                                                </div>
                                                <div class="progress  mt-2 col-9">
                                                    <div class="progress-bar"
                                                         style="width:{{$item->progress() }}%">
                                                    </div>
                                                </div>
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
                        @if(count($purchased_bundles) > 0)
                            <div class="col-12 mt-5">
                                <h4>@lang('labels.backend.dashboard.my_course_bundles')</h4>
                            </div>
                            @foreach($purchased_bundles as $key=>$bundle)
                                @php $key++ @endphp
                                <div class="col-12">
                                    <h5><a
                                                href="{{route('bundles.show',['slug'=>$bundle->slug ])}}">
                                            {{$key.'. '.$bundle->title}}</a>
                                    </h5>
                                </div>
                                @if(count($bundle->courses) > 0)
                                    @foreach($bundle->courses as $item)
                                        <div class="col-md-3 mb-5">
                                            <div class="best-course-pic-text relative-position p-0" data-ref="partials">
                                                <a href="{{ route('courses.show', [$item->slug]) }}">
                                                    <div class="best-course-pic piclip relative-position"
                                                         @if($item->course_image != "") style="background-image: url('{{asset('storage/uploads/'.$item->course_image)}}')" @endif>
                                                    </div>
                                                </a>
                                                <div class="card-body">
                                                    <h3 class="card-title titleofcard">{{$item->getDataFromColumn('title')}}</h3>
                                                    <div class="row p-4">
                               <span class="course-author float-right"> {{ $item->count() }}
                                   @lang('labels.backend.dashboard.course')
                                </span>
                                                    </div>
                                                    <div class="row m-1">
                                                        <div class="col-3 p-0 pl-1  ">
                                                            {{ $item->progress()}} %
                                                        </div>
                                                        <div class="progress  mt-2 col-9">
                                                            <div class="progress-bar"
                                                                 style="width:{{$item->progress() }}%">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            @endforeach

                        @endif
                    </div>
                    @elseif(auth()->user()->hasRole('parent'))
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-3 col-12 border-right">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-inline-block form-group w-100">
                                                <h4 class="mb-0">@lang('labels.backend.students.title')
                                                    ({{count($parent->students)}})<a
                                                            class="btn btn-primary float-right"
                                                            href="{{route('admin.students.index')}}">@lang('labels.backend.dashboard.view_all')</a>
                                                </h4>
                                            </div>
                                            <table class="table table-responsive-sm table-striped">
                                                <thead>
                                                <tr>
                                                    <td>@lang('labels.backend.sign_up.user_name')</td>
                                                    <td>@lang('labels.backend.dashboard.email')</td>
                                                    <td>@lang('labels.backend.general_settings.api_clients.fields.action')</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($parent->students as $student)
                                                    <tr>
                                                        <td>
                                                            <a target="_blank"
                                                               href="{{route('admin.students.show',[$student])}}">{{$student->full_name}}</a>
                                                        </td>
                                                        <td>
                                                            {{$student->email}}
                                                        </td>
                                                        <td>
                                                            <a href="{{route('admin.students.edit',[$student])}}"
                                                               class="btn btn-blue mb-1"><i class="icon-pencil"></i></a>
                                                        </td>
                                                        {{--
                                                        <td>{{$item->created_at->diffforhumans()}}</td>
                                                        --}}
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-12 border-right">
                                    <div class="d-inline-block form-group w-100">
                                        <h4 class="mb-0">@lang('labels.backend.dashboard.recent_reviews') <a
                                                    class="btn btn-primary float-right"
                                                    href="{{route('admin.reviews.index')}}">@lang('labels.backend.dashboard.view_all')</a>
                                        </h4>
                                    </div>
                                    <table class="table table-responsive-sm table-striped">
                                        <thead>
                                        <tr>
                                            <td>@lang('labels.backend.dashboard.course')</td>
                                            <td>@lang('labels.backend.dashboard.review')</td>
                                            <td>@lang('labels.backend.dashboard.time')</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($recent_reviews) > 0)
                                            @foreach($recent_reviews as $item)
                                                <tr>
                                                    <td>
                                                        <a target="_blank"
                                                           href="{{route('courses.show',[$item->reviewable->slug])}}">{{$item->reviewable->title}}</a>
                                                    </td>
                                                    <td>{{$item->content}}</td>
                                                    {{--
                                                    <td>{{$item->created_at->diffforhumans()}}</td>
                                                    --}}
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">@lang('labels.backend.dashboard.no_data')</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="d-inline-block form-group w-100">
                                        <h4 class="mb-0">@lang('labels.backend.dashboard.recent_messages') <a
                                                    class="btn btn-primary float-right"
                                                    href="{{route('admin.messages')}}">@lang('labels.backend.dashboard.view_all')</a>
                                        </h4>
                                    </div>
                                    <table class="table table-responsive-sm table-striped">
                                        <thead>
                                        <tr>
                                            <td>@lang('labels.frontend.course.students')</td>
                                            <td>@lang('labels.backend.courses.title')</td>
                                            <td>@lang('labels.frontend.course.progress')</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($purchased_courses) > 0)
                                            @foreach($purchased_courses as $course)
                                                <tr>
                                                    <td>@foreach($course->students as $student)
                                                            @if (array_search($student->id,array_column($parent->students->toArray(), 'id')))
                                                                <a target="_blank"
                                                                   href="{{route('admin.students.show',$student)}}">{{$student->full_name}}</a>
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <a target="_blank"
                                                           href="{{route('courses.show',$course)}}">{{$course->getDataFromColumn('title')}}</a>
                                                    </td>
                                                    <td>
                                                        <div class="progress my-2">
                                                            <div class="progress-bar"
                                                                 style="width:{{$course->progress() }}%">
                                                                @lang('labels.frontend.course.progress')
                                                                {{ $course->progress()  }} %
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">@lang('labels.backend.dashboard.no_data')</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @elseif(auth()->user()->hasRole('academy'))
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-3 col-12 border-right">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card text-dark bg-white ">
                                                <div class="card-body">
                                                    <h5 class="">@lang('labels.backend.dashboard.course_and_bundles')</h5>
                                                    <h2 class="">{{$courses_count + $bundles_count}}</h2>
                                                    <h6>{{$courses_count}} Courses | {{$bundles_count}} Bundle</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="card text-dark bg-white ">
                                                <div class="card-body">
                                                    <h2 class="">{{$students_count}}</h2>
                                                    <h5>@lang('labels.backend.dashboard.students_enrolled')</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-12 border-right">
                                    <div class="d-inline-block form-group w-100">
                                        <h4 class="mb-0">@lang('labels.backend.dashboard.recent_reviews') <a
                                                    class="btn btn-primary float-right"
                                                    href="{{route('admin.reviews.index')}}">@lang('labels.backend.dashboard.view_all')</a>
                                        </h4>
                                    </div>
                                    <table class="table table-responsive-sm table-striped">
                                        <thead>
                                        <tr>
                                            <td>@lang('labels.backend.dashboard.course')</td>
                                            <td>@lang('labels.backend.dashboard.review')</td>
                                            <td>@lang('labels.backend.dashboard.time')</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($recent_reviews) > 0)
                                            @foreach($recent_reviews as $item)
                                                <tr>
                                                    <td>
                                                        <a target="_blank"
                                                           href="{{route('courses.show',[$item->reviewable->slug])}}">{{$item->reviewable->title}}</a>
                                                    </td>
                                                    <td>{{$item->content}}</td>
                                                    {{--
                                                    <td>{{$item->created_at->diffforhumans()}}</td>
                                                    --}}
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">@lang('labels.backend.dashboard.no_data')</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="d-inline-block form-group w-100">
                                        <h4 class="mb-0">@lang('labels.backend.dashboard.recent_messages') <a
                                                    class="btn btn-primary float-right"
                                                    href="{{route('admin.messages')}}">@lang('labels.backend.dashboard.view_all')</a>
                                        </h4>
                                    </div>
                                    <table class="table table-responsive-sm table-striped">
                                        <thead>
                                        <tr>
                                            <td>@lang('labels.backend.dashboard.message_by')</td>
                                            <td>@lang('labels.backend.dashboard.message')</td>
                                            <td>@lang('labels.backend.dashboard.time')</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($threads) > 0)
                                            @foreach($threads as $item)
                                                <tr>
                                                    <td>
                                                        <a target="_blank"
                                                           href="{{asset('/user/messages/?thread='.$item->id)}}">{{$item->title}}</a>
                                                    </td>
                                                    <td>{{$item->lastMessage->body}}</td>
                                                    <td>{{$item->lastMessage->created_at->diffForHumans() }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">@lang('labels.backend.dashboard.no_data')</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @elseif(auth()->user()->hasRole('teacher'))
                        <div class="col-12">
                            <div class="row">
                                <div class="col-md-3 col-12 border-right">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card text-dark bg-white ">
                                                <div class="card-body">
                                                    <h5 class="">@lang('labels.backend.dashboard.your_courses_and_bundles')</h5>
                                                    <h2 class="">{{count(auth()->user()->courses) + count(auth()->user()->bundles)}}</h2>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="card text-dark bg-white ">
                                                <div class="card-body">
                                                    <h2 class="">{{$students_count}}</h2>
                                                    <h5>@lang('labels.backend.dashboard.students_enrolled')</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 col-12 border-right">
                                    <div class="d-inline-block form-group w-100">
                                        <h4 class="mb-0">@lang('labels.backend.dashboard.recent_reviews') <a
                                                    class="btn btn-primary float-right"
                                                    href="{{route('admin.reviews.index')}}">@lang('labels.backend.dashboard.view_all')</a>
                                        </h4>
                                    </div>
                                    <table class="table table-responsive-sm table-striped">
                                        <thead>
                                        <tr>
                                            <td>@lang('labels.backend.dashboard.course')</td>
                                            <td>@lang('labels.backend.dashboard.review')</td>
                                            <td>@lang('labels.backend.dashboard.time')</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($recent_reviews) > 0)
                                            @foreach($recent_reviews as $item)
                                                <tr>
                                                    <td>
                                                        <a target="_blank"
                                                           href="{{route('courses.show',[$item->reviewable->slug])}}">{{$item->reviewable->title}}</a>
                                                    </td>
                                                    <td>{{$item->content}}</td>
                                                    {{--
                                                    <td>{{$item->created_at->diffforhumans()}}</td>
                                                    --}}
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">@lang('labels.backend.dashboard.no_data')</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-md-4 col-12">
                                    <div class="d-inline-block form-group w-100">
                                        <h4 class="mb-0">@lang('labels.backend.dashboard.recent_messages') <a
                                                    class="btn btn-primary float-right"
                                                    href="{{route('admin.messages')}}">@lang('labels.backend.dashboard.view_all')</a>
                                        </h4>
                                    </div>
                                    <table class="table table-responsive-sm table-striped">
                                        <thead>
                                        <tr>
                                            <td>@lang('labels.backend.dashboard.message_by')</td>
                                            <td>@lang('labels.backend.dashboard.message')</td>
                                            <td>@lang('labels.backend.dashboard.time')</td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($threads) > 0)
                                            @foreach($threads as $item)
                                                <tr>
                                                    <td>
                                                        <a target="_blank"
                                                           href="{{asset('/user/messages/?thread='.$item->id)}}">{{$item->title}}</a>
                                                    </td>
                                                    @if($item->lastMessage)
                                                        <td>{{$item->lastMessage->body}}</td>
                                                        <td>{{$item->lastMessage->created_at->diffForHumans() }}</td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">@lang('labels.backend.dashboard.no_data')</td>
                                            </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @elseif(auth()->user()->hasRole('administrator'))
                        <div class="col-md-4 col-12">
                            <div class="card shadow-lg text-dark bg-white  py-3 mx-1">
                                <div class="card-body row">
                                    <div class="col-9">
                                        <h4 class="text-primary">@lang('labels.backend.dashboard.course_and_bundles')</h4>
                                        <h1 class="">{{$courses_count}}</h1>
                                    </div>
                                    <div class="col-2 border rounded-circle text-white bg-primary text-center  py-2">
               <span class="icon">
               <i class="fas fa-graduation-cap"></i>
               </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="card shadow-lg text-white bg-white text-dark  py-3 mx-1">
                                <div class="card-body row">
                                    <div class="col-9">
                                        <h4 class="pink">@lang('labels.backend.dashboard.students')</h4>
                                        <h1 class="">{{$students_count}}</h1>
                                    </div>
                                    <div class="col-2 border rounded-circle text-white bg-pink text-center py-2">
               <span class="icon">
               <i class="fas fa-user-friends"></i>                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="card shadow-lg text-dark bg-white  py-3">
                                <div class="card-body row ">
                                    <div class="col-9">
                                        <h4 class="text-primary">@lang('labels.backend.dashboard.teachers')</h4>
                                        <h1 class="">{{$teachers_count}}</h1>
                                    </div>
                                    <div class="col-2 border rounded-circle text-white bg-primary text-center py-2">
               <span class="icon">
               <i class="fas fa-user-edit"></i>                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-lg p-3 mb-5 bg-white rounded col-md-6 col-12">
                            <div class="card-header ">
                                <h4 class="mb-0">@lang('labels.backend.dashboard.recent_orders') <a
                                            class="btn btn-primary float-right"
                                            href="{{route('admin.orders.index')}}">@lang('labels.backend.dashboard.view_all')</a>
                                </h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-responsive-sm table-striped">
                                    <thead>
                                    <tr>
                                        <td>@lang('labels.backend.dashboard.ordered_by')</td>
                                        <td>@lang('labels.backend.dashboard.amount')</td>
                                        <td>@lang('labels.backend.dashboard.time')</td>
                                        <td>@lang('labels.backend.dashboard.view')</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($recent_orders) > 0)
                                        @foreach($recent_orders as $item)
                                            <tr>
                                                <td>
                                                    {{$item->user ? $item->user->full_name : html_entity_decode("<span class='text-danger'>error showing user data</span>")}}
                                                </td>
                                                <td>{{$item->amount.' '.$appCurrency['symbol']}}</td>
                                                <td>{{$item->created_at->diffforhumans()}}</td>
                                                <td><a class="btn btn-sm btn-primary"
                                                       href="{{route('admin.orders.show', $item->id)}}" target="_blank"><i
                                                                class="fa fa-arrow-right"></i></a></td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4">@lang('labels.backend.dashboard.no_data')</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card shadow-lg p-3 mb-5 bg-white rounded col-md-6 col-12 p  x-2">
                            <div class="card-header d-inline-block form-group w-100">
                                <h4 class="mb-0">@lang('labels.backend.dashboard.recent_contact_requests') <a
                                            class="btn btn-primary float-right"
                                            href="{{route('admin.contact-requests.index')}}">@lang('labels.backend.dashboard.view_all')</a>
                                </h4>
                            </div>
                            <div class="card-body">
                                <table class="table table-responsive-sm table-striped">
                                    <thead>
                                    <tr>
                                        <td>@lang('labels.backend.dashboard.name')</td>
                                        <td>@lang('labels.backend.dashboard.email')</td>
                                        <td>@lang('labels.backend.dashboard.message')</td>
                                        <td>@lang('labels.backend.dashboard.time')</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($recent_contacts) > 0)
                                        @foreach($recent_contacts as $item)
                                            <tr>
                                                <td>
                                                    {{$item->name}}
                                                </td>
                                                <td>{{$item->email}}</td>
                                                <td>{{$item->message}}</td>
                                                <td>{{$item->created_at->diffforhumans()}}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4">@lang('labels.backend.dashboard.no_data')</td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="col-12">
                            <h1>@lang('labels.backend.dashboard.title')</h1>
                        </div>
                    @endif
                </div>


            </div>
            <!--card-body-->
        </div><!--card-->
    </div><!--col-->

    <!-- Modal -->
    <div class="modal fade" id="gradesModal" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4>@lang('labels.backend.dashboard.chapters')</h4>
                </div>
                <div class="modal-body">
                    <table>
                        <thead class="modal-degrees">
                        <th>@lang('labels.backend.dashboard.chapters')</th>
                        <th>@lang('labels.backend.dashboard.test')</th>
                        <th>@lang('labels.backend.dashboard.results')</th>
                        </thead>
                        <tbody id="chaptersTable">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


<script>
    function cancleRequest(id) {

        console.log(id);


        $.ajax({
            url: "{{route('order.cancleRequest')}}",
            method: "POST",
            data: {
                "_token": "{{ csrf_token() }}",
                'order_id': parseInt(id),

            },
        });

        location.reload();


    }

</script>
<script>
    function courseChapters(id) {
        $.ajax({
            url: "{{route('admin.students.get_chapters')}}",
            method: "get",
            data: {
                "_token": "{{ csrf_token() }}",
                'id': id,
            },
            success: function (result) {
                $('#chaptersTable').html('');
                $(result).each(function (key, value) {

                    var resultsTD = '';
                    $(value.chapter.test.results).each(function (key, value) {
                        resultsTD = resultsTD.concat('<p>@lang('labels.backend.dashboard.attempt') ( ' + value.attempts + ' ) : ' + value.test_result + '</p>');
                    })
                    var trElem = '<tr><td>' + value.chapter.title + '</td><td>' + value.chapter.test.title + '</td><td>' + resultsTD + '</td></tr>'
                    $('#chaptersTable').append(trElem)
                })
            }
        });
    }
</script>
