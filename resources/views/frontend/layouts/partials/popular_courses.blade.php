<style>
    .navl{
        background-color:#CECECE;
        color:black;
        margin-left:1%;
    } 
    .navv .active{
        background-color:#D2498B !important;
        color:white !important;
    } 
    </style>

<!-- Start popular course
       ============================================= -->
@if(count($popular_courses) > 0)
    <section id="popular-course" class="popular-course-section {{isset($class) ? $class : ''}} pt-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
            <div class="section-title mb20 headline text-left ">
                <span class="subtitle text-uppercase">@lang('labels.frontend.layouts.partials.learn_new_skills')</span>
                <h2>@lang('labels.frontend.layouts.partials.popular_courses')</h2>
            </div>

            <ul class="nav nav-tabs custom-tabs" id="myTab" role="tablist">
                @foreach($categories as $key=>$category)
  <li class="nav-item navv" role="presentation">
    <a class=" nav-link navl @if ($key == 0) active @endif" id="{{$category->id}}" data-toggle="tab" href="#content-{{$category->id}}" role="tab" aria-controls="{{$category->id}}" aria-selected="true">{{$category->name}}</a>
  </li>
  
  @endforeach
</ul>

            <div class="tab-content">
                @foreach($categories as $key=>$category)
                    <div class="tab-pane fade in @if ($key == 0) active @endif" id="content-{{$category->id}}" aria-labelledby="content-{{$category->id}}">
                        <div class="owl-carousel owl-theme p-3 ">
                            <?php
                            $courses = App\Models\Course::where('category_id', $category->id)->orderBy('created_at', 'desc')->get();
                            ?>
                            @if($popular_courses->count() > 0)

                                @foreach($courses as $course)

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
                                                <!-- <div class="course-details-btn">
                                            <a href="{{ route('courses.show', [$course->slug]) }}">@lang('labels.frontend.course.course_detail')
                                                        <i class="fas fa-arrow-right"></i></a>
                                                </div>
                                                <div class="blakish-overlay"></div> -->
                                                </div>
                                                <div class="card-body">
                                                    <h3 class="card-title titleofcard">{{$course->title}}</h3>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <i class="fa fa-star text-warning"></i>
                                                            <i class="fa fa-star text-warning"></i>
                                                            <i class="fa fa-star text-warning"></i>
                                                            <i class="fa fa-star text-warning"></i>
                                                            <i class="fa fa-star text-warning"></i>
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
                                                            <img src="../../assets/img/course/c-3.jpg"
                                                                 class="rounded-circle">
                                                        </div>
                                                        <div class="col-9">
                                                            <div class="row">
                                                                @foreach($course->teachers as $key=>$teacher)
                                                                    @php $key++ @endphp
                                                                    <a href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                                                       target="_blank">
                                                                        {{$teacher->full_name}}@if($key < count($course->teachers ))
                                                                            , @endif
                                                                    </a>
                                                                @endforeach
                                                                @foreach($course->teachers as $key=>$teacher)
                                                                    @php $key++ @endphp
                                                                    <a href="{{route('teachers.show',['id'=>$teacher->id])}}"
                                                                       target="_blank">
                                                                        {{$teacher->description}}
                                                                    </a>
                                                                @endforeach

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-10">
                                                            @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                                                <button type="submit"
                                                                        class="btn btn-block btnAddCard">   @lang('labels.frontend.course.add_to_cart')
                                                                    <i class="fa fa-shopping-bag ml-1"></i>
                                                                </button>

                                                            @elseif(!auth()->check())
                                                                @if($course->free == 1)
                                                                    <a id="openLoginModal"
                                                                       class="btn btn-block btnAddCard"
                                                                       data-target="#myModal"
                                                                       href="#">@lang('labels.frontend.course.get_now')
                                                                        <i
                                                                                class="fas fa-caret-right"></i></a>
                                                                @else

                                                                    <a id="openLoginModal"
                                                                       class="btn btn-block btnAddCard"
                                                                       data-target="#myModal"
                                                                       href="#">@lang('labels.frontend.course.add_to_cart')
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
                                                                        <button class="btn btn-block btnAddCard"
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
                                                                                class="btn btn-block btnAddCard">
                                                                            @lang('labels.frontend.course.add_to_cart')
                                                                            <i
                                                                                    class="fa fa-shopping-bag"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            @endif
                                                        </div>
                                                        <div class="col-2 " style="margin-left: -10%;">
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
                @endforeach
            </div>
                </div>
            </div>
        </div>

    </section>
    <!-- End popular course
        ============================================= -->
@endif
