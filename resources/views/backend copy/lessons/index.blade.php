@inject('request', 'Illuminate\Http\Request')
@extends('backend.layouts.app')
@section('title', __('labels.backend.lessons.title').' | '.app_name())
<link rel="stylesheet" type="text/css" href="{{asset('plugins/amigo-sorter/css/theme-default.css')}}">
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


    </style>
@section('content')
<div class="title my-3 mx-5">   
             <h1 class="page-title d-inline mb-5">@lang('labels.backend.lessons.title')</h1>
</div>
    <div class="card shadow-lg p-3 mb-5 bg-white rounded">
        <div class="">
            @can('lesson_create')
                <div class="float-right">
                    <a href="{{ route('admin.lessons.create') }}@if(request('chapter_id')){{'?chapter_id='.request('chapter_id')}}@endif"
                       class="btn btn-pink">@lang('strings.backend.general.app_add_new')</a>

                </div>
            @endcan
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-12 col-lg-6 form-group">
                    {!! Form::label('chapter_id', trans('labels.backend.chapters.title'), ['class' => 'control-label']) !!}
                    {!! Form::select('chapter_id', $chapters,  (request('chapter_id')) ? request('chapter_id') : old('chapter_id'), ['class' => 'form-control js-example-placeholder-single select2 ', 'id' => 'chapter_id']) !!}
                </div>
            </div>
            <div class="d-block">
                <ul class="list-inline">
                    <li class="list-inline-item">
                        <a href="{{ route('admin.lessons.index',['chapter_id'=>request('chapter_id')]) }}"
                           style="{{ request('show_deleted') == 1 ? '' : 'font-weight: 700' }}">{{trans('labels.general.all')}}</a>
                    </li>
                    |
                    <li class="list-inline-item">
                        <a href="{{trashUrl(request()) }}"
                           style="{{ request('show_deleted') == 1 ? 'font-weight: 700' : '' }}">{{trans('labels.general.trash')}}</a>
                    </li>
                </ul>
            </div>

            @if(request('chapter_id') != "" || request('show_deleted') != "")
                <div class="table-responsive">

                    <table id="myTable"
                           class="shadow-lg @can('lesson_delete') @if ( request('show_deleted') != 1 ) dt-select @endif @endcan">
                        <thead>
                        <tr>
                            @can('lesson_delete')
                                @if ( request('show_deleted') != 1 )
                                    <th style="text-align:center;"><input class="mass" type="checkbox" id="select-all"/>
                                    </th>@endif
                            @endcan
                            <th>@lang('labels.general.sr_no')</th>
                            <th>@lang('labels.backend.lessons.fields.title')</th>
                            <th>@lang('labels.backend.lessons.fields.published')</th>
                            @if( request('show_deleted') == 1 )
                                <th>@lang('strings.backend.general.actions') &nbsp;</th>
                            @else
                                <th>@lang('strings.backend.general.actions') &nbsp;</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>

                </div>
            @endif

          

        </div>
    </div>

@stop

@push('after-scripts')
<!-- <script src="{{asset('plugins/amigo-sorter/js/amigo-sorter.min.js')}}"></script> -->

    <script>

        $(document).ready(function () {
            var route = '{{route('admin.lessons.get_data')}}';


            @php
                $show_deleted = (request('show_deleted') == 1) ? 1 : 0;
                $chapter_id = (request('chapter_id') != "") ? request('chapter_id') : 0;
            $route = route('admin.lessons.get_data',['show_deleted' => $show_deleted,'chapter_id' => $chapter_id]);
            @endphp

            route = '{{$route}}';
            route = route.replace(/&amp;/g, '&');


            @if(request('chapter_id') != "" || request('show_deleted') != "")

            $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                retrieve: true,
                dom: 'lfBrtip<"actions">',
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: [ 1, 2, 3, 4]
                        }
                    },
                    'colvis'
                ],
                ajax: route,
                columns: [
                        @if(request('show_deleted') != 1)
                    {
                        "data": function (data) {
                            return '<input type="checkbox" class="single" name="id[]" value="' + data.id + '" />';
                        }, "orderable": false, "searchable": false, "name": "id"
                    },
                        @endif
                    {
                        data: "DT_RowIndex", name: 'DT_RowIndex'
                    },
                    {data: "title", name: 'title'},
                    {data: "published", name: "published"},
                    {data: "actions", name: "actions"}
                ],
                @if(request('show_deleted') != 1)
                columnDefs: [
                    {"width": "5%", "targets": 0},
                    {"className": "text-center", "targets": [0]}
                ],
                @endif

                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-entry-id', data.id);
                },
                language:{
                    url : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons :{
                        colvis : '{{trans("datatable.colvis")}}',
                        pdf : '{{trans("datatable.pdf")}}',
                        csv : '{{trans("datatable.csv")}}',
                    }
                }
            });

            @endif

            @can('lesson_delete')
            @if(request('show_deleted') != 1)
            $('.actions').html('<a href="' + '{{ route('admin.lessons.mass_destroy') }}' + '" class="btn btn-xs btn-danger js-delete-selected" style="margin-top:0.755em;margin-left: 20px;">Delete selected</a>');
            @endif
            @endcan


            $(".js-example-placeholder-single").select2({
                placeholder: "{{trans('labels.backend.lessons.select_course')}}",
            });
            var chapter_id = $('#chapter_id').val();
            $('#chapter_id').on('change', function (e) {
                var chapter_id = $('#chapter_id').val();
                console.log(chapter_id)
                window.location.href = "{{route('admin.lessons.index')}}" + "?chapter_id=" + chapter_id
            });
        });


    </script>
@endpush
