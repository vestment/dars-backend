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
        .in-total:not(:first-child):not(:last-child){
            font-size: 15px;
        }

    </style>

    <script src='https://js.stripe.com/v2/' type='text/javascript'></script>
@endpush


@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style bg-header-ch">
        <div class="blakish-overlay" ></div>
        <div class="container">
            <div class="page-breadcrumb-content">
                    <div class="page-breadcrumb-title">
                        <p class="text-white pragchechout">
                            explore/wishlist
                        </p>                  
                    </div>
                <div class="page-breadcrumb-title">
                    <h2 class="breadcrumb-head black bold"><span>My Wishlist</span></h2>
                </div>
            </div>
        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->
        <section id="checkout" class="checkout-section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="order-item mb30 course-page-section">
                            <div class="section-title mb45 headline ">
                         
                        </div>
                      

                    <div class="course-list-view table-responsive">
                        <table class="table">

                            <thead>
                            <tr class="list-head text-uppercase">
                                <th>@lang('labels.frontend.cart.course_name')</th>
                                <th>@lang('labels.frontend.cart.price')</th>
                                <th>@lang('labels.frontend.cart.starts')</th>
                                <th></th>
                                <th>@lang('labels.frontend.cart.action')</th>
                            </tr>
                            </thead>
                            


                            <tbody>
                                @if(count($courses) > 0)
                                        @foreach($courses as $courses)
                                <tr class="position-relative">

                                    <td class="w-td">    
                                        <div class="course-list-img-text">
                                            <div class="course-list-img" @if($courses->course_image != "") style="background-image: url({{asset('storage/uploads/'.$courses->course_image)}})" @endif >
                                            </div>
                                            <div class="course-list-text">
                                                <h3>
                                                    <a href="{{ route('courses.show', [$courses->slug]) }}">{{$courses->title}}</a>
                                                </h3>   
                                                <div>    
                                               <img src="storage/uploads/star.png">
                                               <img src="storage/uploads/star.png">
                                               <img src="storage/uploads/star.png">
                                               <img src="storage/uploads/star.png">
                                               <img src="storage/uploads/star.png">
                                                </div>
                                                <div>
                                                    <small><i class="fas fa-map-marker-alt"></i> cairo |</small><small> <i class="far fa-clock"></i> 10 hours |</small><small> <i class="fab fa-youtube"></i> 10 lecture </small>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td>
                                        <span class="course-category bold-font"><a
                                            href="#">@if($courses->free == 1)
                                            <span class="priceLabel">{{trans('labels.backend.bundles.fields.free')}}</span>
                                        @else
                                            <span class="priceLabel"> {{$courses->price}}</span>
                                        @endif</a></span>
                                    </td>
                                    <td>{{($courses->start_date != "") ? $courses->start_date : 'N/A'}}</td>
                                    <td> <form action="{{ route('cart.addToCart') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $courses->id }}"/>
                                        <input type="hidden" name="amount" value="{{($courses->free == 1) ? 0 : $courses->price}}"/>
                                        <button type="submit"
                                                class="genius-btn btn-primary text-center text-white text-uppercase ">
                                            @lang('labels.frontend.course.add_to_cart') <i
                                                    class="fa fa-shopping-bag"></i></button>
                                    </form></td>
                                    <td> <a  class="te-remove "
                                        href="{{route('wishlist.remove',['course'=>$courses])}}">remove</a></td>
                                </tr>                                    
                                
                                @endforeach
                            </tbody>
                        </table>
                                @else
                                <div class="text-center">
                                  <div class="row">
                                      <div class="col-12">
                                            <h2 style="font-weight:bold; font-size: -webkit-xxx-large; color:black">oh no</h2>
                                      </div>
                                  </div>
                                  <div class="row">
                                        <div class="col-12">
                                            <h3 class="text-black-50">no wish list</h3>
                                        </div>
                                    </div>
                                    <div class="row">
                                            <div class="col-12">
                                               <a href="{{route('courses.all')}}"><button class="btn btn-primary">course</button></a>
                                            </div>
                                        </div>
                                    <div class="row">
                                            <div class="col-12">
                                                <img src="{{url('img/frontend/user/error.svg')}}">
                                            </div>
                                        </div>

                                </div>
                                @endif
                               

                    </div>
                </div>
            </div>
        <section>
@endsection
