@extends('backend.layouts.app')

@section('title',' Notes | '.app_name())

@section('content')
    @push('after-styles')
        <style>
            .action{
                font-size:20px;
                text-align:center;

            }
        </style>
    @endpush
    <div class="card">
        <div class="card-header">
            <h3 class="page-title ">Notes</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">

                        <table id="myTable"
                               class="table table-bordered table-striped ">
                            <thead>
                            <tr>
                                <th>@lang('labels.general.sr_no')</th>
                                <th>@lang('labels.backend.certificates.fields.course_name')</th>
                                <th>@lang('labels.backend.certificates.fields.action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @if(count($notes) > 0)
                                @foreach($notes as $key=>$note)
                                    @if ($note->lesson && $note->lesson->course)
                                        <tr>
                                            <td>{{$key}}</td>
                                            <td>{{$note->lesson->course->title}} / {{$note->lesson->chapter->title}}</td>
                                            <td class="action ">
                                                <form method="post" action="{{route('admin.notes.destroy',['note'=>$note])}}" class="d-inline">
                                                    @csrf
                                                    @Method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="far fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                                <a class="btn btn-primary" href="{{route('admin.notes.edit',['note'=>$note])}}"><i class="far fa-edit"></i></a></td>

                                        </tr>
                                    @endif
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('after-scripts')
    <script>

        $(document).ready(function () {

            $('#myTable').DataTable({
                dom: 'lfBrtip<"actions">',
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [0, 1, 2]

                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    'colvis'
                ],
                language:{
                    url : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons :{
                        colvis : '{{trans("datatable.colvis")}}',
                        pdf : '{{trans("datatable.pdf")}}',
                        csv : '{{trans("datatable.csv")}}',
                    }
                }

            });
        });

    </script>

@endpush
