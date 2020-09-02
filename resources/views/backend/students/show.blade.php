@extends('backend.layouts.app')

@section('title', __('labels.backend.students.title').' | '.app_name())
@push('after-styles')
<link rel="stylesheet" href="../../assets/css/course.css"/>
<link rel="stylesheet" href="../../assets/css/frontend.css"/>
    <style>
        table th {
            width: 20%;
        }
        .progresss {
   background-color: #b6b9bb;
   height: 3px;
   font-weight: bold;
   font-size: 0.8rem;
   padding : 0px !important;
   }
   .progress-bar{
   height: 4px !important;
   background-color:  #D2498B;
   }

   .best-course-pic-text:before {
    background-image: url(../../images/MIX-SLP8.svg);
    background-position: center;
    -webkit-filter: grayscale(100%);
    filter: grayscale(100%);
    content: ' ';
    position: absolute;
    top: 10%;
    z-index: -1;
    transform: scale(2);
    height: 100%;
    opacity: 0.05;
    overflow: hidden;
    width: 100%;
}
.best-course-pic-text {
    padding-top: 0;
    position:relative;
    margin-bottom: 10%;
    box-shadow: 0px 1px 5px #9ea3a9;
    transition: 0.3s ease;
}
.best-course-pic-text {
    border-radius: 4px;
    padding-top: 30px;
    overflow: hidden !important;
}
.best-course-pic-text:hover {
    box-shadow: 0px 10px 15px #9ea3a9;
    transform: scale(1.015);
}
   .pink{
   color:var(--pink);
   }
   .bg-pink{
   background-color:var(--pink);
   }
   .round{
   }
   .icon{
   font-size:43px;
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
   .titleofcard{
       font-weight: bolder;
   }
   
   .best-course-pic-text{
    width: 90% !important;
   }
   .progress-bar{
   height: 4px !important;
   background-color:  #D2498B;
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
                        <img src="{{asset($student->avatar_location)}}"  height="100px" class="img-rounded user-profile-image p-2"/>
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

                    <div  class="row m-3">
                            @if(count($purchased_courses) > 0)
                               @foreach($purchased_courses as $item)
                                  <div class="col-md-3">
                                     <div class="best-course-pic-text relative-position p-0" data-ref="partials">
                                        <a href="{{ route('courses.show', [$item->slug]) }}">
                                           <div class="best-course-pic piclip relative-position"
                                                 @if($item->image != "") style="background-image: url('{{$item->image}}')" @endif>
                                           </div>
                                        </a>
                                        <div class="card-body">
                                              <h3 class="card-title titleofcard">{{$item->getDataFromColumn('title')}}</h3>
                                              <div class="row p-4">
                                                    <button type="submit" class="btn btn-info btn-sm ml-1 sharebutton" data-toggle="modal"
                                                    data-target="#shareModal"><i class="fa fa-share-alt"
                                                                                 aria-hidden="true"></i>
                                                @lang('labels.frontend.course.Share')
                                            </button>
                                              </div>
                                           <div class="row m-1">
                                                 <div class="col-3 p-0 pl-1  ">
                                                 {{ $item->progress()}} %
                                                 </div>
                                                 <div class="progresss  mt-2 col-9">
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
                            {{-- @if(count($purchased_bundles) > 0)
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
                                                         <i class="far fa-user"></i> &nbsp &nbsp<span class="course-author float-right"> {{ $item->students()->count() }}
                                                         @lang('labels.backend.dashboard.students')
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
                          
                            @endif --}}
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
     <!-- Modal -->
     <div class="modal fade" id="shareModal" role="dialog">
            <div class="modal-dialog modal-lg">
    
                <!-- Modal content-->
                <div class="modal-content">
                    <div class="mo-head">
                       
                    </div>
                    <div class="modal-body">
                       
                    </div>
                </div>
            </div>
        </div>
@stop
  