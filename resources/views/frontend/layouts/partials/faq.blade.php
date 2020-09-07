<section id="faq" class=" shadow-lg p-3 mb-5 rounded bg-light faq-section {{isset($classes) ? $classes : '' }}">
    <div class="container">
        <div class="section-title mb45 headline  ">
            <span class="subtitle text-uppercase"> @lang('labels.frontend.layouts.partials.faq')</span>
            <h4 class="title">@lang('labels.frontend.layouts.partials.faq_full')</h4>
        </div>
        <div>
        @if(count($faqs)> 0)
                    <div class="owl-carousel custom-owl-theme d-sm-block d-lg-none"> 
                        @foreach($faqs as $key=>$faq)
                        
                            <div class="faq-tab-ques ul-li" style="text-align: center; margin-left: 35px; margin-top:50px;">

                                <div class="tab-button pt-5 mb65 ">

                                    <ul class="product-tab">
                                        <div class="item ml-lg-5">
                                                <li rel="tab{{$faq->id}}">{{$faq->getDataFromColumn('name')}}</li>
                                        </div>
                                    </ul>
                                </div>
                            </div>

                        @endforeach

                    </div>

            <div class="faq-tab text-center d-sm-none d-lg-block">
                <div class="faq-tab-ques ul-li">
                    <div class="tab-button pt-5 mb65 ">
                        <ul class="product-tab">
                            @foreach($faqs as $key=>$faq)
                                <li rel="tab{{$faq->id}}">{{$faq->getDataFromColumn('name')}}</li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- /tab-head -->

                    <!-- tab content -->
                    <div class="tab-container">
                    @foreach($faqs as $key=>$faq)
                        <!-- 1st tab -->
                            <div id="tab{{$faq->id}}" class="tab-content-1 pt35">
                                <div class="row">
                                    @foreach($faq->faqs->take(4) as $item)
                                        <div class="col-md-6">
                                            <div class="ques-ans mb45 headline">
                                                <h3> {{$item->question}}</h3>
                                                <p>{{$item->answer}}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- #tab1 -->
                        @endforeach

                    </div>
                    <div class="view-all-btn bold-font {{isset($classes) ? 'text-white' : '' }}">
                        <a href="{{route('faqs')}}">{{trans('labels.frontend.layouts.partials.more_faqs')}} <i class="fas fa-chevron-circle-right"></i></a>
                    </div>
                </div>
            </div>
        @else
            <h4>@lang('labels.general.no_data_available')</h4>
        @endif
        </div>
    </div>
</section>
