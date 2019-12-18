@if(isset($receipts))
    <div class="card mb-3 shadow no-b r-0 mt-15">
        <div class="box-body no-padding">
            <table class="table table-striped mb-0">
                <tbody>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Date</th>
                    <th>Customer Name</th>
                    <th>Invoice No</th>
                    <th>Payment Mode</th>
                    <th>Amount</th>
                    <th>Customer Total Due</th>
                </tr>
                @foreach($receipts as $receipt)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d M Y', strtotime($receipt->date)) }}</td>
                        <td>
                            <a href="{{ route('customer.show', $receipt->customer->code) }}">
                                {{ $receipt->customer->name }}
                            </a>
                        </td>
                        <td>
                            @if($receipt->other_transaction_no)
                                <a href="{{ route('invoice.show', $receipt->other_transaction_no) }}">
                                    {{ $receipt->other_transaction_no }}
                                </a>
                            @endif
                        </td>
                        <td>{{ ucfirst($receipt->tmode) }}</td>
                        <td>{{ $receipt->debit }}</td>
                        <td>
                            @if($receipt->customer->balance > 0)
                                <span class="bg-danger mb-0 p-5 pl-15 pr-15" style="color: #000;">
                                {{ $receipt->customer->balance }}
                            </span>
                            @else
                                {{ $receipt->customer->balance }}
                            @endif
                        </td>
                    </tr>
                @endforeach

                @if(isset($totalReceived))
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Grand Total</th>
                        <th class="text-black">{{ $totalReceived }}</th>
                        <th></th>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endif
