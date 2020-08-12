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
        .margin_left{
            margin-left:50px !important
        }


    </style>
@section('content')
<div class="title my-3 mx-5">   
             <h1 class="page-title d-inline mb-5">@lang('labels.backend.courses.content')</h1>
</div>
    <div class="card shadow-lg p-3 mb-5 bg-white rounded">
        <div class="">
           
        </div>
        <div class="card-body">
           
             <div class="card-body">
            @if(count($chapterContent) > 0)
                <div class="row justify-content-center">
                    <div class="col-6  ">
                        <!-- <h4 class="">@lang('labels.backend.hero_slider.sequence_note')</h4> -->
                        <ul class="sorter d-inline-block">
                            @foreach($chapterContent as $item)
                            @foreach ($timeline as  $singleTimeline)
                            @if($singleTimeline->model_id == $item->id)
                                <li  class="@if ($singleTimeline->model_type != 'App\Models\Chapter') margin_left @endif"  >
                            <span data-id="{{$item->id}}" data-sequence="{{$singleTimeline->sequence}}">

                                <p  class="title d-inline ml-2">{{$item->title}} {{$singleTimeline->sequence}}</p>
                           </span>

                                </li>
                                @endif

                            @endforeach
                            @endforeach

                        </ul>
                        <a href="{{ route('admin.courses.index') }}"
                           class="btn btn-default border float-left">@lang('strings.backend.general.app_back_to_list')</a>

                        <a href="#" id="save_timeline"
                           class="btn btn-primary float-right">@lang('labels.backend.hero_slider.save_sequence')</a>

                    </div>

                </div>
            @endif
        </div>

        </div>
    </div>

@stop

@push('after-scripts')
<script src="{{asset('plugins/amigo-sorter/js/amigo-sorter.min.js')}}"></script>

    <script>

       


        $('ul.sorter').amigoSorter({
            li_helper: "li_helper",
            li_empty: "empty",
        });
        $(document).on('click', '#save_timeline', function (e) {
            e.preventDefault();
            var list = [];
            $('ul.sorter li').each(function (key, value) {
                key++;
                var val = $(value).find('span').data('id');
                list.push({id: val, sequence: key});
            });

            $.ajax({
                method: 'POST',
                url: "{{route('admin.courses.saveSequence')}}",
                data: {
                    _token: '{{csrf_token()}}',
                    list: list
                }
            }).done(function () {
                location.reload();
            });
        })
        

        $(document).on('click', '.switch-input', function (e) {
            var id = $(this).data('id');
            $.ajax({
                type: "POST",
                url: "{{ route('admin.sliders.status') }}",
                data: {
                    _token:'{{ csrf_token() }}',
                    id: id,
                },
            }).done(function() {
                location.reload();
            });
        })
    </script>
@endpush
