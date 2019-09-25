@if(isset($sales))
    <div class="card mb-3 shadow no-b r-0 mt-15">
        <div class="box-body no-padding">
            <table class="table table-striped mb-0">
                <tbody>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Invoice No</th>
                    <th>Vehicle No</th>
                    <th>Destination</th>
                    <th>Amount</th>
                    <th>Customer Total Due</th>
                </tr>
                @foreach($sales as $sale)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d M Y', strtotime($sale->date)) }}</td>
                        <td>
                            <a href="{{ route('customer.show', $sale->customer->code) }}">
                                {{ $sale->customer->name }}
                            </a>
                        </td>
                        <td>
                            <a href="{{ route('invoice.show', $sale->invoice_no) }}">
                                {{ $sale->invoice_no }}
                            </a>
                        </td>
                        <td>{{ $sale->vehicle_no }}</td>
                        <td>{{ $sale->destination }}</td>
                        <td>{{ $sale->grand_total }}</td>
                        <td>
                            @if($sale->customer->balance > 0)
                                <span class="bg-danger mb-0 p-5 pl-15 pr-15" style="color: #000;">
                                {{ $sale->customer->balance }}
                            </span>
                            @else
                                {{ $sale->customer->balance }}
                            @endif
                        </td>
                    </tr>
                @endforeach

                @if(isset($totalSales))
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Grand Total</th>
                        <th class="text-black">{{ $totalSales }}</th>
                        <th></th>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endif
