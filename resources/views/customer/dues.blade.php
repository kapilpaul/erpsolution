@extends('layouts.default')

@section('title', 'Customer Dues')

@push('header_top_css')
    <link rel="stylesheet" href="{{ asset('assets/css/remodal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/remodal-default-theme.css') }}">
@endpush

@section('sidebar')

    @parent

    @section('pagetitle', 'Customer Dues')

    @section('content')
        <customer-index :dues=true></customer-index>
    @stop

@endsection

@push('footer_top_js')
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ asset('assets/js/remodal.min.js') }}"></script>
@endpush
