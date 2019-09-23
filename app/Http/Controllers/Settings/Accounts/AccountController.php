<?php

namespace App\Http\Controllers\Settings\Accounts;

use App\Models\Settings\Accounts\Account;
use App\Models\Settings\Accounts\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Validator;
use Illuminate\Database\QueryException;
use PDOException;

class AccountController extends Controller
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
            $accounts = Account::all();
            return response()->json(['accounts' => $accounts], 200);
        }
        return view('settings.accounts.account.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.accounts.account.create');
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
            $input['code'] = Str::random(15);

            Account::create($input);

            return response()->json(['success' => 'Added Successfully!'], 200);
        }catch(PDOException $e){
            return response()->json(['error' => "PDOException Error!"], 500);
        }catch(QueryException $e){
            return response()->json(['error' => "QueryException Error!"], 500);
        }
    }

    /**
     * @param $code
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($code)
    {
        $account = Account::whereCode($code)->firstOrFail();

        $transactions = Transaction::where('category', 'office')
                        ->where('receiver_id', $account->id)
                        ->orderBy('created_at', 'desc')
                        ->orderBy('id', 'desc');

        $totalPayment = $transactions->sum('credit');
        $transactions = $transactions->get();

        return view('settings.accounts.account.show', compact('totalPayment', 'transactions', 'account'));
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

            $item = Account::findOrFail($id);
            $item->update($input);

            return response()->json(['success' => 'Updated Successfully!'], 200);

        }catch(PDOException $e){
            return response()->json(['error' => "PDOException Error!"], 500);
        }catch(QueryException $e){
            return response()->json(['error' => "QueryException Error!"], 500);
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
        $account = Account::findOrFail($id);
        $account->delete();
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
            'name' => 'required'
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function allAccounts()
    {
        $accounts = Account::orderBy('name', 'asc')->select('name', 'code')->get();
        return response()->json(['items' => $accounts], 200);
    }
}
