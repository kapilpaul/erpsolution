@extends('layouts.default')

@section('title', 'Invoice')

@section('sidebar')

    @parent

@section('pagetitle', 'Add Invoice')

@section('content')
    <div class="card mb-3 shadow no-b r-0">
        <div class="card-header white">
            <h6>Invoice Edit</h6>
        </div>

        <invoice-edit
                :customers="{{ $customers }}"
                :invoiceno="{{ $id }}"
                :product-items="{{ $productItems }}">
        </invoice-edit>

    </div>
@stop

@endsection

@push('footer_top_js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush