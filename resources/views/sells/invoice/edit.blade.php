@extends('layouts.default')

@section('title', 'Invoice')

@section('sidebar')

    @parent

@section('pagetitle', 'Edit Invoice')

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
    <script src="{{ mix('js/app.js') }}"></script>
@endpush
