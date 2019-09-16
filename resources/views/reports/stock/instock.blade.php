@extends('layouts.default')

@section('title', 'In Stock')

@section('sidebar')

    @parent

@section('pagetitle', 'In Stock')

    @section('content')
        <product-in-stock></product-in-stock>
    @stop

@endsection

@push('footer_top_js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush
