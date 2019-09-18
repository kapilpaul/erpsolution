<?php

namespace App\Http\Controllers\Settings;


use App\Models\Photo\Photo;
use App\Models\Settings\Category;
use App\Models\Settings\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use PDOException;
use Validator;
use DB;
use Excel;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->is('api/*')) {
            $products = Product::with('category', 'photo')->paginate(100);
            return response()->json(['products' => $products], 200);
        }
        $categories = Category::pluck('name', 'id');
        return view('settings.products.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::pluck('name', 'id');
        return view('settings.products.create', compact('categories'));
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
            $input = $request->except('image');
            $input['code'] = str_random(15);
            $input['name'] = ucwords($request->name);

            if($image = $request->image) {
                $input['photo_id'] = Photo::encodedImageUpload($image, $request->name, 'assets/img/products');
                if(! $input['photo_id']) {
                    return response()->json(['errors' => "You can Only Upload Jpg, jpeg, png image"], 500);
                }
            }

            Product::create($input);

            return response()->json(['success' => 'Added Successfully!'], 200);

        }catch(PDOException $e){
            return response()->json(['errors' => "PDOException Error!"], 500);
        }catch(QueryException $e){
            return response()->json(['errors' => "QueryException Error!"], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        if($product) {
            return response()->json(['product' => $product], 200);
        } else {
            return response()->json(['error' => "No data found"], 404);
        }
    }

    /**
     * show details of a product which has purchase and sells details
     * @param $code
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function details($code)
    {
        $product = Product::whereCode($code)->first();

        $totalPurchaseAmount = DB::table('purchase_products')
                           ->where('product_id', $product->id)
                           ->whereNull('deleted_at')
                           ->sum('total');

        $totalSaleAmount = DB::table('invoice_products')
                           ->where('product_id', $product->id)
                           ->whereNull('deleted_at')
                           ->sum('total');

        $totalDiscount = DB::table('invoice_products')
                           ->where('product_id', $product->id)
                           ->whereNull('deleted_at')
                           ->sum('discount');

        $purchaseDetails = DB::table('purchase_products')
                            ->where('purchase_products.product_id', $product->id)
                            ->whereNull('purchase_products.deleted_at')
                            ->join('purchases', 'purchase_products.purchase_id', '=', 'purchases.id')
                            ->join('suppliers', 'purchases.supplier_id', '=', 'suppliers.id')
                            ->whereNull('purchases.deleted_at')
                            ->select('purchase_products.*', 'purchases.*', 'suppliers.name as supplier_name', 'suppliers.code as supplier_code')
                            ->orderBy('purchases.purchase_date', 'desc')
                            ->paginate(100);

        $salesDetails = DB::table('invoice_products')
                            ->where('invoice_products.product_id', $product->id)
                            ->whereNull('invoice_products.deleted_at')
                            ->join('invoices', 'invoice_products.invoice_id', '=', 'invoices.id')
                            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
                            ->whereNull('invoices.deleted_at')
                            ->select('invoice_products.*', 'invoices.*', 'customers.name as customer_name', 'customers.code as customer_code')
                            ->orderBy('invoices.date', 'desc')
                            ->paginate(100);

        return view('settings.products.details', compact(
            'product',
            'totalPurchaseAmount',
            'totalSaleAmount',
            'totalDiscount',
            'purchaseDetails',
            'salesDetails'
        ));
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
            $input = $request->except('image');
            $input['name'] = ucwords($request->name);

            $product = Product::findOrFail($id);

            if($image = $request->image) {
                $input['photo_id'] = Photo::encodedImageUpload($image, $request->name, 'assets/img/products');
                if(! $input['photo_id']) {
                    return response()->json(['errors' => "You can Only Upload Jpg, jpeg, png image"], 500);
                }

                if($product->photo_id) {
                    $photo = Photo::whereId($product->photo_id)->first();

                    if($photo) {
                        unlink(public_path() . '/assets/img/products/' . $product->photo->photo);
                        $photo->delete();
                    }
                }
            }

            $product->update($input);

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
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['success' => 'Deleted Successfully.'], 200);
    }

    /**
     * @param $value
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($value)
    {
        $products = Product::where('name', 'like', '%'. $value .'%')
                           ->orWhere('barcode', 'like', '%'. $value .'%')
                           ->orWhere('model', 'like', '%'. $value .'%')
                           ->orWhere('price', 'like', '%'. $value .'%')
                           ->orWhere('sale_price', 'like', '%'. $value .'%')
                           ->with('category', 'photo')->paginate(100);

        return response()->json(['products' => $products], 200);
    }

    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customRules($request)
    {
        $rules = [
            'name' => 'required',
            'unit' => 'required',
            'sale_price' => 'required'
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
     * Import Products
     * @return \Illuminate\Support\Collection
     */
    public function import()
    {
        $file = '/var/www/laravel-quickstart/public/assets/file.xlsx';
        $data = Excel::load($file)->get(array('code', 'name', 'model', 'barcode', 'price', 'sale_price', 'unit', 'description', 'category_id'))->toArray();
        $collection = collect($data);
        $chunks = $collection->chunk(100);

        foreach($chunks as $chunk) {
            foreach($chunk as $item) {
                if($item['name'] != '' || $item['model'] != '' || $item['unit'] != '' || $item['category_id'] != '' || $item['sale_price'] != '' ) {
                    Product::create($item);
                }
            }
        }

        return $chunks;
    }
}
