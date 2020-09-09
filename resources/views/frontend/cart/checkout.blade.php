@extends('frontend.layouts.app')
@section('title', trans('labels.frontend.cart.payment_status').' | '.app_name())

@push('after-styles')
    <style>
        input[type="radio"] {
            display: inline-block !important;
        }

        .course-rate li {
            color: #ffc926 !important;
        }

        .breadcrumb {
            background-color: unset;
            margin-top: 3rem;
        }

        .breadcrumb > li {
            display: inline-block;
        }

        .breadcrumb > li + li:before {
            padding: 0 5px;
            color: #ccc;
            content: "/\00a0";
        }

        #applyCoupon {
            box-shadow: none !important;
            color: #fff !important;
            font-weight: bold;
        }

        #coupon.warning {
            border: 1px solid red;
        }

        .purchase-list .in-total {
            font-size: 18px;
        }

        #coupon-error {
            color: red;
        }

        .in-total:not(:first-child):not(:last-child) {
            font-size: 15px;
        }

    </style>

    <script src='https://js.stripe.com/v2/' type='text/javascript'></script>
@endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style bg-header-ch">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="page-breadcrumb-content">
                <ol class="breadcrumb">
                    <li><a href="#">@lang('labels.frontend.layouts.partials.explore')</a></li>
                    <li><a href="#">@lang('labels.frontend.layouts.partials.business')</a></li>
                    <li class="active">@lang('labels.frontend.cart.checkout')</li>
                </ol>

                <div class="page-breadcrumb-title pb-4">
                    <h2 class="breadcrumb-head black bold"><span>@lang('labels.frontend.cart.checkout')</span></h2>
                </div>
            </div>
        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of Checkout content
        ============================================= -->
    <section id="checkout" class="checkout-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-9 col-md-12">
                    <div class="section-title mb45 headline ">
                        <p>@lang('labels.frontend.cart.your_shopping_cart')</p>
                        <h2>@lang('labels.frontend.cart.complete_your_purchases')</h2>
                    </div>
                    <div class="checkout-content">
                        @if(session()->has('danger'))
                            <div class="alert alert-dismissable alert-danger fade show">
                                <button type="button" class="close" data-dismiss="alert">&times;</button>
                                {!! session('danger')  !!}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-lg-12 col-md-12 ">
                                <div class="order-item mb30 course-page-section">
                                    <div class="section-title-2  headline text-left">
                                        <h2>@lang('labels.frontend.cart.order_item')</h2>
                                    </div>

                                    <div class="course-list-view table-responsive">

                                        @if(count($courses) > 0)
                                            <table class="table">

                                                <thead>
                                                <tr class="list-head text-uppercase">
                                                    <th>@lang('labels.frontend.cart.course_name')</th>
                                                    <th>@lang('labels.frontend.cart.course_type')</th>
                                                    <th>@lang('labels.frontend.cart.starts')</th>
                                                    <th>@lang('labels.frontend.cart.action')</th>

                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($courses as $course)
                                                    <tr class="position-relative">

                                                        <td>
                                                            <div class="course-list-img-text">
                                                                <div class="course-list-img"
                                                                     @if($course->image != "") style="background-image: url('{{$course->image}}')" @endif>

                                                                </div>
                                                                <div class="course-list-text">
                                                                    <h4 class="text-dark">
                                                                        <a @if(class_basename($course) == 'Course') href="{{ route('courses.show', [$course->slug]) }}"
                                                                           @else href="{{ route('bundles.show', [$course->slug]) }}" @endif>{{$course->title}}</a>

                                                                    </h4>
                                                                    @if(array_key_exists($course->id,$courseData))
                                                                        <p>Booking
                                                                            date: {{$courseData[$course->id]['selectedDate']}}</p>
                                                                        <p>Booking
                                                                            time: {{$courseData[$course->id]['selectedTime']}}</p>

                                                                    @endif
                                                                    <div class="course-meta">

                                                                        @if (array_key_exists($course->id,$courseData) && $courseData[$course->id]['offlinePrice'])
                                                                            <span class="badge badge-primary bg-pink"> {{$appCurrency['symbol'].' '.$course->offline_price}}</span>
                                                                        @elseif($course->free == 1)
                                                                            <span class="badge badge-primary bg-pink">{{trans('labels.backend.bundles.fields.free')}}</span>
                                                                        @else
                                                                            <span class="badge badge-primary bg-pink"> {{$appCurrency['symbol'].' '.$course->price}}</span>
                                                                        @endif

                                                                        <div class="course-rate ul-li">
                                                                            <ul>
                                                                                @for($i=1; $i<=(int)$course->rating; $i++)
                                                                                    <li><i class="fas fa-star"></i></li>
                                                                                @endfor
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td>
                                                            <div class="course-type-list">
                                                                <span>{{class_basename($course)}}</span>
                                                            </div>
                                                        </td>
                                                        <td>{{($course->start_date != "") ? $course->start_date : 'N/A'}}</td>
                                                        <td><a class="te-remove "
                                                               href="{{route('cart.remove',['course'=>$course])}}">remove</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            <div class="text-center">
                                                <h1><b>@lang('http.204.title')</b></h1>

                                                <h4><b>@lang('http.204.description')</b></h4>
                                                <p>
                                                    @lang('http.204.description2')
                                                </p>
                                                <div>
                                                    <a href="{{url('/')}}"><img
                                                                src="{{url('img/frontend/user/empty.svg')}}"/></a>
                                                </div>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                                @if(count($courses) > 0)
                                    @if((config('services.stripe.active') == 0) && (config('paypal.active') == 0) && (config('payment_offline_active') == 0) && (config('paymob.active') == 0))
                                        <div class="order-payment">
                                            <div class="section-title-2 headline text-left">
                                                <h2>@lang('labels.frontend.cart.no_payment_method')</h2>
                                            </div>
                                        </div>
                                    @else
                                        <div class="order-payment">
                                            <div class="section-title-2  headline text-left">
                                                <h2>@lang('labels.frontend.cart.order_payment')</h2>
                                            </div>
                                            <div id="accordion">
                                                @if(config('services.stripe.active') == 1)
                                                    <div class="payment-method w-100 mb-0">
                                                        <div class="payment-method-header">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="method-header-text">
                                                                        <div class="radio">
                                                                            <label>
                                                                                <input data-toggle="collapse"
                                                                                       href="#collapsePaymentOne"
                                                                                       type="radio" name="paymentMethod"
                                                                                       value="1"
                                                                                       checked>
                                                                                @lang('labels.frontend.cart.payment_cards')
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="payment-img float-right">
                                                                        <img src="{{asset('assets/img/banner/p-1.jpg')}}"
                                                                             alt="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="check-out-form collapse show"
                                                             id="collapsePaymentOne"
                                                             data-parent="#accordion">


                                                            <form accept-charset="UTF-8"
                                                                  action="{{route('cart.stripe.payment')}}"
                                                                  class="require-validation" data-cc-on-file="false"
                                                                  data-stripe-publishable-key="{{config('services.stripe.key')}}"
                                                                  id="payment-form"
                                                                  method="POST">

                                                                <div style="margin:0;padding:0;display:inline">
                                                                    <input name="utf8" type="hidden"
                                                                           value="✓"/>
                                                                    @csrf
                                                                </div>


                                                                <div class="payment-info">
                                                                    <label class=" control-label">@lang('labels.frontend.cart.name_on_card')
                                                                        :</label>
                                                                    <input type="text" autocomplete='off'
                                                                           class="form-control required card-name"
                                                                           placeholder="@lang('labels.frontend.cart.name_on_card_placeholder')"
                                                                           value="">
                                                                </div>
                                                                <div class="payment-info">
                                                                    <label class=" control-label">@lang('labels.frontend.cart.card_number')
                                                                        :</label>
                                                                    <input autocomplete='off' type="text"
                                                                           class="form-control required card-number"
                                                                           placeholder="@lang('labels.frontend.cart.card_number_placeholder')"
                                                                           value="">
                                                                </div>
                                                                <div class="payment-info input-2">
                                                                    <label class=" control-label">@lang('labels.frontend.cart.cvv')
                                                                        :</label>
                                                                    <input type="text"
                                                                           class="form-control card-cvc required"
                                                                           placeholder="@lang('labels.frontend.cart.cvv')"
                                                                           value="">
                                                                </div>
                                                                <div class="payment-info input-2">
                                                                    <label class=" control-label">@lang('labels.frontend.cart.expiration_date')
                                                                        :</label>
                                                                    <input autocomplete='off' type="text"
                                                                           class="form-control required card-expiry-month"
                                                                           placeholder="@lang('labels.frontend.cart.mm')"
                                                                           value="">
                                                                    <input autocomplete='off' type="text"
                                                                           class="form-control required card-expiry-year"
                                                                           placeholder="@lang('labels.frontend.cart.yy')"
                                                                           value="">
                                                                </div>
                                                                <button type="submit"
                                                                        class="text-white genius-btn mt25 gradient-bg text-center text-uppercase  bold-font">
                                                                    @lang('labels.frontend.cart.pay_now') <i
                                                                            class="fas fa-caret-right"></i>
                                                                </button>
                                                                <div class="row mt-3">
                                                                    <div class="col-12 error form-group d-none">
                                                                        <div class="alert-danger alert">
                                                                            @lang('labels.frontend.cart.stripe_error_message')
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif

                                                @if(config('paypal.active') == 1)
                                                    <div class="payment-method w-100 mb-0">
                                                        <div class="payment-method-header">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="method-header-text">
                                                                        <div class="radio">
                                                                            <label>
                                                                                <input data-toggle="collapse"
                                                                                       href="#collapsePaymentTwo"
                                                                                       type="radio" name="paymentMethod"
                                                                                       value="2">
                                                                                @lang('labels.frontend.cart.paypal')
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="payment-img float-right">
                                                                        <img src="{{asset('assets/img/banner/p-2.jpg')}}"
                                                                             alt="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="check-out-form collapse disabled"
                                                             id="collapsePaymentTwo"
                                                             data-parent="#accordion">
                                                            <form class="w3-container w3-display-middle w3-card-4 "
                                                                  method="POST"
                                                                  id="payment-form"
                                                                  action="{{route('cart.paypal.payment')}}">
                                                                {{ csrf_field() }}
                                                                <p> @lang('labels.frontend.cart.pay_securely_paypal')</p>

                                                                <button type="submit"
                                                                        class="text-white genius-btn mt25 gradient-bg text-center text-uppercase  bold-font">
                                                                    @lang('labels.frontend.cart.pay_now') <i
                                                                            class="fas fa-caret-right"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(config('fawry.active') == 1)
                                                    <div class="payment-method w-100 mb-0">
                                                        <div class="payment-method-header">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="method-header-text">
                                                                        <div class="radio">
                                                                            <label>
                                                                                <input data-toggle="collapse"
                                                                                       href="#collapsePaymentFour"
                                                                                       type="radio" name="paymentMethod"
                                                                                       value="2">
                                                                                @lang('labels.frontend.cart.fawry')
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="payment-img float-right">
                                                                        <img src="{{asset('assets/img/banner/fawry.png')}}"
                                                                             alt="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="check-out-form collapse disabled"
                                                             id="collapsePaymentFour"
                                                             data-parent="#accordion">
                                                            <form class="w3-container w3-display-middle w3-card-4"
                                                                  method="POST"
                                                                  id="payment-form"
                                                                  action="{{route('cart.fawry.payment')}}">
                                                                {{ csrf_field() }}
                                                                <p> @lang('labels.frontend.cart.pay_securely_fawry')</p>

                                                                <button type="submit"
                                                                        class="text-white genius-btn mt25 gradient-bg text-center text-uppercase  bold-font">
                                                                    @lang('labels.frontend.cart.pay_now') <i
                                                                            class="fas fa-caret-right"></i>
                                                                </button>


                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(config('paymob.active') == 1)
                                                    <div class="payment-method w-100 mb-0">
                                                        <div class="payment-method-header">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="method-header-text">
                                                                        <div class="radio">
                                                                            <label>
                                                                                <input data-toggle="collapse"
                                                                                       href="#collapsePaymentFive"
                                                                                       type="radio" name="paymentMethod"
                                                                                       value="2">
                                                                                @lang('labels.frontend.cart.accept')
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="payment-img float-right">
                                                                        <img src="{{asset('assets/img/banner/accept.png')}}"
                                                                             alt="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="check-out-form collapse disabled"
                                                             id="collapsePaymentFive"
                                                             data-parent="#accordion">
                                                            <p>In this method you will enter your credit card to
                                                                pay.</p>
                                                            <button type="submit" id="payMobButton"
                                                                    class="text-white genius-btn mt25 gradient-bg text-center text-uppercase  bold-font">
                                                                @lang('labels.frontend.cart.pay_now') <i
                                                                        class="fas fa-caret-right"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="payMobModal" role="dialog">
                                                        <div class="modal-dialog modal-lg">

                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header" style="background: unset">
                                                                    <h2>Pay by PayMob</h2>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal">&times;
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(config('vodafoneCash.active') == 1)
                                                    <div class="payment-method w-100 mb-0">
                                                        <div class="payment-method-header">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="method-header-text">
                                                                        <div class="radio">
                                                                            <label>
                                                                                <input data-toggle="collapse"
                                                                                       href="#vodafoneCashPayment"
                                                                                       type="radio" name="paymentMethod"
                                                                                       value="2">
                                                                                @lang('labels.frontend.cart.vodafoneCash')
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="payment-img float-right">
                                                                        <img src="{{asset('assets/img/banner/ezgif-7-7f87b8a8bf19.jpg')}}"
                                                                             alt="">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="check-out-form collapse disabled"
                                                             id="vodafoneCashPayment"
                                                             data-parent="#accordion">
                                                            <p>In this method you will enter your Mobile number pay.</p>
                                                            <button type="submit" data-toggle="modal"
                                                                    data-target="#vodafoneCashModal"
                                                                    class="text-white genius-btn mt25 gradient-bg text-center text-uppercase  bold-font">
                                                                @lang('labels.frontend.cart.pay_now') <i
                                                                        class="fas fa-caret-right"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                    <div class="modal fade" id="vodafoneCashModal" role="dialog">
                                                        <div class="modal-dialog modal-lg">

                                                            <!-- Modal content-->
                                                            <div class="modal-content">
                                                                <div class="modal-header" style="background: unset">
                                                                    <h2>Pay by Vodafone Cash</h2>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal">&times;
                                                                    </button>
                                                                </div>
                                                                <form method="post" action="#">
                                                                    <div class="modal-body">
                                                                        @csrf
                                                                        <div class="form-group row ">
                                                                            <div class="col-12 ">
                                                                                <input name="mobileNumber" type="text"
                                                                                       class="form-control"
                                                                                       placeholder="You mobile number">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row d-none">
                                                                            <div class="col-6 ">
                                                                                <input type="password" name="mobile_PIN"
                                                                                       class="form-control"
                                                                                       maxlength="6"
                                                                                       placeholder="You PIN">
                                                                            </div>
                                                                            <div class="col-6 ">
                                                                                <input type="number" name="OTP"
                                                                                       class="form-control"
                                                                                       maxlength="6" placeholder="OTP">
                                                                            </div>
                                                                        </div>

                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <div class="col-6 ">
                                                                            <button data-dismiss="modal"
                                                                                    class="btn btn-light ">Close
                                                                            </button>
                                                                        </div>
                                                                        <div class="col-6 ">
                                                                            <button type="submit"
                                                                                    class="btn btn-primary float-right">
                                                                                Submit
                                                                            </button>
                                                                        </div>

                                                                    </div>

                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                                @if(config('payment_offline_active') == 1)
                                                    <div class="payment-method w-100 mb-0">
                                                        <div class="payment-method-header">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <div class="method-header-text">
                                                                        <div class="radio">
                                                                            <label>
                                                                                <input data-toggle="collapse"
                                                                                       href="#collapsePaymentThree"
                                                                                       type="radio"
                                                                                       name="paymentMethod" value="3">
                                                                                @lang('labels.frontend.cart.offline_payment')
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="check-out-form collapse disabled"
                                                             id="collapsePaymentThree"
                                                             data-parent="#accordion">
                                                            <p> @lang('labels.frontend.cart.offline_payment_note')</p>
                                                            <p>{{ config('payment_offline_instruction')  }}</p>
                                                            <form method="post"
                                                                  action="{{route('cart.offline.payment')}}">
                                                                @csrf
                                                                <button type="submit"
                                                                        class="text-white genius-btn mt25 gradient-bg text-center text-uppercase  bold-font">
                                                                    @lang('labels.frontend.cart.request_assistance') <i
                                                                            class="fas fa-caret-right"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="terms-text pb45 mt25">
                                                <p>@lang('labels.frontend.cart.confirmation_note')</p>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <div>
                                    <div class="side-bar-widget first-widget">
                                        <div class="sub-total-item">
                                            @if(count($courses) > 0)

                                                    @include('frontend.cart.partials.order-stats')
                                            @else
                                                {{--                                        <div class="purchase-list mt15 ul-li-block row">--}}
                                                {{--                                            <div class="col-6">--}}
                                                {{--                                                <span class="in-total text-uppercase">@lang('labels.frontend.cart.total') </span>--}}
                                                {{--                                                       --}}
                                                {{--                                                <span>(0-items)</span>--}}
                                                {{--                                            </div>--}}
                                                {{--                                            <div class="col-6">--}}
                                                {{--                                                <span>{{$appCurrency['symbol']}}0.00</span>--}}
                                                {{--                                            </div>--}}
                                                {{--                                            </div> --}}

                                            @endif
                                        </div>
                                    </div>
                                    @if($global_featured_course != "")
                                        <div class="side-bar-widget">
                                            <h2 class="widget-title text-capitalize">@lang('labels.frontend.blog.featured_course')</h2>
                                            <div class="featured-course">
                                                <div class="best-course-pic-text relative-position pt-0">
                                                    <div class="best-course-pic relative-position "
                                                         style="background-image: url({{asset('storage/uploads/'.$global_featured_course->course_image)}})">

                                                        @if($global_featured_course->trending == 1)
                                                            <div class="trend-badge-2 text-center text-uppercase">
                                                                <i class="fas fa-bolt"></i>
                                                                <span>@lang('labels.frontend.badges.trending')</span>
                                                            </div>
                                                        @endif
                                                        @if($global_featured_course->free == 1)
                                                            <div class="trend-badge-3 text-center text-uppercase">
                                                                <i class="fas fa-bolt"></i>
                                                                <span>@lang('labels.backend.courses.fields.free')</span>
                                                            </div>
                                                        @endif

                                                    </div>
                                                    <div class="best-course-text" style="left: 0;right: 0;">
                                                        <div class="course-title mb20 headline relative-position">
                                                            <h3>
                                                                <a href="{{ route('courses.show', [$global_featured_course->slug]) }}">{{$global_featured_course->title}}</a>
                                                            </h3>
                                                        </div>
                                                        <div class="course-meta">
                                                        <span class="course-category"><a
                                                                    href="{{route('courses.category',['category'=>$global_featured_course->category->slug])}}">{{$global_featured_course->category->name}}</a></span>
                                                            <span class="course-author">{{ $global_featured_course->students()->count() }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>


                        </div>
                    </div>
                </div>
                <div class="col-lg-2 bg-right-list">
                    <P>@lang('labels.frontend.layouts.partials.courses_categories')</P>
                    <h2 class="black bold">@lang('labels.frontend.course.category')</h2>

                    <ul class="ul-right">
                        @if(count($categories) > 0)
                            @foreach($categories as $category)
                                <a href="{{route('courses.category',['category'=>$category->slug])}}">
                                    <li class="li-right"><i
                                                class="{{$category->icon}} p-2"></i>{{$category->getDataFromColumn('name')}}
                                    </li>
                                </a>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>

        </div>

    </section>
    <!-- End  of Checkout content
        ============================================= -->

@endsection

@push('after-scripts')
    @if(config('services.stripe.active') == 1)
        <script type="text/javascript" src="{{asset('js/stripe-form.js')}}"></script>
    @endif
    <script>
        $(document).ready(function () {
            $(document).on('click', 'input[type="radio"]:checked', function () {
                $('#accordion .check-out-form').addClass('disabled')
                $(this).closest('.payment-method').find('.check-out-form').removeClass('disabled')
            })

            $(document).on('click', '#applyCoupon', function () {
                var coupon = $('#coupon');
                if (!coupon.val() || (coupon.val() == "")) {
                    coupon.addClass('warning');
                    $('#coupon-error').html("<small>{{trans('labels.frontend.cart.empty_input')}}</small>").removeClass('d-none')
                    setTimeout(function () {
                        $('#coupon-error').empty().addClass('d-none')
                        coupon.removeClass('warning');

                    }, 5000);
                } else {
                    $('#coupon-error').empty().addClass('d-none')
                    $.ajax({
                        method: 'POST',
                        url: "{{route('cart.applyCoupon')}}",
                        data: {
                            _token: '{{csrf_token()}}',
                            coupon: coupon.val()
                        }
                    }).done(function (response) {
                        if (response.status === 'fail') {
                            coupon.addClass('warning');
                            $('#coupon-error').removeClass('d-none').html("<small>" + response.message + "</small>");
                            setTimeout(function () {
                                $('#coupon-error').empty().addClass('d-none');
                                coupon.removeClass('warning');

                            }, 5000);
                        } else {
                            $('.purchase-list').empty().html(response.html)
                            $('#applyCoupon').removeClass('btn-dark').addClass('btn-success')
                            $('#coupon-error').empty().addClass('d-none');
                            coupon.removeClass('warning');
                        }
                    });

                }
            });


            $(document).on('click', '#removeCoupon', function () {
                $.ajax({
                    method: 'POST',
                    url: "{{route('cart.removeCoupon')}}",
                    data: {
                        _token: '{{csrf_token()}}',
                    }
                }).done(function (response) {
                    $('.purchase-list').empty().html(response.html)
                });
            })
            $('#payMobButton').on('click', function () {
                $('#payMobModal').modal('show');
                $('#payMobModal .modal-body').html('');
                $('#payMobModal .modal-body').append('<div class="ajax-loader" style="margin: 0 auto;"></div>');
                $.ajax({
                    method: 'POST',
                    url: "{{route('cart.paymob.payment')}}",
                    data: {
                        _token: '{{csrf_token()}}',
                    },
                    success: function (resp) {
                        $('#payMobModal .modal-body .ajax-loader').remove();
                        $('#payMobModal .modal-body').css('justify-content', 'unset');
                        if (resp.paymentKey) {
                            $('#payMobModal .modal-body').html('<iframe style="height: 570px;border: none;" src="' + resp.url + '"></iframe>')
                        } else {
                            location.reload();
                            $('#payMobModal').modal('hide');
                        }
                    }
                })
            })
            $('#vodafoneCashModal form').on('submit', function (e) {
                e.preventDefault();

                $.ajax({
                    method: 'post',
                    url: "{{route('cart.vodafoneCash.payment')}}",
                    data: {
                        _token: '{{csrf_token()}}',
                        mobileNumber: $('input[name="mobileNumber"]').val(),
                    },
                    beforeSend: function () {
                        $('#vodafoneCashModal .modal-body').html('');
                        $('#vodafoneCashModal .modal-body').append('<div class="ajax-loader" style="margin: 0 auto;"></div>');
                    },
                    success: function (resp) {
                        $('#vodafoneCashModal .modal-body .ajax-loader').remove();
                        $('#vodafoneCashModal .modal-body').css('justify-content', 'unset');
                        if (resp.payment.detail) {
                            $('#vodafoneCashModal .modal-body').text(resp.payment.detail);
                        }
                        console.log(resp)
                    }
                })
            })
        })
    </script>
@endpush





