<?php

namespace App\Models\Settings\Accounts;

use App\Models\Bank\Bank;
use App\Models\Bank\Transaction as BankTransaction;
use App\Models\Customer\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Settings\Supplier;
use Carbon\Carbon;
use Validator;
use DB;

class Transaction extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'transactions';

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
        'transaction_no',
        'type',
        'date',
        'category',
        'tmode',
        'other_transaction_no',
        'description',
        'debit',
        'credit',
        'receiver_id',
        'banktransaction_id',
        'balance'
    ];

    /**
     * set Date attribute formatted as Y-m-d
     * @param $value
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::parse($value)->format('Y-m-d');
    }

    /**
     * get Date attribute formatted as 29 AUG 2018
     * @param $value
     */
    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y');
    }

    /**
     * @param $input
     * @param $request
     * @return bool
     */
    public static function insertion($request)
    {
        $input = $request->except(['amount', 'chequeDetails']);
        $input['transaction_no'] = str_random(15);
        $bankTransactionData = [];
        $bank = null;

        if($request->tmode !== 'cash' && $request->chequeDetails['add_bank_transaction'] == 'yes') {
            $bank = Bank::whereCode($request->chequeDetails['bank_id'])->first();
            $bankTransactionData = $request->chequeDetails;
            $bankTransactionData['bank_id'] = $bank->id;
        }

        if($request->type === 'payment') {
            $input['credit'] = $bankTransactionData['debit'] = $request->amount;
        }else {
            $input['debit'] = $bankTransactionData['credit'] = $request->amount;
        }

        if(!isset($request->date)) {
            $input['date'] = Carbon::now();
        }

        $input['receiver_id'] = self::getReceiverId($request->category, $request->receiver_id);

        DB::transaction(function () use ($request, $bankTransactionData, $bank, $input) {
            if($request->tmode !== 'cash' && $request->chequeDetails['add_bank_transaction'] == 'yes') {
                $bankTransaction = BankTransaction::create($bankTransactionData);

                //update specific bank balance
                Bank::updateBankBalance($bank->id);
                $input['banktransaction_id'] = $bankTransaction->id;
            }

            $transaction = Transaction::create($input);

            //update balance by category
            $receiver = self::balanceUpdate($input['category'], $input['receiver_id']);
            $balance = $receiver->balance;

            $transaction->update([
                'balance' => $balance
             ]);
        });



        return true;
    }

    /**
     * @param $category
     * @param $id
     * @return bool
     */
    public static function balanceUpdate($category, $id)
    {
        if($category == 'customer') {
            $customer = Customer::balanceUpdate($id);
            return $customer;
        } else if ($category == 'supplier') {
            $supplier = Supplier::balanceUpdate($id);
            return $supplier;
        }
    }

    /**
     * get receiver id by category
     * @param $category
     * @param $value
     * @return mixed
     */
    public static function getReceiverId($category, $value)
    {
        if($category == 'customer') {
            $customer = Customer::whereCode($value)->first();
            return $customer->id;
        } else if($category == 'supplier') {
            $supplier = Supplier::whereCode($value)->first();
            return $supplier->id;
        }

        return false;
    }

}
