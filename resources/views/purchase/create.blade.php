@extends('layouts.default')

@section('title', 'Purchase')

@section('sidebar')

    @parent

    @section('pagetitle', 'Add Stock')

    @section('content')
        <div class="card mb-3 shadow no-b r-0">
            <div class="card-header white">
                <h6>Stock</h6>
            </div>

            <purchase-create :suppliers="{{ $suppliers }}"></purchase-create>

        </div>
    @stop

@stop

@push('footer_top_js')
    <script src="{{ mix('js/app.js') }}"></script>
@endpush
