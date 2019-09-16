@extends('layouts.default')

@section('title', 'Customer')

@section('sidebar')

    @parent

@section('pagetitle', 'Customer')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="text-center my-auto">
                <div class="card card-block d-flex" style="height: 252px">
                    <div class="card-body align-items-center d-flex justify-content-center">
                        <div>
                            <h5 class="card-title mb-0">Customer Name : {{ $customer->name }}</h5>
                            <p class="card-text mb-0">Mobile No : {{ $customer->mobile }}</p>
                            <p class="card-text mb-0">Address : {{ $customer->address }}</p>
                            <p class="card-text mb-0">{{ $customer->details }}</p>
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
                                    <span class="sc-counter">{{ $customer->balance }}</span>
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
                                <th>Transaction No</th>
                                <th>Transaction Type</th>
                                <th>Invoice No</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Balance</th>
                            </tr>
                            @foreach($transactions as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->date }}</td>
                                <td>{{ $item->transaction_no }}</td>

                                <td>{{ $item->tmode }}</td>
                                <td>
                                    @if($item->other_transaction_no)
                                    <a href="{{ route('invoice.show', $item->other_transaction_no) }}">{{ $item->other_transaction_no }}</a>
                                    @endif
                                </td>
                                <td>{{ $item->debit }}</td>
                                <td>{{ $item->credit }}</td>
                                <td>{{ $item->balance }}</td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@endsection
