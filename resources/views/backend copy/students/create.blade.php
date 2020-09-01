@extends('backend.layouts.app')

@section('title', __('labels.backend.students.title').' | '.app_name())

@section('content')
    {{ html()->form('POST', route('admin.students.store'))->acceptsFiles()->class('form-horizontal')->open() }}
    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">@lang('labels.backend.students.create')</h3>
            <div class="float-right">
                <a href="{{ route('admin.students.index') }}"
                   class="btn btn-success">@lang('labels.backend.students.view')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.students.fields.en_first_name'))->class('col-md-2 form-control-label')->for('first_name') }}

                        <div class="col-md-10">
                            {{ html()->text('first_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.students.fields.en_first_name'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.students.fields.ar_first_name'))->class('col-md-2 form-control-label')->for('ar_first_name') }}

                        <div class="col-md-10">
                            {{ html()->text('ar_first_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.students.fields.ar_first_name'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.students.fields.en_last_name'))->class('col-md-2 form-control-label')->for('last_name') }}

                        <div class="col-md-10">
                            {{ html()->text('last_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.students.fields.en_last_name'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.students.fields.ar_last_name'))->class('col-md-2 form-control-label')->for('ar_last_name') }}

                        <div class="col-md-10">
                            {{ html()->text('ar_last_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.students.fields.ar_last_name'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->
                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.bank_details.phone'))->class('col-md-2 form-control-label')->for('phone') }}
                        <div class="col-md-10">
                            {{ html()->text('phone')
                                    ->class('form-control')
                                    ->placeholder(__('labels.teacher.bank_details.phone')) }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.students.fields.en_address'))->class('col-md-2 form-control-label')->for('address') }}
                        <div class="col-md-10">
                            {{ html()->text('address')
                                    ->class('form-control')
                                    ->placeholder(__('labels.backend.students.fields.en_address')) }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.students.fields.ar_address'))->class('col-md-2 form-control-label')->for('ar_address') }}
                        <div class="col-md-10">
                            {{ html()->text('ar_address')
                                    ->class('form-control')
                                    ->placeholder(__('labels.backend.students.fields.ar_address')) }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.students.fields.en_city'))->class('col-md-2 form-control-label')->for('city') }}
                        <div class="col-md-10">
                            {{ html()->text('city')
                                    ->class('form-control')
                                    ->placeholder(__('labels.backend.students.fields.en_city')) }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.students.fields.ar_city'))->class('col-md-2 form-control-label')->for('ar_city') }}
                        <div class="col-md-10">
                            {{ html()->text('ar_city')
                                    ->class('form-control')
                                    ->placeholder(__('labels.backend.students.fields.ar_city')) }}
                        </div><!--col-->
                    </div>


                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.students.fields.email'))->class('col-md-2 form-control-label')->for('email') }}

                        <div class="col-md-10">
                            {{ html()->email('email')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.students.fields.email'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.students.fields.password'))->class('col-md-2 form-control-label')->for('password') }}

                        <div class="col-md-10">
                            {{ html()->password('password')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.students.fields.password'))
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.students.fields.image'))->class('col-md-2 form-control-label')->for('image') }}

                        <div class="col-md-10">
                            {!! Form::file('image', ['class' => 'form-control d-inline-block', 'placeholder' => '']) !!}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.general_settings.user_registration_settings.fields.gender'))->class('col-md-2 form-control-label')->for('gender') }}
                        <div class="col-md-10">
                            <label class="radio-inline mr-3 mb-0">
                                <input type="radio" name="gender" {{old('gender') == 'male'? 'checked':''}}
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

                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.facebook_link'))->class('col-md-2 form-control-label')->for('facebook_link') }}

                        <div class="col-md-10">
                            {{ html()->text('facebook_link')
                                            ->class('form-control')
                                            ->placeholder(__('labels.teacher.facebook_link')) }}
                        </div><!--col-->
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.twitter_link'))->class('col-md-2 form-control-label')->for('twitter_link') }}

                        <div class="col-md-10">
                            {{ html()->text('twitter_link')
                                            ->class('form-control')
                                            ->placeholder(__('labels.teacher.twitter_link')) }}

                        </div><!--col-->
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.linkedin_link'))->class('col-md-2 form-control-label')->for('linkedin_link') }}

                        <div class="col-md-10">
                            {{ html()->text('linkedin_link')
                                            ->class('form-control')
                                            ->placeholder(__('labels.teacher.linkedin_link')) }}
                        </div><!--col-->
                    </div>



                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.students.fields.status'))->class('col-md-2 form-control-label')->for('active') }}
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
                            {{ form_cancel(route('admin.students.index'), __('buttons.general.cancel')) }}
                            {{ form_submit(__('buttons.general.crud.create')) }}
                        </div>
                    </div><!--col-->
                </div>
            </div>
        </div>
    </div>
    {{ html()->form()->close() }}
@endsection
