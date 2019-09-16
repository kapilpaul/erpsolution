@extends('layouts.default')

@section('title', 'Bank Transaction')

@section('sidebar')

    @parent

@section('pagetitle', 'Add Bank Transaction')

@section('content')
    <div class="card mb-3 shadow no-b r-0">
        <div class="card-header white">
            <h6>Bank Transaction</h6>
        </div>

        <bank-transaction-create :banks="{{ $banks }}"></bank-transaction-create>
    </div>
@stop

@endsection

@push('footer_top_js')
    <script src="{{ asset('js/app.js') }}"></script>
@endpush