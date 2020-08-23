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

        .in-total:not(:first-child):not(:last-child) {
            font-size: 15px;
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
                    <li class="active">@lang('labels.frontend.course.wishlist')</li>
                </ol>
                <div class="page-breadcrumb-title pb-4 mr-3">
                    <h2 class="breadcrumb-head black bold"><span>@lang('labels.frontend.course.wishlist')</span></h2>
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
                        @if(session()->has('message'))
                            <div class="alert-danger alert">
                                <span> {{session()->get('message')}}</span>
                            </div>
                        @endif
                            @if(session()->has('success_message'))
                                <div class="alert-success alert">
                                    <span> {{session()->get('success_message')}}</span>
                                </div>
                            @endif
                        <div class="course-list-view ">
                            @if(count($courses) > 0)
                                <table class="table table-responsive">

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
                                    @foreach($courses as $course)
                                        <tr class="position-relative">

                                            <td class="w-td">
                                                <div class="course-list-img-text">
                                                    <div class="course-list-img"
                                                         @if($course->course_image != "") style="background-image: url({{asset('storage/uploads/'.$course->course_image)}})" @endif >
                                                    </div>
                                                    <div class="course-list-text">
                                                        <h3>
                                                            <a href="{{ route('courses.show', [$course->slug]) }}">{{$course->title}}</a>
                                                        </h3>
                                                        <div>
                                                            <img src="storage/uploads/star.png">
                                                            <img src="storage/uploads/star.png">
                                                            <img src="storage/uploads/star.png">
                                                            <img src="storage/uploads/star.png">
                                                            <img src="storage/uploads/star.png">
                                                        </div>
                                                        <div>
                                                            <small><i class="fas fa-map-marker-alt"></i> cairo |</small>
                                                            <small><i class="far fa-clock"></i> 10 hours |</small>
                                                            <small><i class="fab fa-youtube"></i> 10 lecture</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <span class="course-category bold-font"><a
                                                    href="#">@if($course->free == 1)
                                                    <span class="priceLabel">{{trans('labels.backend.bundles.fields.free')}}</span>
                                                @else
                                                    <span class="priceLabel"> {{$course->price}}</span>
                                                @endif</a></span>
                                            </td>
                                            <td>{{($course->start_date != "") ? $course->start_date : 'N/A'}}</td>




                                            <td>


                            @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                <button class="btn btn-info  addcart"
                                        type="submit">@lang('labels.frontend.course.added_to_cart')
                                </button>
                            @elseif(!auth()->check())
                                @if($course->free == 1)
                                    <a href="{{route('login.index')}}" class="btn btn-info addcart">
                                        @lang('labels.frontend.course.get_now')
                                        <i class="fas fa-caret-right"></i>
                                    </a>
                                @else

                                    <a href="{{route('login.index')}}" class="btn btn-info addcart"> <i
                                                class="fa fa-shopping-bag" aria-hidden="true"></i>
                                        @lang('labels.frontend.course.add_to_cart')
                                    </a>
                                @endif

                            @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                        

                                    <form action="{{ route('wishlist.addToCart') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                        <input type="hidden" name="amount"
                                               value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                        <button type="submit" class="btn btn-info addcart"><i
                                                    class="fa fa-shopping-bag" aria-hidden="true"></i>
                                            @lang('labels.frontend.course.add_to_cart')
                                        </button>
                                    </form>

                                    @endif


                  


                                                {{-- <form action="{{ route('cart.addToCart') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                                    <input type="hidden" name="amount"
                                                           value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                    <button type="submit"
                                                            class="genius-btn btn-primary text-center text-white text-uppercase ">
                                                        @lang('labels.frontend.course.add_to_cart') <i
                                                                class="fa fa-shopping-bag"></i></button>
                                                </form> --}}




                                            </td>

                                            <td>
                                                  
                                                    <div class="container">
                                                      <h2>Modal Example</h2>
                                                      <!-- Trigger the modal with a button -->
                                                      <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal">Open Modal</button>
                                                    
                                                      <!-- Modal -->
                                                      <div class="modal fade modal-xl" id="myModal" role="dialog">
                                                        <div class="modal-dialog">
                                                        
                                                          <!-- Modal content-->
                                                          <div class="modal-content">
                                                            <div class="mo-head">
                                                              <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                             
                                                            </div>
                                                            <div class="modal-body">
                                                                    <div class="sharethis-inline-share-buttons"></div>
                                                            </div>
                                                            <div class="modal-footer">
                                                              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                            </div>
                                                          </div>
                                                          
                                                        </div>
                                                      </div>
                                                      
                                                    </div>
                                                    
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
                                        <a href="{{url('/')}}"><img src="{{url('img/frontend/user/empty.svg')}}"/></a>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <section>
@endsection
