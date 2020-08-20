@extends('frontend.layouts.app')
@push('after-styles')
    <style>
        .iconss {
            font-size: 90px;
        }

        .b-not_found {
            padding-bottom: 100px;
            padding-top: 50px;
        }

        .b-not_found .b-page_header {
            border-bottom: 0;
            padding-bottom: 0;
            margin: 0;
            margin-bottom: 10px;
            position: relative;
            overflow: hidden;
        }

        .b-not_found .b-page_header::before {
            content: "404";
            top: 0;
            width: 100%;
            text-align: center;
            left: 0;
            position: absolute;
            color: rgba(142, 142, 142, 0.15);
            font-size: 400px;
            line-height: 320px;
            font-weight: 700;
        }

        .b-not_found .b-page_header h1 {
            margin: auto;
            padding: 115px 0;
            text-align: center;
            text-transform: uppercase;
            opacity: .8;
            letter-spacing: 3px;
            font-weight: 700;
            color: black;
        }

        h1 {
            color: black;
            font-size: 70px;


        }

        .b-not_found h2 {
            font-size: 36px;
            letter-spacing: 1px;
            line-height: 1.5;
            color: #1B1919;
            font-weight: bold;
        }

        .b-not_found p {
            line-height: 1.7;
            color: #8E8E8E;
            margin-bottom: 20px;
        }

        .b-not_found .b-searchform {
            max-width: 350px;
            margin: auto;
            position: relative;
        }

        .b-not_found .b-searchform input {
            width: 100%;
            height: 40px;
            position: relative;
            padding-right: 105px;
            border: 1px solid rgba(129, 129, 129, 0.25);
            font-size: 14px;
            line-height: 18px;
            padding: 0 10px;
            transition: border-color .5s;
            box-shadow: none;
            border-radius: 0;
        }

        .b-not_found .b-searchform .btn {
            cursor: pointer;
            background-color: #1daaa3;
            color: #fff;
            position: absolute;
            right: 0;
            top: 0;
        }

        .b-not_found .b-searchform .btn:hover {
            opacity: 0.75;
        }

        .categ {
            text-align: center;
            padding: 3%;
        }

        .categ:hover {
            /* background-color: linear-gradient(98deg, #52ADE1 0%, #625CA8 50%, #D2498B 100%); */
            background: -webkit-linear-gradient(to bottom right, #52ADE1 0%, #625CA8 50%, #D2498B 100%);
            background: -moz-linear-gradient(to bottom right, #52ADE1 0%, #625CA8 50%, #D2498B 100%);
            background: -o-linear-gradient(to bottom right, #52ADE1 0%, #625CA8 50%, #D2498B 100%);
            background: linear-gradient(to bottom right, #52ADE1 0%, #625CA8 50%, #D2498B 100%);

            -webkit-transition: background 1s ease-out;
            -moz-transition: background 1s ease-out;
            -o-transition: background 1s ease-out;
            transition: background 1s ease-out;
            color: white;
            border-radius: 5px;


        }

        @media (max-width: 990px) {
            .b-not_found .b-page_header::before {
                font-size: 300px;
            }

            .b-not_found h2 {
                font-size: 28px;
            }
        }

        @media (max-width: 767px) {
            .b-not_found .b-page_header h1 {
                font-size: 35px;
                padding: 55px 0;

            }

            .b-not_found .b-page_header::before {
                font-size: 150px;
                line-height: 150px;
            }

            .b-not_found h2 {
                font-size: 22px;
            }

            .b-not_found .b-searchform {
                max-width: 300px;
            }
        }
    </style>
@endpush

@section('content')

    <section id="about-page" class="about-page-section pb-0">
        <div class="container">
            <div class="row">
                <div class="b-not_found w-100">
                    <div class="text-center">
                        <h1><b>@lang('http.404.title')</b></h1>

                        <h4><b>@lang('http.404.description')</b></h4>
                        <p>
                            @lang('http.404.description2')
                        </p>
                        <div>
                            <a href="{{url('/')}}"><img src="{{url('img/frontend/user/404.svg')}}"/></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                @if(count($categories) > 0)
                    @foreach($categories as $categ)
                        <div class="col-3 categ">
                            <a href="{{url('/')}}">
                                <span class="iconss"><i class="{{$categ->icon}}"></i></span>
                                <h3>{{$categ->name}}</h3>
                            </a>
                        </div>
                    @endforeach
                @endif


            </div>

        </div>
    </section>    <!-- Start of footer section

@endsection
