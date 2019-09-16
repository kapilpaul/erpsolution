@extends('layouts.default')

@section('title', 'Suppliers')

@push('header_top_css')
    <link rel="stylesheet" href="{{ asset('assets/css/remodal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/remodal-default-theme.css') }}">
@endpush

@section('sidebar')

    @parent

    @section('pagetitle', 'Suppliers')

    @section('content')
        <supplier-index></supplier-index>
    @stop

@endsection

@push('footer_top_js')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="{{ asset('assets/js/remodal.min.js') }}"></script>
@endpush