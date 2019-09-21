@extends('layouts.default')

@section('title', 'Dashboard')

@section('sidebar')

    @parent

    @section('pagetitle', 'Dashboard')

    @section('content')

        <div class="row">
            <div class="col-md-12">
                @include('dashboard.parts.top_customers')
            </div>
        </div>

        <div class="row pt-5">
            <div class="col-md-7">
                @include('dashboard.parts.orders')
            </div>
            <div class="col-md-5">
                @include('dashboard.parts.revenue')
            </div>
        </div>

        <div class="row pt-5">
            <div class="col-md-12">
                <h6 class="mb-2">Product</h6>
            </div>
            <div class="col-md-4">
                @include('dashboard.parts.top_sale_products', [
                    'topSalesProducts' => $topSalesProductsLifetime,
                    'name' => 'Lifetime Top Sales Product'
                ])
            </div>
            <div class="col-md-4">
                @include('dashboard.parts.top_sale_products', ['topSalesProducts' => $topSalesProductsLifetime,
                'name' => 'Monthly Top Sales Product'])
            </div>
            <div class="col-md-4">
                @include('dashboard.parts.top_sale_products', ['topSalesProducts' => $topSalesProductsPreviousMonth,
                'name' => 'Last Month Top Sales Product'])
            </div>
        </div>


    @stop

@endsection
