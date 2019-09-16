@extends('layouts.default')

@section('title', 'Invoice')

@section('sidebar')

    @parent

@section('pagetitle', 'Create Invoice')

@section('content')
    <div class="card mb-3 shadow no-b r-0">
        <div class="card-header white">
            <h6>Invoice</h6>
        </div>

        <invoice-create></invoice-create>

    </div>
@stop

@endsection

@push('footer_top_js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush