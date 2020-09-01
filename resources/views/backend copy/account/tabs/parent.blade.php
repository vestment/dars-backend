<div class="d-inline-block form-group w-100">
    <h4 class="mb-0">@lang('labels.backend.parent.title')
        <button onclick="$('.parent-form').toggle(500),$('.parent-form input').focus()" class="btn btn-dark float-right"><i class="fa fa-plus"></i> @lang('labels.backend.parent.create')</button>
    </h4>

</div>

<form class="parent-form form-row p-2" style="display: none;" method="post" action="{{route('admin.parent.store')}}">
    @csrf
    <label for="parent-email">Parent Email</label>
    <input type="email" id="parent-email" required name="parentEmail" class="form-control" placeholder="parentEmail@domain.com">
    <button type="submit" class="btn btn-primary mt-2">Send request</button>
</form>
<div class="table-responsive">
    <table class="table table-striped table-hover table-bordered align-items-center">
        <thead>
        <tr>
            <td>@lang('labels.backend.sign_up.user_name')</td>
            <td>@lang('labels.frontend.user.profile.avatar')</td>
            <td>@lang('labels.backend.dashboard.email')</td>
            <td>@lang('labels.backend.general_settings.api_clients.fields.action')</td>
        </tr>
        </thead>
        <tbody>
        @foreach($user->parents as $parent)
            @if($parent->pivot->status == 1)
            <tr>
                <td>{{$parent->full_name}}</td>
                <td><img src="{{ $parent->picture }}" height="64" width="64" class="user-profile-image img-fluid img-thumbnail "/></td>
                <td>{{$parent->email}}</td>
                <td>
                    <button data-trans-button-cancel="{{__('buttons.general.cancel')}}"
                            data-trans-button-confirm="{{__('buttons.general.crud.delete')}}" data-trans-title="{{__('strings.backend.general.are_you_sure')}}" onclick="removeParent('{{$parent->id}}',this);" class="btn btn-blue mb-1"><i class="icon-trash"></i></button>
                </td>
            </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>
<script>
    function removeParent(id,element) {
        swal({
            title: $(element).data('trans-title'),
            showCancelButton: true,
            confirmButtonText: $(element).data('trans-button-confirm'),
            cancelButtonText: $(element).data('trans-button-cancel'),
            type: 'warning'
        }).then(function (result) {
           if (result) {
               $.ajax({
                   type: "POST",
                   url: "{{ route('admin.parent.remove') }}",
                   data: {
                       _token: '{{ csrf_token() }}',
                       id: id,
                   },
                   success: function (resp) {
                       $(element).parents("tr").fadeOut(500, function () {
                           $(this).remove();
                       });
                   }
               })
           }

        });

    }
</script>
