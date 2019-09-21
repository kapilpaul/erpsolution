@if(isset($expenses))
    <div class="card mb-3 shadow no-b r-0 mt-15">
        <div class="box-body no-padding">
            <table class="table table-striped mb-0">
                <tbody>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Account Name</th>
                    <th>Payment Mode</th>
                    <th>Amount</th>
                </tr>
                @foreach($expenses as $expense)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <a href="{{ route('customer.show', $expense->account->code) }}">
                                {{ $expense->account->name }}
                            </a>
                        </td>
                        <td>{{ ucfirst($expense->tmode) }}</td>
                        <td>{{ $expense->credit }}</td>
                    </tr>
                @endforeach

                @if(isset($totalReceived))
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Grand Total</th>
                        <th class="text-black">{{ $totalExpenses }}</th>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
@endif
