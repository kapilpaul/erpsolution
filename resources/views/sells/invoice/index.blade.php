@extends('layouts.default')

@section('title', 'Invoices')

@push('header_top_css')
    <link rel="stylesheet" href="{{ asset('assets/css/remodal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/remodal-default-theme.css') }}">
@endpush

@section('sidebar')

    @parent

    @section('pagetitle', 'Invoices')

    @section('content')
        <invoice-index></invoice-index>
    @stop

@endsection

@push('footer_top_js')
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ asset('assets/js/remodal.min.js') }}"></script>
@endpush
