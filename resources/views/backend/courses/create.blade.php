@extends('backend.layouts.app')
@section('title', __('labels.backend.courses.title').' | '.app_name())
@push('after-styles')
    <style>
        .form-control-label {
            line-height: 35px;
        }

        .remove {
            float: right;
            color: red;
            font-size: 20px;
            cursor: pointer;
        }

        .error {
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
                <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab"
                   aria-controls="v-pills-home" aria-selected="true">Create Course</a>

            </div>
        </div>
        <div class="col-9">
            <div class="tab-content" id="v-pills-tabContent">
                <!-- create course -->
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                     aria-labelledby="v-pills-home-tab">

                    {!! Form::open(['method' => 'POST', 'route' => ['admin.courses.store'], 'files' => true]) !!}
                    {!!  Form::hidden('offlineData', null, ['id'=>'offlineData']) !!}
                    {!!  Form::hidden('academy_id', null, ['id'=>'academy_id']) !!}
                    {!!  Form::hidden('offline_price', null, ['id'=>'offline_price']) !!}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="page-title float-left">@lang('labels.backend.courses.create')</h3>
                            <div class="float-right">
                                <a href="{{ route('admin.courses.index') }}"
                                   class="btn btn-success">@lang('labels.backend.courses.view')</a>
                            </div>
                        </div>

                        <div class="card-body">
                            @if (Auth::user()->isAdmin() || auth()->user()->hasRole('academy'))

                            <div class="row">
                                <div class="col-10 form-group ">
                                    {!! Form::label('type',trans('labels.backend.courses.fields.type'), ['class' => 'control-label']) !!}
                                    <select name="type"  class="form-control select2"  required = true >
                                    
                                    <option value="dars">Dars</option>
                                    <option value="dars_plus">Dars Plus</option>

                                    </select>

                                </div>
                               
                            </div>

                            <div class="row">
                                <div class="col-10 form-group">
                                    {!! Form::label('country_id',trans('labels.backend.courses.fields.country'), ['class' => 'control-label']) !!}
                                    
                                    {!! Form::select('country_id', $countriesToSelect, old('country_id'), ['class' => 'form-control select2 js-example-placeholder-single', 'id' => 'countryID','multiple' => false, 'required' => true]) !!}


                                </div>
                               
                            </div>

                           
                  
                            <div class="row">
                                <div class="col-10 form-group ">
                                    {!! Form::label('edusystems',trans('labels.backend.courses.fields.eduSys'), ['class' => 'control-label']) !!}
                                    <select name="edusystems[]"  class="form-control select2" id = 'C_ES' multiple = 'multiple' required = true >
                                    </select>

                                </div>
                               
                            </div>



                            <div class="row">
                                <div class="col-10 form-group ">
                                    {!! Form::label('eduStatge',trans('labels.backend.courses.fields.eduStatge'), ['class' => 'control-label']) !!}
                                    <select name="eduStatge[]"  class="form-control select2" id = 'C_Estages' multiple = 'multiple' required = true >
                                    </select>

                                </div>
                               
                            </div>
                            <div class="row">
                            <div class="col-10 form-group">
                                    {!! Form::label('year_id',trans('labels.backend.courses.fields.year'), ['class' => 'control-label']) !!}
                                    {!! Form::select('year_id', $yearsToSelect, old('year_id'), ['class' => 'form-control select2 js-example-placeholder-single', 'multiple' => false, 'required' => true]) !!}

                                    </div>
                                </div>
                         
                            <div class="row">
                                <div class="col-10 form-group ">
                                    {!! Form::label('semesters',trans('labels.backend.courses.fields.semester'), ['class' => 'control-label']) !!}
                                    <select name="semesters[]"  class="form-control select2" id = 'ES_Semesters' multiple = 'multiple' required = true >
                                    </select>

                                </div>
                               
                            </div>

                            
                              
                            @endif


        

     
                            

                            <div class="row">
                                <div class="col-10 form-group">
                                    {!! Form::label('category_id',trans('labels.backend.courses.fields.category'), ['class' => 'control-label']) !!}
                                    {!! Form::select('category_id', $categoriesToSelect, old('category_id'), ['class' => 'form-control select2 js-example-placeholder-single', 'multiple' => false, 'required' => true]) !!}


                                </div>
                                <div class="col-2 d-flex form-group flex-column">
                                    OR <a target="_blank" class="btn btn-primary mt-auto"
                                          href="{{route('admin.categories.index').'?create'}}">{{trans('labels.backend.courses.add_categories')}}</a>
                                </div>
                            </div>

                            <div class="row">
                                    <div class="col-10 form-group">
                                        {!! Form::label('teachers',trans('labels.backend.courses.fields.teachers'), ['class' => 'control-label']) !!}
                                        {!! Form::select('teachers[]', $teachersToSelect, old('teachers'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => true]) !!}

                                    </div>
                                    <div class="col-2 d-flex form-group flex-column">
                                        OR <a target="_blank" class="btn btn-primary mt-auto"
                                              href="{{route('admin.teachers.create')}}">{{trans('labels.backend.courses.add_teachers')}}</a>
                                    </div>
                                </div>

                            <div class="row">
                                <div class="col-6 col-lg-6 form-group">

                                    {!! Form::label('title', trans('labels.backend.courses.fields.title').' *', ['class' => 'control-label']) !!}
                                    {!! Form::text('title', old('title'), [ 'maxlength' => 35 , 'class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.title')]) !!}
                                </div>

                                <div class="col-6 col-lg-6 form-group">
                                    {!! Form::label('title', trans('labels.backend.courses.fields.title_ar').' *', ['class' => 'control-label']) !!}

                                    {!! Form::text('title_ar', old('title_ar'), ['maxlength' => 35 , 'class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.title_ar')]) !!}
                                </div>
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
                                   
                                 


                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-12 form-group">
                                    {!! Form::label('add_video', trans('labels.backend.lessons.fields.add_video'), ['class' => 'control-label']) !!}

                                    {!! Form::select('media_type', ['youtube' => 'Youtube','vimeo' => 'Vimeo','upload' => 'Upload','embed' => 'Embed'],null,['class' => 'form-control', 'placeholder' => 'Select One','id'=>'media_type' ]) !!}

                                    {!! Form::text('video', old('video'), ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video'  ]) !!}

                                    {!! Form::select('video_file', $videos, old('video_file'), ['class' => 'form-control mt-3 d-none ','id'=>'video_file']) !!}
                                    <div class="mt-2">
                                        @lang('labels.backend.lessons.video_guide')
                                    </div>

                                    <div class="col-12 col-lg-6 form-group d-none" id="duration">
                                        {!! Form::label('duration',  trans('labels.backend.courses.duration'), ['class' => 'control-label']) !!}
                                        {!! Form::text('duration', old('duration'), ['class' => 'form-control ', 'placeholder' =>  trans('labels.backend.courses.video_format')]) !!}

                                    </div>
                                    <div class="not-necessary-section d-none">
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
                                    </div>

                                        <div class="row">
                                            <div class="col-10 form-group">
                                                {!! Form::label('optional_courses',trans('labels.backend.courses.fields.optional_courses'), ['class' => 'control-label']) !!}
                                                {!! Form::select('opt_courses[]', $courses, old('optional_courses'), ['class' => 'form-control opt_courses select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => false]) !!}
                                            </div>
                                        </div>
                                   
                                        <div class="row">
                                            <div class="col-10 form-group">
                                                {!! Form::label('mandatory_courses',trans('labels.backend.courses.fields.mandatory_courses'), ['class' => 'control-label']) !!}
                                                {!! Form::select('mand_courses[]', $courses, old('mandatory_courses'), ['class' => 'form-control mand_courses select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => false]) !!}
                                            </div>
                                        </div>


                                    <div class="row">
                                        <div class="col-10 form-group">
                                            {!! Form::label('learned',trans('labels.backend.courses.fields.learned'), ['class' => 'control-label']) !!}
                                            {!! Form::select('learned[]',$learned, old('learned'), ['class' => 'form-control learned_courses select2 js-input-tag', 'multiple' => 'multiple', 'required' => false]) !!}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-10 form-group">
                                            {!! Form::label('learned',trans('labels.backend.courses.fields.learned_ar'), ['class' => 'control-label']) !!}
                                            {!! Form::select('learned_ar[]',$learned_ar, old('learned_ar'), ['class' => 'form-control learned_courses select2 js-input-tag', 'multiple' => 'multiple', 'required' => false]) !!}
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
                <div class="modal fade" id="offlineDataModal" tabindex="-1" aria-labelledby="offlineDataModal"
                     aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="offlineDataModal">
                                    Add Booking times</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="academy d-none">
                                    <div class="row ">
                                        <div class="col-12 form-group">
                                            {!! Form::label('teachers',trans('labels.backend.teachers.fields.academy'), ['class' => 'control-label']) !!}
                                            {!! Form::select('academies', $academies, old('academies'), ['class' => 'form-control d-none select2', 'id'=>'selected-academy','multiple' => false]) !!}
                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-12 form-group">
                                            {!! Form::label('teachers',trans('labels.backend.courses.fields.offline_price'), ['class' => 'control-label']) !!}
                                            {!! Form::input('number','offline-price',old('offline_price'), ['class' => 'form-control', 'id'=>'offline-price']) !!}
                                        </div>
                                    </div>
                                    <div class="row ">

                                        <div class="col-12 form-group">
                                            {{ html()->label()->class(' form-control-label')->for('buttons') }}
                                            <button type="button" id="add-button"
                                                    class="btn  btn-primary">{{__('labels.backend.hero_slider.fields.buttons.add').' '.__('labels.backend.teachers.fields.Booking_Date&Time')}}</button>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-12 col-md-12 form-group button-container mt-2">

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type='button' onclick="saveOfflineData(this)"
                                        class='add-but btn-block btn  btn-primary'>Save
                                </button>
                            </div>

                        </div>
                    </div>
                </div>

                @stop
                @push('after-scripts')
                    <script>


// function getedusys(country_id) {
//             console.log(country_id)
//     $.ajax({
//                 type: "get",
//                 url: '{{route('admin.country.eduSys')}}',
//                 data: {
//                     _token: '{{ csrf_token() }}',
//                     id:country_id
                    
//                 },
//                 success: function (resp) {
                  
//                     console.log(resp)
//                 }
//             })
//         }

        $(function(){
   $('#countryID').change(function(e) {
    var country =  $(this).val();
    $.ajax({
                type: "get",
                url: '{{route('admin.country.eduSys')}}',
                data: {
                    _token: '{{ csrf_token() }}',
                    id:country
                    
                },
                success: function (resp) {
                    $('#C_ES').empty();
                 for( var i=0; i < resp.length; i ++){
                    var option = '<option  value="'+resp[i].id+'">'+resp[i].en_name+'</option>';
                    $('#C_ES').append(option);
                 }
                 
                }
            })
    

 
   });
});



$(function(){
   $('#C_ES').change(function(e) {
    var Country_ESID =  $(this).val();

    $.ajax({
                type: "get",
                url: '{{route('admin.eduSys.eduStatges')}}',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids:Country_ESID
                    
                },
                success: function (resp) {
                    $('#C_Estages').empty();
                 for( var i=0; i < resp.length; i ++){
                    var option = '<option  value="'+resp[i].id+'">'+resp[i].en_name+'</option>';
                    $('#C_Estages').append(option);
                 }
                 
                }
            })
    
   
});
});



$(function(){
   $('#C_Estages').change(function(e) {
    var EStatgeIDs =  $(this).val();

    $.ajax({
                type: "get",
                url: '{{route('admin.eduStatges.semesters')}}',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids:EStatgeIDs
                    
                },
                success: function (resp) {
                    $('#ES_Semesters').empty();
                 for( var i=0; i < resp.length; i ++){
                    var option = '<option  value="'+resp[i].id+'">'+resp[i].en_name+'</option>';
                    $('#ES_Semesters').append(option);
                 }
                 
                }
            })
    
   
});
});

                        $(document).ready(function () {

                            var country =   $('#countryID').val();
    $.ajax({
                type: "get",
                url: '{{route('admin.country.eduSys')}}',
                data: {
                    _token: '{{ csrf_token() }}',
                    id:country
                    
                },
                success: function (resp) {
                    $('#C_ES').empty();
                 for( var i=0; i < resp.length; i ++){
                    var option = '<option  value="'+resp[i].id+'">'+resp[i].en_name+'</option>';
                    $('#C_ES').append(option);
                 }
                 
                }
            })

                          
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
                            $(".opt_courses").select2({
                                placeholder: "{{trans('labels.backend.courses.select_opt_courses')}}",
                            });
                            $(".mand_courses").select2({
                                placeholder: "{{trans('labels.backend.courses.select_mand_courses')}}",
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

                        $(document).ready(function () {
                            var max_fields_limit = 8; //set limit for maximum input fields
                            var x = 1; //initialize counter for text box
                            $('.add_more_button').click(function (e) { //click event on add more fields button having class add_more_button
                                e.preventDefault();
                                if (x < max_fields_limit) { //check conditions
                                    x++; //counter increment
                                    $('.input_fields_container_part').append('<div><input type="text" name="knowledge"/><a href="#" class="remove_field" style="margin-left:10px;">Remove</a></div>'); //add input field
                                }
                            });
                            $('.input_fields_container_part').on("click", ".remove_field", function (e) { //user click on remove text links
                                e.preventDefault();
                                $(this).parent('div').remove();
                                x--;
                            })
                        });
                        $('.date-input').datepicker({
                            autoclose: true,
                            dateFormat: "{{ config('app.date_format_js') }}"

                        });
                        $(document).on('click', '#add-button', function (e) {
                            e.preventDefault()
                            var name = 'Booking Date&Time';
                            var html = "<div class='button-wrapper'> <h6 class='mt-3'> " + " <span class='remove'><i class='fa fa-window-close'></i></span></h6>" +
                                "<div class='row'>" +
                                "<div class='col-lg-10'>" +
                                "<label for='start_dat' class='control-label'>Start Date (yyyy-mm-dd)</label>" +
                                "<input class='form-control date-input dat' pattern='(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))' placeholder='Start Date (Ex . 2019-01-01)' autocomplete='off' type='text'>" +


                                "</div>" +
                                "<div class='col-2 mt-4'>" +
                                "<button type='button' onclick=\"addInputTime(this)\" class='add-but btn-block btn  btn-primary'>{{__('labels.backend.hero_slider.fields.buttons.add')}}</button>" +
                                "</div>" +
                                "</div><div class='timepicker'></div>" +
                                "</div>";

                            $('.button-container').append(html);


                        });

                        function addInputTime(elemt) {
                            var name = 'Booking Date&Time';
                            var html = "<span class='remove'><i class='fa fa-window-close'></i></span>" +
                                "<div class='row mt-3'>" +
                                "<div class='col-lg-12'>" +
                                "<div class='row'>" +
                                "<div class='col-lg-6'>" +
                                "<input class='form-control time-input dat' pattern='([01]?[0-9]|2[0-3]):[0-5][0-9]' placeholder='Start Date (Ex . 2019-01-01)' autocomplete='off' type='time'>" +
                                "</div>" +
                                "<div class='col-lg-6'>" +
                                "<input class='form-control seats-input' placeholder='seats' autocomplete='off' name='seats' type='number'>" +

                                "</div>" +

                                "</div>" +
                                "</div>" +
                                "</div>" +
                                "</div>";
                            // $(this).parent('.button-container')
                            $(elemt).parent().parent().next('.timepicker').append(html);

                            $('.date-input').datepicker({
                                autoclose: true,
                                dateFormat: "{{ config('app.date_format_js') }}"

                            });
                        }

                        $(document).on('click', '.remove', function () {
                            if (confirm('Are you sure want to remove button?')) {
                                $(this).parents('.button-wrapper').remove();
                                $('#buttons').val($('.button-wrapper').length)
                            }
                        });

                        function saveOfflineData(element) {
                            var arrObj = [];
                            var button_wrapper = $(element).parent().parent().find('.button-wrapper');
                            button_wrapper.each(function (key, value) {

                                var date_input = $(value).find('.date-input').val();
                                var time_input = $(value).find('.time-input');
                                var seats = $(value).find('.seats-input');

                                var obj = {
                                    'date': date_input
                                };
                                time_input.each(function (key, value) {
                                    obj['time-' + key] = $(value).val();
                                    obj['seats-' + key] = $(seats[key]).val();
                                })
                                arrObj.push(obj);
                            })

                            $('#offlineData').val(JSON.stringify(arrObj))
                            $('#academy_id').val($('#selected-academy').val());
                            $('#offline_price').val($('#offline-price').val());
                            $('#offlineDataModal').modal('hide');
                        }

                        function toggleOfflineMode() {
                            if ($('#offline').prop('checked')) {
                                $('#offlineDataModal').modal();
                                $("#selected-academy").select2({
                                    placeholder: "{{trans('labels.backend.courses.select_academies')}}",
                                });
                                $('.academy').removeClass('d-none');
                                $('#selected-academy').next('span').show();
                                $('#selected-academy').next('span').show();
                            } else {
                                $('.academy').addClass('d-none');
                                $('#selected-academy').next('span').hide();
                                $('.academy input').each(function (key, value) {
                                    $(value).val('');
                                });
                                $('.academy select').each(function (key, value) {
                                    $(value).val('');
                                    if ($(value).select2()) {
                                        $(value).select2('destroy');
                                    }
                                })
                            }
                        }

                        toggleOfflineMode();
                    </script>

    @endpush
