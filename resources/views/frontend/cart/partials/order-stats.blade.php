<div class="purchase-list row">
    <div class="col-lg-3 col-sm-12">
        <span class="in-total ">@lang('labels.frontend.cart.total_price')</span>
        <small class="text-muted">
            ({{Cart::getContent()->count()}}{{(Cart::getContent()->count() > 1) ? ' '.trans('labels.frontend.cart.items') : ' '.trans('labels.frontend.cart.item')}})

        </small>
        <p>@if(isset($total))
            {{$total}}EGP
            @endif</p>
    </div>

    <div class="input-group col-lg-3 col-sm-12">
        <input type="text" id="coupon" pattern="[^\s]+" name="coupon"
        class="form-control " placeholder="Enter Coupon">
        <div class="input-group-append">
                <button class="btn btn-dark shadow-none " id="applyCoupon"
                        type="button">
                    @lang('labels.frontend.cart.apply')
                </button>
        </div>
    </div>
    
   

    <div class="col-lg-6 col-sm-12">
        @if(Cart::getConditionsByType('coupon') != null)
        @foreach(Cart::getConditionsByType('coupon') as $condition)

                <i style="cursor: pointer" id="removeCoupon" class="fa text-danger fa-times-circle"></i>
            </div>
        @endforeach
    @endif
    @if($taxData != null)
        @foreach($taxData as $tax)
            {{-- <div class="in-total font-weight-normal  mb-3"> {{ $tax['name']}}
                <span class="font-weight-bold">{{ $appCurrency['symbol'].' '.number_format($tax['amount'],2)}}</span>
            </div> --}}
        @endforeach
    @endif
    <div class="in-total ">
        <span>
                {{number_format(Cart::session(auth()->user()->id)->getTotal(),2)}} EGP
        </span>
    </div>
    
   
   
</div>

{{--<div class="col-2">--}}
{{--        <button class="btn bt-pay shadow-none text-center w-100" id="applyCoupon"--}}
{{--        type="button">--}}
{{--    @lang('labels.frontend.cart.pay')--}}
{{--</button>--}}
{{--</div>--}}
<p class="d-none" id="coupon-error"></p>   
</div>
