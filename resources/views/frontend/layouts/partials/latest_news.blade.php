<section id="latest-area" class="">
    <div class="container-fluid">
        <div class="section-title  text-dark p-5">
            <span class="subtitle text-uppercase ">@lang('labels.frontend.layouts.partials.subtext')</span>
            <h4 class="font-weight-bolder title">@lang('labels.frontend.layouts.partials.trending_courses')</h4>
            <p class="pl-4">@lang('labels.frontend.layouts.partials.paragh')</p>
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
