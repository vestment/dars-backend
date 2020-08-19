@if(isset($order) && $order == 'accept')
    <a data-method="post" data-trans-button-cancel="{{__('buttons.general.cancel')}}"
       data-trans-button-confirm="{{__('buttons.general.save')}}"
       data-trans-title="{{__('strings.backend.general.are_you_sure')}}"
       class="btn btn-success text-white mb-1" style="cursor:pointer;"
       onclick="$(this).find('form').submit();">
        <i class="fa fa-check"
           data-toggle="tooltip"
           data-placement="top" title="Become parent for this student"
           data-original-title="{{__('buttons.general.save')}}"></i>
        <form action="{{$route}}"
              method="POST" name="save_item" style="display:none">
            @csrf
            {{method_field('post')}}
        </form>
    </a>
@else
    <a data-method="post" data-trans-button-cancel="{{__('buttons.general.cancel')}}"
       data-trans-button-confirm="{{__('buttons.general.save')}}"
       data-trans-title="{{__('strings.backend.general.are_you_sure')}}"
       class="btn btn-danger text-white mb-1" style="cursor:pointer;"
       onclick="$(this).find('form').submit();">
        <i class="fa fa-times"
           data-toggle="tooltip"
           data-placement="top" title="Decline student invitation"
           data-original-title="{{__('buttons.general.save')}}"></i>
        <form action="{{$route}}"
              method="POST" name="decline_item" style="display:none">
            @csrf
            {{method_field('post')}}
        </form>
    </a>
@endif
