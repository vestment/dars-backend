@extends('frontend.layouts.app')
@section('title', trans('labels.frontend.course.courses').' | '. app_name() )

@push('after-styles')
    <link rel="stylesheet" href="../../assets/css/course.css"/>

@endpush
@section('content')

    <!-- Start of breadcrumb section
        ============================================= -->
    <section id="breadcrumb" class="breadcrumb-section relative-position pb-5 backgroud-style bg-header-cat">
        <div class="blakish-overlay"></div>
        <div class="container">
            <div class="page-breadcrumb-content text-center">
                <div class=row>
                    <div class="page-breadcrumb-title text-left col-7 col-xl-7 col-md-7 col-lg-7">
                        <h1 class="breadcrumb-head black bold">
                            <span>@if(isset($category)) {{$category->getDataFromColumn('name')}} @else @lang('labels.frontend.course.courses') @endif </span>
                        </h1>
                        <h3>
                        @lang('labels.backend.courses.courses_to_start')
                            <!-- Courses to get you started
                            دورات لتبدأ بها -->
                        </h3>
                    </div>
                    <div class="col-xl-5 col-md-5 col-lg-5 col-5">
                        <img class="breadcrumb-image" src="/assets/img/Learn Online.svg">

                    </div>

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
                            <section class="p-3 pb-0 border-bottom rating-filter">

                                <h5 class="font-weight-bold mb-3">@lang('labels.frontend.course.filters.rating') </h5>
                                <div class="form-group form-check">
                                    <input name="rate" type="radio" data-value="4" class="form-check-input" id="4+">
                                    <label class="form-check-label" for="4+">
                                        <ul class="small rating-list">
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <p class=" px-2">& @lang('labels.frontend.course.filters.up')</p>
                                            </li>
                                        </ul>
                                    </label>

                                </div>
                                <div class="form-group form-check">
                                    <input name="rate" type="radio" data-value="3" class="form-check-input" id="3+">
                                    <label class="form-check-label" for="3+">
                                        <ul class="small rating-list">
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <p class=" px-2">& @lang('labels.frontend.course.filters.up')</p>
                                            </li>
                                        </ul>
                                    </label>
                                </div>
                                <div class="form-group form-check">
                                    <input name="rate" type="radio" data-value="2" class="form-check-input" id="2+">
                                    <label class="form-check-label" for="2+">
                                        <ul class="small rating-list">
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <p class=" px-2">& @lang('labels.frontend.course.filters.up')</p>
                                            </li>
                                        </ul>
                                    </label>
                                </div>
                                <div class="form-group form-check">
                                    <input name="rate" type="radio" data-value="1" class="form-check-input" id="1+">
                                    <label class="form-check-label" for="1+">
                                        <ul class="small rating-list">
                                            <li>
                                                <i class="fas fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <i class="far fa-star fa-sm text-warning"></i>
                                            </li>
                                            <li>
                                                <p class=" px-2">& @lang('labels.frontend.course.filters.up')</p>
                                            </li>
                                        </ul>
                                    </label>
                                </div>


                            </section>
                            <!-- Section: Average -->

                            <!-- Section: Price -->
                            <section class="p-3 pb-0 border-bottom duration-filter">

                                <h5 class="font-weight-bold mb-3">@lang('labels.frontend.course.filters.duration') </h5>

                                <div class="form-check pl-0 mb-3">
                                    <input type="radio" class="form-check-input" id="under2" data-value="0-2"
                                           name="duration">
                                    <label class="form-check-label small font-weight-bold" for="under2">0-2
                                        @lang('labels.frontend.course.filters.hours')</label>
                                </div>
                                <div class="form-check pl-0 mb-3">
                                    <input type="radio" class="form-check-input" data-value="3-6" id="3-6"
                                           name="duration">
                                    <label class="form-check-label small font-weight-bold" for="3-6">3-6
                                         @lang('labels.frontend.course.filters.hours')</label>
                                </div>
                                <div class="form-check pl-0 mb-3">
                                    <input type="radio" class="form-check-input" data-value="7-16" id="7-16"
                                           name="duration">
                                    <label class="form-check-label small font-weight-bold" for="7-16">7-16
                                         @lang('labels.frontend.course.filters.hours')</label>
                                </div>
                                <div class="form-check pl-0 mb-3">
                                    <input type="radio" class="form-check-input" data-value="20-26" id="20-26"
                                           name="duration">
                                    <label class="form-check-label small font-weight-bold" for="20-26">20-26
                                         @lang('labels.frontend.course.filters.hours')</label>
                                </div>
                            </section>
                            <!-- Section: Price -->

                            <!-- Section: Price  -->
                            <section class="pb-0 p-3 border-bottom price-filter">
                                <h5 class="font-weight-bold mb-3">@lang('labels.frontend.course.filters.price') </h5>
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
                        <div class="form-group row filters-category">
                            <label class="col-sm-2 col-form-label col-form-label-sm " for="sort"><h3
                                        class="font-weight-bold text-dark">@lang('labels.frontend.search_result.sort_by')</h3></label>
                            <div class="col">
                                <select id="sortFilter" class="form-control">
                                    <option selected value="All">All</option>
                                    <option value="popular">@lang('labels.frontend.search_result.popular')</option>
                                    <option value="trending">@lang('labels.frontend.search_result.trending')</option>
                                    <option value="featured">@lang('labels.frontend.search_result.featured')</option>
                                </select>
                            </div>
                        </div>
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
            var rating = $('.rating-filter input:checked').data('value');
            var duration = $('.duration-filter input:checked').data('value');
            var maxPrice = $('.price-filter-input').val();
            var isFree = $('#isFree').prop('checked');
            var sortBy = $('#sortFilter').val();
            var category = '{{$category->id ?? null}}';
            $('.filters-section .btn-apply').on('click', function (e) {
                e.preventDefault();
                var rating = $('.rating-filter input:checked').data('value') ? $('.rating-filter input:checked').data('value') : '';
                var duration = $('.duration-filter input:checked').data('value');
                var maxPrice = $('.price-filter-input').val();
                var isFree = $('#isFree').prop('checked');
                var sortBy = $('#sortFilter').val();
                var category = '{{$category->id ?? null}}';
                $.ajax({
                    url: "{{route('courses.filterCategory')}}",
                    method: "GET",
                    data: {
                        'rating': rating,
                        'duration': duration,
                        'maxPrice': maxPrice,
                        'isFree': isFree,
                        'type': sortBy,
                        'category':category
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
                if (rating || duration || maxPrice !== '0' || isFree || sortBy) {
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
                    location.href = '{{route('courses.all')}}';
                }
            })

            @if(request('type') != "")
            $('#sortBy').find('option[value="' + "{{request('type')}}" + '"]').attr('selected', true);
            @endif

        });


    </script>
@endpush

