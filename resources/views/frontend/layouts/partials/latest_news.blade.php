<section id="latest-area" class="">
    <div class="container-fluid">
        <div class="section-title  text-dark p-5">
            <p class="subtitle font-weight-lighter">The world's largest selection of courses</p>
            <h2 class="font-weight-bolder">Trending <span>Courses.</span> </h2>
            <p>Choose from 100,000 online video courses with new additions published every month</p>
        </div>
        <div class="owl-carousel default-owl-theme p-3">
            @if($trending->count() > 0)

                @foreach($trending as $course)
                    <div class="item">

                        <div class="">
                            <div class="best-course-pic-text relative-position">
                                <div class="best-course-pic piclip relative-position"
                                     @if($course->course_image != "") style="background-image: url('{{asset('storage/uploads/'.$course->course_image)}}')" @endif>
                                    <div class="course-price text-center gradient-bg">
                                        @if($course->free == 1)
                                            <span>{{trans('labels.backend.courses.fields.free')}}</span>
                                        @else
                                            <span> {{$appCurrency['symbol'].' '.$course->price}}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title titleofcard">{{$course->title}}</h3>
                                    <div class="row">
                                        <div class="col-12">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <span class="ml-1  rate">0</span>
                                        </div>
                                    </div>
                                    <div class="course-meta my-1 vv">
                                            <span class="course-category">
                                                <a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a>
                                            </span>
                                        <span class="course-author"><a href="#">{{ $course->students()->count() }}
                                                @lang('labels.frontend.course.students')</a></span>
                                        <span class="course-author">
                                                    {{ $course->lessons()->count() }} @lang('labels.backend.courses.lessons') 
                                            </span>
                                    </div>
                                    <div class="row my-2">
                                        <div class="col-3">
                                            <img src="../../assets/img/course/c-3.jpg" class="rounded-circle">
                                        </div>
                                        <div class="col-9">
                                            <div class="row">
                                                @foreach($course->teachers as $key=>$teacher)
                                                    <?php
                                                    $teacherProfile = \App\Models\TeacherProfile::where('user_id',$teacher->id)->get()[0];
                                                    ?>
                                                    @php $key++ @endphp
                                                    <a class="text-pink" href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                                       target="_blank">
                                                        {{$teacher->full_name}}@if($key < count($course->teachers ))
                                                            , @endif
                                                    </a>
                                                    @php $key++ @endphp
                                                    <a class="text-muted teacher-title" href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                                       target="_blank">
                                                        {{$teacherProfile->title}}
                                                    </a>
{{--                                                    <a href="{{route('teachers.show',['id'=>$teacher->id])}}"--}}
{{--                                                       target="_blank">--}}
{{--                                                        {{$teacher->title}}--}}
{{--                                                    </a>--}}
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-10 col-9">
                                            @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                                <button type="submit"
                                                        class="btn btn-block btn-info btnAddCard">   @lang('labels.frontend.course.add_to_cart')
                                                    <i class="fa fa-shopping-bag ml-1"></i>
                                                </button>

                                            @elseif(!auth()->check())
                                                @if($course->free == 1)
                                                    <a class="btn btn-info btn-block btnAddCard"
                                                       href="{{ route('login.index') }}">@lang('labels.frontend.course.get_now') <i
                                                                class="fas fa-caret-right"></i></a>
                                                @else

                                                    <a class="btn btn-info btnAddCard btn-block"
                                                       href="{{ route('login.index') }}">@lang('labels.frontend.course.add_to_cart')
                                                        <i class="fa fa-shopping-bag"></i>
                                                    </a>
                                                @endif
                                            @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                                                @if($course->free == 1)
                                                    <form action="{{ route('cart.getnow') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="course_id"
                                                               value="{{ $course->id }}"/>
                                                        <input type="hidden" name="amount"
                                                               value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                        <button class="btn btn-info btnAddCard btn-block"
                                                                href="#">@lang('labels.frontend.course.get_now') <i
                                                                    class="fas fa-caret-right"></i></button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('cart.addToCart') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="course_id"
                                                               value="{{ $course->id }}"/>
                                                        <input type="hidden" name="amount"
                                                               value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                        <button type="submit"
                                                                class="btn btn-info btnAddCard btn-block">
                                                           @lang('labels.frontend.course.add_to_cart') <i
                                                                    class="fa fa-shopping-bag"></i></button>
                                                    </form>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="">
                                            <a href="{{ route('courses.show', [$course->slug]) }}"
                                               class="btn btnWishList">
                                                <i class="far fa-bookmark"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <h3>@lang('labels.general.no_data_available')</h3>
            @endif

        </div>
    </div>
</section>
