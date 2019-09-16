@extends('layouts.default')

@section('title', 'Supplier')

@section('sidebar')

    @parent

@section('pagetitle', 'Supplier')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="text-center my-auto">
                <div class="card card-block d-flex" style="height: 252px">
                    <div class="card-body align-items-center d-flex justify-content-center">
                        <div>
                            <h5 class="card-title mb-0">Supplier Name : {{ $supplier->name }}</h5>
                            <p class="card-text mb-0">Mobile No : {{ $supplier->mobile }}</p>
                            <p class="card-text mb-0">Address : {{ $supplier->address }}</p>
                            <p class="card-text mb-0">{{ $supplier->details }}</p>
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
                            <h5 class="font-weight-normal s-14">Balance</h5>
                            <span class="s-48 font-weight-lighter text-primary">
                                    <span class="sc-counter">{{ $supplier->balance }}</span>
                                </span>
                            <div class="d-flex justify-content-between pt-2">
                                <div>
                                    <p class="mb-0">
                                        <i class="icon-circle-o text-red mr-2"></i>Total Purchase</p>
                                    <p class="mb-0">
                                        <i class="icon-circle-o text-green mr-2"></i>Total Paid</p>
                                    <p class="mb-0">
                                        <i class="icon-circle-o text-green mr-2"></i>Due</p>
                                </div>
                                <div>
                                    <p class="mb-0">{{ $totalPurchase }}</p>
                                    <p class="mb-0">{{ $totalPaid }}</p>
                                    <p class="mb-0">{{ $totalPurchase - $totalPaid }}</p>
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
                    <strong> Ledger </strong>
                </div>
                <div class="card-body p-0 bg-light">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <!-- Table heading -->
                            <tbody>
                            <tr class="no-b">
                                <th>Sl.</th>
                                <th>Date</th>
                                <th>Invoice No</th>
                                <th>Purchase No</th>
                                <th>Details</th>
                                <th>Total Amount</th>
                            </tr>
                            @foreach($supplier->purchases as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->purchase_date }}</td>
                                <td>
                                    <a href="{{ route('purchase.show', $item->purchase_no) }}">{{ $item->invoice_no }}</a>
                                </td>

                                <td><a href="{{ route('purchase.show', $item->purchase_no) }}">{{ $item->purchase_no }}</a></td>
                                <!-- Status -->
                                <td>
                                    {{ $item->details }}
                                </td>
                                <!-- Sort -->
                                <td>
                                    {{ $item->total_amount }}
                                </td>
                            </tr>
                            @endforeach

                            <tr class="">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total Amount</th>
                                <th>{{ $totalPurchase }}</th>
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
