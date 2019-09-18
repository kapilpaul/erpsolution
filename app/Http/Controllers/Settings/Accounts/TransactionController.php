<?php

namespace App\Http\Controllers\Settings\Accounts;

use App\Models\Bank\Bank;
use App\Models\Settings\Accounts\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Database\QueryException;
use PDOException;

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
        if($type !== 'receipt' && $type !== 'payment') {
            abort(404);
        }
        $banks = Bank::orderBy('name', 'asc')->select('name', 'code')->get();
        return view('settings.accounts.transaction.create', compact('type', 'banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($validation = $this->customRules($request)) {
            return $validation;
        }

        try{
            Transaction::insertion($request);

            return response()->json(['success' => 'Added Successfully!'], 200);
        }catch(PDOException $e){
            return response()->json(['errors' => "PDOException Error!"], 500);
        }catch(QueryException $e){
            return response()->json(['errors' => "QueryException Error!"], 500);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
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

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 500);
        }
    }

    /**
     * @param $date
     */
    public function dailySummary($date)
    {
        $date = date('Y-m-d', strtotime($date));
        $salesQuery = Transaction::whereDate('date', $date)->where('type', 'payment')->where('category', 'customer')->with('customer');
        $receiptsQuery = Transaction::whereDate('date', $date)->where('type', 'receipt')->where('category', 'customer')->with('customer');

        $expensesQuery = Transaction::whereDate('date', $date)->where('type', 'payment')->where('category', 'office')->with('account');

        $totalSales = $salesQuery->sum('credit');
        $totalReceived = $receiptsQuery->sum('debit');
        $totalExpenses = $expensesQuery->sum('credit');

        $sales = $salesQuery->get();
        $receipts = $receiptsQuery->get();
        $expenses = $expensesQuery->get();

        dd($totalSales, $totalReceived, $totalExpenses);

    }
}
