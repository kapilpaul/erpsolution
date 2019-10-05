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
                                <th>Date</th>
                                <th>Transaction No</th>
                                <th>Transaction Type</th>
                                <th>Debit</th>
                                <th>Credit</th>
                            </tr>
                            @foreach($transactions as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->date }}</td>
                                <td>{{ $item->transaction_no }}</td>

                                <td>{{ $item->tmode }}</td>
                                <td>{{ $item->debit }}</td>
                                <td>{{ $item->credit }}</td>
                            </tr>
                            @endforeach

                            <tr class="no-b">
                                <th></th>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>Total Amount</th>
                                <th>{{ $totalPayment }}</th>
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
