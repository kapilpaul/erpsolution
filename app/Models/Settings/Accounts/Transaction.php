<?php

namespace App\Models\Settings\Accounts;

use App\Models\Bank\Bank;
use App\Models\Bank\Transaction as BankTransaction;
use App\Models\Customer\Customer;
use App\Models\Sales\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Settings\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Str;
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
     * @return string
     */
    public function getDateAttribute($value)
    {
        return Carbon::parse($value)->format('d M Y');
    }

    /**
     * @param $request
     * @return bool
     */
    public static function insertion($request)
    {
        $input = $request->except(['amount', 'chequeDetails']);
        $input['transaction_no'] = Str::random(15);
        $bankTransactionData = [];
        $bank = null;

        if($request->tmode !== 'cash' && $request->chequeDetails['add_bank_transaction'] == 'yes') {
            $bank = Bank::where('code', $request->chequeDetails['bank_id'])->first();
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
        } else if ($category == 'office') {
            $account = Account::balanceUpdate($id);
            return $account;
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
            $customer = Customer::where('code', $value)->first();
            return $customer->id;
        } else if($category == 'supplier') {
            $supplier = Supplier::where('code', $value)->first();
            return $supplier->id;
        } else if($category == 'office') {
            $account = Account::where('code', $value)->first();
            return $account->id;
        }

        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'receiver_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account()
    {
        return $this->belongsTo(Account::class, 'receiver_id', 'id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'other_transaction_no', 'invoice_no');
    }

    /**
     * @param $from_date
     * @param $to_date
     * @return array
     */
    public static function filterSummary($from_date, $to_date)
    {
        $fromDate = date('Y-m-d', strtotime($from_date));
        $toDate = date('Y-m-d', strtotime($to_date));

//                $salesQuery = Transaction::whereDate('date', $date)->where('type', 'payment')->where('category', 'customer')->with(['customer', 'invoice']);
        $salesQuery = Invoice::whereDate('date', '>=', $fromDate)->whereDate('date', '<=', $toDate)->orderBy('date', 'desc')->with('customer', 'products');

        $receiptsQuery = Transaction::whereDate('date', '>=', $fromDate)->whereDate('date', '<=', $toDate)->where('type', 'receipt')->where('category', 'customer')->orderBy('date', 'desc')->with('customer');

        $expensesQuery = Transaction::whereDate('date', '>=', $fromDate)->whereDate('date', '<=', $toDate)->where('type', 'payment')->where('category', 'office')->orderBy('date', 'desc')->with('account');

        $totalSales = $salesQuery->sum('grand_total'); //credit
        $totalReceived = $receiptsQuery->sum('debit');
        $totalExpenses = $expensesQuery->sum('credit');

        $sales = $salesQuery->get();
        $receipts = $receiptsQuery->get();
        $expenses = $expensesQuery->get();

        return [
            'totalSales' => $totalSales,
            'totalReceived' => $totalReceived,
            'totalExpenses' => $totalExpenses,
            'sales' => $sales,
            'receipts' => $receipts,
            'expenses' => $expenses,
        ];
    }

    /**
     * @param $date
     * @return mixed
     */
    public static function findIncome($date)
    {
        return Transaction::whereDate('date', $date)->where('type', 'receipt')->where('category', 'customer')->sum('debit');
    }

    /**
     * @param $category
     * @param $receiverId
     * @return mixed
     */
    public static function updateTransactionByCategory($category, $receiverId)
    {
        $transactions = Transaction::where('category', $category)->where('receiver_id', $receiverId)->orderBy('date', 'asc')->get();

        $credit = $debit = $balance = 0;
        foreach ($transactions as $transaction) {
            $credit += $transaction->credit;
            $debit += $transaction->debit;

            $balance = $credit - $debit;
            $transaction->update(['balance' => $balance]);
        }

        return $balance;
    }
}
