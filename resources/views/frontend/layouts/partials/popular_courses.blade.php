<style>
    .navl {
        background-color: #CECECE;
        color: black;
        margin-left: 1%;
    }

    .navv .active {
        background-color: #D2498B !important;
        color: white !important;
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
                    <div class="col-xl-12 categories-container border-bottom">
                        @foreach($categories as $key=>$category)
                            <button onclick="showTab($('#content-{{$category->id}}'),$(this))"
                                    class="tab-button btn @if ($key == 0) active @endif btn-light">{{$category->name}}</button>
                        @endforeach
                    </div>
                    <div class="col-xl-12 courses-container">
                        @foreach($categories as $key=>$category)
                            <div class="course-container fade in @if ($key == 0) show active @else hide @endif"
                                 id="content-{{$category->id}}" aria-labelledby="content-{{$category->id}}">
                                <div class="owl-carousel default-owl-theme p-3 ">
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
                                                            <div class="row justify-content-around">
                                                                <div class="">
                                                                    @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                                                        <button type="submit"
                                                                                class="btn btn-info btnAddCard">   @lang('labels.frontend.course.add_to_cart')
                                                                            <i class="fa fa-shopping-bag ml-1"></i>
                                                                        </button>

                                                                    @elseif(!auth()->check())
                                                                        @if($course->free == 1)
                                                                            <a id="openLoginModal"
                                                                               class="btn btn-info btnAddCard"
                                                                               data-target="#myModal"
                                                                               href="#"><span
                                                                                        class="d-lg-inline-block d-sm-none">@lang('labels.frontend.course.get_now') </span><i
                                                                                        class="fas fa-caret-right"></i></a>
                                                                        @else

                                                                            <a id="openLoginModal"
                                                                               class="btn btn-info btnAddCard w-100"
                                                                               data-target="#myModal"
                                                                               href="#"><span
                                                                                        class="d-lg-inline-block d-sm-none">@lang('labels.frontend.course.add_to_cart')</span>
                                                                                <i class="fa fa-shopping-bag"></i>
                                                                            </a>
                                                                        @endif
                                                                    @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                                                                        @if($course->free == 1)
                                                                            <form action="{{ route('cart.getnow') }}"
                                                                                  method="POST">
                                                                                @csrf
                                                                                <input type="hidden"
                                                                                       name="course_id"
                                                                                       value="{{ $course->id }}"/>
                                                                                <input type="hidden" name="amount"
                                                                                       value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                                <button class="btn btn-info btnAddCard w-100"
                                                                                        href="#"><span
                                                                                            class="d-lg-inline-block d-sm-none">@lang('labels.frontend.course.get_now')</span>
                                                                                    <i
                                                                                            class="fas fa-caret-right"></i>
                                                                                </button>
                                                                            </form>
                                                                        @else
                                                                            <form action="{{ route('cart.addToCart') }}"
                                                                                  method="POST">
                                                                                @csrf
                                                                                <input type="hidden"
                                                                                       name="course_id"
                                                                                       value="{{ $course->id }}"/>
                                                                                <input type="hidden" name="amount"
                                                                                       value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                                <button type="submit"
                                                                                        class="btn btn-info btnAddCard w-100">
                                                                                    <span class="d-lg-inline-block d-sm-none"> @lang('labels.frontend.course.add_to_cart')</span>
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
    @push('after-scripts')
        <script>
            function showTab(element,button) {
                var elem = element[0];
                $('.course-container.show').addClass('hide');
                $('button.active').removeClass('active');
                $('button.active').removeClass('active');
                $('.course-container.show').removeClass('show');
                $('.course-container.hide').css('display','none');
                $(elem).removeClass('hide');
                $(elem).addClass('show acive');
                $(elem).css('display','block');
                button.addClass('active');
                // console.log(elem.classList)
                window.dispatchEvent(new Event('resize'));
            }
        </script>
    @endpush
@endif
