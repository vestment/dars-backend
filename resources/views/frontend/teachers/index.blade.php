@extends('frontend.layouts.app')
@push('after-styles')
    <style>
        .couse-pagination li.active {
            color: #333333!important;
            font-weight: 700;
        }
        .page-link {
            position: relative;
            display: block;
            padding: .5rem .75rem;
            margin-left: -1px;
            line-height: 1.25;
            color: #c7c7c7;
            background-color: white;
            border: none;
        }
        .page-item.active .page-link {
            z-index: 1;
            color: #333333;
            background-color:white;
            border:none;

        }
        ul.pagination{
            display: inline;
            text-align: center;
		}
		.finger-img {
    position: relative;
    z-index: 1;
    width: 84%;
}
		.prof-img {
    position: absolute;
    margin-bottom: 100px;
    z-index: 2;
    right: -6px;
    top: 24px;
    left: -38px;
}
    </style>
@endpush
@section('content')

	<!-- Start of breadcrumb section
		============================================= -->
		<section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style">
			<div class="blakish-overlay"></div>
			<div class="container">
				<div class="page-breadcrumb-content text-center">
					<div class="page-breadcrumb-title">
						<h2 class="breadcrumb-head black bold">{{env('APP_NAME')}} <span>@lang('labels.frontend.teacher.title')</span></h2>
					</div>
				</div>
			</div>
		</section>
	<!-- End of breadcrumb section
		============================================= -->



	<!-- Start of teacher section
		============================================= -->
		<section id="teacher-page" class="teacher-page-section">
			<div class="container">
				<div class="row">
					<div class="col-md-9">
						<div class="teachers-archive">
							<div class="row">
							@if(count($teachers)> 0)
                    @foreach($teachers as $key=>$item)
                        @php
                            $teacherProfile = \App\Models\TeacherProfile::where('user_id',$item->id)->first();
                        @endphp
                        @if ($teacherProfile && $teacherProfile->academy_id != $academy_911)
                                <div class="item">
                                    <div class="text-center ">
                                        <div class="bg-card">
                                            <div>
                                                <div class="finger-img">
                                                    <img src="/assets/img/banner/01.png" alt="">
                                                </div>

                                                <div class="prof-img ">
                                                    @if($item->picture == "")
                                                        <a href="{{route('teachers.show',['id'=>$item->id])}}"><img
                                                                    class="teacher-image shadow-lg p-3"
                                                                    src="/assets/img/teacher/d8951937-b033-4829-8166-77a698ec46dc.jpeg"
                                                                    alt=""></a>
                                                    @else
                                                        <a href="{{route('teachers.show',['id'=>$item->id])}}"><img
                                                                    class="teacher-image shadow-lg p-3"
                                                                    src="{{$item->picture}}"
                                                                    alt=""></a>
                                                    @endif


                                                </div>
                                            </div>
                                            <div class="teacher-social-name ul-li-block pt-3">
                                                <div class="teacher-name text-dark font-weight-bold">
                                                    <h5>{{$item->full_name}}</h5>
                                                </div>
                                                <div class="teacher-title text-muted font-weight-light">
                                                    {{$teacherProfile->getDataFromColumn('title')}}
                                                </div>
                                                <hr>
                                                <div class="teacher-name text-dark  justify-content-center">
                                                    <span>{{$teacherProfile->getDataFromColumn('description')}}</span>
                                                </div>
                                                <ul>
                                                    <li><a href="{{'mailto:'.$item->email}}"><i
                                                                    class="fa fa-envelope"></i></a></li>
                                                    <li>
                                                        <a href="{{route('admin.messages',['teacher_id'=>$item->id])}}"><i
                                                                    class="fa fa-comments"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                    @endforeach
                @endif

							</div>
							<div class="couse-pagination text-center ul-li">
                                {{ $teachers->links() }}
							</div>
							
						</div>
					</div>
					@include('frontend.layouts.partials.right-sidebar')
				</div>
			</div>
		</section>
	<!-- End of teacher section
		============================================= -->



@endsection
