@extends('backend.layouts.app')
@section('title', __('labels.backend.courses.title').' | '.app_name())
<link rel="stylesheet" type="text/css" href="{{asset('plugins/amigo-sorter/css/theme-default.css')}}">
<link href="{{asset('vendor/harimayco-menu/style.css')}}" rel="stylesheet">

<style>
    ul.sorter > span {
        display: inline-block;
        width: 100%;
        height: 100%;
        background: #f5f5f5;
        color: #333333;
        border: 1px solid #cccccc;
        border-radius: 6px;
        padding: 0px;
    }

    ul.sorter li > span .title {
        padding-left: 15px;
    }

    ul.sorter li > span .btn {
        width: 20%;
    }

    .margin_left {
        margin-left: 50px !important
    }

    ul.sorter li {
        height: 25%;
    }

    .form-control-label {
        line-height: 35px;
    }

    .remove {
        float: right;
        color: red;
        font-size: 20px;
        cursor: pointer;
    }

    .removeTime {
        float: right;
        color: red;
        font-size: 20px;
        cursor: pointer;
    }

    .error {
        color: red;
    }


</style>
@section('content')

    <div class="row">
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab"
                   aria-controls="v-pills-home" aria-selected="true">Edit Course</a>
                <a class="nav-link " id="v-pills-messages-tab" data-toggle="pill" href="#v-pills-messages" role="tab"
                   aria-controls="v-pills-messages" aria-selected="true">All Chapters</a>

                <button type="button" class="btn btn-primary mt-4" data-toggle="modal" data-target="#exampleModal">
                    Create Chapter
                </button>


            </div>
        </div>


        <div class="col-9">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel"
                     aria-labelledby="v-pills-home-tab">

                    {!! Form::model($course, ['method' => 'PUT', 'route' => ['admin.courses.update', $course->id], 'files' => true,]) !!}
                    {!!  Form::hidden('offlineData', null, ['id'=>'offlineData']) !!}
                    {!!  Form::hidden('academy_id', null, ['id'=>'academy_id']) !!}
                    {!!  Form::hidden('offline_price', null, ['id'=>'offline_price']) !!}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="page-title float-left mb-0">@lang('labels.backend.courses.edit')</h3>
                            <div class="float-right">
                                <a href="{{ route('admin.courses.index') }}"
                                   class="btn btn-success">@lang('labels.backend.courses.view')</a>
                            </div>
                        </div>

                        <div class="card-body">

                            @if (Auth::user()->isAdmin() || auth()->user()->hasRole('academy'))
                                <div class="row">

                                    <div class="col-10 form-group">
                                        {!! Form::label('teachers',trans('labels.backend.courses.fields.teachers'), ['class' => 'control-label']) !!}
                                        {!! Form::select('teachers[]', $teachersToSelect, old('teachers') ? old('teachers') : $course->teachers->pluck('id')->toArray(), ['class' => 'form-control select2', 'multiple' => 'multiple','required' => true]) !!}
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
                                    {!! Form::select('category_id', $categoriesToSelect, old('category_id'), ['class' => 'form-control select2 js-example-placeholder-single', 'multiple' => false, 'required' => true]) !!}
                                </div>
                                <div class="col-2 d-flex form-group flex-column">
                                    OR <a target="_blank" class="btn btn-primary mt-auto"
                                          href="{{route('admin.categories.index').'?create'}}">{{trans('labels.backend.courses.add_categories')}}</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6 col-lg-6 form-group">

                                    {!! Form::label('title', trans('labels.backend.courses.fields.title').' *', ['class' => 'control-label']) !!}
                                    {!! Form::text('title', old('title'), ['maxlength' => 35 ,'class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.title')]) !!}
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
                                    {!! Form::label('price', trans('labels.backend.courses.fields.price').' (in '.$appCurrency["symbol"].')', ['class' => 'control-label']) !!}
                                    {!! Form::number('price', old('price'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.price') ,'step' => 'any', 'pattern' => "[0-9]"]) !!}
                                </div>
                                <div class="col-12 col-lg-4 form-group">

                                    {!! Form::label('course_image', trans('labels.backend.courses.fields.course_image'), ['class' => 'control-label','accept' => 'image/jpeg,image/gif,image/png']) !!}
                                    {!! Form::file('course_image', ['class' => 'form-control']) !!}
                                    {!! Form::hidden('course_image_max_size', 8) !!}
                                    {!! Form::hidden('course_image_max_width', 4000) !!}
                                    {!! Form::hidden('course_image_max_height', 4000) !!}
                                    @if ($course->course_image)
                                        <a href="{{ asset('storage/uploads/'.$course->course_image) }}" target="_blank"><img
                                                    height="50px"
                                                    src="{{ asset('storage/uploads/'.$course->course_image) }}"
                                                    class="mt-1"></a>
                                    @endif
                                </div>
                                <div class="col-12 col-lg-4 form-group">
                                    {!! Form::label('start_date', trans('labels.backend.courses.fields.start_date').' (yyyy-mm-dd)', ['class' => 'control-label']) !!}
                                    {!! Form::text('start_date', old('start_date'), ['class' => 'form-control date', 'pattern' => '(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))', 'placeholder' => trans('labels.backend.courses.fields.start_date').' (Ex . 2019-01-01)']) !!}
                                    <p class="help-block"></p>
                                    @if($errors->has('start_date'))
                                        <p class="help-block">
                                            {{ $errors->first('start_date') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 form-group">
                                    {!! Form::label('add_video', trans('labels.backend.lessons.fields.add_video'), ['class' => 'control-label']) !!}

                                    {!! Form::select('media_type', ['youtube' => 'Youtube','vimeo' => 'Vimeo','upload' => 'Upload','embed' => 'Embed'],($course->mediavideo && $course->mediavideo->type) ? $course->mediavideo->type : '',['class' => 'form-control media_type', 'placeholder' => 'Select One','id'=>'media_type' ,'data-type'=>'Course']) !!}

                                    {!! Form::text('video', ($course->mediavideo && $course->mediavideo->url) ? $course->mediavideo->url : old('video'), ['class' => 'form-control mt-3 video d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>''  ]) !!}

                                    {!! Form::select('video_file', $videos, old('video_file'), ['class' => 'form-control mt-3 d-none video_file','id'=>'']) !!}

                                    <input type="hidden" name="old_video_file"
                                           value="{{($course->mediavideo && $course->mediavideo->type == 'upload') ? $course->mediavideo->id  : ""}}">


                                    @if($course->mediavideo && ($course->mediavideo->type == 'upload'))
                                        <video width="300" class="mt-2 d-none video-player" controls
                                               controlsList="nodownload">
                                            <source src="{{route('videos.stream',['encryptedId'=>\Illuminate\Support\Facades\Crypt::encryptString($course->mediavideo->id)])}}"/>
                                            Your browser does not support HTML5 video.
                                        </video>
                                    @endif

                                    @lang('labels.backend.lessons.video_guide')

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12 form-group">
                                    <div class="checkbox d-inline mr-4">
                                        {!! Form::hidden('published', 0) !!}
                                        {!! Form::checkbox('published', 1, old('published'), []) !!}
                                        {!! Form::label('published', trans('labels.backend.courses.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                    </div>

                                    @if (Auth::user()->isAdmin())

                                        <div class="checkbox d-inline mr-4">
                                            {!! Form::hidden('featured', 0) !!}
                                            {!! Form::checkbox('featured', 1, old('featured'), []) !!}
                                            {!! Form::label('featured',  trans('labels.backend.courses.fields.featured'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                        </div>

                                        <div class="checkbox d-inline mr-4">
                                            {!! Form::hidden('trending', 0) !!}
                                            {!! Form::checkbox('trending', 1, old('trending'), []) !!}
                                            {!! Form::label('trending',  trans('labels.backend.courses.fields.trending'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                        </div>

                                        <div class="checkbox d-inline mr-4">
                                            {!! Form::hidden('popular', 0) !!}
                                            {!! Form::checkbox('popular', 1, old('popular'), []) !!}
                                            {!! Form::label('popular',  trans('labels.backend.courses.fields.popular'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                        </div>
                                    @endif
                                    <div class="checkbox d-inline mr-4">
                                        {!! Form::hidden('free', 0) !!}
                                        {!! Form::checkbox('free', 1, old('free'), []) !!}
                                        {!! Form::label('free',  trans('labels.backend.courses.fields.free'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                    </div>

                                    <div class="checkbox d-inline mr-3">
                                        {!! Form::hidden('online', 0) !!}
                                        {!! Form::checkbox('online', 1 , old('online'),  ['id'=>'online'], false) !!}
                                        {!! Form::label('online',  trans('labels.backend.courses.fields.online_courses'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                    </div>
                                    <div class="checkbox d-inline mr-3">
                                        {!! Form::hidden('offline', 0) !!}
                                        {!! Form::checkbox('offline', 1 ,old('offline'), ['id'=>'offline','onclick'=>'toggleOfflineMode()'], false) !!}
                                        {!! Form::label('offline',  trans('labels.backend.courses.fields.offline_courses'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                    </div>

                                </div>
                            </div>
                            <div class="not-necessary-section d-none">
                                <div class="row">
                                    <div class="col-12 form-group">
                                        {!! Form::label('meta_title',trans('labels.backend.courses.fields.meta_title'), ['class' => 'control-label']) !!}
                                        {!! Form::text('meta_title', old('meta_title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_title')]) !!}

                                    </div>
                                    <div class="col-12 form-group">
                                        {!! Form::label('meta_description',trans('labels.backend.courses.fields.meta_description'), ['class' => 'control-label']) !!}
                                        {!! Form::textarea('meta_description', old('meta_description'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_description')]) !!}
                                    </div>
                                    <div class="col-12 form-group">
                                        {!! Form::label('meta_keywords',trans('labels.backend.courses.fields.meta_keywords'), ['class' => 'control-label']) !!}
                                        {!! Form::textarea('meta_keywords', old('meta_keywords'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.courses.fields.meta_keywords')]) !!}
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-10 form-group">
                                    {!! Form::label('optional_courses',trans('labels.backend.courses.fields.optional_courses'), ['class' => 'control-label']) !!}
                                    {!! Form::select('opt_courses[]', $allCourses,old('opt_courses') ? old('opt_courses') : $opt_courses, ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => false]) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-10 form-group">
                                    {!! Form::label('mandatory_courses',trans('labels.backend.courses.fields.mandatory_courses'), ['class' => 'control-label']) !!}
                                    {!! Form::select('mand_courses[]', $allCourses, old('mand_courses') ? old('mand_courses') : $mand_courses, ['class' => 'form-control select2 js-example-placeholder-multiple', 'multiple' => 'multiple', 'required' => false]) !!}
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-10 form-group">
                                    {!! Form::label('learned',trans('labels.backend.courses.fields.learned'), ['class' => 'control-label']) !!}
                                    {!! Form::select('learn[]', $allLearned, old('learn') ? old('learn') : $prevLearned, ['class' => 'form-control select2 js-input-tag', 'multiple' => 'multiple', 'required' => false]) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-10 form-group">
                                    {!! Form::label('learned_ar',trans('labels.backend.courses.fields.learned_ar'), ['class' => 'control-label']) !!}
                                    {!! Form::select('learn_ar[]', $allLearned_ar, old('learn_ar') ? old('learn_ar') : $prevLearned_ar, ['class' => 'form-control select2 js-input-tag', 'multiple' => 'multiple', 'required' => false]) !!}
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-12  text-center form-group">
                                    {!! Form::submit(trans('strings.backend.general.app_update'), ['class' => 'btn btn-danger']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}


                </div>
                <div class="tab-pane fade" id="v-pills-profile" role="tabpanel" aria-labelledby="v-pills-profile-tab">
                </div>
                <div class="tab-pane fade" id="v-pills-messages" role="tabpanel" aria-labelledby="v-pills-messages-tab">

                    <div class="title my-3 mx-5">
                        <h1 class="page-title d-inline mb-5">@lang('labels.backend.courses.content')</h1>
                    </div>

                    @if(count($chapterContent) > 0)
                        <div class="shadow-lg p-3 mb-5 bg-white rounded">

                            <div class="card-body">
                                <?php
                                $currentUrl = url()->current();
                                if (config('nav_menu') != 0) {
                                    $nav_menu = \Harimayco\Menu\Models\Menus::findOrFail(config('nav_menu'));
                                }


                                ?>

                                <div id="hwpwrap">
                                    <div class="custom-wp-admin wp-admin wp-core-ui js   menu-max-depth-0 nav-menus-php auto-fold admin-bar">
                                        <div id="wpwrap">
                                            <div id="wpcontent">
                                                <div id="wpbody">
                                                    <div id="wpbody-content">
                                                        <div class="wrap">
                                                            <div id="nav-menus-frame" class="row">

                                                                <div class="col-lg-12 col-12"
                                                                     id="menu-management-liquid">
                                                                    <div id="menu-management">
                                                                        <form id="update-nav-menu" action=""
                                                                              method="post"
                                                                              enctype="multipart/form-data">
                                                                            <div class="menu-edit ">

                                                                                <div id="post-body">
                                                                                    <div id="post-body-content">

                                                                                        <ul class="menu ui-sortable"
                                                                                            id="menu-to-edit">
                                                                                            @foreach($chapterContent as $item)
                                                                                                @foreach ($timeline as  $singleTimeline)
                                                                                                    @if($singleTimeline->model_type == 'App\Models\Chapter')
                                                                                                        @if($singleTimeline->model_id == $item->id)

                                                                                                            @php
                                                                                                                $lessons = \App\Models\CourseTimeline::where('course_id', $course->id)->where('model_type','!=','App\Models\Chapter')->where('chapter_id',$singleTimeline->model_id)->orderBy('sequence')->get();
                                                                                                            @endphp

                                                                                                            <li id="menu-item-{{$item->id}}"
                                                                                                                data-type="{{$singleTimeline->model_type}}"
                                                                                                                class="menu-item menu-item-depth-0  menu-item-page menu-item-edit-inactive pending"
                                                                                                                style="display: list-item;">
                                                                                                                <dl class="menu-item-bar">
                                                                                                                    <div class="menu-item-handle col-12 col-lg-12">
                                                                                                                    <span class="item-title"> <span
                                                                                                                                class="menu-item-title"> <span
                                                                                                                                    id="{{$item->id}}">{{$item->title}} | {{$singleTimeline->sequence}}</span> </span>
                                                                                                                    <span class="item-controls">
                                                                                                                            <a class="item-edit"
                                                                                                                               id="edit-{{$item->id}}"
                                                                                                                               title=" "
                                                                                                                               href="{{ url()->current() }}?edit-menu-item=#menu-item-settings-{{$item->id}}"> </a>
                                                                                                                        </span>  </span>
                                                                                                                    </div>
                                                                                                                </dl>

                                                                                                                <div class="menu-item-settings col-12 col-lg-12"
                                                                                                                     id="menu-item-settings-{{$item->id}}">
                                                                                                                    <div class="row">
                                                                                                                        <div class="col-4">
                                                                                                                            @if($singleTimeline->model_type == 'App\Models\Chapter')
                                                                                                                                <button onclick="$('#chapter_id_lesson').val({{$item->id}});"
                                                                                                                                        type="button"
                                                                                                                                        class="btn btn-primary"
                                                                                                                                        data-toggle="modal"
                                                                                                                                        data-target="#exampleModal2">
                                                                                                                                    Create
                                                                                                                                    Lesson
                                                                                                                                </button>
                                                                                                                            @endif
                                                                                                                        </div>
                                                                                                                        <div class="col-4">
                                                                                                                            @if($singleTimeline->model_type == 'App\Models\Chapter')
                                                                                                                                <button onclick="$('#chapter_id_test').val({{$item->id}});"
                                                                                                                                        type="button"
                                                                                                                                        class="btn btn-primary"
                                                                                                                                        data-toggle="modal"
                                                                                                                                        data-target="#create-test">
                                                                                                                                    Create
                                                                                                                                    Test
                                                                                                                                </button>
                                                                                                                            @endif
                                                                                                                        </div>
                                                                                                                        <div class="col-4">
                                                                                                                            @if($singleTimeline->model_type == 'App\Models\Chapter')
                                                                                                                                <button type="button"
                                                                                                                                        class="btn btn-danger"
                                                                                                                                        onclick="removeChapter({{$item->id}})">
                                                                                                                                    <i class="fa fa-trash"></i>
                                                                                                                                </button>
                                                                                                                                <button type="button"
                                                                                                                                        class="btn btn-info"
                                                                                                                                        data-toggle="modal"
                                                                                                                                        data-target="#edit-chapter"
                                                                                                                                        onclick="editChapter({{$item->id}})">
                                                                                                                                    <i
                                                                                                                                            class="fa fa-edit"></i>
                                                                                                                                </button>

                                                                                                                            @endif
                                                                                                                        </div>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </li>
                                                                        </form>
                                                                        @foreach($lessons as $lesson)
                                                                            @php
                                                                                if($lesson->model_type == \App\Models\Lesson::class) {
                                                                                        $lessonData = \App\Models\Lesson::withTrashed()->find($lesson->model_id);
                                                                                        } elseif ($lesson->model_type == \App\Models\Test::class) {
                                                                                        $lessonData = \App\Models\Test::withTrashed()->find($lesson->model_id);
                                                                                        }
                                                                            @endphp
                                                                            @if($lessonData)

                                                                                <li id="menu-item-{{$lesson->model_id}}"
                                                                                    data-type="{{$lesson->model_type}}"
                                                                                    class="menu-item  menu-item-depth-1  menu-item-page menu-item-edit-inactive pending"
                                                                                    style="display: list-item;">
                                                                                    <dl class="menu-item-bar">
                                                                                        <div class="menu-item-handle col-12 col-lg-7">
                                                                                                                                <span class="item-title">
                                                                                                                                    <span class="menu-item-title">
                                                                                                                                        <p data-id="{{$lesson->id}}">{{$lessonData->title}} | {{$lesson->chapter_id}} @if($lesson->model_type == \App\Models\Test::class)
                                                                                                                                                |
                                                                                                                                                Test
                                                                                                                                            @endif
                                                                                                                                         </p>
                                                                                                                                    </span>
                                                                                                                                </span>
                                                                                            <div class="float-right">
                                                                                                @if ( !is_null($lessonData->deleted_at))
                                                                                                    <form method="post"
                                                                                                          class="d-inline"
                                                                                                          @if($lesson->model_type == \App\Models\Test::class)
                                                                                                          action="{{route('admin.tests.restore', ['id' => $lesson->model_id])}}"
                                                                                                          @elseif ( $lesson->model_type == \App\Models\Lesson::class)
                                                                                                          action="{{route('admin.lessons.restore', ['id' => $lesson->model_id])}}"
                                                                                                            @endif
                                                                                                    >
                                                                                                        @csrf
                                                                                                        <button type="submit"
                                                                                                                class="btn btn-dark ml-1">
                                                                                                            <i class="fa fa-repeat"></i>
                                                                                                        </button>
                                                                                                    </form>
                                                                                                    <form method="post"
                                                                                                          class="d-inline"
                                                                                                          @if($lesson->model_type == \App\Models\Test::class)
                                                                                                          action="{{route('admin.tests.perma_del', ['id' => $lesson->model_id])}}"
                                                                                                          @elseif ( $lesson->model_type == \App\Models\Lesson::class)
                                                                                                          action="{{route('admin.lessons.perma_del', ['id' => $lesson->model_id])}}"
                                                                                                            @endif
                                                                                                    >
                                                                                                        @csrf
                                                                                                        @method('DELETE')
                                                                                                        <button type="submit"
                                                                                                                class="btn btn-danger ml-1">
                                                                                                            <i class="fa fa-times"></i>
                                                                                                        </button>
                                                                                                    </form>
                                                                                                @else
                                                                                                    @if($lesson->model_type == \App\Models\Test::class)
                                                                                                        @if($lesson->must_finish == 0)
                                                                                                            <form method="post"
                                                                                                                  class="d-inline"
                                                                                                                  action="{{route('admin.test.must_finish', ['test' => $lesson->model_id])}}">
                                                                                                                @csrf
                                                                                                                @method('post')
                                                                                                                <button type="submit"
                                                                                                                        class="btn btn-light float-right ml-1">
                                                                                                                    make
                                                                                                                    this
                                                                                                                    test
                                                                                                                    Must
                                                                                                                    Pass
                                                                                                                </button>
                                                                                                            </form>
                                                                                                        @else
                                                                                                            <form class="d-inline">
                                                                                                                <p class="btn btn-light float-right ml-1">
                                                                                                                    <i class="fa fa-check"></i>
                                                                                                                    Must
                                                                                                                    Pass
                                                                                                                    this
                                                                                                                    test
                                                                                                                    before
                                                                                                                    certificate
                                                                                                                </p>

                                                                                                            </form>

                                                                                                        @endif



                                                                                                        <form method="post"
                                                                                                              class="d-inline"
                                                                                                              action="{{route('admin.tests.destroy', ['test' => $lesson->model_id])}}">
                                                                                                            @csrf
                                                                                                            @method('DELETE')
                                                                                                            <button type="submit"
                                                                                                                    class="btn btn-light float-right ml-1">
                                                                                                                <i
                                                                                                                        class="fa fa-trash"></i>
                                                                                                            </button>
                                                                                                        </form>


                                                                                                    @elseif ($lesson->model_type == \App\Models\Lesson::class)
                                                                                                        <form method="post"
                                                                                                              class="d-inline"
                                                                                                              action="{{route('admin.lessons.destroy', ['lesson' => $lesson->model_id])}}">
                                                                                                            @csrf
                                                                                                            @method('DELETE')
                                                                                                            <button type="submit"
                                                                                                                    class="float-right btn btn-light  ml-1">
                                                                                                                <i
                                                                                                                        class="fa fa-trash"></i>
                                                                                                            </button>
                                                                                                        </form>



                                                                                                    @endif
                                                                                                    <a @if($lesson->model_type == \App\Models\Test::class) onclick="editTest({{$lesson->model_id}})"
                                                                                                       @else onclick="editLesson({{$lesson->model_id}})"
                                                                                                       @endif class="btn btn-info float-right"
                                                                                                       href="#editModal"
                                                                                                       data-toggle="modal"
                                                                                                       data-target="#editModal"><i
                                                                                                                class="fa fa-edit"></i>
                                                                                                    </a>
                                                                                                @endif
                                                                                            </div>
                                                                                        </div>
                                                                                    </dl>
                                                                                </li>
                                                                                @endif
                                                                                @endforeach
                                                                                @endif
                                                                                @endif

                                                                                @endforeach
                                                                                @endforeach

                                                                                </ul>

                                                                                <a href="#" id="save_timeline"
                                                                                   class="btn btn-primary float-right">@lang('labels.backend.hero_slider.save_sequence')</a>


                                                                    </div>
                                                                </div>

                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                </div>
            </div>
        </div>


        @endif
    </div>

    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">
                        Create Lesson</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['method' => 'POST', 'route' => ['admin.lessons.store'], 'files' => true,]) !!}
                    {!! Form::hidden('chapter_id',null,['id'=>'chapter_id_lesson']) !!}
                    {!! Form::hidden('course_id',$course->id)!!}

                    <div class="card">
                        <div class="card-header">
                            <h3 class="page-title float-left mb-0">@lang('labels.backend.lessons.create')</h3>
                            <div class="float-right">

                            </div>
                        </div>

                        <div class="card-body">

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

                                    {!! Form::select('media_type', ['youtube' => 'Youtube','vimeo' => 'Vimeo','upload' => 'Upload','embed' => 'Embed'],($course->mediavideo && $course->mediavideo->type) ? $course->mediavideo->type : '',['class' => 'form-control media_type', 'placeholder' => 'Select One','id'=>'media_type','data-type'=>'Lesson' ]) !!}

                                    {!! Form::text('video', null, ['class' => 'form-control mt-3 video d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>''  ]) !!}

                                    {!! Form::select('video_file', $notSelectedVideos, null, ['class' => 'form-control mt-3 d-none video_file','id'=>'']) !!}

                                    <div class="mt-2">
                                        @lang('labels.backend.lessons.video_guide')
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 form-group d-none"
                                     id="duration">
                                    {!! Form::label('duration',  trans('labels.backend.courses.duration'), ['class' => 'control-label']) !!}
                                    {!! Form::text('duration', old('duration'), ['class' => 'form-control ','title'=>'Required format ex:(05:05:05)', 'placeholder' =>  trans('labels.backend.courses.video_format'),'pattern'=>'([01]?[0-9]+|2[0-3]):[0-5][0-9]:[0-5][0-9]']) !!}

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

            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Chapter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['method' => 'POST', 'route' => ['admin.chapters.store'], 'files' => true,]) !!}
                    {!! Form::hidden('course_id',$course->id)!!}

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

                                <div class="col-12 col-lg-6 form-group">
                                    {!! Form::label('title', trans('labels.backend.chapters.fields.title').'*', ['class' => 'control-label']) !!}
                                    {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.chapters.fields.title'), 'required' => '']) !!}
                                </div>
                                <div class="col-12 col-lg-6 form-group">
                                    {!! Form::label('title_ar', trans('labels.backend.chapters.fields.title_ar').'*', ['class' => 'control-label']) !!}
                                    {!! Form::text('title_ar', old('title_ar'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.chapters.fields.title_ar'), 'required' => '']) !!}
                                </div>

                                <div class="checkbox d-inline mr-3">
                                    {!! Form::hidden('published', 0) !!}
                                    {!! Form::checkbox('published', 1, false, []) !!}
                                    {!! Form::label('published',  trans('labels.backend.courses.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                                </div>
                            </div>


                            <div class="row">


                                <div class="col-12  text-left form-group">
                                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn  btn-danger']) !!}
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}


                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="edit-chapter" tabindex="-1" aria-labelledby="editChapterModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editChapterModalLabel">Edit Chapter</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                {!! Form::open(['method' => 'POST','id'=>'editChapterForm','style'=>'display: none', 'files' => true,]) !!}
                {!! Form::hidden('course_id',$course->id)!!}
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row">

                        <div class="col-12 col-lg-6 form-group">
                            {!! Form::label('title', trans('labels.backend.chapters.fields.title').'*', ['class' => 'control-label']) !!}
                            {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.chapters.fields.title'), 'required' => '']) !!}
                        </div>
                        <div class="col-12 col-lg-6 form-group">
                            {!! Form::label('title_ar', trans('labels.backend.chapters.fields.title_ar').'*', ['class' => 'control-label']) !!}
                            {!! Form::text('title_ar', old('title_ar'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.chapters.fields.title_ar'), 'required' => '']) !!}
                        </div>
                        <div class="col-12 col-lg-6 form-group">
                            <div class="checkbox d-inline mr-3">
                                {!! Form::hidden('published', 0) !!}
                                {!! Form::checkbox('published', 1, false, []) !!}
                                {!! Form::label('published',  trans('labels.backend.courses.fields.published'), ['class' => 'checkbox control-label font-weight-bold']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer pb-0">
                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn  btn-danger']) !!}
                </div>
                {!! Form::close() !!}


            </div>
        </div>
    </div>
    <div class="modal fade" id="create-test" tabindex="-1" aria-labelledby="create-test"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="create-test">Create Test</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['method' => 'POST', 'route' => ['admin.tests.store']]) !!}
                    {!! Form::hidden('course_id',$course->id)!!}
                    {!! Form::hidden('chapter_id',null,['id'=>'chapter_id_test'])!!}
                    <div class="row">

                        <div class="col-12 col-lg-6 form-group">
                            {!! Form::label('title', trans('labels.backend.tests.fields.title').'*', ['class' => 'control-label']) !!}
                            {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.chapters.fields.title'), 'required' => 'required']) !!}
                        </div>
                        <div class="col-12 col-lg-6 form-group">
                            {!! Form::label('title_ar', trans('labels.backend.chapters.fields.title_ar').'*', ['class' => 'control-label']) !!}
                            {!! Form::text('title_ar', old('title_ar'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.chapters.fields.title_ar'), 'required' => '']) !!}
                        </div>
                        <div class="col-12 col-lg-6 form-group">
                            {!! Form::label('timer', trans('labels.backend.lessons.fields.test_timer').'*', ['class' => 'control-label']) !!}
                            {!! Form::number('timer', old('timer'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.lessons.fields.test_timer'), 'required' => 'required','min'=>0]) !!}
                        </div>
                        <div class="col-12 col-lg-6 form-group">
                            {!! Form::label('no_questions', trans('labels.backend.tests.fields.no_questions').'*', ['class' => 'control-label']) !!}
                            {!! Form::number('no_questions', old('no_questions'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.tests.fields.no_questions'), 'required' => 'required','min'=>0]) !!}
                        </div>
                        <div class="col-12 col-lg-12 form-group">
                            {!! Form::label('min_grade', trans('labels.backend.tests.fields.min_grade').'*', ['class' => 'control-label']) !!}
                            {!! Form::number('min_grade', old('min_grade'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.tests.fields.min_grade'), 'required' => 'required','min'=>0]) !!}
                        </div>
                        <div class="col-12 col-lg-6 form-group">
                            <div class="checkbox d-inline mr-3">
                                {!! Form::checkbox('published', 1, false, []) !!}
                                {!! Form::label('published', trans('labels.backend.tests.fields.published'), ['class' => 'control-label font-weight-bold']) !!}</div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn  btn-danger']) !!}

                </div>
            </div>

            {!! Form::close() !!}


        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit <span>Model</span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editTestForm" method="post" action="" style="display: none">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">


                        <div class="row">
                            <div class="col-12 col-lg-6 form-group">
                                {!! Form::label('title', trans('labels.backend.tests.fields.title').'*', ['class' => 'control-label']) !!}
                                {!! Form::text('title', old('title'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.chapters.fields.title'), 'required' => 'required']) !!}
                            </div>
                            <div class="col-12 col-lg-6 form-group">
                                {!! Form::label('title_ar', trans('labels.backend.chapters.fields.title_ar').'*', ['class' => 'control-label']) !!}
                                {!! Form::text('title_ar', old('title_ar'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.chapters.fields.title_ar'), 'required' => '']) !!}
                            </div>
                            <div class="col-12 col-lg-6 form-group">
                                {!! Form::label('timer', trans('labels.backend.lessons.fields.test_timer').'*', ['class' => 'control-label']) !!}
                                {!! Form::number('timer', old('timer'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.lessons.fields.test_timer'), 'required' => 'required','min'=>0]) !!}
                            </div>
                            <div class="col-12 col-lg-6 form-group">
                                {!! Form::label('no_questions', trans('labels.backend.tests.fields.no_questions').'*', ['class' => 'control-label']) !!}
                                {!! Form::number('no_questions', old('no_questions'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.tests.fields.no_questions'), 'required' => 'required','min'=>0]) !!}
                            </div>
                            <div class="col-12 col-lg-12 form-group">
                                {!! Form::label('min_grade', trans('labels.backend.tests.fields.min_grade').'*', ['class' => 'control-label']) !!}
                                {!! Form::number('min_grade', old('min_grade'), ['class' => 'form-control', 'placeholder' => trans('labels.backend.tests.fields.min_grade'), 'required' => 'required','min'=>0]) !!}
                            </div>
                            <div class="col-12 col-lg-6 form-group">
                                <div class="checkbox d-inline mr-3">
                                    {!! Form::checkbox('published', 1, false, []) !!}
                                    {!! Form::label('published', trans('labels.backend.tests.fields.published'), ['class' => 'control-label font-weight-bold']) !!}</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn  btn-danger']) !!}

                    </div>
                </form>
                <form id="editLessonForm" method="post" action="" style="display: none">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
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

                                {!! Form::select('media_type', ['youtube' => 'Youtube','vimeo' => 'Vimeo','upload' => 'Upload','embed' => 'Embed'],($course->mediavideo && $course->mediavideo->type) ? $course->mediavideo->type : '',['class' => 'form-control media_type', 'placeholder' => 'Select One','id'=>'media_type','data-type'=>'Lesson' ]) !!}

                                {!! Form::text('video', null, ['class' => 'form-control mt-3 video d-none', 'placeholder' => trans('labels.backend.lessons.enter_video_url'),'id'=>''  ]) !!}

                                {!! Form::select('video_file', $notSelectedVideos, null, ['class' => 'form-control mt-3 d-none video_file','id'=>'']) !!}

                                <div class="mt-2">
                                    @lang('labels.backend.lessons.video_guide')
                                </div>
                            </div>
                            <div class="col-12 col-lg-6 form-group d-none"
                                 id="duration">
                                {!! Form::label('duration',  trans('labels.backend.courses.duration'), ['class' => 'control-label']) !!}
                                {!! Form::text('duration', old('duration'), ['class' => 'form-control ', 'title'=>'Required format ex:(05:05:05)', 'placeholder' =>  trans('labels.backend.courses.video_format'),'pattern'=>'([01]?[0-9]+|2[0-3]):[0-5][0-9]:[0-5][0-9]']) !!}

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
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::submit(trans('strings.backend.general.app_save'), ['class' => 'btn  btn-danger']) !!}

                    </div>
                </form>
            </div>
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
                                {!! Form::input('number','offline_price',$course->offline_price ? $course->offline_price : old('offline_price'), ['class' => 'form-control', 'id'=>'offline-price']) !!}
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

                                @if($date)
                                    @foreach($date as $Pkey => $singleDate )
                                        @foreach($singleDate as  $key => $value)
                                            @if($key =='date')
                                                <div class='button-wrapper'>
                                                    <h6 class='mt-3'>
                                                            <span class='remove'>
                                                                <i class='fa fa-window-close'></i>
                                                            </span>
                                                    </h6>
                                                    <div class='row'>
                                                        <div class='col-lg-10'>
                                                            <label for='start_dat' class='control-label'>Start
                                                                Date
                                                                (yyyy-mm-dd)</label>
                                                            <input class='form-control date-input dat'
                                                                   pattern='(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))'
                                                                   value='{{$value}}' autocomplete='off'
                                                                   type='text'>


                                                        </div>
                                                        <div class='col-2 mt-4'>
                                                            <button type='button'
                                                                    onclick="addInputTime(this)"
                                                                    class='add-but btn-block btn  btn-primary'>{{__('labels.backend.hero_slider.fields.buttons.add')}}</button>
                                                        </div>
                                                    </div>
                                                    <div class='timepicker'></div>
                                                    @endif
                                                    @if(substr($key , 0,4) == 'time' )
                                                        <div class='row mt-3'
                                                             style="justify-content: flex-end;">
                                                            <span class='removeTime mr-3'><i
                                                                        class='fa fa-window-close'></i></span>
                                                            <div class='col-lg-12'>
                                                                <div class='row timeRemove'>
                                                                    <div class='col-lg-6'>
                                                                        <input class='form-control time-input dat'
                                                                               value="{{$value}}"
                                                                               autocomplete='off'
                                                                               type='time'>
                                                                    </div>
                                                                    @endif
                                                                    @if(substr($key , 0,4) == 'seat')
                                                                        <div class='col-lg-6'>
                                                                            <input class='form-control seats-input'
                                                                                   value='{{$value}}'
                                                                                   autocomplete='off'
                                                                                   name='seats'
                                                                                   type='number'>
                                                                        </div>
                                                                </div>

                                                            </div>
                                                        </div>

                                                    @endif

                                                    @endforeach
                                                    @endforeach
                                                </div>
                                            @endif

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
    </div>
    </div>
    </div>
    </div>
@stop

@push('after-scripts')
    <script>
        function editTest(id) {
            $('#editLessonForm').hide();
            $.ajax({
                type: "get",
                url: '{{route('admin.test.getData')}}',
                data: {
                    _token: '{{ csrf_token() }}',
                    'id': id
                },
                success: function (resp) {
                    $('#editTestForm').show();
                    var url = "{{ route('admin.tests.update',":id") }}";
                    url = url.replace(':id', id);

                    $('#editTestForm').attr('action', url)
                    var inputName = Object.keys(resp);
                    $(inputName).each(function (key, value) {
                        $('#editTestForm input[name="' + value + '"]').val(resp[value])
                    })
                    if (resp.published) {
                        $('#editTestForm input[name="published"]').prop('checked', true);
                    }
                    console.log(resp)
                }
            })
        };

        function editChapter(id) {
            $('#editChapterForm').hide();
            var editUrl = "{{ route('admin.chapters.edit',":id") }}";
            editUrl = editUrl.replace(':id', id);
            $.ajax({
                type: "get",
                url: editUrl,
                data: {
                    _token: '{{ csrf_token() }}',
                    'id': id
                },
                success: function (resp) {
                    $('#editChapterForm').show();
                    var url = "{{ route('admin.chapters.update',":id") }}";
                    url = url.replace(':id', id);

                    $('#editChapterForm').attr('action', url)
                    var inputName = Object.keys(resp);
                    $(inputName).each(function (key, value) {
                        $('#editChapterForm input[name="' + value + '"]').val(resp[value])
                    })
                    if (resp.published) {
                        $('#editChapterForm input[name="published"]').prop('checked', true);
                    }
                    console.log(resp)
                }
            })
        }

        function removeChapter(id) {
            if (confirm('Are your sure you want to delete this chapter?')) {
                $.ajax({
                    type: "post",
                    url: '{{route('admin.chapters.remove')}}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        'id': id
                    },
                    success: function (resp) {
                        console.log(resp)
                    }
                })
            }
        }

        function editLesson(id) {
            $('#editTestForm').hide();
            $.ajax({
                type: "get",
                url: '{{route('admin.lesson.getData')}}',
                data: {
                    _token: '{{ csrf_token() }}',
                    'id': id
                },
                success: function (resp) {
                    $('#editLessonForm').show();
                    var url = "{{ route('admin.lessons.update',":id") }}";
                    url = url.replace(':id', id);
                    $('#editLessonForm').attr('action', url)
                    var inputName = Object.keys(resp);
                    $(inputName).each(function (key, value) {
                        $('#editLessonForm input[name="' + value + '"]').val(resp[value])
                    })
                    if (resp.published) {
                        $('#editLessonForm input[name="published"]').prop('checked', true);
                    }
                    if (resp.media) {
                        $('#editLessonForm input[name="video"]').val(resp.media[0].url);
                        $('#editLessonForm input[name="duration"]').val(resp.media[0].duration);
                    }
                    console.log(resp)
                }
            })
        }

        $(document).ready(function () {


            $('.date-input').datepicker({
                autoclose: true,
                dateFormat: "{{ config('app.date_format_js') }}"
            });
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


        $(document).on('change', '.media_type', function () {
            console.log($(this).data('type'))
            if ($(this).val()) {
                if ($(this).val() != 'upload') {
                    $(this).parent().find('.video').removeClass('d-none').attr('required', true)
                    $(this).parent().find('.video_file').addClass('d-none').attr('required', false)
                    $(this).parent().parent().find('#duration').removeClass('d-none').attr('required', true)
                } else if ($(this).val() == 'upload') {
                    $(this).parent().find('.video').addClass('d-none').attr('required', false)
                    $(this).parent().find('.video_file').removeClass('d-none').attr('required', true)
                }
            } else {
                $(this).parent().find('.video_file').addClass('d-none').attr('required', false)
                $(this).parent().find('.video').addClass('d-none').attr('required', false)
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

    </script>
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
                tags: true,
            })


        });

        $(document).ready(function () {
            $(document).on('click', '.delete', function (e) {
                e.preventDefault();
                var parent = $(this).parent('.form-group');
                var confirmation = confirm('{{trans('strings.backend.general.are_you_sure')}}')
                if (confirmation) {
                    var media_id = $(this).data('media-id');
                    $.post('{{route('admin.media.destroy')}}', {media_id: media_id, _token: '{{csrf_token()}}'},
                        function (data, status) {
                            if (data.success) {
                                parent.remove();
                                $('.video').val('').addClass('d-none').attr('required', false);
                                $('.video_file').attr('required', false);
                                $('#media_type').val('');
                                @if($course->mediavideo && $course->mediavideo->type ==  'upload')
                                $('.video-player').addClass('d-none');
                                $('.video-player').empty();
                                @endif


                            } else {
                                alert('Something Went Wrong')
                            }
                        });
                }
            })
        });


        @if($course->mediavideo)
        @if($course->mediavideo->type !=  'upload')
        $('.video').removeClass('d-none').attr('required', true);
        $('.video_file').addClass('d-none').attr('required', false);
        $('.video-player').addClass('d-none');
        @elseif($course->mediavideo->type == 'upload')
        $('.video').addClass('d-none').attr('required', false);
        $('.video_file').removeClass('d-none').attr('required', false);
        $('.video-player').removeClass('d-none');
        @else
        $('.video-player').addClass('d-none');
        $('.video_file').addClass('d-none').attr('required', false);
        $('.video').addClass('d-none').attr('required', false);
        @endif
        @endif


    </script>

    <script>

        $(document).on('click', '#save_timeline', function (e) {

            // console.log("dfvgdf");
            e.preventDefault();
            var list = [];
            $('.ui-sortable .menu-item-depth-1').each(function (key, value) {
                key++;
                var val = $(value).find('p').data('id');

                list.push({id: val, sequence: key});
            });

            console.log(list);

            $.ajax({
                method: 'POST',
                url: "{{route('admin.courses.saveSequence')}}",
                data: {
                    _token: '{{csrf_token()}}',
                    list: list
                },
                success: function (resp) {
                    console.log(resp);
                }
            }).done(function () {
                // location.reload();
            });
        })


        $(document).on('click', '.switch-input', function (e) {
            var id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "{{ route('admin.sliders.status') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                },
            }).done(function () {
                location.reload();
            });
        })
    </script>


    {!! Menu::scripts() !!}
    <script src="{{url('/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.bundle.min.js')}}"></script>
    <script type="text/javascript">
        $('#menu_icon').iconpicker({});

        $(document).ready(function () {
            $(document).on('click', '.btn-add', function (e) {
                e.preventDefault();

                var tableFields = $('.table-fields'),
                    currentEntry = $(this).parents('.entry:first'),
                    newEntry = $(currentEntry.clone()).appendTo(tableFields);

                newEntry.find('input').val('');
                tableFields.find('.entry:not(:last) .btn-add')
                    .removeClass('btn-add').addClass('btn-remove')
                    .removeClass('btn-success').addClass('btn-danger')
                    .html('<span class="fa fa-minus"></span>');
            }).on('click', '.btn-remove', function (e) {
                $(this).parents('.entry:first').remove();

                e.preventDefault();
                return false;
            });

        });
    </script>

    <script src="{{asset('plugins/bootstrap-tagsinput/bootstrap-tagsinput.js')}}"></script>
    <script type="text/javascript" src="{{asset('/vendor/unisharp/laravel-ckeditor/ckeditor.js')}}"></script>
    <script type="text/javascript"
            src="{{asset('/vendor/unisharp/laravel-ckeditor/adapters/jquery.js')}}"></script>
    <script src="{{asset('/vendor/laravel-filemanager/js/lfm.js')}}"></script>
    <script>
        $('.editor').each(function () {

            CKEDITOR.replace($(this).attr('id'), {
                filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
                filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{csrf_token()}}',
                filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
                filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{csrf_token()}}',
                extraPlugins: 'smiley,lineutils,widget,codesnippet,prism,flash',
            });

        });

        var uploadField = $('input[type="file"]');

        $(document).on('change', 'input[type="file"]', function () {
            var $this = $(this);
            $(this.files).each(function (key, value) {
                console.log(value.size);
            })
        })


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
            $('.date-input').datepicker({
                autoclose: true,
                dateFormat: "{{ config('app.date_format_js') }}"

            });
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

        $(document).on('click', '.removeTime', function () {
            if (confirm('Are you sure want to remove button?')) {
                console.log($(this).parent().find('.timeRemove')[0])
                $(this).parent().find('.timeRemove')[0].remove();
                $(this).remove();
                $('#buttons').val($('.timeRemove').length)
            }
        });

        function saveOfflineData(element) {
            var arrObj = [];
            var button_wrapper = $(element).parent().parent().find('.button-wrapper');
            button_wrapper.each(function (key, value) {

                var date_input = $(value).find('.date-input').val();
                //  console.log(date_input) ;
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
                    //$(value).val('');
                });
                $('.academy select').each(function (key, value) {
                    // $(value).val('');
                    // if ($(value).select2()) {
                    // $(value).select2('destroy');
                    // }
                })
            }
        }

        // toggleOfflineMode();

    </script>
@endpush
