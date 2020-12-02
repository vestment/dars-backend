@extends('backend.layouts.app')

@section('title', __('labels.backend.teachers.title').' | '.app_name())

@section('content')
    {{ html()->form('POST', route('admin.teachers.store'))->acceptsFiles()->class('form-horizontal')->open() }}
    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">@lang('labels.backend.teachers.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.teachers.index') }}"
                   class="btn btn-success">@lang('labels.backend.teachers.view')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.en_first_name'))->class('col-md-2 form-control-label')->for('first_name') }}

                        <div class="col-md-10">
                            {{ html()->text('first_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.teachers.fields.en_first_name'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.ar_first_name'))->class('col-md-2 form-control-label')->for('ar_first_name') }}

                        <div class="col-md-10">
                            {{ html()->text('ar_first_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.teachers.fields.ar_first_name'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.en_last_name'))->class('col-md-2 form-control-label')->for('last_name') }}

                        <div class="col-md-10">
                            {{ html()->text('last_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.teachers.fields.en_last_name'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.ar_last_name'))->class('col-md-2 form-control-label')->for('ar_last_name') }}

                        <div class="col-md-10">
                            {{ html()->text('ar_last_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.teachers.fields.ar_last_name'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->
                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.bank_details.phone'))->class('col-md-2 form-control-label')->for('phone') }}
                        <div class="col-md-10">
                            {{ html()->text('phone')
                                    ->class('form-control')
                                    ->placeholder(__('labels.teacher.bank_details.phone'))->required() }}
                        </div><!--col-->
                    </div>
                   
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.email'))->class('col-md-2 form-control-label')->for('email') }}

                        <div class="col-md-10">
                            {{ html()->email('email')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.teachers.fields.email'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.password'))->class('col-md-2 form-control-label')->for('password') }}

                        <div class="col-md-10">
                            {{ html()->password('password')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.teachers.fields.password'))
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.image'))->class('col-md-2 form-control-label')->for('image') }}

                        <div class="col-md-10">
                            {!! Form::file('image', ['class' => 'form-control d-inline-block', 'placeholder' => '' , 'required']) !!}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.general_settings.user_registration_settings.fields.gender'))->class('col-md-2 form-control-label')->for('gender') }}
                        <div class="col-md-10">
                            <label class="radio-inline mr-3 mb-0">
                                <input type="radio" name="gender" {{old('gender') == 'male'|| (old('gender') != 'female' && old('gender') != 'other' ) ? 'checked':''}}
                                value="male"> {{__('validation.attributes.frontend.male')}}
                            </label>
                            <label class="radio-inline mr-3 mb-0">
                                <input type="radio" name="gender" {{old('gender') == 'female'? 'checked':''}}
                                       value="female"> {{__('validation.attributes.frontend.female')}}
                            </label>
                            <label class="radio-inline mr-3 mb-0">
                                <input type="radio" name="gender" {{old('gender') == 'other'? 'checked':''}}
                                       value="other"> {{__('validation.attributes.frontend.other')}}
                            </label>
                        </div>
                    </div>

                  

                    

                   
                    <div class="form-group row " hidden>
                        {{ html()->label(__('labels.teacher.type'))->class('col-md-2 form-control-label')->for('type') }}
                        <div class="col-md-10 ">
                         
                                <select class="form-control" name="type" id="type" hidden>
                                    <option value="individual" {{ old('type') == 'individual'?'selected':'' }}>{{ trans('labels.teacher.individual') }}</option>
                                </select>
                           
                        </div>

                    </div>

                   

                    

                  
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.percentage'))->class('col-md-2 form-control-label')->for('percentage') }}

                        <div class="col-md-10">
                            {{ html()->input('number','percentage')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.teachers.fields.percentage'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->

                  

                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.en_description'))->class('col-md-2 form-control-label')->for('description') }}

                        <div class="col-md-10">
                            {{ html()->textarea('description')
                                            ->class('form-control')
                                            ->placeholder(__('labels.teacher.en_description'))->required() }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.ar_description'))->class('col-md-2 form-control-label')->for('ar_description') }}

                        <div class="col-md-10">
                            {{ html()->textarea('ar_description')
                                            ->class('form-control')
                                            ->placeholder(__('labels.teacher.ar_description'))->required() }}
                        </div><!--col-->
                    </div>
                    





                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.title'))->class('col-md-2 form-control-label')->for('title') }}

                        <div class="col-md-10">
                            {{ html()->textarea('title')
                                            ->class('form-control')
                                            ->placeholder(__('labels.teacher.en_title'))->required() }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.ar_title'))->class('col-md-2 form-control-label')->for('ar_title') }}

                        <div class="col-md-10">
                            {{ html()->textarea('ar_title')
                                            ->class('form-control')
                                            ->placeholder(__('labels.teacher.ar_title'))->required() }}
                        </div><!--col-->
                    </div>











                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.status'))->class('col-md-2 form-control-label')->for('active') }}
                        <div class="col-md-10">
                            {{ html()->label(html()->checkbox('')->name('active')
                                        ->checked(true)->class('switch-input')->value(1)

                                    . '<span class="switch-label"></span><span class="switch-handle"></span>')
                                ->class('switch switch-lg switch-3d switch-primary')
                            }}
                        </div>

                    </div>

                    <div class="form-group row justify-content-center">
                        <div class="col-4">
                            {{ form_cancel(route('admin.teachers.index'), __('buttons.general.cancel')) }}
                            {{ form_submit(__('buttons.general.crud.create')) }}
                        </div>
                    </div><!--col-->
                </div>
            </div>
        </div>
    </div>
    {{ html()->form()->close() }}
@endsection

