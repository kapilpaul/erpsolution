<?php

namespace App\Http\Controllers\Sells;

use App\Models\Customer\Customer;
use App\Models\Sales\Invoice;
use App\Models\Settings\Accounts\Transaction;
use App\Models\Settings\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use stdClass;
use Validator;
use Illuminate\Database\QueryException;
use PDOException;
use DB;

class InvoiceController extends Controller
{
    protected $customerId;

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->is('api/*')) {
            $invoices = Invoice::orderBy('date', 'desc')->with('customer')->paginate(100);
            return response()->json(['invoices' => $invoices], 200);
        }

        return view('sells.invoice.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('sells.invoice.create');
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
            $inputData = $request->except(['products', 'customer']);
            $customer = Customer::whereMobile($request->customer['mobile'])->first();
            //create customer if not exist
            if (!$customer) {
                $customerData = $request->customer;
                $customer = Customer::createCustomer($customerData);
            }

            $inputData['invoice_no'] = time();
            $this->customerId = $inputData['customer_id'] = $customer->id;
            $inputData['total_amount'] = $inputData['total_discount'] = $inputData['grand_total'] = 0;

            $invoice = Invoice::create($inputData);

            //iterate product
            foreach ($request->products as $product) {
                $total = $product['price'];

                //attaching product to pivot table
                $invoice->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'discount' => $product['discount'],
                    'total' => $total
                ]);

                //update product item stock and out quantity
                Product::updateProductStockAndOutQty($product['product_id']);

                $inputData['total_amount'] += $total;
                $inputData['total_discount'] += $product['discount'];
//                $inputData['tax'] += $product['tax'];
            }
            $grand_total = round($inputData['total_amount'] - $inputData['total_discount']);

            //updating invoice total amount, discount, grand total
            $invoice->update([
                'total_amount' => $inputData['total_amount'],
                'total_discount' => $inputData['total_discount'],
                'grand_total' => $grand_total
            ]);

            //adding transaction
            $this->transactionRequest('payment', $grand_total, $customer->code, $invoice->invoice_no, $inputData['date']);

            if($request->paid_amount != 0) {
                $this->transactionRequest('receipt', $request->paid_amount, $customer->code, $invoice->invoice_no, $inputData['date']);
            }

            //updating customer balance
            //Customer::balanceUpdate($this->customerId);

            return response()->json(['success' => 'Added Successfully'], 200);
        } catch (PDOException $e) {
            return response()->json(['errors' => $e->getMessage()], 500);
            return response()->json(['errors' => "PDOException Error!"], 500);
        } catch (QueryException $e) {
            return response()->json(['errors' => "QueryException Error!"], 500);
        }
    }

    /**
     * transaction request for payment and receipt for customer
     * @param $type
     * @param $amount
     * @param $customerCode
     * @param $additional_transaction_no
     * @param bool $date
     * @param string $description
     * @return bool
     */
    public function transactionRequest($type, $amount, $customerCode, $additional_transaction_no, $date = false, $description = "")
    {
        $request = new \Illuminate\Http\Request();

        $request->replace([
            'type' => $type,
            'date' => $date,
            'category' => 'customer',
            'tmode' => 'cash',
            'other_transaction_no' => $additional_transaction_no,
            'amount' => $amount,
            'description' => $description,
            'receiver_id' => $customerCode
        ]);

        if (Transaction::insertion($request)) {
            return true;
        }

        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $invoice = Invoice::whereInvoiceNo($id)->with('customer', 'products')->first();
        $totalItemQuantity = DB::table('invoice_products')->whereInvoiceId($invoice->id)->sum('quantity');

        return view('sells.invoice.show', compact('invoice', 'totalItemQuantity'));
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
        if ($request->is('api/*')) {
            $invoice = Invoice::whereInvoiceNo($id)->first();
            $pid = $invoice->id;
            $invoice = Invoice::whereInvoiceNo($id)
                ->with(['products' => function ($query) use ($pid) {
                    $query->where('invoice_id', $pid)
                        ->select('name', 'model', 'stock', 'product_id', 'quantity', 'invoice_products.price',
                            'discount', 'total');
                }])->first();

            return response()->json(['invoice' => $invoice], 200);
        }

        $customers = Customer::pluck('name', 'id');
        $productItems = Product::orderBy('name', 'asc')->get();

        return view('sells.invoice.edit', compact('id', 'customers', 'productItems'));
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
        $rules = [
            'customer_id' => 'required',
            'date' => 'required',
            'products' => 'required|array|min:0',
            'products.*.product_id' => 'required'
        ];
        $customMessages = [
            'required' => 'The :attribute field can not be blank.'
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 500);
        }

        try {
            $inputData = $request->except(['products']);
            $invoice = Invoice::whereInvoiceNo($id)->first();
            $customer = Customer::findOrFail($request->customer_id);
            //return error if customer not exist
            if (!$customer) {
                return response()->json(['errors' => "Customer Do Not Exist!"], 500);
            }

            $this->customerId = $inputData['customer_id'] = $customer->id;
            $inputData['total_amount'] = $inputData['total_discount'] = $inputData['grand_total'] = 0;

            $invoice->update($inputData);

            //iterate product
            foreach ($request->products as $product) {
                $total = $product['price'];
                $invoice->products()->detach($product['product_id']);

                //attaching product to pivot table
                $invoice->products()->attach($product['product_id'], [
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'discount' => $product['discount'],
                    'total' => $total
                ]);

                //update product item stock and out quantity
                Product::updateProductStockAndOutQty($product['product_id']);

                $inputData['total_amount'] += $total;
                $inputData['total_discount'] += $product['discount'];
//                $inputData['tax'] += $product['tax'];
            }
            $grand_total = round($inputData['total_amount'] - $inputData['total_discount']);

            //updating invoice total amount, discount, grand total
            $invoice->update([
                'total_amount' => $inputData['total_amount'],
                'total_discount' => $inputData['total_discount'],
                'grand_total' => $grand_total
            ]);

            //updating customer balance
            Customer::balanceUpdate($this->customerId);

            return response()->json(['success' => 'Updated Successfully'], 200);
        } catch (PDOException $e) {
            return response()->json(['errors' => "PDOException Error!"], 500);
        } catch (QueryException $e) {
            return response()->json(['errors' => "QueryException Error!"], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $products = $invoice->products()->get();
        $invoice->delete();

        DB::table('invoice_products')
            ->where('invoice_id', $invoice->id)
            ->update(['deleted_at' => Carbon::now()]);

        foreach ($products as $item) {
            //update product stock, in and out quantity
            Product::updateProductStockAndOutQty($item->pivot->product_id);
        }

        DB::table('transactions')
            ->where('other_transaction_no', $invoice->invoice_no)
            ->update(['deleted_at' => Carbon::now()]);

        Customer::balanceUpdate($invoice->customer_id);

        return response()->json(['success' => 'Deleted Successfully'], 200);
    }


    /**
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function customRules($request)
    {
        $rules = [
            'customer.name' => 'required',
            'customer.mobile' => 'required',
            'date' => 'required',
            'products' => 'required|array|min:0',
            'products.*.product_id' => 'required'
        ];
        $customMessages = [
            'required' => 'The :attribute field can not be blank.'
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 500);
        }
    }


    /**
     * @param $value
     * @return \Illuminate\Http\JsonResponse
     */
    public function search($value)
    {
        $invoices = DB::table('invoices')
            ->join('customers', 'invoices.customer_id', '=', 'customers.id')
            ->whereNull('invoices.deleted_at')
            ->whereNull('customers.deleted_at')
            ->where(function ($query) use ($value) {
                $query->where('invoices.invoice_no', 'like', '%' . $value . '%')
                    ->orWhere('invoices.vehicle_no', 'like', '%' . $value . '%')
                    ->orWhere('invoices.destination', 'like', '%' . $value . '%')
                    ->orWhere('customers.name', 'like', '%' . $value . '%');
            })
            ->orderBy('date', 'desc')
            ->select('invoices.*', 'customers.id as customer_id', 'customers.code as customer_code', 'customers.name as customer_name')
            ->paginate(100);


        return response()->json(['invoices' => $invoices], 200);
    }
}
