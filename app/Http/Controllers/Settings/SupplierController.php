<?php

namespace App\Http\Controllers\Settings;


use App\Models\Purchase\Purchase;
use App\Models\Settings\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use PDOException;
use Validator;
use DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->is('api/*')) {
            $suppliers = Supplier::all();
            return response()->json(['suppliers' => $suppliers], 200);
        }

        return view('settings.suppliers.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('settings.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'mobile' => 'required'
        ];
        $customMessages = [
            'required' => 'The :attribute field can not be blank.',
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 500);
        }

        try{
            $input = $request->all();
            $input['code'] = str_random(15);
            $input['name'] = ucwords($request->name);

            Supplier::create($input);

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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($code)
    {
        $supplier = Supplier::whereCode($code)->first();
        if($supplier) {
            $totalPurchase = Purchase::whereSupplierId($supplier->id)->sum('total_amount');
            $totalPaid = Supplier::paidAmount($supplier->id);
            return view('settings.suppliers.show', compact('supplier','totalPurchase', 'totalPaid'));
        }
        return false;
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
            'name' => 'required',
            'mobile' => 'required'
        ];
        $customMessages = [
            'required' => 'The :attribute field can not be blank.',
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if($validator->fails()){
            return response()->json(['errors'=> $validator->errors()], 500);
        }

        try{
            $input = $request->all();
            $input['name'] = ucwords($request->name);

            $item = Supplier::findOrFail($id);
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
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return response()->json(['success' => 'Deleted Successfully.'], 200);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function allSuppliers()
    {
        $suppliers = Supplier::orderBy('name', 'asc')
                             ->select(DB::raw('CONCAT(name, "-", mobile) AS name'), 'code')
                             ->get();
        return response()->json(['items' => $suppliers], 200);
    }
}
