<div class="best-course-pic-text relative-position" data-ref="partials">
    <a href="{{ route('courses.show', [$course->slug]) }}">
        <div class="best-course-pic piclip relative-position"
             @if($course->image != "") style="background-image: url('{{$course->image}}')" @endif>
            <div class="course-price text-center gradient-bg">
                @if($course->free == 1)
                    <span>{{trans('labels.backend.courses.fields.free')}}</span>
                @else
                    <span> {{$appCurrency['symbol'].' '.$course->price}}</span>
                @endif
            </div>

        </div>
    </a>
    <div class="card-body">
        <a href="{{ route('courses.show', [$course->slug]) }}"><h3 class="card-title titleofcard">{{$course->getDataFromColumn('title')}}</h3></a>
        <div class="row">
            <div class="col-12">
                <div class="course-rate ul-li pb-1">
                    <ul data-rate="{{$course->rating}}">
                        @for ($i=0; $i<5; ++$i)
                            <li>
                                <i class="fa{{($course->rating<=$i?'r':'s')}} fa-star{{($course->rating==$i+.5?'-half-alt':'')}}"
                                   aria-hidden="true"></i></li>
                        @endfor
                        <li><span class="text-muted">{{number_format($course->rating)}} ({{number_format($course->reviews->count())}})</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row">
         <p class="pl-3" style="font-size:15px;">
         {{Illuminate\Support\Str::words($course->getDataFromColumn('description'),10,'...') }}
        </p>

        </div>
        <div class="course-meta my-1 vv">
            <span class="course-category text-dark"><i class="far fa-clock"></i> {{$course->duration}}</span>
                    <span class="dash"> | </span>
            <span class="course-author">
            <i class="far fa-play-circle"></i> {{ $course->lessons()->count() }} @lang('labels.frontend.course.lessons')
            </span>
        </div>

        <div class="row  tech-height">
            @foreach($course->teachers as $key=>$teacher)
                @if($key == 0)
                    @if ($teacher->hasRole('teacher'))
                        <div class="col-lg-3 " data-role="{{$teacher->hasRole('teacher')}}">
                            <img src="{{$teacher->picture}}"
                                 class="rounded-circle img-tech2">
                        </div>
                        <div class="col-lg-9">
                            <div class="row pt-2 pt-lg-2 pl-lg-0 p-3">
                                @foreach($course->teachers as $key=>$teacher)
                                    @if($key == 0 && $teacher->teacherProfile)
                                        <a class="text-pink tx-font"
                                           href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                           target="_blank">

                                            {{$teacher->full_name}}

                                        </a>
                                        <a class="text-muted teacher-title"
                                           href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                           target="_blank">
                                            {{$teacher->teacherProfile->getDataFromColumn('title')}}
                                        </a>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                    @elseif ($teacher->hasRole('academy'))
                        @php
                            $academyProfile = \App\academy::where('user_id',$teacher->id)->first();
                        @endphp
                        @if ($academyProfile)
                            <div class="col-3" data-role="{{$teacher->hasRole('teacher')}}">
                                <img src="{{asset($academyProfile->logo)}}"
                                     class="rounded-circle">
                            </div>
                            <div class="col-9">
                                <div class="row">


                                    <a class="text-pink"
                                       href="{{route('academy.show',['id'=>$teacher->id])}}"
                                       target="_blank">

                                        {{$teacher->full_name}}

                                    </a>


                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            @endforeach
        </div>
        <div class="row">
            <div class="col-xl-9 col-9 p-0 pl-2">
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
            <div class="col-2 p-0 pl-2">
                <a href="{{ route('courses.show', [$course->slug]) }}"
                   class="btn btnWishList">
                    <i class="far fa-bookmark"></i>
                </a>
            </div>
        </div>
    </div>
</div>
