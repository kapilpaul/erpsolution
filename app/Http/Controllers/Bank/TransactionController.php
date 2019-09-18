<?php

namespace App\Http\Controllers\Bank;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Bank\Bank;
use App\Models\Bank\Transaction;
use Validator;
use Illuminate\Database\QueryException;
use PDOException;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->is('api/*')) {
            $transactions = Transaction::orderBy('date', 'desc')->with(['bank' => function($query) {
                $query->select('id', 'name');
            }])->paginate(100);
            return response()->json(['bankTransactions' => $transactions], 200);
        }
        return view('bank.transaction.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banks = Bank::pluck('name', 'id');
        return view('bank.transaction.create', compact('banks'));
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
            $input = $request->except(['account_type', 'amount']);
            $input['date'] = $request->date;

            if($request->account_type == 'debit') {
                $input['debit'] = $request->amount;
            }else if($request->account_type == 'credit') {
                $input['credit'] = $request->amount;
            }

            Transaction::create($input);

            //update specific bank balance
            if(Bank::updateBankBalance($request->bank_id)) {
                return response()->json(['success' => 'Added Successfully!'], 200);
            }

            return response()->json(['errors' => 'Error In Updating Bank Balance!'], 500);
        }catch(PDOException $e){
            return response()->json(['errors' => "PDOException Error!"], 500);
        }catch(QueryException $e){
            return response()->json(['errors' => "QueryException Error!"], 500);
        }
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
        $rules = [
            'date' => 'required|date',
            'account_type' => 'required',
            'amount' => 'required|numeric',
            'slip_id' => 'required'
        ];
        $customMessages = [
            'required' => 'The :attribute field can not be blank.',
            'date' => 'The :attribute field must be a date format.',
            'numeric' => 'The :attribute field must be a valid format.',
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 500);
        }

        try{
            $input = $request->except(['account_type', 'amount']);
            $input['date'] = Carbon::parse($request->date)->format('Y-m-d');

            if($request->account_type == 'debit') {
                $input['debit'] = $request->amount;
            }else if($request->account_type == 'credit') {
                $input['credit'] = $request->amount;
            }

            $transaction = Transaction::findOrFail($id);
            $transaction->update($input);

            //update specific bank balance
            if(Bank::updateBankBalance($transaction->bank_id)) {
                return response()->json(['success' => 'Updated Successfully!'], 200);
            }

            return response()->json(['errors' => 'Error In Updating Bank Balance!'], 500);
        }catch(PDOException $e){
            return response()->json(['errors' => "PDOException Error!"], 500);
        }catch(QueryException $e){
            return response()->json(['errors' => "QueryException Error!"], 500);
        }
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

        if(Bank::updateBankBalance($transaction->bank_id)) {
            return response()->json(['success' => 'Deleted Successfully.'], 200);
        }

        return response()->json(['errors' => 'Error In Updating Bank Balance!'], 500);
    }


    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customRules($request)
    {
        $rules = [
            'date' => 'required|date',
            'account_type' => 'required',
            'bank_id' => 'required|numeric',
            'amount' => 'required|numeric',
            'slip_id' => 'required'
        ];
        $customMessages = [
            'required' => 'The :attribute field can not be blank.',
            'date' => 'The :attribute field must be a date format.',
            'numeric' => 'The :attribute field must be a valid format.',
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 500);
        }
    }
}
