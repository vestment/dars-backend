@extends('backend.layouts.app')
@section('title', __('labels.backend.courses.title').' | '.app_name())

@section('content')

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
                        @if(app()->getLocale() == 'en')
                        {!! Form::select('teachers[]', $teachers, old('teachers'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => true]) !!}
                        @endif
                        @if(app()->getLocale() == 'ar')
                        {!! Form::select('ar_full_name[]', $ar_full_name, old('ar_full_name'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => true]) !!}
                        @endif
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
                    @if(app()->getLocale() == 'en')
                    {!! Form::select('category_id', $categ_name, old('category_id'), ['class' => 'form-control select2 js-example-placeholder-single', 'multiple' => false, 'required' => true]) !!}
                    @endif
                    @if(app()->getLocale() == 'ar')
                    {!! Form::select('category_id', $categ_name, old('category_id'), ['class' => 'form-control select2 js-example-placeholder-single', 'multiple' => false, 'required' => true]) !!}
                    @endif

                </div>
                <div class="col-2 d-flex form-group flex-column">
                    OR <a target="_blank" class="btn btn-primary mt-auto"
                          href="{{route('admin.categories.index').'?create'}}">{{trans('labels.backend.courses.add_categories')}}</a>
                </div>
            </div>

            <div class="row">
                <div class="col-6 col-lg-6 form-group">

                    {!! Form::label('title', trans('labels.backend.courses.fields.title').' *', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.title'), 'required' => false]) !!}
                    </div>

                    <div class="col-6 col-lg-6 form-group">
                        {!! Form::label('title', trans('labels.backend.courses.fields.title_ar').' *', ['class' => 'control-label']) !!}

                        {!! Form::text('title_ar', old('title_ar'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.title_ar'), 'required' => false]) !!}
                    </div>
                    </div>
                    <div class="row">

                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('slug',  trans('labels.backend.courses.fields.slug'), ['class' => 'control-label']) !!}
                    {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'placeholder' =>  trans('labels.backend.courses.slug_placeholder')]) !!}

                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('course_hours',  trans('labels.backend.courses.course_hours'), ['class' => 'control-label']) !!}
                    {!! Form::text('course_hours', old('course_hours'), ['class' => 'form-control', 'placeholder' =>  trans('labels.backend.courses.course_hours')]) !!}

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
                    <div class="col-md-12 form-group">
                        {!! Form::label('add_video', trans('labels.backend.lessons.fields.add_video'), ['class' => 'control-label']) !!}

                        {!! Form::select('media_type', ['youtube' => 'Youtube','vimeo' => 'Vimeo','upload' => 'Upload','embed' => 'Embed'],null,['class' => 'form-control', 'placeholder' => 'Select One','id'=>'media_type' ]) !!}

                        {!! Form::text('video', old('video'), ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video'  ]) !!}


                        {!! Form::file('video_file', ['class' => 'form-control mt-3 d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>'video_file'  ]) !!}

                        @lang('labels.backend.lessons.video_guide')

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


                </div>

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

<!--- Input type tags for what you will learn same as teachers and optional courses -->
{{--                @if (Auth::user()->isAdmin())--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-10 form-group">--}}
{{--                            {!! Form::label('learn',trans('labels.backend.courses.fields.learn'), ['class' => 'control-label']) !!}--}}
{{--                            {!! Form::select('learn[]', $courses, old('learn'), ['class' => 'form-control select2 js-example-tags', 'multiple' => 'multiple', 'required' => false]) !!}--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                @endif--}}



            @if (Auth::user()->isAdmin())
                <div class="row">
                    <div class="col-10 form-group">
                        {!! Form::label('optional_courses',trans('labels.backend.courses.fields.optional_courses'), ['class' => 'control-label']) !!}
                        {!! Form::select('opt_courses[]', $courses, old('optional_courses'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => false]) !!}
                    </div>
                </div>
            @endif



            @if (Auth::user()->isAdmin())
                <div class="row">
                    <div class="col-10 form-group">
                        {!! Form::label('mandatory_courses',trans('labels.backend.courses.fields.mandatory_courses'), ['class' => 'control-label']) !!}
                        {!! Form::select('mand_courses[]', $courses, old('mandatory_courses'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => false]) !!}
                    </div>    
                </div>
            @endif


                <div class="row">
                    <div class="col-10 form-group">
                        {!! Form::label('learned',trans('labels.backend.courses.fields.learned'), ['class' => 'control-label']) !!}
                        {!! Form::select('learned[]',$learned, old('learned'), ['class' => 'form-control select2 js-input-tag', 'multiple' => 'multiple', 'required' => false]) !!}
                    </div>    
                </div>

        

            <div class="row">
                <div class="col-12  text-center form-group">

                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn btn-lg btn-danger']) !!}
                </div>
            </div>
        </div>
    </div>
   
@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {
            $('#start_date').datepicker({
                autoclose: true,
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
                if (value.size > 5000000) {
                    alert('"' + value.name + '"' + 'exceeds limit of maximum file upload size')
                    $this.val("");
                }
            })
        })


        $(document).on('change', '#media_type', function () {
            if ($(this).val()) {
                if ($(this).val() != 'upload') {
                    $('#video').removeClass('d-none').attr('required', true)
                    $('#video_file').addClass('d-none').attr('required', false)
                } else if ($(this).val() == 'upload') {
                    $('#video').addClass('d-none').attr('required', false)
                    $('#video_file').removeClass('d-none').attr('required', true)
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

    </script>

@endpush
