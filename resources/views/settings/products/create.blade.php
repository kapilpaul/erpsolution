@extends('layouts.default')

@section('title', 'Product')

@section('sidebar')

    @parent

    @section('pagetitle', 'Add Product')

    @section('content')
        <div class="card mb-3 shadow no-b r-0">
            <div class="card-header white">
                <h6>Product</h6>
            </div>

            <product-create :categories="{{ $categories }}"></product-create>

        </div>
    @stop

@endsection

@push('footer_top_js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush