<?php

namespace App\Http\Controllers\Bank;

use App\Models\Bank\Bank;
use App\Models\Bank\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Database\QueryException;
use PDOException;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->is('api/*')) {
            $banks = Bank::all();
            return response()->json(['banks' => $banks], 200);
        }
        return view('bank.default.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('bank.default.create');
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
            $input = $request->all();
            $input['code'] = str_random(15);
            $input['name'] = ucwords($request->name);

            Bank::create($input);

            return response()->json(['success' => 'Added Successfully!'], 200);

        }catch(PDOException $e){
            return response()->json(['errors' => $e->getMessage()], 500);
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
    public function show(Request $request, $code)
    {
        $bank = Bank::whereCode($code)->first();
        Bank::updateBankBalance($bank->id);
        $bank = Bank::findOrFail($bank->id);

        if($request->is('api/*')) {
            $transactions = Transaction::whereBankId($bank->id)->orderBy('date', 'desc')->paginate(100);
            return response()->json(['bankTransactions' => $transactions], 200);
        }
        
        
        return view('bank.default.show', compact('bank'));
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
        if($validation = $this->customRules($request)) {
            return $validation;
        }

        try{
            $input = $request->all();
            $input['name'] = ucwords($request->name);

            $bank = Bank::findOrFail($id);
            $bank->update($input);

            return response()->json(['success' => 'Updated Successfully!'], 200);

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
        $bank = Bank::findOrFail($id);
        $bank->delete();
        return response()->json(['success' => 'Deleted Successfully.'], 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customRules($request)
    {
        $rules = [
            'name' => 'required',
            'ac_name' => 'required',
            'ac_no' => 'required',
            'branch' => 'required'
        ];
        $customMessages = [
            'required' => 'The :attribute field can not be blank.',
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 500);
        }
    }
}
