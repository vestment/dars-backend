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
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="section-title mb20 headline text-left ">
                        <span class="subtitle text-uppercase">@lang('labels.frontend.layouts.partials.learn_new_skills')</span>
                        <h4 class="title">@lang('labels.frontend.layouts.partials.popular_courses')<a class="view-more"
                                                                                                      href="{{route('courses.all')}}">@lang('labels.frontend.layouts.partials.view_more') </a>
                        </h4>

                    </div>
                    <div class="col-xl-12 categories-container border-bottom">
                        @foreach($categories as $key=>$category)
                            @if ($category->slug != '911')
                            @if($category->courses()->count() > 0)
                                <button onclick="showTab($('#content-{{$category->id}}'),$(this))"
                                        class="tab-button btn @if ($key == 0) active @endif btn-light">{{$category->getDataFromColumn('name')}}</button>
                                        @endif
                            @endif
                        @endforeach
                    </div>
                    <div class="col-xl-12 courses-container">
                        @foreach($categories as $key=>$category)
                            @if ($category->slug != '911')
                             @if($category->courses()->count() > 0)
                                <div class="course-container fade in @if ($key == 0) show active @else hide @endif"
                                     id="content-{{$category->id}}" aria-labelledby="content-{{$category->id}}">
                                    <div class="owl-carousel default-owl-theme p-3 " data-items="5">
                                        @if($popular_courses->count() > 0)

                                            @foreach($popular_courses as $course)
                                                @if($course->category_id == $category->id)
                                                    <div class="item">

                                                        <div class="">
                                                            @include('frontend.layouts.partials.coursesTemp')
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach

                                        @else
                                            <h3>@lang('labels.general.no_data_available')</h3>
                                        @endif

                                    </div>
                                </div>
                                @endif
                            @endif
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

        </script>
    @endpush
@endif
