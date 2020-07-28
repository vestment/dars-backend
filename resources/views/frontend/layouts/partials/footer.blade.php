<!-- Start of footer area
    ============================================= -->
@php
    $footer_data = json_decode(config('footer_data'));
@endphp
@if($footer_data != "")
<footer>
    <section id="footer-area" class="footer-area-section">
        <div class="container">
            <div class="footer-content pb10">
                <div class="row">
                    <div class="col-md-3">
                        <div class="footer-widget ">
                            <div class="footer-logo mb35">
                                <!-- <img src="{{asset("storage/logos/".config('logo_b_image'))}}" alt="logo"> -->
                                <img src="{{asset('img/backend/brand/logo.png')}}" alt="logo" style="width: 100%;">
                            </div>
                            @if($footer_data->short_description->status == 1)
                                <div class="footer-about-text">
                                    <p>{!! $footer_data->short_description->text !!} </p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col footersec">
                                <h2>Featured Course</h2>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Consectetur est est placeat ut voluptas quia.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Odit iure perspiciatis tempore sed aut.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Et sint dolor dignissimos officiis.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Accusamus libero odio tempore voluptatem laudantium.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Odit iure perspiciatis tempore sed aut.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Consectetur est est placeat ut voluptas quia.</p>
                            </div>
                            <div class="col footersec">
                                <h2>Popular Course</h2>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Consectetur est est placeat ut voluptas quia.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Odit iure perspiciatis tempore sed aut.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Et sint dolor dignissimos officiis.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Accusamus libero odio tempore voluptatem laudantium.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Odit iure perspiciatis tempore sed aut.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Consectetur est est placeat ut voluptas quia.</p>
                            </div>
                            <div class="col footersec">
                                <h2>Popular Course</h2>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Consectetur est est placeat ut voluptas quia.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Odit iure perspiciatis tempore sed aut.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Et sint dolor dignissimos officiis.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Accusamus libero odio tempore voluptatem laudantium.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Odit iure perspiciatis tempore sed aut.</p>
                                <p class="size16"><i class="fa fa-chevron-right arrow" aria-hidden="true"></i> Consectetur est est placeat ut voluptas quia.</p>
                            </div>
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
                                <h2>Follow Us</h2>
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
                                <h2 >@lang('labels.frontend.layouts.partials.subscribe_newsletter')</h2>

                                <div class="subs-form relative-position">
                                    <form action="{{route("subscribe")}}" method="post">
                                        @csrf
                                        <input class="email" required name="subs_email" type="email" placeholder="@lang('labels.frontend.layouts.partials.email_address').">
                                        <div class="nws-button text-center  text-uppercase">
                                            <button type="submit" value="Submit">@lang('labels.frontend.layouts.partials.subscribe_now')</button>
                                        </div>
                                        @if($errors->has('email'))
                                            <p class="text-danger text-left">{{$errors->first('email')}}</p>
                                        @endif
                                    </form>

                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
            <div class="copy-right-menu">
                <div class="row">
                    <div class="col-md-6">
                        <div class="copy-right-text">
                            <!-- <p>Powered By <a href="https://www.neonlms.com/" target="_blank" class="mr-4"> NeonLMS</a>  {!!  $footer_data->copyright_text->text !!}</p> -->
                        <p class="l-h">© Copyright © 2004 - 2020 E-Council LLC. All rights reserved</p>
                        </div>
                    </div>
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
        <!-- </div> -->
    </section>
</footer>
@endif
<!-- End of footer area
============================================= -->