@extends('layouts.default')

@section('title', 'Purchase')

@section('sidebar')

    @parent

@section('pagetitle', 'Add Purchase')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="text-center my-auto">
                <div class="card card-block d-flex" style="height: 252px">
                    <div class="card-body align-items-center d-flex justify-content-center">
                        <div>
                            {{--<h5 class="card-title mb-0">Supplier Name : <a href="{{ route('suppliers.show', $purchase->supplier->code) }}">{{ $purchase->supplier->name }}</a></h5>--}}
                            <p class="card-text mb-0">Invoice No : {{ $purchase->invoice_no }}</p>
                            <p class="card-text mb-0">{{ $purchase->details }}</p>
                            <p class="card-text mb-0">{{ $purchase->purchase_date }}</p>
                        </div>
                    </div>
                    <div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body p-0">
                    <div class="lighten-2">
                        <div class="pt-5 pb-2 pl-5 pr-5">
                            <h5 class="font-weight-normal s-14">Total Amount</h5>
                            <span class="s-48 font-weight-lighter text-primary">
                                    <span class="sc-counter">{{ $purchase->total_amount }}</span>
                                </span>
                            <div class="d-flex justify-content-between pt-2">
                                <div>
                                    <p>
                                        <i class="icon-circle-o text-red mr-2"></i>Product Type</p>
                                    <p>
                                        <i class="icon-circle-o text-green mr-2"></i>Total Items</p>
                                </div>
                                <div>
                                    <p class="sc-counter">
                                        <i class="icon-angle-double-down text-red mr-2"></i>{{ count($purchase->products) }}</p>
                                    <p class="sc-counter">
                                        <i class="icon-angle-double-up text-green mr-2"></i>{{ $totalItemQuantity }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row pt-5">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header white">
                    <i class="icon-clipboard-edit blue-text"></i>
                    <strong> Products </strong>
                </div>
                <div class="card-body p-0 bg-light">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <!-- Table heading -->
                            <tbody>
                            <tr class="no-b">
                                <th>Sl.</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                            @foreach($purchase->products as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('products.details', $item->code) }}">{{ $item->name }} - {{ $item->model }}</a>
                                </td>
                                <!-- Page size -->
                                <td>{{ $item->pivot->quantity }}</td>
                                <!-- Status -->
                                <td>
                                    {{ $item->pivot->price }}
                                </td>
                                <!-- Sort -->
                                <td>
                                    {{ $item->pivot->total }}
                                </td>
                            </tr>
                            @endforeach

                            <tr class="no-b">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total Amount</th>
                                <th>{{ $purchase->total_amount }}</th>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@endsection
