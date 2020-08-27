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
                                    ->placeholder(__('labels.teacher.bank_details.phone')) }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.en_address'))->class('col-md-2 form-control-label')->for('address') }}
                        <div class="col-md-10">
                            {{ html()->text('address')
                                    ->class('form-control')
                                    ->placeholder(__('labels.backend.teachers.fields.en_address')) }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.ar_address'))->class('col-md-2 form-control-label')->for('ar_address') }}
                        <div class="col-md-10">
                            {{ html()->text('ar_address')
                                    ->class('form-control')
                                    ->placeholder(__('labels.backend.teachers.fields.ar_address')) }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.en_city'))->class('col-md-2 form-control-label')->for('city') }}
                        <div class="col-md-10">
                            {{ html()->text('city')
                                    ->class('form-control')
                                    ->placeholder(__('labels.backend.teachers.fields.en_city')) }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.teachers.fields.ar_city'))->class('col-md-2 form-control-label')->for('ar_city') }}
                        <div class="col-md-10">
                            {{ html()->text('ar_city')
                                    ->class('form-control')
                                    ->placeholder(__('labels.backend.teachers.fields.ar_city')) }}
                        </div><!--col-->
                    </div>
                 
                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.bank_details.en_title'))->class('col-md-2 form-control-label')->for('title') }}
                        <div class="col-md-10">
                            {{ html()->text('title')
                                    ->class('form-control')
                                    ->placeholder(__('labels.teacher.bank_details.en_title')) }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.bank_details.ar_title'))->class('col-md-2 form-control-label')->for('ar_title') }}
                        <div class="col-md-10">
                            {{ html()->text('ar_title')
                                    ->class('form-control')
                                    ->placeholder(__('labels.teacher.bank_details.ar_title')) }}
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
                            {{ html()->input('url','facebook_link')
                                            ->class('form-control')
                                            ->placeholder(__('labels.teacher.facebook_link')) }}
                        </div><!--col-->
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.twitter_link'))->class('col-md-2 form-control-label')->for('twitter_link') }}

                        <div class="col-md-10">
                            {{ html()->input('url','twitter_link')
                                            ->class('form-control')
                                            ->placeholder(__('labels.teacher.twitter_link')) }}

                        </div><!--col-->
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.linkedin_link'))->class('col-md-2 form-control-label')->for('linkedin_link') }}

                        <div class="col-md-10">
                            {{ html()->input('url','linkedin_link')
                                            ->class('form-control')
                                            ->placeholder(__('labels.teacher.linkedin_link')) }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.type'))->class('col-md-2 form-control-label')->for('type') }}
                        <div class="col-md-10">
                            @if (auth()->user()->hasRole('academy'))
                                {{ html()->text('type')
                                ->class('form-control')
                                ->placeholder(__('labels.teacher.type'))
                                ->attributes(['maxlength'=> 191,'readonly'=>true])
                                ->value('academy')}}
                            @else
                                <select class="form-control" name="type" id="type" required>
                                    <option value="academy" {{ old('type') == 'academy'?'selected':'' }}>{{ trans('labels.teacher.academy') }}</option>
                                    <option value="individual" {{ old('type') == 'individual'?'selected':'' }}>{{ trans('labels.teacher.individual') }}</option>

                                </select>
                            @endif
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

                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.payment_details'))->class('col-md-2 form-control-label')->for('payment_details') }}
                        <div class="col-md-10">
                            <select class="form-control" name="payment_method" id="payment_method" required>
                                <option value="bank" {{ old('payment_method') == 'bank'?'selected':'' }}>{{ trans('labels.teacher.bank') }}</option>
                                <option value="paypal" {{ old('payment_method') == 'paypal'?'selected':'' }}>{{ trans('labels.teacher.paypal') }}</option>
                            </select>
                        </div>

                    </div>

                    <div class="bank_details">
                        <div class="form-group row">
                            {{ html()->label(__('labels.teacher.bank_details.name'))->class('col-md-2 form-control-label')->for('bank_name') }}
                            <div class="col-md-10">
                                {{ html()->text('bank_name')
                                        ->class('form-control')
                                        ->placeholder(__('labels.teacher.bank_details.name')) }}
                            </div><!--col-->
                        </div>

                        <div class="form-group row">
                            {{ html()->label(__('labels.teacher.bank_details.bank_code'))->class('col-md-2 form-control-label')->for('ifsc_code') }}
                            <div class="col-md-10">
                                {{ html()->text('ifsc_code')
                                        ->class('form-control')
                                        ->placeholder(__('labels.teacher.bank_details.bank_code')) }}
                            </div><!--col-->
                        </div>

                        <div class="form-group row">
                            {{ html()->label(__('labels.teacher.bank_details.account'))->class('col-md-2 form-control-label')->for('account_number') }}
                            <div class="col-md-10">
                                {{ html()->text('account_number')
                                        ->class('form-control')
                                        ->placeholder(__('labels.teacher.bank_details.account')) }}
                            </div><!--col-->
                        </div>

                        <div class="form-group row">
                            {{ html()->label(__('labels.teacher.bank_details.holder_name'))->class('col-md-2 form-control-label')->for('account_name') }}
                            <div class="col-md-10">
                                {{ html()->text('account_name')
                                        ->class('form-control')
                                        ->placeholder(__('labels.teacher.bank_details.holder_name')) }}
                            </div><!--col-->
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

                    <div class="paypal_details">
                        <div class="form-group row">
                            {{ html()->label(__('labels.teacher.paypal_email'))->class('col-md-2 form-control-label')->for('paypal_email') }}
                            <div class="col-md-10">
                                {{ html()->input('email','paypal_email')
                                        ->class('form-control')
                                        ->placeholder(__('labels.teacher.paypal_email')) }}
                            </div><!--col-->
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.en_description'))->class('col-md-2 form-control-label')->for('description') }}

                        <div class="col-md-10">
                            {{ html()->textarea('description')
                                            ->class('form-control')
                                            ->placeholder(__('labels.teacher.en_description')) }}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.teacher.ar_description'))->class('col-md-2 form-control-label')->for('ar_description') }}

                        <div class="col-md-10">
                            {{ html()->textarea('ar_description')
                                            ->class('form-control')
                                            ->placeholder(__('labels.teacher.ar_description')) }}
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
@push('after-scripts')
    <script>
        @if(old('payment_method') && old('payment_method') == 'bank')
        $('.paypal_details').hide();
        $('.bank_details').show();
        @elseif(old('payment_method') && old('payment_method') == 'paypal')
        $('.paypal_details').show();
        $('.bank_details').hide();
        @else
        $('.paypal_details').hide();
        @endif
        $(document).on('change', '#payment_method', function () {
            if ($(this).val() === 'bank') {
                $('.paypal_details').hide();
                $('.bank_details').show();
            } else {
                $('.paypal_details').show();
                $('.bank_details').hide();
            }
        });

        @if(old('type') && old('type') == 'individual')
        $('.academy').hide();
        @elseif(old('type')&& old('type')  == 'academy')
        $('.academy').show();
        @endif
        $(document).on('change', '#type', function () {
            if ($(this).val() === 'individual') {
                $('.academy').hide();

            } else {
                $('.academy').show();

            }
        });
    </script>
@endpush
