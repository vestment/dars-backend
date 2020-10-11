@extends('backend.layouts.app')
@section('content')
<script>
    var lang = '{{app()->getLocale()}}';
</script>

<!-- @if (auth()->check())
<div class="svg-embedded" style="display: none">{{auth()->user()->full_name}} - {{auth()->user()->id}}</div>
@endif -->

<section id="course-details" class="course-details-section">
    <div class="container-fluid">
        <div class="main-content">
            <div id="app" class="content">

            </div>
        </div>
    </div>
</section>
@endsection

@push('before-scripts')
    <script src="{{ asset(mix('js/manifest.js')) }}"></script>
    <script src="{{ asset(mix('js/vendor.js')) }}"></script>

    <script src="{{ asset(mix('js/frontend.js')) }}"></script>
@endpush
