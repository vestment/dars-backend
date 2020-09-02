<!-- Start of footer area
    ============================================= -->
@php
    $footer_data = json_decode(config('footer_data'));
@endphp
@if($footer_data != "")
<footer>
    <section id="footer-area" class="footer-area-section">
        <div class="container">
            <div class="footer-content pb10 footerHidden">
                <div class="row">
                    <div class="col-md-4">
                        <div class="footer-widget ">
                            <div class="footer-logo mb35">
                            <img src="{{asset("storage/logos/".config('logo_w_image'))}}" alt="logo">

                                <!-- <img src="{{asset("storage/logos/".config('logo_b_image'))}}" alt="logo"> -->
                                <!-- <img src="{{asset('img/backend/brand/logo.png')}}" alt="logo" style="width: 100%;"> -->
                            </div>
                            @if($footer_data->short_description->status == 1)
                                <div class="footer-about-text">
                                    <p>{!! $footer_data->short_description->text !!} </p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="row">

                        @if($footer_data->section1->status == 1)
                                @php
                                    $section_data = section_filter($footer_data->section1)
                                @endphp

                                @include('frontend.layouts.partials.footer_section',['section_data' => $section_data])
                            @endif

                            @if($footer_data->section2->status == 1)
                                @php
                                    $section_data = section_filter($footer_data->section2)
                                @endphp

                                @include('frontend.layouts.partials.footer_section',['section_data' => $section_data])
                            @endif

                            @if($footer_data->section3->status == 1)
                                @php
                                    $section_data = section_filter($footer_data->section3)
                                @endphp

                                @include('frontend.layouts.partials.footer_section',['section_data' => $section_data])
                        @endif


                        </div>
                    </div>
                </div>
            </div>
            <!-- /footer-widget-content -->
            <div class="footer-social-subscribe mb65">
                <div class="row">
                    @if(($footer_data->social_links->status == 1) && (count($footer_data->social_links->links) > 0))
                        <div class="col-md-3">
                            <div class="footer-social ul-li footersec ">
                            <h2>@lang('labels.frontend.layouts.partials.follow_us')</h2>
                                <!-- <h2>Follow Us</h2> -->
                                <ul>
                                    @foreach($footer_data->social_links->links as $item)
                                        <li><a href="{{$item->link}}"><i class="{{$item->icon}}"></i></a></li>
                                    @endforeach

                                </ul>
                            </div>
                        </div>
                    @endif

                    @if($footer_data->newsletter_form->status == 1)
                        <div class="col-md-9">
                            <div class="subscribe-form ml-0 footersec">
                                <div class="container my-4">
                                    <form action="{{route('subscribe')}}" method="post" class=" subs-form">
                                        <div class="row">

                                            <div class="col-sm-9">
                                                <div class="form-group">
                                                    <label class="sr-only" for="subs_email">Enter your email address</label>
                                                    <input name="subs_email" id="subs_email" class="form-control h5-email" placeholder="@lang('labels.frontend.layouts.partials.email_address')." type="email" value="">
                                                </div>
                                            </div>
                                            <div class="col-sm-3 col-md-3">
                                                <div class="nws-button text-center  text-uppercase">
                                                    <button type="submit" value="Submit">@lang('labels.frontend.layouts.partials.subscribe_now')</button>
                                                </div>
                                            </div>
                                            @if($errors->has('email'))
                                                <p class="text-danger text-left">{{$errors->first('email')}}</p>
                                            @endif
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @endif
                </div>
            </div>

            
        </div>
        @if($footer_data->bottom_footer->status == 1)
            <div class="copy-right-menu">
                <div class="row">
                    @if($footer_data->copyright_text->status == 1)
                        <div class="col-md-6">
                            <div class="copy-right-text">
                                <p class="l-h">{!!  $footer_data->copyright_text->text !!}</p>
                            <!-- <p class="l-h">© Copyright © 2004 - 2020 E-Council LLC. All rights reserved</p> -->
                            </div>
                        </div>
                    @endif
                    @if(($footer_data->bottom_footer_links->status == 1) && (count($footer_data->bottom_footer_links->links) > 0))
                    <div class="col-md-6">
                        <div class="copy-right-menu-item float-right ul-li">
                            <ul>
                                @foreach($footer_data->bottom_footer_links->links as $item)
                                <li><a href="{{$item->link}}">{{$item->label}}</a></li>
                                @endforeach
                                @if(config('show_offers'))
                                    <li><a href="{{route('frontend.offers')}}">@lang('labels.frontend.layouts.partials.offers')</a> </li>
                                @endif
                                <li><a href="{{route('frontend.certificates.getVerificationForm')}}">@lang('labels.frontend.layouts.partials.certificate_verification')</a></li>
                            </ul>
                        </div>
                    </div>
                     @endif
                </div>
            </div>
        @endif

        <!-- </div> -->
    </section>
</footer>
@endif
<!-- End of footer area
============================================= -->
