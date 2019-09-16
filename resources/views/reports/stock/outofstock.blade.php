@extends('layouts.default')

@section('title', 'Out Of Stock')

@section('sidebar')

    @parent

@section('pagetitle', 'Out Of Stock')

    @section('content')
        <product-out-of-stock></product-out-of-stock>
    @stop

@endsection

@push('footer_top_js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush
