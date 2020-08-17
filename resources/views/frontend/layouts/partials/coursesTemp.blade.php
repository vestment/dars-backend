
        <div class="best-course-pic-text relative-position" data-ref="partials">
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
                <h3 class="card-title titleofcard">{{$course->getDataFromColumn('title')}}</h3>
                <div class="row">
                    <div class="col-12">
                        <div class="course-rate ul-li">
                            <ul>
                                @for ($i=0; $i<5; ++$i)
                                    <li><i class="fa{{($course->rating<=$i?'r':'s')}} fa-star{{($course->rating==$i+.5?'-half-alt':'')}}" aria-hidden="true"></i></li>
                                @endfor
                                <li><span class="text-muted">{{number_format($course->rating)}} ({{number_format($course->reviews->count())}})</span></li>
                            </ul>
                        </div>
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
                        <img src="../../assets/img/course/c-3.jpg"
                             class="rounded-circle">
                    </div>
                    <div class="col-9">
                        <div class="row">
                            @foreach($course->teachers as $key=>$teacher)
                                @php
                                    $teacherProfile = \App\Models\TeacherProfile::where('user_id',$teacher->id)->first();
                                @endphp
                                <a class="text-pink"
                                   href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                   target="_blank">
                                    {{$teacher->full_name}}@if($key < count($course->teachers ))
                                        , @endif
                                </a>
                                <a class="text-muted teacher-title"
                                   href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                   target="_blank">
                                    {{$teacherProfile->title}}
                                </a>
                                <a href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                   target="_blank">
                                    {{$teacher->title}}
                                </a>
                            @endforeach

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-10 col-9">
                        @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                            <button type="submit"
                                    class="btn btn-info btn-block btnAddCard">   @lang('labels.frontend.course.add_to_cart')
                                <i class="fa fa-shopping-bag ml-1"></i>
                            </button>

                        @elseif(!auth()->check())
                            @if($course->free == 1)
                                <a class="btn btn-info btn-block btnAddCard"
                                   href="{{ route('login.index') }}">@lang('labels.frontend.course.get_now')
                                    <i
                                            class="fas fa-caret-right"></i></a>
                            @else

                                <a class="btn btn-info btnAddCard btn-block"
                                   href="{{ route('login.index') }}">@lang('labels.frontend.course.add_to_cart')
                                    <i class="fa fa-shopping-bag"></i>
                                </a>
                            @endif
                        @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                            @if($course->free == 1)
                                <form action="{{ route('cart.getnow') }}"
                                      method="POST">
                                    @csrf
                                    <input type="hidden" name="course_id"
                                           value="{{ $course->id }}"/>
                                    <input type="hidden" name="amount"
                                           value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                    <button class="btn btn-info btnAddCard btn-block"
                                            href="#">@lang('labels.frontend.course.get_now')
                                        <i
                                                class="fas fa-caret-right"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('cart.addToCart') }}"
                                      method="POST">
                                    @csrf
                                    <input type="hidden" name="course_id"
                                           value="{{ $course->id }}"/>
                                    <input type="hidden" name="amount"
                                           value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                    <button type="submit"
                                            class="btn btn-info btnAddCard btn-block">
                                        @lang('labels.frontend.course.add_to_cart')
                                        <i
                                                class="fa fa-shopping-bag"></i>
                                    </button>
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
