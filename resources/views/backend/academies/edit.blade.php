@extends('backend.layouts.app')
@section('title', __('labels.backend.academies.title').' | '.app_name())

@section('content')
    {{ html()->modelForm($academy, 'PATCH', route('admin.academies.update', $academy->id))->class('form-horizontal')->acceptsFiles()->open() }}

    <div class="card">
        <div class="card-header">
            <h3 class="page-title d-inline">@lang('labels.backend.academies.edit')</h3>
            <div class="float-right">
                <a href="{{ route('admin.academies.index') }}"
                   class="btn btn-success">@lang('labels.backend.academies.view')</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.academies.fields.first_name'))->class('col-md-2 form-control-label')->for('first_name') }}

                        <div class="col-md-10">
                            {{ html()->text('first_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.academies.fields.first_name'))
                                ->attribute('maxlength', 191)
                                ->required()
                                ->autofocus() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.academies.fields.last_name'))->class('col-md-2 form-control-label')->for('last_name') }}

                        <div class="col-md-10">
                            {{ html()->text('last_name')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.academies.fields.last_name'))
                                ->attribute('maxlength', 191)
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.academies.fields.email'))->class('col-md-2 form-control-label')->for('email') }}

                        <div class="col-md-10">
                            {{ html()->email('email')
                                ->class('form-control')
                                ->placeholder(__('labels.backend.academies.fields.email'))
                                ->attributes(['maxlength'=> 191,'readonly'=>true])
                                ->required() }}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.academies.fields.password'))->class('col-md-2 form-control-label')->for('password') }}

                        <div class="col-md-10">
                            {{ html()->password('password')
                                ->class('form-control')
                                ->value('')
                                ->placeholder(__('labels.backend.academies.fields.password'))
}}
                        </div><!--col-->
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.academies.fields.image'))->class('col-md-2 form-control-label')->for('image') }}

                        <div class="col-md-10">
                            {!! Form::file('image', ['class' => 'form-control d-inline-block', 'placeholder' => '']) !!}
                        </div><!--col-->
                    </div>
                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.general_settings.user_registration_settings.fields.gender'))->class('col-md-2 form-control-label')->for('gender') }}
                        <div class="col-md-10">
                            <label class="radio-inline mr-3 mb-0">
                                <input type="radio" name="gender" value="male" {{ $academy->gender == 'male'?'checked':'' }}> {{__('validation.attributes.frontend.male')}}
                            </label>
                            <label class="radio-inline mr-3 mb-0">
                                <input type="radio" name="gender" value="female" {{ $academy->gender == 'female'?'checked':'' }}> {{__('validation.attributes.frontend.female')}}
                            </label>
                            <label class="radio-inline mr-3 mb-0">
                                <input type="radio" name="gender" value="other" {{ $academy->gender == 'other'?'checked':'' }}> {{__('validation.attributes.frontend.other')}}
                            </label>
                        </div>
                    </div>

                    <!-- @php
                        $teacherProfile = $academy->teacherProfile?:'';
                        $payment_details = $academy->teacherProfile?json_decode($academy->teacherProfile->payment_details):new stdClass();
                    @endphp -->

                    <div class="form-group row">
                        {{ html()->label(__('labels.academy.facebook_link'))->class('col-md-2 form-control-label')->for('facebook_link') }}

                        <div class="col-md-10">
                            {{ html()->text('facebook_link')
                                            ->class('form-control')
                                            ->value($academy->facebook_link)
                                            ->placeholder(__('labels.academy.facebook_link')) }}
                        </div><!--col-->
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.academy.twitter_link'))->class('col-md-2 form-control-label')->for('twitter_link') }}

                        <div class="col-md-10">
                            {{ html()->text('twitter_link')
                                            ->class('form-control')
                                            ->value($academy->twitter_link)
                                            ->placeholder(__('labels.academy.twitter_link')) }}

                        </div><!--col-->
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.academy.linkedin_link'))->class('col-md-2 form-control-label')->for('linkedin_link') }}

                        <div class="col-md-10">
                            {{ html()->text('linkedin_link')
                                            ->class('form-control')
                                            ->value($academy->linkedin_link)
                                            ->placeholder(__('labels.academy.linkedin_link')) }}
                        </div><!--col-->
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.academy.payment_details'))->class('col-md-2 form-control-label')->for('payment_details') }}
                        <div class="col-md-10">
                            <select class="form-control" name="payment_method" id="payment_method" required>
                                <option value="bank" {{ $academy->payment_method == 'bank'?'selected':'' }}>{{ trans('labels.academy.bank') }}</option>
                                <option value="paypal" {{ $academy->payment_method == 'paypal'?'selected':'' }}>{{ trans('labels.academy.paypal') }}</option>
                            </select>
                        </div>

                    </div>

                    <div class="bank_details" style="display:{{ $academy->academy->payment_method == 'bank'?'':'none' }}">
                        <div class="form-group row">
                            {{ html()->label(__('labels.academy.bank_details.name'))->class('col-md-2 form-control-label')->for('bank_name') }}
                            <div class="col-md-10">
                                {{ html()->text('bank_name')
                                        ->class('form-control')
                                        ->value($payment_details->bank_name)
                                        ->placeholder(__('labels.academy.bank_details.name')) }}
                            </div><!--col-->
                        </div>

                        <div class="form-group row">
                            {{ html()->label(__('labels.academy.bank_details.bank_code'))->class('col-md-2 form-control-label')->for('ifsc_code') }}
                            <div class="col-md-10">
                                {{ html()->text('ifsc_code')
                                        ->class('form-control')
                                        ->value($payment_details->ifsc_code)
                                        ->placeholder(__('labels.academy.bank_details.bank_code')) }}
                            </div><!--col-->
                        </div>

                        <div class="form-group row">
                            {{ html()->label(__('labels.academy.bank_details.account'))->class('col-md-2 form-control-label')->for('account_number') }}
                            <div class="col-md-10">
                                {{ html()->text('account_number')
                                        ->class('form-control')
                                        ->value($payment_details->account_number)
                                        ->placeholder(__('labels.academy.bank_details.account')) }}
                            </div><!--col-->
                        </div>

                        <div class="form-group row">
                            {{ html()->label(__('labels.academy.bank_details.holder_name'))->class('col-md-2 form-control-label')->for('account_name') }}
                            <div class="col-md-10">
                                {{ html()->text('account_name')
                                        ->class('form-control')
                                        ->value($payment_details->account_name)
                                        ->placeholder(__('labels.academy.bank_details.holder_name')) }}
                            </div><!--col-->
                        </div>
                    </div>

                    <div class="paypal_details" style="display:{{ $academy->academy->payment_method == 'paypal'?'':'none' }}">
                        <div class="form-group row">
                            {{ html()->label(__('labels.academy.paypal_email'))->class('col-md-2 form-control-label')->for('paypal_email') }}
                            <div class="col-md-10">
                                {{ html()->text('paypal_email')
                                        ->class('form-control')
                                        ->value($payment_details->paypal_email)
                                        ->placeholder(__('labels.academy.paypal_email')) }}
                            </div><!--col-->
                        </div>
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.academy.description'))->class('col-md-2 form-control-label')->for('description') }}

                        <div class="col-md-10">
                            {{ html()->textarea('description')
                                    ->class('form-control')
                                    ->value($teacherProfile->description)
                                    ->placeholder(__('labels.academy.description')) }}
                        </div><!--col-->
                    </div>

                    <div class="form-group row">
                        {{ html()->label(__('labels.backend.academies.fields.status'))->class('col-md-2 form-control-label')->for('active') }}
                        <div class="col-md-10">
                            {{ html()->label(html()->checkbox('')->name('active')
                                        ->checked(($academy->active == 1) ? true : false)->class('switch-input')->value(($academy->active == 1) ? 1 : 0)

                                    . '<span class="switch-label"></span><span class="switch-handle"></span>')
                                ->class('switch switch-lg switch-3d switch-primary')
                            }}
                        </div>

                    </div>


                    <div class="form-group row justify-content-center">
                        <div class="col-4">
                            {{ form_cancel(route('admin.academies.index'), __('buttons.general.cancel')) }}
                            {{ form_submit(__('buttons.general.crud.update')) }}
                        </div>
                    </div><!--col-->
                </div>
            </div>
        </div>

    </div>
    {{ html()->closeModelForm() }}
@endsection
@push('after-scripts')
    <script>
        $(document).on('change', '#payment_method', function(){
            if($(this).val() === 'bank'){
                $('.paypal_details').hide();
                $('.bank_details').show();
            }else{
                $('.paypal_details').show();
                $('.bank_details').hide();
            }
        });
    </script>
@endpush