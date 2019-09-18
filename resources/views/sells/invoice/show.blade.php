@extends('layouts.default')

@section('title', 'Invoice')

@section('sidebar')

    @parent

@section('pagetitle', 'Add Invoice')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="text-center my-auto">
                <div class="card card-block d-flex" style="height: 220px">
                    <div class="card-body align-items-center d-flex justify-content-center">
                        <div>
                            <h5 class="card-title mb-0">Customer Name : {{ $invoice->customer->name }}</h5>
                            <p class="card-text mb-0">Invoice No : {{ $invoice->invoice_no }}</p>
                            <p class="card-text mb-0">Vehicle No : {{ $invoice->vehicle_no }}</p>
                            <p class="card-text mb-0">Destination : {{ $invoice->destination }}</p>
                            <p class="card-text mb-0">{{ $invoice->details }}</p>
                            <p class="card-text mb-0">{{ $invoice->date }}</p>
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
                                    <span class="sc-counter">{{ $invoice->grand_total }}</span>
                                </span>
                            <div class="d-flex justify-content-between pt-2">
                                <div>
                                    <p class="mb-0">
                                        <i class="icon-circle-o text-red mr-2"></i>Product Type</p>
                                    <p class="mb-0">
                                        <i class="icon-circle-o text-green mr-2"></i>Total Items</p>
                                </div>
                                <div>
                                    <p class="sc-counter mb-0">
                                        <i class="icon-angle-double-down text-red mr-2"></i>{{ count($invoice->products) }}</p>
                                    <p class="sc-counter mb-0">
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
                                <th>Discount</th>
                                <th>Total</th>
                            </tr>
                            @foreach($invoice->products as $item)
                            <tr class="text-black">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="#">{{ $item->name }} - {{ $item->model }}</a>
                                </td>
                                <td>{{ $item->pivot->quantity }}</td>
                                <td>
                                    {{ $item->pivot->price }}
                                </td>
                                <td> {{ $item->pivot->discount }}
                                    {{--{{ number_format((($item->pivot->discount / $item->pivot->price) * 100), 2, '.', '')--}}
                                    {{--}} %--}}
                                </td>
                                <td>
                                    {{ $item->pivot->total }}
                                </td>
                            </tr>
                            @endforeach

                            <tr class="text-light-black">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total Amount</th>
                                <th>{{ $invoice->total_amount }}</th>
                            </tr>

                            <tr class="text-light-black  no-b">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total Discount</th>
                                <th>{{ $invoice->total_discount }}</th>
                            </tr>

                            <tr class="text-light-black  no-b">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Grand Total</th>
                                <th>{{ $invoice->grand_total }}</th>
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
