
@extends('backend.layouts.app')
@section('title', __('labels.backend.courses.title').' | '.app_name())

@section('content')

<div class="row">
  <div class="col-3">
    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
      <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Create Course</a>
      <a class="nav-link" id="v-pills-profile-tab" data-toggle="pill" href="#v-pills-profile" role="tab" aria-controls="v-pills-profile" aria-selected="false">Create Chapter</a>
      <a class="nav-link" id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab" aria-controls="v-pills-messages" aria-selected="false">Create Lesson</a>
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
                                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.title')]) !!}
                                    </div>

                                    <div class="col-6 col-lg-6 form-group">
                                        {!! Form::label('title', trans('labels.backend.courses.fields.title_ar').' *', ['class' => 'control-label']) !!}

                                        {!! Form::text('title_ar', old('title_ar'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.title_ar')]) !!}
                                    </div>
                                    </div>
                                    <div class="row">

                                <div class="col-12 col-lg-6 form-group">
                                    {!! Form::label('slug',  trans('labels.backend.courses.fields.slug'), ['class' => 'control-label']) !!}
                                    {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'placeholder' =>  trans('labels.backend.courses.slug_placeholder')]) !!}

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
                                <div class="col-12 col-lg-6 form-group d-none" id="duration">
                                    {!! Form::label('duration',  trans('labels.backend.courses.duration'), ['class' => 'control-label']) !!}
                                    {!! Form::text('duration', old('duration'), ['class' => 'form-control ', 'placeholder' =>  trans('labels.backend.courses.video_format')]) !!}

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
                                            {!! Form::checkbox('offline', 1, false, []) !!}
                                            {!! Form::label('offline',  trans('labels.backend.courses.fields.offline_courses'), ['class' => 'checkbox control-label font-weight-bold','id'=>'offline']) !!}
                                    </div>

                                </div>

                            </div>

                            <div class="row academy">
                                    <div class="col-10 form-group">
                                        {!! Form::label('teachers',trans('labels.backend.teachers.fields.academy'), ['class' => 'control-label']) !!}
                                        @if (auth()->user()->hasRole('academy'))
                                            {{ html()->text('academy_id')
                                            ->class('form-control')
                                            ->placeholder(auth()->user()->full_name)
                                            ->attributes(['maxlength'=> 191,'readonly'=>true])
                                            ->value(auth()->user()->full_name)}}
                                        @else
                                            {!! Form::select('academy_id', $academies, old('academy_id'), ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => false]) !!}
                                        @endif
                                    </div>
                                <!-- <div class="col-2 d-flex form-group flex-column">
                                    OR <a target="_blank" class="btn btn-primary mt-auto"
                                          href="{{route('admin.teachers.create')}}">{{trans('labels.backend.courses.add_teachers')}}</a>
                                </div> -->
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

      
      
      </div>
      <!-- end course -->
      <!-- create chapter -->
<div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">


{!! Form::open(['method' => 'POST', 'route' => ['admin.chapters.store'], 'files' => true,]) !!}
    {!! Form::hidden('model_id',0,['id'=>'chapter_id']) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.chapters.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.chapters.index') }}"
                   class="btn btn-success">@lang('labels.backend.chapters.view')</a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
               
                
            </div>
            <div class="row">
                
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title', trans('labels.backend.chapters.fields.title').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.chapters.fields.title'), 'required' => '']) !!}
                </div>
                <div class="col-12 col-lg-6 form-group">
                {!! Form::label('title_ar', trans('labels.backend.chapters.fields.title_ar').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title_ar', old('title_ar'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.chapters.fields.title_ar'), 'required' => '']) !!}
                </div>
            </div>

            <div class="row">
             
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('chapter_image', trans('labels.backend.chapters.fields.chapter_image').' '.trans('labels.backend.chapters.max_file_size'), ['class' => 'control-label']) !!}
                    {!! Form::file('chapter_image', ['class' => 'form-control' , 'accept' => 'image/jpeg,image/gif,image/png']) !!}
                   
                </div>
            </div>


           

            <div class="row">

                <div class="col-12 col-lg-3 form-group">
                    <div class="checkbox">
                        {!! Form::hidden('published', 0) !!}
                        {!! Form::checkbox('published', 1, false, []) !!}
                        {!! Form::label('published', trans('labels.backend.chapters.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div>
                </div>
                <div class="col-12  text-left form-group">
                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn  btn-danger']) !!}
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}





</div>
<!-- end chapter -->
<!-- create Lesson -->
<div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">
      {!! Form::open(['method' => 'POST', 'route' => ['admin.lessons.store'], 'files' => true,]) !!}
    {!! Form::hidden('model_id',0,['id'=>'lesson_id']) !!}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title float-left mb-0">@lang('labels.backend.lessons.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.lessons.index') }}"
                   class="btn btn-success">@lang('labels.backend.lessons.view')</a>
            </div>
        </div>

        <div class="card-body">
            <div class="row">
               
                </div>
                <div class="row">

                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title', trans('labels.backend.lessons.fields.title').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.lessons.fields.title'), 'required' => '']) !!}
                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('title_ar', trans('labels.backend.lessons.fields.title_ar').'*', ['class' => 'control-label']) !!}
                    {!! Form::text('title_ar', old('title_ar'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.lessons.fields.title_ar'), 'required' => '']) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('slug',trans('labels.backend.lessons.fields.slug'), ['class' => 'control-label']) !!}
                    {!! Form::text('slug', old('slug'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.lessons.slug_placeholder')]) !!}

                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('lesson_image', trans('labels.backend.lessons.fields.lesson_image').' '.trans('labels.backend.lessons.max_file_size'), ['class' => 'control-label']) !!}
                    {!! Form::file('lesson_image', ['class' => 'form-control' , 'accept' => 'image/jpeg,image/gif,image/png']) !!}
                    {!! Form::hidden('lesson_image_max_size', 8) !!}
                    {!! Form::hidden('lesson_image_max_width', 4000) !!}
                    {!! Form::hidden('lesson_image_max_height', 4000) !!}

                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('short_text', trans('labels.backend.lessons.fields.short_text'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('short_text', old('short_text'), ['class' => 'form-control ', 'placeholder' => trans('labels.backend.lessons.short_description_placeholder')]) !!}

                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('short-text-ar', trans('labels.backend.lessons.fields.short-text-ar'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('short-text-ar', old('short-text-ar'), ['class' => 'form-control ', 'placeholder' => trans('labels.backend.lessons.short-text-ar')]) !!}

                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('full_text', trans('labels.backend.lessons.fields.full_text'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('full_text', old('full_text'), ['class' => 'form-control editor', 'placeholder' => '','id' => 'editor']) !!}

                </div>
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('full_text_ar', trans('labels.backend.lessons.fields.full_text_ar'), ['class' => 'control-label']) !!}
                    {!! Form::textarea('full_text_ar', old('full_text_ar'), ['class' => 'form-control editor', 'placeholder' => '','id' => 'editor_ar']) !!}

                </div>
            </div>
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('downloadable_files', trans('labels.backend.lessons.fields.downloadable_files').' '.trans('labels.backend.lessons.max_file_size'), ['class' => 'control-label']) !!}
                    {!! Form::file('downloadable_files[]', [
                        'multiple',
                        'class' => 'form-control file-upload',
                        'id' => 'downloadable_files',
                        'accept' => "image/jpeg,image/gif,image/png,application/msword,audio/mpeg,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-powerpoint,application/pdf,video/mp4"
                        ]) !!}
                    <div class="photo-block">
                        <div class="files-list"></div>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('pdf_files', trans('labels.backend.lessons.fields.add_pdf'), ['class' => 'control-label']) !!}
                    {!! Form::file('add_pdf', [
                        'class' => 'form-control file-upload',
                         'id' => 'add_pdf',
                        'accept' => "application/pdf"

                        ]) !!}
                </div>
            </div>

            <div class="row">
                <div class="col-12 form-group">
                    {!! Form::label('audio_files', trans('labels.backend.lessons.fields.add_audio'), ['class' => 'control-label']) !!}
                    {!! Form::file('add_audio', [
                        'class' => 'form-control file-upload',
                         'id' => 'add_audio',
                        'accept' => "audio/mpeg3"

                        ]) !!}
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

                <div class="col-12 col-lg-3 form-group">
                    <div class="checkbox">
                        {!! Form::hidden('published', 0) !!}
                        {!! Form::checkbox('published', 1, false, []) !!}
                        {!! Form::label('published', trans('labels.backend.lessons.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                    </div>
                </div>
                <div class="col-12  text-left form-group">
                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn  btn-danger']) !!}
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}




</div>
<!-- end Lesson -->
    </div>
  </div>
</div>

@stop

@push('after-scripts')
    <script>
        @if(old('offline') && old('offline') == '0')
        $('.academy').hide();
        @elseif(old('offline')&& old('offline')  == '1')
        $('.academy').show();
        @endif
        $(document).on('change', '#offline', function () {
            if ($(this).val() === '0') {
                $('.academy').hide();
            } else {
                $('.academy').show();
            }
        });
    

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
                    $('#duration').removeClass('d-none').attr('required', true)
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
