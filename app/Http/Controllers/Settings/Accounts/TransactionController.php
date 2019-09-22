<?php

namespace App\Http\Controllers\Settings\Accounts;

use App\Models\Bank\Bank;
use App\Models\Settings\Accounts\Transaction;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Session\TokenMismatchException;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $type
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        if ($type !== 'receipt' && $type !== 'payment') {
            abort(404);
        }
        $banks = Bank::orderBy('name', 'asc')->select('name', 'code')->get();
        return view('settings.accounts.transaction.create', compact('type', 'banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($validation = $this->customRules($request)) {
            return $validation;
        }

        try {
            Transaction::insertion($request);

            return response()->json(['success' => 'Added Successfully!'], 200);
        } catch (PDOException $e) {
            return response()->json(['errors' => "PDOException Error!"], 500);
        } catch (QueryException $e) {
            return response()->json(['errors' => "QueryException Error!"], 500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->delete();
        return response()->json(['success' => 'Deleted Successfully.'], 200);
    }


    /**
     * custom rules validation
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customRules($request)
    {
        $rules = [
            'type' => 'required',
            'category' => 'required',
            'tmode' => 'required',
            'receiver_id' => 'required',
            'amount' => 'required|numeric'
        ];

        $customMessages = [
            'required' => 'The :attribute field can not be blank.',
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function dailySummary(Request $request)
    {
        try {
            $fromDate = isset($request->from_date) ? $request->from_date : Carbon::today();
            $toDate = isset($request->to_date) ? $request->to_date : Carbon::today();

            $summary = Transaction::filterSummary($fromDate, $toDate);

            $totalSales = $summary['totalSales'];
            $totalReceived = $summary['totalReceived'];
            $totalExpenses = $summary['totalExpenses'];
            $sales = $summary['sales'];
            $receipts = $summary['receipts'];
            $expenses = $summary['expenses'];

            return view('reports.transactions.daily-summary', compact(
                'totalSales',
                'totalReceived',
                'totalExpenses',
                'sales',
                'receipts',
                'expenses'
            ));

        } catch (PDOException $e) {
            return redirect()->back()->with(['error' => "PDOException Error!"]);
        } catch (QueryException $e) {
            return redirect()->back()->with(['error' => "QueryException Error!"]);
        } catch (TokenMismatchException $e) {
            return redirect()->route('transaction.dailySummary');
        } catch (MethodNotAllowedHttpException $e) {
            return redirect()->route('transaction.dailySummary');
        }
    }

    /**
     * @param bool $request
     * @return mixed
     */
    public function summaryPdf($request = false)
    {
        $fromDate = isset($request->from_date) ? $request->from_date : Carbon::today();
        $toDate = isset($request->to_date) ? $request->to_date : Carbon::today();

        $summary = Transaction::filterSummary($fromDate, $toDate);

        $totalSales = $summary['totalSales'];
        $totalReceived = $summary['totalReceived'];
        $totalExpenses = $summary['totalExpenses'];
        $sales = $summary['sales'];
        $receipts = $summary['receipts'];
        $expenses = $summary['expenses'];

        PDF::setOptions([
            'debugCss' => true,
            'debugLayout' => true,
            'debugLayoutLines' => true,
            'debugLayoutBlocks' => true,
            'debugLayoutInline' => true,
            'debugLayoutPaddingBox' => true,
        ]);

        return $pdf = PDF::loadView('pdf.transaction.summary', compact(
            'fromDate',
            'totalSales',
            'totalReceived',
            'totalExpenses',
            'sales',
            'receipts',
            'expenses'
        )); //->stream()

        return $pdf->download("transaction-summary-$fromDate.pdf");
    }
}
