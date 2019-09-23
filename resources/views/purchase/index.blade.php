@extends('layouts.default')

@section('title', 'Stock')

@push('header_top_css')
    <link rel="stylesheet" href="{{ asset('assets/css/remodal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/remodal-default-theme.css') }}">
@endpush

@section('sidebar')

    @parent

    @section('pagetitle', 'Stock')

    @section('content')
        <purchase-index></purchase-index>
    @stop

@endsection

@push('footer_top_js')
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ asset('assets/js/remodal.min.js') }}"></script>
@endpush
