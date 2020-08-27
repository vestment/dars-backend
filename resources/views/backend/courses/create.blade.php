
@extends('backend.layouts.app')
@section('title', __('labels.backend.courses.title').' | '.app_name())
@push('after-styles')
    <style>
        .form-control-label {
            line-height: 35px;
        }
        .remove{
            float: right;
            color: red;
            font-size: 20px;
            cursor: pointer;
        }
        .error{
            color: red;
        }

    </style>

    <link rel="stylesheet" type="text/css"
          href="{{asset('plugins/jqueryui-datetimepicker/jquery.datetimepicker.css')}}">
@endpush
@section('content')

<div class="row">
  <div class="col-3">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
      <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Create Course</a>
    
    </div>
  </div>
  <div class="col-9">
    <div class="tab-content" id="v-pills-tabContent">
    <!-- create course -->
  <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
      
      {!! Form::open(['method' => 'POST', 'route' => ['admin.courses.store'], 'files' => true]) !!}

                    <div class="card">
                        <div class="card-header">
                            <h3 class="page-title float-left">@lang('labels.backend.courses.create')</h3>
                            <div class="float-right">
                                <a href="{{ route('admin.courses.index') }}"
                                class="btn btn-success">@lang('labels.backend.courses.view')</a>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (Auth::user()->isAdmin())
                                <div class="row">
                                    <div class="col-10 form-group">
                                        {!! Form::label('teachers',trans('labels.backend.courses.fields.teachers'), ['class' => 'control-label']) !!}
                                        {!! Form::select('teachers[]', $ar_full_name, old('teachers'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => true]) !!}

                                    </div>
                                    <div class="col-2 d-flex form-group flex-column">
                                        OR <a target="_blank" class="btn btn-primary mt-auto"
                                            href="{{route('admin.teachers.create')}}">{{trans('labels.backend.courses.add_teachers')}}</a>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-10 form-group">
                                    {!! Form::label('category_id',trans('labels.backend.courses.fields.category'), ['class' => 'control-label']) !!}
                                    {!! Form::select('category_id', $categ_name, old('category_id'), ['class' => 'form-control select2 js-example-placeholder-single', 'multiple' => false, 'required' => true]) !!}


                                </div>
                                <div class="col-2 d-flex form-group flex-column">
                                    OR <a target="_blank" class="btn btn-primary mt-auto"
                                        href="{{route('admin.categories.index').'?create'}}">{{trans('labels.backend.courses.add_categories')}}</a>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6 col-lg-6 form-group">

                                    {!! Form::label('title', trans('labels.backend.courses.fields.title').' *', ['class' => 'control-label']) !!}
                                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.title')]) !!}
                                    </div>

                                    <div class="col-6 col-lg-6 form-group">
                                        {!! Form::label('title', trans('labels.backend.courses.fields.title_ar').' *', ['class' => 'control-label']) !!}

                                        {!! Form::text('title_ar', old('title_ar'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.title_ar')]) !!}
                                    </div>
                                    </div>
                                    <div class="row">


                            </div>
                            <div class="row">

                                <div class="col-6 form-group">
                                    {!! Form::label('description',  trans('labels.backend.courses.fields.description'), ['class' => 'control-label']) !!}
                                    {!! Form::textarea('description', old('description'), ['class' => 'form-control ', 'placeholder' => trans('labels.backend.courses.fields.description')]) !!}
                                    </div>
                                    <div class="col-6 form-group"> 
                                    {!! Form::label('description',  trans('labels.backend.courses.fields.description_ar'), ['class' => 'control-label']) !!}

                                    {!! Form::textarea('description_ar', old('description_ar'), ['class' => 'form-control ', 'placeholder' => trans('labels.backend.courses.fields.description_ar')]) !!}
                                    </div>

                                
                            </div>
                            <div class="row">
                                <div class="col-12 col-lg-4 form-group">
                                    {!! Form::label('price',  trans('labels.backend.courses.fields.price').' (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                                    {!! Form::number('price', old('price'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price'),'step' => 'any', 'pattern' => "[0-9]"]) !!}
                                </div>
                                <div class="col-12 col-lg-4 form-group">
                                    {!! Form::label('course_image',  trans('labels.backend.courses.fields.course_image'), ['class' => 'control-label']) !!}
                                    {!! Form::file('course_image',  ['class' => 'form-control', 'accept' => 'image/jpeg,image/gif,image/png']) !!}
                                    {!! Form::hidden('course_image_max_size', 8) !!}
                                    {!! Form::hidden('course_image_max_width', 4000) !!}
                                    {!! Form::hidden('course_image_max_height', 4000) !!}

                                </div>
                                <div class="col-12 col-lg-4  form-group">
                                    {!! Form::label('start_date', trans('labels.backend.courses.fields.start_date').' (yyyy-mm-dd)', ['class' => 'control-label']) !!}
                                    {!! Form::text('start_date', old('start_date'), ['class' => 'form-control date','pattern' => '(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))', 'placeholder' => trans('labels.backend.courses.fields.start_date').' (Ex . 2019-01-01)', 'autocomplete' => 'off']) !!}

                                </div>
                            </div>


                            <div class="row">
                                <div class="col-12 form-group">
                                    <div class="checkbox d-inline mr-3">
                                        {!! Form::hidden('published', 0) !!}
                                        {!! Form::checkbox('published', 1, false, []) !!}
                                        {!! Form::label('published',  trans('labels.backend.courses.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                    </div>

                                    @if (Auth::user()->isAdmin())


                                    <div class="checkbox d-inline mr-3">
                                        {!! Form::hidden('featured', 0) !!}
                                        {!! Form::checkbox('featured', 1, false, []) !!}
                                        {!! Form::label('featured',  trans('labels.backend.courses.fields.featured'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                    </div>

                                    <div class="checkbox d-inline mr-3">
                                        {!! Form::hidden('trending', 0) !!}
                                        {!! Form::checkbox('trending', 1, false, []) !!}
                                        {!! Form::label('trending',  trans('labels.backend.courses.fields.trending'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                    </div>

                                    <div class="checkbox d-inline mr-3">
                                        {!! Form::hidden('popular', 0) !!}
                                        {!! Form::checkbox('popular', 1, false, []) !!}
                                        {!! Form::label('popular',  trans('labels.backend.courses.fields.popular'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                    </div>

                                    @endif

                                    <div class="checkbox d-inline mr-3">
                                        {!! Form::hidden('free', 0) !!}
                                        {!! Form::checkbox('free', 1, false, []) !!}
                                        {!! Form::label('free',  trans('labels.backend.courses.fields.free'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                    </div>
                                    <div class="checkbox d-inline mr-3">
                                            {!! Form::hidden('offline', 0) !!}
                                            {!! Form::checkbox('offline', 1 , false, ['id'=>'offline']) !!}
                                            {!! Form::label('offline',  trans('labels.backend.courses.fields.offline_courses'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                    </div>

                                </div>

                            </div>
                            <div class="academy d-none">
                                <div class="row ">
                                    <div class="col-6 form-group">
                                        {!! Form::label('teachers',trans('labels.backend.teachers.fields.academy'), ['class' => 'control-label']) !!}
                                        {!! Form::select('academy_id', $academies, old('academy_id'), ['class' => 'form-control d-none select2 js-example-placeholder-multiple', 'id'=>'selected-academy','multiple' => false]) !!}
                                    </div>
                                    <div class="col-6 form-group">
                                            {!! Form::label('seats',  trans('labels.backend.teachers.fields.seats'), ['class' => 'control-label']) !!}
                                            {!! Form::text('seats', old('seats'), ['class' => 'form-control', 'placeholder' =>  trans('labels.backend.courses.seats_placeholder')]) !!}
                                    </div>
                                    <div class="row form-group">
                                          
                                            <div class="col-12">
                                                {{ html()->label(__('labels.backend.teachers.fields.Booking_Date&Time'))->class(' form-control-label')->for('buttons') }}
                                                <button type="button" id="add-button" class="btn  btn-primary">{{__('labels.backend.hero_slider.fields.buttons.add')}}</button>
                                            </div>
                                            <div class="col-12 col-md-10 ml-auto button-container mt-2">
                            
                                            </div>
                            
                                        </div>

                                
                                </div>
                            </div>
                                <div class="row">
                                    <div class="col-md-12 form-group">
                                        {!! Form::label('add_video', trans('labels.backend.lessons.fields.add_video'), ['class' => 'control-label']) !!}

                                        {!! Form::select('media_type', ['youtube' => 'Youtube','vimeo' => 'Vimeo','upload' => 'Upload','embed' => 'Embed'],null,['class' => 'form-control', 'placeholder' => 'Select One','id'=>'media_type' ]) !!}

                                        {!! Form::text('video', old('video'), ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video'  ]) !!}

                                        {!! Form::select('video_file', $videos, old('video_file'), ['class' => 'form-control mt-3 d-none ','id'=>'video_file']) !!}

                                        @lang('labels.backend.lessons.video_guide')

                            
                                <div class="col-12 col-lg-6 form-group d-none" id="duration">
                                    {!! Form::label('duration',  trans('labels.backend.courses.duration'), ['class' => 'control-label']) !!}
                                    {!! Form::text('duration', old('duration'), ['class' => 'form-control ', 'placeholder' =>  trans('labels.backend.courses.video_format')]) !!}

                                </div>
                            <div class="row">
                                    <div class="col-6 form-group">
                                        {!! Form::label('meta_title',trans('labels.backend.courses.fields.meta_title'), ['class' => 'control-label']) !!}
                                        {!! Form::text('meta_title', old('meta_title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_title')]) !!}
                                    
                                    </div>
                                    <div class="col-6 form-group">

                                    {!! Form::label('meta_title',trans('labels.backend.courses.fields.meta_title_ar'), ['class' => 'control-label']) !!}

                                    {!! Form::text('meta_title_ar', old('meta_title_ar'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_title_ar')]) !!}
                                    
                                    </div>
                            </div>
                            <div class="row">

                                <div class="col-6 form-group">
                                    {!! Form::label('meta_description',trans('labels.backend.courses.fields.meta_description'), ['class' => 'control-label']) !!}
                                
                                    {!! Form::textarea('meta_description', old('meta_description'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_description')]) !!}
                                    </div>
                                    <div class="col-6 form-group">
                                    {!! Form::label('meta_description_ar',trans('labels.backend.courses.fields.meta_description_ar'), ['class' => 'control-label']) !!}

                                    {!! Form::textarea('meta_description_ar', old('meta_description_ar'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_description_ar')]) !!}
                                
                                </div>
                                </div>
                                <div class="row">
                                <div class="col-6 form-group">
                                    {!! Form::label('meta_keywords',trans('labels.backend.courses.fields.meta_keywords'), ['class' => 'control-label']) !!}
                                    
                                    {!! Form::textarea('meta_keywords', old('meta_keywords'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_keywords')]) !!}
                                    
                                    </div>
                                    <div class="col-6 form-group">
                                    {!! Form::label('meta_keywords',trans('labels.backend.courses.fields.meta_keywords_ar'), ['class' => 'control-label']) !!}
                                    
                                    {!! Form::textarea('meta_keywords_ar', old('meta_keywords_ar'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_keywords_ar')]) !!}
                                </div>
                                </div>




                            @if (Auth::user()->isAdmin())
                                <div class="row">
                                    <div class="col-10 form-group">
                                        {!! Form::label('optional_courses',trans('labels.backend.courses.fields.optional_courses'), ['class' => 'control-label']) !!}
                                        {!! Form::select('opt_courses[]', $courses, old('optional_courses'), ['class' => 'form-control opt_courses select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => false]) !!}
                                    </div>
                                </div>
                            @endif



                            @if (Auth::user()->isAdmin())
                                <div class="row">
                                    <div class="col-10 form-group">
                                        {!! Form::label('mandatory_courses',trans('labels.backend.courses.fields.mandatory_courses'), ['class' => 'control-label']) !!}
                                        {!! Form::select('mand_courses[]', $courses, old('mandatory_courses'), ['class' => 'form-control mand_courses select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => false]) !!}
                                    </div>    
                                </div>
                            @endif


                                <div class="row">
                                    <div class="col-10 form-group">
                                        {!! Form::label('learned',trans('labels.backend.courses.fields.learned'), ['class' => 'control-label']) !!}
                                        {!! Form::select('learned[]',$learned, old('learned'), ['class' => 'form-control learned_courses select2 js-input-tag', 'multiple' => 'multiple', 'required' => false]) !!}
                                    </div>    
                                </div>

                        

                            <div class="row">
                                <div class="col-12  text-center form-group">

                                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn btn-lg btn-danger']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

      
      
  </div>
      <!-- end course -->
    
    </div>
  </div>

@php
 
$a = 0; 

@endphp
@stop

@push('after-scripts')
    <script>
      
        $(document).ready(function () { 
            $('#offline').on('change', function () {
            if ($('#offline').prop('checked')) {
                $('.academy').removeClass('d-none');
                $('#selected-academy').next('span').show();
            } else {
                $('.academy').addClass('d-none');
                $('#selected-academy').next('span').hide();
            }
            });
            $('#start_date').datepicker({
                autoclose: true,
                dateFormat: "{{ config('app.date_format_js') }}"
            });
    
            $('#datetimepicker1').datepicker({
                autoclose: true,
                multidate: 5,
    closeOnDateSelect: true,
                dateFormat: "{{ config('app.date_format_js') }}"
            });
            $(".js-example-placeholder-single").select2({
                placeholder: "{{trans('labels.backend.courses.select_category')}}",
            });

            $(".js-example-placeholder-multiple").select2({
                placeholder: "{{trans('labels.backend.courses.select_teachers')}}",
            });
            $(".js-input-tag").select2({
                tags: true
            })
        });
        var uploadField = $('input[type="file"]');

        $(document).on('change', 'input[type="file"]', function () {
            var $this = $(this);
            $(this.files).each(function (key, value) {
                console.log(value.size);
            })
        })


        $(document).on('change', '#media_type', function () {
            if ($(this).val()) {
                if ($(this).val() != 'upload') {
                    $('#video').removeClass('d-none').attr('required', true)
                    $('#video_file').addClass('d-none').attr('required', false)
                    $('#duration').removeClass('d-none').attr('required', true)
                } else if ($(this).val() == 'upload') {
                    $('#video').addClass('d-none').attr('required', false)
                    $('#video_file').removeClass('d-none').attr('required', true)
                    $('#duration').addClass('d-none').attr('required', false)

                }
            } else {
                $('#video_file').addClass('d-none').attr('required', false)
                $('#video').addClass('d-none').attr('required', false)
            }
        })

        $(document).ready(function() {
var max_fields_limit = 8; //set limit for maximum input fields
var x = 1; //initialize counter for text box
$('.add_more_button').click(function(e){ //click event on add more fields button having class add_more_button
e.preventDefault();
if(x < max_fields_limit){ //check conditions
x++; //counter increment
$('.input_fields_container_part').append('<div><input type="text" name="knowledge"/><a href="#" class="remove_field" style="margin-left:10px;">Remove</a></div>'); //add input field
}
}); 
$('.input_fields_container_part').on("click",".remove_field", function(e){ //user click on remove text links
e.preventDefault(); $(this).parent('div').remove(); x--;
})
});


$(document).on('click','#add-button',function (e) {
                e.preventDefault()
               
                
                    var name = 'Booking Date&Time';
                    var html = "<div class='button-wrapper'> <h6 class='mt-3'> " + " <span class='remove'><i class='fa fa-window-close'></i></span></h6>" +
                    "<div class='row'>" +
                        "<div class='col-lg-6'>" +
                         "<label for='start_dat' class='control-label'>Start Date (yyyy-mm-dd)</label>"+
                        "<input class='form-control date-input dat' pattern='(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))' placeholder='Start Date (Ex . 2019-01-01)' autocomplete='off' name='start_dat' type='text'>" +
                    
                        
                        "</div>"+
                        "<div class='col-6'>" +
                            "<button type='button' onclick=\"addInputTime(this)\" class='add-but btn-block btn  btn-primary'>{{__('labels.backend.hero_slider.fields.buttons.add')}}</button>" +
                        "</div>" +
                        "</div>"+
                        "</div>";

                    $('.button-container').append(html);
                
                
                $('.date-input').datepicker({
                autoclose: true,
                dateFormat: "{{ config('app.date_format_js') }}"
            
            });
            });

            $(document).on('click','.remove',function () {
                if(confirm('Are you sure want to remove button?')){
                    $(this).parents('.button-wrapper').remove();
                    $('#buttons').val($('.button-wrapper').length)
                }
             });



             function addInputTime (elemt) {
                console.log($(elemt).parents().find('.button-wrapper'))
                    var name = 'Booking Date&Time';
                    var html = "<span class='remove'><i class='fa fa-window-close'></i></span>" +
                    "<div class='row mt-3'>" +
                        "<div class='col-lg-6'>" +
                        "<input class='form-control dat' pattern='([01]?[0-9]|2[0-3]):[0-5][0-9]' placeholder='Start Date (Ex . 2019-01-01)' autocomplete='off' name='start_dat' type='time'>" +
                        "</div>"+
                        "</div>"+
                        "</div>";
                    // $(this).parent('.button-container')
                    $(elemt).parent().find('.button-wrapper').append(html);
               
                $('.date-input').datepicker({
                autoclose: true,
                dateFormat: "{{ config('app.date_format_js') }}"
            
            });

             }
              

            $(document).on('click','.remove',function () {
                if(confirm('Are you sure want to remove button?')){
                    $(this).parents('.button-wrapper').remove();
                    $('#buttons').val($('.button-wrapper').length)
                }
             });
    </script>

@endpush
