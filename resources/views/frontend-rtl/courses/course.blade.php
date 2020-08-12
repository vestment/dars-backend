@extends('frontend-rtl.layouts.app'.config('theme_layout'))

@section('title', ($course->meta_title) ? $course->meta_title : app_name() )
@section('meta_description', $course->meta_description)
@section('meta_keywords', $course->meta_keywords)

@push('after-styles')
    <style>
        .leanth-course.go {
            right: 0;
        }
        .video-container iframe{
            max-width: 100%;
        }

    </style>
    <link rel="stylesheet" href="https://cdn.plyr.io/3.5.3/plyr.css"/>

    @endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style bgcolor">
        <div class="blakish-overlay"></div>
            <div class="container">
                <div class="col m-5 p-3 paragraph1">
                    <div class="m-1">    
                        <p >Explore /{{$course->category->name}}</p>
                    </div>
                    <div class="p-1">
                        <h2 class="text-white"><b>{{$course->title}}</b></h2>
                    </div>
                    <div class="p-1">    
                        {{-- <i class="fas fa-star" style="color: yellow"></i>
                        <i class="fas fa-star" style="color: yellow"></i>
                        <i class="fas fa-star" style="color: yellow"></i>
                        <i class="fas fa-star" style="color: yellow"></i>
                        <i class="fas fa-star" style="color: yellow"></i> --}}
                        @for($r=1; $r<=$course_rating; $r++)
                            <i class="fas fa-star" style="color: yellow"></i>
                        @endfor
                        @for($r=1; $r<=5-$course_rating; $r++)
                        <i class="fas fa-star"></i>
                        @endfor
                        <span class="text-white">{{$course_rating}}</span>
                    </div>
                    <div class="row col-lg-3 flex">
                        @foreach($course->teachers as $key=>$teacher)
                                <img style="border-radius: 50%"  src=" {{$teacher->picture}}" alt="">
                                @php $key++ @endphp
                                    <p class="text-white mt-4 ml-2">   {{$teacher->full_name}}</p>@if($key < count($course->teachers )), @endif
                        @endforeach

                    </div>         
                    <div class="row mt-3 flex">        
                        <div class="row col-lg-6 buttoncart">
                            @if (!$purchased_course)
                        
                                @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                                    <button class="btn btn-outline-light m-1 addcart"
                                            type="submit">@lang('labels.frontend.course.added_to_cart')
                                    </button>
                                @elseif(!auth()->check())
                                    @if($course->free == 1)
                                        <a id="openLoginModal" data-target="#myModal" href="#"> 
                                            <button class="btn btn-outline-light m-1 addcart"> 
                                                @lang('labels.frontend.course.get_now') 
                                                <i class="fas fa-caret-right"></i>
                                            </button>
                                        </a>
                                    @else
                    


                                    <button id="openLoginModal" type="submit"
                                        data-target="#myModal" href="#" class="btn btn-outline-light m-1 addcart"> <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                        @lang('labels.frontend.course.add_to_cart')
                                    </button>

                                    <!-- {{-- <a id="openLoginModal"
                                    class="genius-btn btn-block my-2 bg-dark text-center text-white text-uppercase "
                                    data-target="#myModal" href="#">@lang('labels.frontend.course.add_to_cart') <i
                                                class="fa fa-shopping-bag"></i></a> --}} -->
                                    @endif
                                @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                                    @if($course->free == 1)
                                        <form action="{{ route('cart.getnow') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                            <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                            <button class="btn btn-outline-light text-white m-1 addcart"
                                                    href="#">@lang('labels.frontend.course.get_now') <i
                                                        class="fas fa-caret-right"></i></button>
                                        </form>
                                    @else
                                    
                                        <form action="{{ route('cart.addToCart') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                            <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                            <button type="submit" class="btn btn-outline-light m-1 addcart"> <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                                @lang('labels.frontend.course.add_to_cart')
                                                </button>
                                        </form>
                                    @endif


                                @else
                                    <h6 class="alert alert-danger"> @lang('labels.frontend.course.buy_note')</h6>
                                @endif
                            @else
                            @if($continue_course)
                                <a  href="{{route('lessons.show',['id' => $course->id,'slug'=>$continue_course->model->slug])}}">
                                    <button class="btn btn-outline-light m-1 addcart" type="submit">
                                        @lang('labels.frontend.course.continue_course')
                                        <i class="fa fa-arow-right"></i>
                                    </button>
                                </a>
                                
                                <!-- <a href="{{route('lessons.show',['id' => $course->id,'slug'=>$continue_course->model->slug])}}"
                                class="btn btn-outline-light m-1 addcart">
                                    @lang('labels.frontend.course.continue_course')
                                    <i class="fa fa-arow-right"></i></a> -->
                                @endif

                            @endif

                                <!-- {{-- <button type="submit" class="btn btn-outline-light m-1"> <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                @lang('labels.frontend.course.add_to_cart')
                                </button> --}} -->
                    
                                <!-- wishlist -->
            
                                <button type="submt" class="btn btn-outline-light m-1 btnsize"> <i class="fa fa-bookmark" aria-hidden="true"></i>
                                    @lang('labels.frontend.course.wishlist')
                                </button>
                                <button type="submt" class="btn btn-outline-light m-1"> <i class="fa fa-share-alt" aria-hidden="true"></i>
                                    @lang('labels.frontend.course.Share')
                                    </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End of breadcrumb section
        ============================================= -->

    <!-- Start of what you will learn content section
        ============================================= -->
    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="row col-lg-8 col-sm-12 coursesec d-block m-2">
                    <h2 > What you will learn</h2>
                <div class="row subtitle2">
                    <div class="col-lg-6 col-sm-12">
                        <p> <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Over 350,000 students in 195 countries!</p>
                        <p> <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Free $99 384 page book version of this course!</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Get many customers by using the best networking tool!</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Create financial models from scratch (the Professor makes it so easy to understand).</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Over 350,000 students in 195 countries!</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Understand how macro economics and micro economics works.</p>
                        <p><i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Over 350,000 students in 195 countries!</p>
                    </div>
                    <div class="col-lg-6 col-sm-12">
                        <p> <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Superb reviews!</p>
                        <p>  <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Get any job the easy way.</p>
                        <p>  <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Raise a lot of money quickly.</p>
                        <p>  <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Analyze company financials with ease!</p>
                        <p>  <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Over 350,000 students in 195 countries!</p>
                        <p>  <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Over 350,000 students in 195 countries!</p>
                        <p>  <i class="fa fa-angle-down p-1" aria-hidden="true"></i>
                            Change careers easily.</p>
                    </div>
                </div>
            </div>
            <div class="row  coursesec d-block m-3">
                <h2 > Requirements</h2>
            </div>
            <div class="row d-block m-3">
                <p > <i class="fa fa-angle-down p-2" aria-hidden="true"></i>
                    Nothing except a positive attitude!</p>
            </div>
 
            <!-- video modal -->
            <!--Modal: Name-->
            <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <!--Content-->
                        <!-- <div class="modal-content"> -->
                        <!--Body-->
                        <div class="modal-body mb-0 p-0 mt-5">
                            @if($course->mediaVideo && $course->mediavideo->count() > 0)
                                    <!-- <div class="course-single-text"> -->
                                        @if($course->mediavideo != "")
                                            <div class="course-details-content">
                                                <div class="video-container mb-5" data-id="{{$course->mediavideo->id}}">
                                                    @if($course->mediavideo->type == 'youtube')


                                                        <div id="player" class="js-player embed-responsive embed-responsive-16by9" data-plyr-provider="youtube"
                                                            data-plyr-embed-id="{{$course->mediavideo->file_name}}"></div>
                                                            
                                                    @elseif($course->mediavideo->type == 'vimeo')
                                                        <div id="player" class="js-player" data-plyr-provider="vimeo"
                                                            data-plyr-embed-id="{{$course->mediavideo->file_name}}"></div>
                                                    @elseif($course->mediavideo->type == 'upload')
                                                        <video poster="" id="player" class="js-player" playsinline controls>
                                                            <source src="{{$course->mediavideo->url}}" type="video/mp4"/>
                                                        </video>
                                                    @elseif($course->mediavideo->type == 'embed')
                                                        {!! $course->mediavideo->url !!}
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                            @endif
                            </div>
                        <!-- </div> -->
                        <!--/.Content-->
            </div>
        </div>
            <!--Modal: Name-->

            <!-- video -->
        <div class="col-4 m-5 shadow-lg divfixed paddingleft text-left">
            <!-- Grid row -->
            <a>
                <div class="col divpoly" data-toggle="modal" data-target="#modal1"
                    @if($course->course_image != "") 
                    style="background-image: url('{{asset('storage/uploads/'.$course->course_image)}}')" @endif>
                </div>
            </a>
                <!-- Grid row -->
                    {{-- <!-- @if($course->mediaVideo && $course->mediavideo->count() > 0)
                                <div class="course-single-text">
                                    @if($course->mediavideo != "")
                                        <div class="course-details-content">
                                            <div class="video-container mb-5" data-id="{{$course->mediavideo->id}}">
                                                @if($course->mediavideo->type == 'youtube')


                                                    <div id="player" class="js-player col divpoly embed-responsive embed-responsive-16by9" data-plyr-provider="youtube"
                                                         data-plyr-embed-id="{{$course->mediavideo->file_name}}"></div>
                                                         
                                                @elseif($course->mediavideo->type == 'vimeo')
                                                    <div id="player" class="js-player" data-plyr-provider="vimeo"
                                                         data-plyr-embed-id="{{$course->mediavideo->file_name}}"></div>
                                                @elseif($course->mediavideo->type == 'upload')
                                                    <video poster="" id="player" class="js-player" playsinline controls>
                                                        <source src="{{$course->mediavideo->url}}" type="video/mp4"/>
                                                    </video>
                                                @elseif($course->mediavideo->type == 'embed')
                                                    {!! $course->mediavideo->url !!}
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                </div>
                    @endif --> --}}

                    <!-- <div class="col divpoly embed-responsive embed-responsive-16by9">
                            <iframe  class="embed-responsive-item" src="https://www.youtube.com/embed/XHOmBV4js_E" allowfullscreen></iframe>
                    </div> -->
                    {{-- <h3>hello</h3> --}}
            <div class="col mr-3 pricebottom">
                <h3 class="font49">
                    @if($course->free == 1)
                            <span> {{trans('labels.backend.courses.fields.free')}}</span>
                        @else
                            <span>   {{$appCurrency['symbol'].' '.$course->price}}</span>
                    @endif
                </h3>
                <h6 class="font20">This course includes: </h6>
                <p class="smpara"> <i class="fa fa-play-circle" aria-hidden="true"></i> 8 hours on-demand video</p>
                <p class="smpara"> <i class="fa fa-file" aria-hidden="true"></i> <span>  {{$chaptercount}} </span>  @lang('labels.frontend.course.chapters')</p>
                <p class="smpara"> <i class="fa fa-download" aria-hidden="true"></i> 65 downloadable resources</p>
                    <!-- <p class="smpara"> <i class="fa fa-film" aria-hidden="true"></i> Access on mobile and TV</p>
                    <p class="smpara"> <i class="fa fa-certificate" aria-hidden="true"></i> Certificate of completion</p> -->

                @if (!$purchased_course)
                        @if(auth()->check() && (auth()->user()->hasRole('student')) && (Cart::session(auth()->user()->id)->get( $course->id)))
                            <button class="btn btncolor btn-sm btn-block text-white"
                                    type="submit">@lang('labels.frontend.course.added_to_cart')
                            </button>
                            @elseif(!auth()->check())
                                @if($course->free == 1)
                                    <a id="openLoginModal"
                                    class="genius-btn btn-block text-white  gradient-bg text-center text-uppercase  bold-font"
                                    data-target="#myModal" href="#">@lang('labels.frontend.course.get_now') <i
                                                class="fas fa-caret-right"></i>
                                    </a>
                                    @else

                                    <button id="openLoginModal" type="submit"
                                        data-target="#myModal" href="#" class="btn btncolor btn-sm btn-block text-white"> <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                        @lang('labels.frontend.course.add_to_cart')
                                    </button>
                                @endif
                            @elseif(auth()->check() && (auth()->user()->hasRole('student')))

                            @if($course->free == 1)
                                    <form action="{{ route('cart.getnow') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                        <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                        <button class="btn btncolor btn-sm btn-block text-white"
                                                href="#">@lang('labels.frontend.course.get_now') <i
                                                    class="fas fa-caret-right"></i></button>
                                    </form>
                                @else
                                    <form action="{{ route('cart.addToCart') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="course_id" value="{{ $course->id }}"/>
                                        <input type="hidden" name="amount" value="{{($course->free == 1) ? 0 : $course->price}}"/>
                                        <button type="submit" class="btn btncolor btn-sm btn-block text-white"> <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                            @lang('labels.frontend.course.add_to_cart')
                                            </button>
                                    </form>
                            @endif


                            @else
                                <h6 class="alert alert-danger"> @lang('labels.frontend.course.buy_note')</h6>
                        @endif
                    @else

                        @if($continue_course)

                            <a href="{{route('lessons.show',['id' => $course->id,'slug'=>$continue_course->model->slug])}}"
                            class="genius-btn btn-block text-white  gradient-bg text-center text-uppercase  bold-font">

                                @lang('labels.frontend.course.continue_course')

                                <i class="fa fa-arow-right"></i>
                            </a>
                        @endif

                @endif
            </div>
        </div>
    </section>
    <!-- end of what you will learn content section
        ============================================= -->

    <!-- Start of course content section
        ============================================= -->
    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="row  coursecontent d-block m-2">
                <h2>@lang('labels.frontend.course.course_content') </h2>
            </div>
            <div class="row smpara d-block m-2">
                <p></i> <span>  {{$chaptercount}} </span>  @lang('labels.frontend.course.chapters') •
                    <span>  {{$lessoncount}} </span>  @lang('labels.frontend.course.lessons') • 8h 0m total length</p>
            </div>
            
            @foreach($chapters as $chapter)
                <div class="row m-2 shadow">
                    <div class="accordion" id="accordionExample">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                                <h2 class="mb-0">
                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#{{$chapter->id}}" aria-expanded="true" aria-controls="{{$chapter->id}}" >
                                {{ $chapter->title}} <i class="fa fa-angle-down float-right" aria-hidden="true"></i>
                                </button>
                                @if($course->trending == 1)
                                    <span class="trend-badge text-uppercase bold-font"><i
                                        class="fas fa-bolt"></i> @lang('labels.frontend.badges.trending')</span>
                                @endif
                                </h2>
                            </div>
                    
                            <div id="{{$chapter->id}}" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                <div class="card-body">
                                    @foreach($lessons->get() as $key=>$item)
                                        @php $key++; @endphp
                                        <div class="bordered">
                                            @if($item->model && $item->model->published == 1)    
                                                <p class="subtitle2">  
                                                    <a href="{{route('lessons.show',['id' => $item->course->id,'slug'=>$item->model->slug])}}">
                                                        @if($item->model->chapter_id == $chapter->id)
                                                            {{$item->model->title}}
                                                        @endif
                                                        @if($item->model_type == 'App\Models\Test')
                                                            <p class="mb-0 text-primary">- @lang('labels.frontend.course.test')</p>
                                                        @endif  
                                                    </a> 
                                                </p>
                                            <!-- <p class="play10"> <i class="fa fa-play-circle" aria-hidden="true"></i> 10 Min </p> -->
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <!-- End of course content section
        ============================================= -->

    <!-- Start of course review section
        ============================================= --> 
    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="course-review">
                <div class="section-title-2 mb20 headline text-left">
                    <h2>@lang('labels.frontend.course.course_reviews')</h2>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="ratting-preview">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="avrg-rating ul-li">
                                        <b>@lang('labels.frontend.course.average_rating')</b>
                                        <span class="avrg-rate">{{$course_rating}}</span>
                                        <ul>
                                            @for($r=1; $r<=$course_rating; $r++)
                                                <li><i class="fas fa-star"></i></li>
                                            @endfor
                                            @for($r=1; $r<=5-$course_rating; $r++)
                                            <i class="fas fa-star"></i>
                                            @endfor

                                        </ul>
                                        <b>{{$total_ratings}} @lang('labels.frontend.course.ratings')</b>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="avrg-rating ul-li">
                                        <span><b>@lang('labels.frontend.course.details')</b></span>
                                        @for($r=5; $r>=1; $r--)
                                            <div class="rating-overview">
                                                <span class="start-item">{{$r}} @lang('labels.frontend.course.stars')</span>
                                                <span class="start-bar"></span>
                                                <span class="start-count">{{$course->reviews()->where('rating','=',$r)->get()->count()}}</span>
                                            </div>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
    </section>
    <!-- End of course review section
        ============================================= -->

    <!-- Start of Instructor info review section
        ============================================= --> 
    <section id="course-page" class="course-page-section">
        <div class="container">
            <div class="row  coursecontent d-block m-2">
                <h2> @lang('labels.frontend.course.instructors') </h2>
            </div>
            <div class="row m-2">
                @foreach($course->teachers as $key=>$teacher)
                    <div class="col-lg-2 col-md-2 col-sm-3">
                        <img src=" {{$teacher->picture}}" alt="">

                        <!-- {{-- <img src="{{asset('img/backend/brand/logo.png')}}" alt="logo"> --}} -->
                    </div>
                    <div class="col-lg-3 col-md-5 col-sm-3">
                        @php $key++ @endphp
                        <p style="font-size:30px;">{{$teacher->full_name}}</p>@if($key < count($course->teachers )), @endif
                    </div>
                    <div class="col-3">
                        <p>{{$teacher->description}}</p>
                    </div>
                @endforeach
            </div>
            <div class="row m-2">
                <p>  </p>

                {{-- <p> Chris has sold more than 1,000,000 of his online business & self improvement courses 
                    in 12 languages in 196 countries and his courses have been profiled in Business Insider, NBC, Inc, Forbes,
                    CNN, Entrepreneur & on other business news websites. Chris is the author of the #1 best selling online 
                    business course called "An Entire MBA in 1 Course®” & many other courses.</p> 
                    
                <p> He’s the author of the book "101 Crucial Lessons They Don't Teach You in Business School®,"​ which 
                Business Insider wrote is "the most popular book of 2016."​ Forbes called this book "1 of 6 books that all 
                entrepreneurs must read right now. </p>
                
                <p>"​He is the founder & CEO of Haroun Education Ventures, an award winning business school professor, 
                    MBA graduate from Columbia University & former Goldman Sachs employee. He has raised/managed over $1bn 
                    in his career.</p> --}}
            </div>
        </div>
    </section>
  <!-- End of Instructor info review section
        ============================================= --> 





    @endsection



@push('after-scripts')
    <script src="https://cdn.plyr.io/3.5.3/plyr.polyfilled.js"></script>

    <script>
        const player = new Plyr('#player');
        $(document).on('change', 'input[name="stars"]', function () {
            $('#rating').val($(this).val());
        })
                @if(isset($review))
        var rating = "{{$review->rating}}";
        $('input[value="' + rating + '"]').prop("checked", true);
        $('#rating').val(rating);
        @endif
    </script>
@endpush