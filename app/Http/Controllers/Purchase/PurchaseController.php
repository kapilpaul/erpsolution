<?php

namespace App\Http\Controllers\Purchase;

use App\Models\Purchase\Purchase;
use App\Models\Settings\Product;
use App\Models\Settings\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Database\QueryException;
use PDOException;
use DB;

class PurchaseController extends Controller
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
            $purchases = Purchase::orderBy('purchase_date', 'desc')->with('supplier')->paginate(100);
            return response()->json(['purchases' => $purchases], 200);
        }

        return view('purchase.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $suppliers = Supplier::pluck('name', 'id');
        return view('purchase.create', compact('suppliers'));
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
            $inputData = $request->except('products');

            $inputData['purchase_no'] = time();
            $inputData['purchase_date'] = Carbon::parse($request->purchase_date)->format('Y-m-d');
            $inputData['total_amount'] = 0;

            $purchase = Purchase::create($inputData);

            foreach ($request->products as $product) {
                $total = $product['price'] = 0;

                //attaching product to pivot table
                $purchase->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'total' => $total
                ]);

                //update product item stock and in quantity
                $item = Product::findOrFail($product['product_id']);
                $item->update([
                    'price' => $item->price != null ? collect([$item->price, $product['price']])->avg() : $product['price'],
                ]);

                Product::updateProductStockAndOutQty($item->id);

                $inputData['total_amount'] += $total;
            }

            //updating supplier balance
//            $supplier = Supplier::findOrFail($inputData['supplier_id']);
//            $supplier->update([
//                'balance' => ($supplier->balance + $inputData['total_amount'])
//            ]);

            //updating purchase total amount
            $purchase->update(['total_amount' => $inputData['total_amount']]);

            return response()->json(['success' => 'Added Successfully'], 200);
        }catch(PDOException $e){
            return response()->json(['error' => "PDOException error!"], 500);
        }catch(QueryException $e){
            return response()->json(['error' => "QueryException error!"], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $purchase = Purchase::wherePurchaseNo($id)->with('supplier', 'products')->first();
        $totalItemQuantity = DB::table('purchase_products')->wherePurchaseId($purchase->id)->sum('quantity');
        return view('purchase.show', compact('purchase', 'totalItemQuantity'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if($request->is('api/*')) {
            $purchase = Purchase::wherePurchaseNo($id)->first();
            $pid = $purchase->id;
            $purchase = Purchase::wherePurchaseNo($id)
                ->with(['products' => function ($query) use ($pid) {
                    $query->where('purchase_id', $pid)->select('name', 'model', 'stock', 'product_id', 'quantity', 'total');
                }])->first();

            return response()->json(['purchase' => $purchase], 200);
        }

        $suppliers = Supplier::pluck('name', 'id');
//        $productItems = Product::orderBy('name', 'asc')->select(DB::raw('CONCAT(name, " - ", model) AS name'), 'id', 'stock')->get();
        $productItems = Product::orderBy('name', 'asc')->get();
        return view('purchase.edit', compact('id', 'suppliers', 'productItems'));
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
            $purchase = Purchase::wherePurchaseNo($id)->first();
            $inputData = $request->except('products');

            $inputData['purchase_date'] = Carbon::parse($request->purchase_date)->format('Y-m-d');
            $inputData['total_amount'] = 0;

            foreach ($request->products as $product) {
                $total = $product['price'] * $product['quantity'];
                $item = Product::findOrFail($product['product_id']);

                $purchase->products()->detach($product['product_id']);
                //attaching product to pivot table
                $purchase->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'total' => $total
                ]);

                //update product item stock and in quantity

                //avg price from pivot table
                $itemAvgPrice = DB::table('purchase_products')->whereProductId($product['product_id'])->avg('price');

                $item->update([
                    'price' => $itemAvgPrice,
                ]);

                Product::updateProductStockAndOutQty($item->id);

                $inputData['total_amount'] += $total;
            }

            //updating purchase total amount
            $purchase->update(['total_amount' => $inputData['total_amount']]);

            //updating supplier balance
//            $supplier = Supplier::findOrFail($inputData['supplier_id']);
//            Supplier::balanceUpdate($supplier->id);

            return response()->json(['success' => 'Updated Successfully'], 200);
        }catch(PDOException $e){
            return redirect()->back()->with(['errors' => "PDOException error!"]);
        }catch(QueryException $e){
            return redirect()->back()->with(['errors' => "QueryException error!"]);
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
        $purchase = Purchase::findOrFail($id);
        $products = $purchase->products()->get();
        $purchase->delete();

        DB::table('purchase_products')
            ->where('purchase_id', $purchase->id)
            ->update(['deleted_at' => Carbon::now()]);

        foreach ($products as $item) {
            //update product stock, in and out quantity
            Product::updateProductStockAndOutQty($item->pivot->product_id);
        }

        return response()->json(['success' => 'Deleted Successfully'], 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customRules($request)
    {
        $rules = [
            'purchase_date' => 'required',
            'products' => 'required|array|min:0',
            'products.*.product_id' => 'required'
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
     * @param $value
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($value)
    {
        $purchases = Purchase::where('invoice_no', 'like', '%'. $value .'%')
            ->orWhere('purchase_no', 'like', '%'. $value .'%')
            ->orWhere('vehicle_no', 'like', '%'. $value .'%')
            ->orWhere('total_amount', 'like', '%'. $value .'%')
            ->orderBy('purchase_date', 'desc')
            ->with('supplier')->paginate(100);

        return response()->json(['purchases' => $purchases], 200);
    }
}
