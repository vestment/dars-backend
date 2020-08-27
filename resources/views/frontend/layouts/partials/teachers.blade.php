<!-- Start of course teacher
        ============================================= -->
<section id="course-teacher" class="course-teacher-section p-5">
    <div class="">
        <div class="container ">
            <div class=" section-title mb20 headline p-5 mb-5">
                <span class=" subtitle text-uppercase font-weight-lighter">@lang('labels.frontend.home.our_professionals')</span>
                <h4 class="text-dark font-weight-bolder instructresp title"><span>@lang('labels.frontend.home.Instructors') <a class="view-more" href="{{route('teachers.index')}}">@lang('labels.frontend.layouts.partials.view_more') </a><span>
                </h4>
            </div>

            <div class="owl-carousel custom-owl-theme">
                @if(count($teachers)> 0)
                    @foreach($teachers as $key=>$item)
                        @php
                            $teacherProfile = \App\Models\TeacherProfile::where('user_id',$item->id)->first();
                        @endphp
                        @if ($teacherProfile)
                                <div class="item">
                                    <div class="text-center ">
                                        <div class="bg-card">
                                            <div>
                                                <div class="finger-img">
                                                    <img src="/assets/img/banner/01.png" alt="">
                                                </div>

                                                <div class="prof-img ">
                                                  
                                                        <a href="{{route('teachers.show',['id'=>$item->id])}}"><img
                                                                    class="teacher-image shadow-lg p-3"
                                                                    src="{{$item->picture}}"
                                                                    alt=""></a>
                                              


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
           
        </div>
    </div>
</section>

<!-- End of course teacher
    ============================================= -->
