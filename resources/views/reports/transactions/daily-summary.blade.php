@extends('layouts.default')

@section('title', 'Daily Summary')

@section('sidebar')

    @parent

@section('pagetitle', 'Daily Summary')

@section('content')

    {!! Form::open(['method' => 'POST', 'route' => 'transaction.dailySummary', 'class' => 'mb-20']) !!}

    <div class="row">
        <div class="col-md-5">
            {!! Form::label('from_date') !!}
            <div class="input-group">
                {!! Form::date('from_date', null, ['id' =>"from_date", 'class' =>"form-control mb-10"]) !!}
            </div>
        </div>
        <div class="col-md-5">
            {!! Form::label('to_date') !!}
            <div class="input-group">
                {!! Form::date('to_date', null, ['id' =>"to_date", 'class' =>"form-control mb-10"]) !!}
            </div>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary" style="margin-top: 27px;">
                <i class="fa fa-search"></i> Search
            </button>
        </div>
    </div>

    {!! Form::close() !!}



    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="sales-tab" data-toggle="tab" href="#sales" role="tab" aria-controls="sales" aria-selected="true">Sales</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="receipt-tab" data-toggle="tab" href="#receipt" role="tab" aria-controls="receipt" aria-selected="false">Receipts</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="expense-tab" data-toggle="tab" href="#expense" role="tab" aria-controls="expense" aria-selected="false">Expenses</a>
        </li>
    </ul>

    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="sales" role="tabpanel" aria-labelledby="sales-tab">
            @include('reports.transactions.sales')
        </div>
        <div class="tab-pane fade" id="receipt" role="tabpanel" aria-labelledby="receipt-tab">
            @include('reports.transactions.receipts')
        </div>
        <div class="tab-pane fade" id="expense" role="tabpanel" aria-labelledby="expense-tab">
            @include('reports.transactions.expenses')
        </div>
    </div>

@stop

@endsection

@push('footer_top_js')
    <script src="{{ mix('js/app.js') }}"></script>
@endpush
