@extends('backend.layouts.app')

@section('title', __('labels.backend.reports.sales_report').' | '.app_name())

@push('after-styles')
@endpush

@section('content')
<div class="title my-3 mx-5">        
        <h1 class="page-title d-inline mb-5">@lang('labels.backend.reports.sales_report')</h1>
</div>
    <div class="">
       
        <div class="card shadow-lg p-4 mb-5 bg-white rounded">
            <div class="row my-5 mx-3">
                <div class="col-12 col-lg-6">
                    <div class="card  bg-light  shadow-lg">
                        <div class="card-body row p-4">
                        <div class="col-9 ">
                            <h4  class="text-primary">@lang('labels.backend.reports.total_earnings')</h4>
                            <h1>{{$appCurrency['symbol'].' '.$total_earnings}}</h1>

                            </div>
                            <div class="col-2   border rounded-circle text-white bg-primary text-center  py-2">
                                        <span class="icon">
                                        <i class="fas fa-comment-dollar"></i>                                        </span>
                                
                                    </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-6 ">
                    <div class="card  bg-light  shadow-lg">
                        <div class="card-body row p-4">
                            <div class="col-9 ">

                               
                                <h4 class="text-pink">@lang('labels.backend.reports.total_sales')</h4>
                                <h1  >{{$total_sales}}</h1>
                            </div>
                            <div class="col-2    border rounded-circle text-white bg-pink    text-center  py-2">
                                        <span class="icon">
                                        <i class="fas fa-chart-line"></i>                                        </span>
                                
                                    </div>
                        </div>
                    </div>
                </div>
            </div>
           <div>   
                <h3 class=" mx-3 mb-5 text-primary">@lang('labels.backend.reports.courses')</h3>
           </div>
            <div class="row card  bg-light  shadow-lg p-4 mx-2  mb-5">
                <div class="col-12 ">
                    <div class="table-responsive">
                        <table id="myCourseTable" class="border-0">
                            <thead class="thead">
                            <tr>
                                <th>@lang('labels.general.sr_no')</th>
                                <th>@lang('labels.backend.reports.fields.name')</th>
                                <th>@lang('labels.backend.reports.fields.orders')</th>
                                <th>@lang('labels.backend.reports.fields.earnings') <span style="font-weight: lighter">(in {{$appCurrency['symbol']}})</span></th>
                            </tr>
                            </thead>

                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <hr>
            <div>                   
             <h3 class="mx-3 mb-2 text-primary">@lang('labels.backend.reports.bundles')</h3>
            </div>
            <div class="row my-5 card  bg-light  shadow-lg p-4 mx-2">
                <div class="col-12">
                    <div class="table-responsive">
                        <table id="myBundleTable" class="border-0">
                            <thead class="thead">
                            <tr>
                                <th>@lang('labels.general.sr_no')</th>
                                <th>@lang('labels.backend.reports.fields.name')</th>
                                <th>@lang('labels.backend.reports.fields.orders')</th>
                                <th>@lang('labels.backend.reports.fields.earnings') <span style="font-weight: lighter">(in {{$appCurrency['symbol']}})</span></th>
                            </tr>
                            </thead>

                            <tbody>
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
            var course_route = '{{route('admin.reports.get_course_data')}}';
            var bundle_route = '{{route('admin.reports.get_bundle_data')}}';

            $('#myCourseTable').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                retrieve: true,
                dom: 'lfBrtip<"actions">',
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':visible',
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible',
                        }
                    },
                    'colvis'
                ],
                ajax: course_route,
                columns: [
                    {data: "DT_RowIndex", name: 'DT_RowIndex', width: '8%'},
                    {data: "course", name: 'course'},
                    {data: "orders", name: 'orders'},
                    {data: "earnings", name: 'earnings'},
                ],


                createdRow: function (row, data, dataIndex) {
                    console.log(data)
                    $(row).attr('data-entry-id', data.id);
                },
            });

            $('#myBundleTable').DataTable({
                processing: true,
                serverSide: true,
                iDisplayLength: 10,
                retrieve: true,
                dom: 'lfBrtip<"actions">',
                buttons: [
                    {
                        extend: 'csv',
                        exportOptions: {
                            columns: ':visible',
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible',
                        }
                    },
                    'colvis'
                ],
                ajax: bundle_route,
                columns: [
                    {data: "DT_RowIndex", name: 'DT_RowIndex', width: '8%'},
                    {data: "name", name: 'name'},
                    {data: "orders", name: 'orders'},
                    {data: "earnings", name: 'earnings'},
                ],
                language:{
                    url : "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/{{$locale_full_name}}.json",
                    buttons :{
                        colvis : '{{trans("datatable.colvis")}}',
                        pdf : '{{trans("datatable.pdf")}}',
                        csv : '{{trans("datatable.csv")}}',
                    }
                },


                createdRow: function (row, data, dataIndex) {
                    $(row).attr('data-entry-id', data.id);
                },
            });
        });

    </script>

@endpush