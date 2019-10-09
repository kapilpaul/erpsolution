@extends('layouts.default')

@section('title', 'Customer')

@section('sidebar')

    @parent

@section('pagetitle', 'Customer')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="text-center my-auto mb-20">
                <div class="card card-block d-flex" style="height: 253px">
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
                        <div class="p-30">
                            <h5 class="font-weight-normal s-14">Balance</h5>
                            <span class="s-48 font-weight-lighter text-primary">
                                    <span class="">{{ $customer->balance }}</span>
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
                                <th style="text-align: center">Date</th>
                                <th style="text-align: center">Transaction No</th>
                                <th style="text-align: center">Transaction Type</th>
                                <th style="text-align: center">Invoice No</th>
                                <th style="text-align: center">Debit (Received)</th>
                                <th style="text-align: center">Credit (Sell)</th>
                                <th style="text-align: center">Balance</th>
                            </tr>
                            @foreach($transactions as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="text-align: center">{{ $item->date }}</td>
                                <td style="text-align: center">{{ $item->transaction_no }}</td>

                                <td style="text-align: center">{{ $item->tmode }}</td>
                                <td style="text-align: center">
                                    @if($item->other_transaction_no)
                                    <a href="{{ route('invoice.show', $item->other_transaction_no) }}">{{ $item->other_transaction_no }}</a>
                                    @endif
                                </td>
                                <td style="text-align: center">{{ $item->debit }}</td>
                                <td style="text-align: center">{{ $item->credit }}</td>
                                <td style="text-align: center">{{ $item->balance }}</td>
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
