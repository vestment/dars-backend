@extends('frontend-rtl.layouts.app'.config('theme_layout'))
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
    <link rel="stylesheet" href="../../assets-rtl/css/course.css"/>
@endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="page-breadcrumb-content text-center">
                <div class="page-breadcrumb-title">
                    <h2 class="breadcrumb-head black bold"><span>@if(isset($category)) {{$category->name}} @else @lang('labels.frontend.course.courses') @endif </span></h2>
                </div>
            </div>
        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->


    <!-- Start of course section
        ============================================= -->
    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="row">
                <div class="col-md-9">
                    @if(session()->has('success'))
                        <div class="alert alert-dismissable alert-success fade show">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{session('success')}}
                        </div>
                    @endif
                    <div class="short-filter-tab">
                        <div class="shorting-filter w-50 d-inline float-left mr-3">
                            <span>@lang('labels.frontend.course.sort_by')</span>
                            <select id="sortBy" class="form-control d-inline w-50">
                                <option value="">@lang('labels.frontend.course.none')</option>
                                <option value="popular">@lang('labels.frontend.course.popular')</option>
                                <option value="trending">@lang('labels.frontend.course.trending')</option>
                                <option value="featured">@lang('labels.frontend.course.featured')</option>
                            </select>
                        </div>

                        <div class="tab-button blog-button ul-li text-center float-right">
                            <ul class="product-tab">
                                <li class="active" rel="tab1"><i class="fas fa-th"></i></li>
                                <li rel="tab2"><i class="fas fa-list"></i></li>
                            </ul>
                        </div>

                    </div>

                    <div class="genius-post-item">
                        <div class="tab-container">
                            <div id="tab1" class="tab-content-1 pt35">
                                <div class="best-course-area best-course-v2">
                                    <div class="row">
                                           @if($courses->count() > 0)

                                        @foreach($courses as $course)
                                            <div class="col-lg-4 col-md-6">
                                                <div class="best-course-pic-text relative-position">
                                                    <div class="best-course-pic piclip relative-position"
                                                            @if($course->course_image != "") style="background-image: url('{{asset('storage/uploads/'.$course->course_image)}}')" @endif>
                                                            <div class="course-price text-center gradient-bg">
                                                            @if($course->free == 1)
                                                                <span>{{trans('labels.backend.courses.fields.free')}}</span>
                                                            @else
                                                                <span> {{$appCurrency['symbol'].' '.$course->price}}</span>
                                                            @endif
                                                        </div>
                                                        <!-- <div class="course-details-btn">
                                                            <a href="{{ route('courses.show', [$course->slug]) }}">@lang('labels.frontend.course.course_detail')
                                                                <i class="fas fa-arrow-right"></i></a>
                                                        </div>
                                                        <div class="blakish-overlay"></div> -->
                                                    </div>
                                                    <div class="card-body">
                                                        <h3 class="card-title titleofcard">{{$course->title}}</h3>
                                                        <div class="row">
                                                        <div class="col-12">
                                                            <div class="course-rate ul-li">
                                                                <ul>
                                                                    @for($i=1; $i<=(int)$course->rating; $i++)
                                                                        <li><i class="fas fa-star"></i></li>
                                                                    @endfor
                                                                </ul>
                                                            </div>
                                                            <img src="../../assets/img/course/Cat – 1/star.svg">
                                                            <img src="../../assets/img/course/Cat – 1/star.svg">
                                                            <img src="../../assets/img/course/Cat – 1/star.svg">
                                                            <img src="../../assets/img/course/Cat – 1/star.svg">
                                                            <img src="../../assets/img/course/Cat – 1/star.svg">
                                                            <span class="ml-1  rate">4.4 (222)</span>
                                                        </div>
                                                    </div>
                                                    <div class="course-meta my-1">
                                                            <span class="course-category" style="margin-right:-2%">
                                                                <a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a>
                                                            </span>
                                                            <span class="course-author" style="margin-right:-2%"><a href="#">{{ $course->students()->count() }}
                                                                    @lang('labels.frontend.course.students')</a></span>
                                                            <!-- <span class="course-author"><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-people" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                                            <path fill-rule="evenodd" d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.995-.944v-.002.002zM7.022 13h7.956a.274.274 0 0 0 .014-.002l.008-.002c-.002-.264-.167-1.03-.76-1.72C13.688 10.629 12.718 10 11 10c-1.717 0-2.687.63-3.24 1.276-.593.69-.759 1.457-.76 1.72a1.05 1.05 0 0 0 .022.004zm7.973.056v-.002.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10c-1.668.02-2.615.64-3.16 1.276C1.163 11.97 1 12.739 1 13h3c0-1.045.323-2.086.92-3zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                                                                            </svg> {{ $course->students()->count() }}Students</span> -->
                                                            <span class="course-author" style="margin-right:-2%">
                                                                    {{ $course->lessons()->count() }}    @lang('labels.backend.courses.lessons') 
                                                            </span>
                                                    </div>
                                                    <div class="row my-3">
                                                            <div class="col-3">
                                                                <img src="../../assets/img/course/c-3.jpg" class="rounded-circle">
                                                            </div>
                                                            <div class="col-9">
                                                                <div class="row">
                                                                <div class="col-12 metatitle">{{$course->meta_title}}</div>
                                                                <div class="col-12 metadescr">{{$course->meta_description}}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                            <div class="col-10">
                                                                @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                                                        <button type="submit" class="btn btn-block btnAddCard">   @lang('labels.frontend.course.add_to_cart') 
                                                                            <i class="fa fa-shopping-bag ml-1"></i>
                                                                        </button> 
                                                                    
                                                                @elseif(!auth()->check())
                                                                    @if($course->free == 1)
                                                                                <a id="openLoginModal"
                                                                                class="btn btn-block btnAddCard"
                                                                                data-target="#myModal" href="#">@lang('labels.frontend.course.get_now') <i
                                                                                            class="fas fa-caret-right"></i></a>
                                                                        @else
               
                                                                            <a id="openLoginModal"
                                                                                class="btn btn-block btnAddCard"
                                                                                data-target="#myModal" href="#">@lang('labels.frontend.course.add_to_cart') 
                                                                                <i class="fa fa-shopping-bag"></i>
                                                                            </a>
                                                                        @endif
                                                                @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                                                                    @if($course->free == 1)
                                                                            <form action="{{ route('cart.getnow') }}" method="POST">
                                                                                @csrf
                                                                                <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                                                                <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                                <button class="btn btn-block btnAddCard"
                                                                                        href="#">@lang('labels.frontend.course.get_now') <i
                                                                                            class="fas fa-caret-right"></i></button>
                                                                            </form>
                                                                    @else
                                                                            <form action="{{ route('cart.addToCart') }}" method="POST">
                                                                                @csrf
                                                                                <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                                                                <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                                                                <button type="submit"
                                                                                        class="btn btn-block btnAddCard">
                                                                                    @lang('labels.frontend.course.add_to_cart') <i
                                                                                            class="fa fa-shopping-bag"></i></button>
                                                                            </form>
                                                                    @endif
                                                                @endif
                                                            </div>
                                                            <div class="col-2 "  style="margin-right: -27px;">
                                                                <a href="{{ route('courses.show', [$course->slug]) }}" class="btn btnWishList">
                                                                    <i class="far fa-bookmark"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div> 
                                                </div>
                                            </div>

                                                <!-- /*his rtl */
                                            <div class="col-md-4">
                                                <div class="best-course-pic-text relative-position">
                                                    <div class="best-course-pic relative-position" @if($course->course_image != "") style="background-image: url('{{asset('storage/uploads/'.$course->course_image)}}')" @endif>

                                                        @if($course->trending == 1)
                                                        <div class="trend-badge-2 text-center text-uppercase">
                                                        <i class="fas fa-bolt"></i>
                                                        <span>@lang('labels.frontend.badges.trending')</span>
                                                        </div>
                                                        @endif
                                                            @if($course->free == 1)
                                                                <div class="trend-badge-3 text-center text-uppercase">
                                                                    <i class="fas fa-bolt"></i>
                                                                    <span>@lang('labels.backend.courses.fields.free')</span>
                                                                </div>
                                                            @endif
                                                        <div class="course-price text-center gradient-bg">
                                                            @if($course->free == 1)
                                                                <span>{{trans('labels.backend.courses.fields.free')}}</span>
                                                            @else
                                                                <span> {{$appCurrency['symbol'].' '.$course->price}}</span>
                                                            @endif
                                                        </div>
                                                        <div class="course-rate ul-li">
                                                            <ul>
                                                                @for($i=1; $i<=(int)$course->rating; $i++)
                                                                    <li><i class="fas fa-star"></i></li>
                                                                @endfor
                                                            </ul>
                                                        </div>
                                                        <div class="course-details-btn">
                                                            <a href="{{ route('courses.show', [$course->slug]) }}">@lang('labels.frontend.course.course_detail') <i class="fas fa-arrow-right"></i></a>
                                                        </div>
                                                        <div class="blakish-overlay"></div>
                                                    </div>
                                                    <div class="best-course-text">
                                                        <div class="course-title mb20 headline relative-position">
                                                            <h3>
                                                                <a href="{{ route('courses.show', [$course->slug]) }}">{{$course->title}}</a>
                                                            </h3>
                                                        </div>
                                                        <div class="course-meta">
                                                            <span class="course-category"><a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a></span>
                                                            <span class="course-author"><a href="#">{{ $course->students()->count() }}
                                                                    @lang('labels.frontend.course.students')</a></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> -->
                                    @endforeach
                                    @else
                                            <h3>@lang('labels.general.no_data_available')</h3>
                                    @endif


                                    <!-- /course -->


                                    </div>
                                </div>
                            </div><!-- /tab-1 -->

                            <div id="tab2" class="tab-content-1">
                                <div class="course-list-view">
                                    <table>
                                        <tr class="list-head">
                                            <th>@lang('labels.frontend.course.course_name')</th>
                                            <th>@lang('labels.frontend.course.course_type')</th>
                                            <th>@lang('labels.frontend.course.starts')</th>
                                        </tr>
                                      @if($courses->count() > 0)

                                        @foreach($courses as $course)
                                            <tr>
                                                <td>
                                                    <div class="course-list-img-text">
                                                        <div class="course-list-img" @if($course->course_image != "") style="background-image: url({{asset('storage/uploads/'.$course->course_image)}})" @endif >
                                                        </div>
                                                        <div class="course-list-text">
                                                            <h3>
                                                                <a href="{{ route('courses.show', [$course->slug]) }}">{{$course->title}}</a>
                                                            </h3>
                                                            <div class="course-meta">
                                                                <span class="course-category bold-font"><a
                                                                            href="{{ route('courses.show', [$course->slug]) }}">
                                                                         @if($course->free == 1)
                                                                            {{trans('labels.backend.courses.fields.free')}}
                                                                        @else
                                                                            {{$appCurrency['symbol'].' '.$course->price}}
                                                                        @endif
                                                                    </a></span>

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
                                                        <span><a href="{{route('courses.category',['category'=>$course->category->slug])}}">{{$course->category->name}}</a></span>
                                                    </div>
                                                </td>
                                                <td>{{\Carbon\Carbon::parse($course->start_date)->format('d M Y')}}</td>
                                            </tr>
                                        @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3">
                                                    <h3>@lang('labels.general.no_data_available')</h3>

                                                </td>
                                            </tr>
                                        @endif

                                    </table>
                                </div>
                            </div><!-- /tab-2 -->
                        </div>
                        <div class="couse-pagination text-center ul-li">
                            {{ $courses->links() }}
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="side-bar">

                        <div class="side-bar-widget  first-widget">
                            <h2 class="widget-title text-capitalize">@lang('labels.frontend.course.find_your_course')</h2>
                            <div class="listing-filter-form pb30">
                                <form action="{{route('search-course')}}" method="get">
                                    <div class="filter-search mb20">
                                        <label class="text-uppercase">@lang('labels.frontend.course.category')</label>
                                        <select name="category" class="form-control listing-filter-form select">
                                            <option value="">@lang('labels.frontend.course.select_category')</option>
                                            @if(count($categories) > 0)
                                                @foreach($categories as $category)
                                                    <option @if(request('category') && request('category') == $category->id) selected
                                                            @endif value="{{$category->id}}">{{$category->name}}</option>

                                                @endforeach
                                            @endif

                                        </select>
                                    </div>


                                    <div class="filter-search mb20">
                                        <label>@lang('labels.frontend.course.full_text')</label>
                                        <input type="text" class="" value="{{(request('q') ? request('q') : old('q'))}}"
                                               name="q" placeholder="{{trans('labels.frontend.course.looking_for')}}">
                                    </div>
                                    <button class="genius-btn gradient-bg text-center text-uppercase btn-block text-white font-weight-bold"
                                            type="submit">@lang('labels.frontend.course.find_courses') <i
                                                class="fas fa-caret-right"></i></button>
                                </form>

                            </div>
                        </div>

                        @if($recent_news->count() > 0)
                            <div class="side-bar-widget">
                                <h2 class="widget-title text-capitalize">@lang('labels.frontend.course.recent_news')</h2>
                                <div class="latest-news-posts">
                                    @foreach($recent_news as $item)
                                        <div class="latest-news-area">

                                            @if($item->image != "")
                                                <div class="latest-news-thumbnile relative-position"
                                                     style="background-image: url({{asset('storage/uploads/'.$item->image)}})">
                                                    <div class="blakish-overlay"></div>
                                                </div>
                                            @endif
                                            <div class="date-meta">
                                                <i class="fas fa-calendar-alt"></i> {{$item->created_at->format('d M Y')}}
                                            </div>
                                            <h3 class="latest-title bold-font"><a href="{{route('blogs.index',['slug'=>$item->slug.'-'.$item->id])}}">{{$item->title}}</a></h3>
                                        </div>
                                        <!-- /post -->
                                    @endforeach


                                    <div class="view-all-btn bold-font">
                                        <a href="{{route('blogs.index')}}">@lang('labels.frontend.course.view_all_news') <i class="fas fa-chevron-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>

                        @endif


                        @if($global_featured_course != "")
                            <div class="side-bar-widget">
                                <h2 class="widget-title text-capitalize">@lang('labels.frontend.course.featured_course')</h2>
                                <div class="featured-course">
                                    <div class="best-course-pic-text relative-position pt-0">
                                        <div class="best-course-pic relative-position "
                                            @if($global_featured_course->course_image != "") style="background-image: url({{asset('storage/uploads/'.$global_featured_course->course_image)}})" @endif>

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
                                                <h3><a href="{{ route('courses.show', [$global_featured_course->slug]) }}">{{$global_featured_course->title}}</a></h3>
                                            </div>
                                            <div class="course-meta">
                                                <span class="course-category"><a href="{{route('courses.category',['category'=>$global_featured_course->category->slug])}}">{{$global_featured_course->category->name}}</a></span>
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
    </section>
    <!-- End of course section
        ============================================= -->

    <!-- Start of best course
   =============================================  -->
    @include('frontend.layouts.partials.browse_courses')
    <!-- End of best course
            ============================================= -->


@endsection

@push('after-scripts')
    <script>
        $(document).ready(function () {
            $(document).on('change','#sortBy',function () {
               if($(this).val() != ""){
                   location.href = '{{url()->current()}}?type='+$(this).val();
               }else{
                   location.href = '{{route('courses.all')}}';
               }
            })

            @if(request('type') != "")
                $('#sortBy').find('option[value="'+"{{request('type')}}"+'"]').attr('selected',true);
            @endif
        });

    </script>
@endpush