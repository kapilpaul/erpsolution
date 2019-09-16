@extends('layouts.default')

@section('title', 'Stock')

@section('sidebar')

    @parent

@section('pagetitle', 'Stock')

    @section('content')
        <product-stock-index></product-stock-index>
    @stop

@endsection

@push('footer_top_js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush
