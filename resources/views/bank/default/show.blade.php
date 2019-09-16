@extends('layouts.default')

@section('title', 'Bank Ledger')

@push('header_top_css')
    <link rel="stylesheet" href="{{ asset('assets/css/remodal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/remodal-default-theme.css') }}">
@endpush

@section('sidebar')

    @parent

    @section('pagetitle', 'Bank Ledger')

    @section('content')
        <div class="row">
            <div class="col-md-8">
                <div class="text-center my-auto">
                    <div class="card card-block d-flex" style="height: 151px">
                        <div class="card-body align-items-center d-flex justify-content-center">
                            <div>
                                <h5 class="card-title mb-0">Bank Name : {{ $bank->name }}</h5>
                                <p class="card-text mb-0">Account Name : {{ $bank->ac_name }}</p>
                                <p class="card-text mb-0">Account Number : {{ $bank->ac_no }}</p>
                                <p class="card-text mb-0">Branch : {{ $bank->branch }}</p>
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
                                    <span class="sc-counter">{{ $bank->balance }}</span>
                                </span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <bank-transaction-show :code="'{{ $bank->code }}'"></bank-transaction-show>
    @stop

@endsection

@push('footer_top_js')
    <script src="{{ mix('js/app.js') }}"></script>
    <script src="{{ asset('assets/js/remodal.min.js') }}"></script>
@endpush
