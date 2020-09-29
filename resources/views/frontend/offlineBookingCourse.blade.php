@extends('frontend.layouts.app')
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
    <link rel="stylesheet" href="../../assets/css/course.css"/>

@endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
        <section id="breadcrumb" class="breadcrumb-section relative-position backgroud-style bg-header-offline">
                <div class="blakish-overlay" ></div>
                <div class="container-fluid">
                    <div class="page-breadcrumb-content">
                            <div class="page-breadcrumb-title">
                                <p class="text-white pragchechout p-1">
                                   <span style="opacity: 0.3"> @lang('labels.frontend.course.explore')</span> / @lang('labels.frontend.course.offline_booking_course')
                                </p>                  
                            </div>
                        <div class="page-breadcrumb-title">
                            <h2 class="breadcrumb-head black bold p-1"><span>@lang('labels.frontend.course.offline_booking_course')</h2>
                        </div>
                    </div>
                </div>
            </section>
    <!-- End of breadcrumb section
        ============================================= -->
    {{-- start myyy of course section --}}
   
    <section>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12 col-lg-2 col-xl-2 col-md-4 filters-section">
                        <button type="button"
                                class="btn btn-block btn-primary btn-toggler mb-xl-0 mb-lg-0 mb-3">@lang('labels.frontend.course.filters.filters')
                            <i class="fas fa-filter"></i></button>

                        <!-- Section: Filters -->
                        <section class="p-2 filters-side-bar">

                            <!-- Section: Average -->
                            <section class="p-3 pb-0 border-bottom academies-filter">
                               
                                <h5 class="font-weight-bold mb-3 head-coll" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">@lang('labels.frontend.course.filters.academies') <i class="fas fa-chevron-down arrowDown"></i></h5>
                               
                                <div class="collapse" id="collapseExample">
                                        @if(count($ac_filter) > 0)
                                        @foreach($ac_filter as $academy)
                                        
                                    <div class="input-group mb-3">
                                    
                                        <div class="input-group-prepend">
                                            <div class="input-group-text checkinput">

                                                <input value="{{$academy->assoc_id}}" type="checkbox" aria-label="Checkbox for following text input" >
                                            </div>
                                        </div>
                                                <label class="form-check-label small font-weight-bold d-block" >
                                                       {{$academy->full_name}}
                                                    </label>
                                    
                                    </div>

                                    @endforeach

                                   @else
                                <h3>@lang('labels.general.no_data_available')</h3>
                            @endif
                                </div>
                            </section>
                            <!-- Section: Average -->

                            <!-- Section: Price -->
                            <section class="p-3 pb-0 border-bottom teachers-filter">

                                <h5 class="font-weight-bold mb-3 head-coll"  data-toggle="collapse" href="#collapseExampl" role="button" aria-expanded="false" aria-controls="collapseExampl">@lang('labels.frontend.course.filters.teachers')  <i class="fas fa-chevron-down arrowDown"></i></h5>
                                <div class="collapse" id="collapseExampl">
                                        @if(count($teach_filtering) > 0)
                                        @foreach($teach_filtering as $teach)
                                    <div class="input-group mb-3">
                                    
                                        <div class="input-group-prepend">
                                            <div class="input-group-text checkinput">
                                                <input value="{{$teach->id}}" type="checkbox" aria-label="Checkbox for following text input">
                                            </div>
                                        </div>
                                                <label class="form-check-label small font-weight-bold d-block" >
                                                       {{$teach->first_name}}
                                                    </label>
                                    
                                    </div>

                                    @endforeach
                                    @else
                                    <h3>@lang('labels.general.no_data_available')</h3>
                                
                                    @endif
                                </div>
                            </section>
                            <!-- Section: Price -->
                            <!-- Section: categories  -->
                            <section class="pb-0 p-3 border-bottom categories-filter">
                                    <h5 class="font-weight-bold mb-3 head-coll" data-toggle="collapse" href="#collapseExam" role="button" aria-expanded="false" aria-controls="collapseExam">@lang('labels.frontend.course.filters.categories')  <i class="fas fa-chevron-down arrowDown"></i></h5>
                                    <div class="collapse" id="collapseExam">
                                        @if(count($cate_filter) > 0)
                                        @foreach($cate_filter as $category)
                                    <div class="input-group mb-3">
                                    
                                        <div class="input-group-prepend">
                                            <div class="input-group-text checkinput">

                                                <input value="{{$category->id}}" type="checkbox" aria-label="Checkbox for following text input">
                                            </div>
                                        </div>
                                                <label class="form-check-label small font-weight-bold d-block" >
                                                       {{$category->name}}
                                                    </label>
                                    
                                    </div>

                                    @endforeach
                                    @else
                                    <h3>@lang('labels.general.no_data_available')</h3>
                              
                                    @endif
                                </div>
                                </section>
                                <!-- Section: categories -->
                            <!-- Section: Price  -->
                            <section class="pb-0 p-3 border-bottom price-filter">
                                <h5 class="font-weight-bold mb-3 head-coll" data-toggle="collapse" href="#collapseExamp" role="button" aria-expanded="false" aria-controls="collapseExamp">@lang('labels.frontend.course.filters.price')  <i class="fas fa-chevron-down arrowDown"></i></h5>
                                <div class="collapse" id="collapseExamp">
                                <div class="form-check pl-0 mb-3">
                                    <input type="checkbox" class="form-check-input" id="isFree">
                                    <label class="form-check-label small font-weight-bold" for="isFree">@lang('labels.backend.courses.fields.free')</label>
                                </div>
                                <input class="price-filter-input" type="range" name="price" id="price" value="0"
                                       step="10"
                                       min="0"
                                       max="10000">
                                <span class="text-muted font-weight-light float-right"><span id="current-price">0</span>  EGP</span>
                                {{--                                <span class="text-muted font-weight-light float-right">10000 EGP</span>--}}
                                </div>
                            </section>
                            <!-- Section: Price -->
                            <section class="filters-controler">
                                <button type="button" style="display: none;"
                                        class="btn btn-block btn-primary btn-apply"><i
                                            class="fas fa-check"></i> @lang('labels.frontend.course.filters.apply')
                                </button>
                                <button type="button" style="display: none;"
                                        class="btn btn-block btn-primary btn-reset"><i
                                            class="fas fa-recycle"></i> @lang('labels.frontend.course.filters.reset')
                                </button>
                            </section>
                        </section>
                        <!-- Section: Filters -->
                    </div>
                    <div class="col-12 col-lg-9 col-xl-9 col-md-8">

                        <div class="row all-courses">
                            @if($courses->count() > 0)

                                @foreach($courses as $course)

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-12 mb-2">
                                        @include('frontend.layouts.partials.coursesTemp')
                                    </div>

                                @endforeach
                            @else
                                <h3>@lang('labels.general.no_data_available')</h3>
                            @endif
                        </div>
                        <div class="row filtered-items" style="display: none">

                        </div>
                    </div>
                </div>
            </div>
        </section>

    {{-- end myyy of course section --}}
    <!-- End of course section
        ============================================= -->

    <!-- Start of best course
   =============================================  -->
    <!-- @in clude('frontend.layouts.partials.browse_courses') -->
    <!-- End of best course
            ============================================= -->


@endsection

@push('after-scripts')
    <script>
        $(document).ready(function () {
            if ($(window).width() <= 768) {
                $('.filters-section .btn-toggler').click(function () {
                    $('.filters-side-bar').toggle(500);
                    $('.filters-category').toggle(500);
                });
            }
            var categories = [];
            $('.categories-filter input').on('click',function() {
                if (!categories[$(this).val()]) {
                    categories.push($(this).val())
                }
                
            });
            var teachers = [];
            $('.teachers-filter input').on('click',function() {
                if (!teachers[$(this).val()]) {
                    teachers.push($(this).val())
                }
                
            });

            var academies = [];
            $('.academies-filter input').on('click',function() {
                if (!academies[$(this).val()]) {
                    academies.push($(this).val())
                }
                
            });
           
            var maxPrice = $('.price-filter-input').val();
            var isFree = $('#isFree').prop('checked');
            var sortBy = $('#sortFilter').val();
            var category = '{{$category->id ?? null}}';
            $('.filters-section .btn-apply').on('click', function () {
                
                var maxPrice = $('.price-filter-input').val();
                var isFree = $('#isFree').prop('checked');
                var sortBy = $('#sortFilter').val();
                var category = '{{$category->id ?? null}}';
               
                $.ajax({
                    url: "{{route('offlineCourses.filterCategory')}}",
                    method: "GET",
                    data: {
                        'academies':academies,
                        'teachers':teachers,
                        'maxPrice': maxPrice,
                        'isFree': isFree,
                        'type': sortBy,
                        'categories':categories
                    },
                    beforeSend: function () {
                        $('.all-courses').hide();
                        $('.filtered-items').html('');
                        $(".filtered-items").show();
                        $(".filtered-items").css('justify-content', 'center').append('<div class="ajax-loader"></div>');
                    },
                    success: function (resp) {
                        console.log(resp);
                        $(".filtered-items").css('justify-content', 'unset');
                        $('.filtered-items').show();
                        $('.filtered-items').html(resp);
                    }
                });
            });
            $('input[type=range]').on('change', function () {
                $('#current-price').text($('input[type=range]').val())
            });
            $('.filters-section input , #sortFilter').on('click', function () {
                if (academies || teachers || categories || maxPrice !== '0' || isFree || sortBy) {
                    $('.btn-apply').show();
                    $('.btn-reset').show();
                } else {
                    $('.btn-apply').fadeOut(500);
                    $('.btn-reset').fadeOut(500);
                }
            });
            $('.btn-reset').click(function () {
                $('.rating-filter input:checked').prop("checked", false);
                $('.duration-filter input:checked').prop("checked", false);
                $('.price-filter-input').val('0');
                $('#isFree').prop("checked", false);
                $('#sortFilter').val('All');
                $('#current-price').text($('input[type=range]').val());
                $('.btn-reset').fadeOut(500);
                $('.btn-apply').fadeOut(500);
                $('.filtered-items').fadeOut();
                $('.all-courses').fadeIn();
            });
            $(document).on('change', '#sortBy', function () {
                if ($(this).val() != "") {
                    location.href = '{{url()->current()}}?type=' + $(this).val();
                } else {
                    location.href = '{{route('offlineBooking.index')}}';
                }
            })

            @if(request('type') != "")
            $('#sortBy').find('option[value="' + "{{request('type')}}" + '"]').attr('selected', true);
            @endif

        });


    </script>
@endpush

