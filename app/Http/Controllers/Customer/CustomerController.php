<?php

namespace App\Http\Controllers\Customer;

use App\Models\Customer\Customer;
use App\Models\Settings\Accounts\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use PDOException;
use Validator;
use DB;

class CustomerController extends Controller
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
            $customersQuery = Customer::orderBy('id', 'desc');
            $customers = $customersQuery->paginate(100);
            $totalDue = $customersQuery->sum('balance');
            return response()->json(['customers' => $customers, 'totalDue' => $totalDue], 200);
        }

        return view('customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customer.create');
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
            Customer::createCustomer($input);

            return response()->json(['success' => 'Added Successfully!'], 200);

        }catch(PDOException $e){
            return response()->json(['error' => "PDOException Error!"], 500);
        }catch(QueryException $e){
            return response()->json(['error' => "QueryException Error!"], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $code
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        $customer = Customer::whereCode($code)->firstOrFail();

        $transactions = Transaction::whereCategory('customer')
                                   ->whereReceiverId($customer->id)
                                   ->orderBy('created_at', 'desc')
                                   ->orderBy('id', 'desc')
                                   ->get();

        $totalPurchase = Customer::grandTotal($customer->id);
        $totalPaid = Customer::receiptTotal($customer->id);

        return view('customer.show', compact('customer', 'transactions', 'totalPurchase', 'totalPaid'));
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

            $customer = Customer::findOrFail($id);

            $customer->update($input);

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
        if ( $customer = Customer::find($id) ) {
            $customer->delete();
            return response()->json(['success' => 'Deleted Successfully!'], 200);
        }
        return response()->json(['error' => 'Something went wrong'], 404);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customRules($request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'nullable|email',
            'mobile' => 'required|numeric',
            'previous_purchase_amount' => 'nullable|numeric',
            'balance' => 'nullable|numeric'
        ];
        $customMessages = [
            'required' => 'The :attribute field can not be blank.',
            'email' => 'The :attribute field must be a valid email address.',
            'numeric' => 'The :attribute field must be a numeric value.'
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 500);
        }
    }

    /**
     * @param $value
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($value)
    {
        $customers = Customer::where('mobile', 'like', '%'. $value .'%')
                    ->orWhere('email', 'like', '%'. $value .'%')
                    ->orWhere('name', 'like', '%'. $value .'%')
                    ->orWhere('balance', 'like', '%'. $value .'%')
                    ->paginate(100);

        return response()->json(['customers' => $customers], 200);
    }

    /**
     * @param $value
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchByMobile($value)
    {
        $customer = Customer::where('mobile', $value)->first();
        if($customer) {
            return response()->json(['customer' => $customer], 200);
        }
        return response()->json(['notfound' => 'No customer found with this mobile number.'], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function allCustomers()
    {
        $customers = Customer::orderBy('name', 'asc')->select(DB::raw('CONCAT(name, " - ", mobile) AS name'), 'code')->get();
        return response()->json(['items' => $customers], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function dueCustomers()
    {
        if(request()->is('api/*')) {
            $customersQuery = Customer::where('balance', '>', '0')->orderBy('id', 'desc');
            $customers = $customersQuery->paginate(100);
            $totalDue = $customersQuery->sum('balance');

            return response()->json(['customers' => $customers, 'totalDue' => $totalDue], 200);
        }

        return view('customer.dues');
    }
}
