<?php

namespace App\Models\Customer;

use App\Models\Sales\Invoice;
use App\Models\Settings\Accounts\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
        $input['code'] = str_random(15);
        $input['name'] = ucwords($input['name']);

        if (Customer::create($input)) {
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

        return $customer;
    }

    /**
     * grand total of customer
     * @param $id
     */
    public static function grandTotal($id)
    {
        $invoiceTotal = Invoice::whereCustomerId($id)->sum('grand_total');
        $paymentTotal = Transaction::whereType('payment')->whereCategory('customer')->whereReceiverId($id)->sum('credit');

        $grandTotal = ($invoiceTotal + $paymentTotal);
        return $grandTotal;
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
