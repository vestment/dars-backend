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
                                <div class="owl-carousel default-owl-theme p-3 " data-items="5">
                                    <?php
                                    $courses = App\Models\Course::where('category_id', $category->id)->orderBy('created_at', 'desc')->get();
                                    ?>
                                    @if($popular_courses->count() > 0)

                                        @foreach($courses as $course)

                                            <div class="item">

                                                <div class="">
                                                    @include('frontend.layouts.partials.coursesTemp')
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

        </script>
    @endpush
@endif
