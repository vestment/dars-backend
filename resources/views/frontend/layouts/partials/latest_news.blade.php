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
                            @include('frontend.layouts.partials.coursesTemp')
                        </div>
                    </div>
                @endforeach
            @else
                <h3>@lang('labels.general.no_data_available')</h3>
            @endif

        </div>
    </div>
</section>
