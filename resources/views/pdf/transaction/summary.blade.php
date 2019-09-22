<!DOCTYPE html>
<html>
<head>
    <title>Summary Of {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }}</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700" rel="stylesheet">

    @include('pdf.transaction.style')
</head>
<body>

<div class="header text-center fix">
    <h3 class="">Summary Of {{ \Carbon\Carbon::parse($fromDate)->format('d M Y') }}</h3>
</div>

<h3 class="nametitle">Sales</h3>
<table class="table table-striped mb-30">
    <tbody>
    <tr>
        <th style="width: 20px">#</th>
        <th style="width: 150px">Customer Name</th>
        <th style="width: 100px">Invoice No</th>
        <th style="width: 100px">Vehicle No</th>
        <th style="width: 100px">Destination</th>
        <th style="width: 100px;text-align: center;">Amount</th>
        <th style="width: 110px;text-align: center;">Customer Total Due</th>
    </tr>

    @if(isset($sales))
    @foreach($sales as $sale)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $sale->customer->name }}</td>
            <td>{{ $sale->invoice_no }}</td>
            <td>{{ $sale->vehicle_no }}</td>
            <td>{{ $sale->destination }}</td>
            <td style="width: 100px; text-align: center;">{{ $sale->grand_total }}</td>
            <td style="width: 110px; text-align: center;">{{ $sale->customer->balance }}</td>
        </tr>
    @endforeach
    @endif

    @if(isset($totalSales))
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Grand Total</th>
            <th style="width: 90px; text-align: center;font-weight: 600;font-size: 14px;">{{ $totalSales }}</th>
            <th></th>
        </tr>
    @endif
    </tbody>
</table>

<h3 class="nametitle">Receipts</h3>
<table class="table table-striped mb-30">
    <tbody>
    <tr>
        <th style="width: 20px">#</th>
        <th style="width: 150px">Customer Name</th>
        <th style="width: 130px">Invoice No</th>
        <th style="width: 40px">Payment Mode</th>
        <th style="width: 170px; text-align: center;">Amount</th>
        <th style="width: 130px; text-align: center;">Customer Total Due</th>
    </tr>
    @if(isset($receipts))
    @foreach($receipts as $receipt)
        <tr>
            <td style="width: 20px">{{ $loop->iteration }}</td>
            <td style="width: 150px">{{ $receipt->customer->name }}</td>
            <td style="width: 130px">{{ $receipt->other_transaction_no }}</td>
            <td style="width: 40px">{{ ucfirst($receipt->tmode) }}</td>
            <td style="width: 170px; text-align: center;">{{ $receipt->debit }}</td>
            <td style="width: 130px; text-align: center;">{{ $receipt->customer->balance }}</td>
        </tr>
    @endforeach
    @endif

    @if(isset($totalReceived))
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th>Grand Total</th>
            <th style="width: 90px; text-align: center;font-weight: 600;font-size: 14px;">{{ $totalReceived }}</th>
            <th></th>
        </tr>
    @endif
    </tbody>
</table>

<h3 class="nametitle">Expenses</h3>
<table class="table table-striped mb-0">
    <tbody>
    <tr>
        <th style="width: 20px">#</th>
        <th style="width: 230px;text-align: center;">Account Name</th>
        <th style="width: 203px;text-align: center;">Payment Mode</th>
        <th style="width: 240px;text-align: center;">Amount</th>
    </tr>
    @if(isset($expenses))
    @foreach($expenses as $expense)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td style="width: 230px;text-align: center;">{{ $expense->account->name }}</td>
            <td style="width: 203px;text-align: center;">{{ ucfirst($expense->tmode) }}</td>
            <td style="width: 240px;text-align: center;">{{ $expense->credit }}</td>
        </tr>
    @endforeach
    @endif

    @if(isset($totalReceived))
        <tr>
            <th></th>
            <th></th>
            <th style="width: 203px;text-align: center;">Grand Total</th>
            <th style="width: 240px; text-align: center;font-weight: 600;font-size: 14px;">{{ $totalExpenses }}</th>
        </tr>
    @endif
    </tbody>
</table>


</body>
</html>
