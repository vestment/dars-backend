<!-- Start of course teacher
        ============================================= -->
<section id="course-teacher" class="course-teacher-section p-5">
    <div class="">
        <div class="container ">
            <div class=" section-title mb20 headline p-5 mb-5">
                <span class=" subtitle text-uppercase font-weight-lighter">@lang('labels.frontend.home.our_professionals')</span>
                <h2 class="text-dark font-weight-bolder "><span>@lang('labels.frontend.home.Instructors').<span>
                </h2>
            </div>

            <div class="owl-carousel custom-owl-theme">
                @if(count($teachers)> 0)
                    @foreach($teachers as $key=>$item)
                        @foreach($teacher_data as $teacher)
                            @if($item->id == $teacher->user_id)
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
                                                    {{$teacher->getDataFromColumn('title')}}
                                                </div>
                                                <hr>
                                                <div class="teacher-name text-dark  justify-content-center">
                                                    <span>{{$teacher->getDataFromColumn('description')}}</span>
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
                    @endforeach
                @endif
            </div>

        </div>
    </div>
</section>

<!-- End of course teacher
    ============================================= -->
