<?php

namespace App\Models\Customer;

use App\Http\Controllers\Sells\InvoiceController;
use App\Models\Sales\Invoice;
use App\Models\Settings\Accounts\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Customer extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'customers';

    /**
     * The database primary key value.
     * @var string
     */
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];

    /**
     * Attributes that should be mass-assignable.
     * @var array
     */
    protected $fillable = [
        'id', 'code', 'name', 'email', 'mobile', 'address', 'previous_purchase_amount', 'balance'
    ];


    /**
     * @param $input
     * @return bool
     */
    public static function createCustomer($input)
    {
        $input['code'] = Str::random(15);
        $input['name'] = ucwords($input['name']);


        if ($customer = Customer::create($input)) {
            $invoice = new InvoiceController();
            $invoice->transactionRequest('payment', $input['balance'], $customer->code, null, false, "Previous Balance");
            return true;
        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     */
    public static function balanceUpdate($id)
    {
        $customer = Customer::findOrFail($id);

        $grandTotal = self::grandTotal($customer->id);
        $receiptAmount = self::receiptTotal($customer->id);

        $customer->update([
            'balance' => ($grandTotal - $receiptAmount)
        ]);

        Transaction::updateTransactionByCategory('customer', $customer->id);

        return $customer;
    }

    /**
     * grand total of customer
     * @param $id
     * @return mixed
     */
    public static function grandTotal($id)
    {
        $invoiceTotal = Invoice::whereCustomerId($id)->sum('grand_total');
        $paymentTotal = Transaction::where('type', 'payment')->where('category', 'customer')->whereReceiverId($id)->sum('credit');

        $grandTotal = ($invoiceTotal + $paymentTotal);
        return $paymentTotal;
    }

    /**
     * receipt amount from customer
     * @param $id
     * @return mixed
     */
    public static function receiptTotal($id)
    {
        $receiptAmount = Transaction::whereType('receipt')->whereCategory('customer')->whereReceiverId($id)->sum('debit');
        return $receiptAmount;
    }

}
