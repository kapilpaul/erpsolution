@extends('layouts.default')

@section('title', 'Account')

@section('sidebar')

    @parent

@section('pagetitle', 'Account')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="text-center my-auto mb-20">
                <div class="card card-block d-flex" style="height: 157px">
                    <div class="card-body align-items-center d-flex justify-content-center">
                        <div>
                            <h5 class="card-title mb-0">Account Name : {{ $account->name }}</h5>
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
                                    <span class="">{{ $totalPayment }}</span>
                                </span>
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
                                <th style="text-align: center">Debit</th>
                                <th style="text-align: center">Credit</th>
                                <th style="text-align: center">Action</th>
                            </tr>
                            @foreach($transactions as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="text-align: center">{{ $item->date }}</td>
                                <td style="text-align: center">{{ $item->transaction_no }}</td>

                                <td style="text-align: center">{{ $item->tmode }}</td>
                                <td style="text-align: center">{{ $item->debit }}</td>
                                <td style="text-align: center">{{ $item->credit }}</td>
                                <td style="text-align: center">
                                    <a href="{{ route('transaction.destroy', $item->id) }}"><i class="icon-close2 text-danger-o text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach

                            <tr class="no-b">
                                <th></th>
                                <th style="text-align: center"></th>
                                <th style="text-align: center"></th>
                                <th style="text-align: center"></th>
                                <th style="text-align: center">Total Amount</th>
                                <th style="text-align: center">{{ $totalPayment }}</th>
                                <th style="text-align: center"></th>
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
