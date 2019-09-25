@if(isset($totalReceived) && isset($totalExpenses))
    <div class="card mb-3 shadow no-b r-0 mt-15">
        <div class="box-body no-padding">
            <table class="table table-striped mb-0">
                <tbody>
                <tr>
                    <th>Name</th>
                    <th>Amount</th>
                </tr>
                <tr>
                    <td>Total Sale Amount</td>
                    <td>{{ $totalSales }}</td>
                </tr>
                <tr>
                    <td>Total Received Amount</td>
                    <td>{{ $totalReceived }}</td>
                </tr>
                <tr>
                    <td>Total Expense Amount</td>
                    <td>{{ $totalExpenses }}</td>
                </tr>


                <tr>
                    <th>Grand Total (Total Received Amount - Total Expense Amount) </th>
                    <th class="text-black">{{ $totalReceived - $totalExpenses }}</th>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endif
