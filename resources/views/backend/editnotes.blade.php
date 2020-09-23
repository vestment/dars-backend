@extends('backend.layouts.app')

@section('title', __('labels.backend.certificates.title').' | '.app_name())

@section('content')
@push('after-styles')
<link href="{{asset('froala_editor_3.2.1/css/froala_style.min.css')}}" rel="stylesheet">
    <link href="{{asset('froala_editor_3.2.1/css/froala_editor.pkgd.min.css')}}" rel="stylesheet">
    <link href="{{asset('froala_editor_3.2.1/css/plugins.pkgd.min.css')}}" rel="stylesheet">
<style>
.action{
    font-size:20px;
    text-align:center;

}
a[href="https://www.froala.com/wysiwyg-editor?k=u"] {
    display:none !important;
}
</style>
@endpush
                               
<div class="container my-5">
                                    <h2 class="m-3">edit Your Notes</h2>
                                    <div class="row">
                                        <div class="col-12">

                                            <form action="{{route('admin.notes.update',['note'=>$notes])}}" method="POST">
                                                @csrf
                                                @Method('PUT')
                                                <textarea class='edit-froala' name="contentText"
                                                          style="margin-top: 30px;">
                                                          {{$notes->contentText}}
                                                
                                                </textarea>

                                                <button type="submit" class=" float-right btn btn-success my-5">
                                                    save
                                                </button>

                                            </form>
                                        </div>
                                    </div>
                                </div>

@stop

@push('after-scripts')
<script src="{{asset('froala_editor_3.2.1/js/froala_editor.min.js')}}"></script>
    <script src="{{asset('froala_editor_3.2.1/js/froala_editor.pkgd.min.js')}}"></script>
    <script src="{{asset('froala_editor_3.2.1/js/plugins.pkgd.min.js')}}"></script>

<script>
        $(document).ready(function () {
            new FroalaEditor(".edit-froala", {
                enter: FroalaEditor.ENTER_BR,
                fileUpload: false,
                fileInsertButtons: [],
                imageUpload: false
            }, function () {
                // Call the method inside the initialized event.
                $('#insertFile-1').remove();
                $('#insertFiles-1').remove();

                $('#insertLink-1').remove();
                $('#insertImage-1').remove();
                $('#insertVideo-1').remove();
                $('#getPDF-1').remove();
                $('#print-1').remove();
                $('#insertFile-2').remove();
                $('#insertFiles-2').remove();

                $('#insertLink-2').remove();
                $('#insertImage-2').remove();
                $('#insertVideo-2').remove();
                $('#getPDF-2').remove();
                $('#print-2').remove();
                $('#logo').remove();

                $('a[href="https://www.froala.com/wysiwyg-editor?k=u"]').parent().remove()
            })
        })

    </script>


@endpush