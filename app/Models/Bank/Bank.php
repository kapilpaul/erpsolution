<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bank extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'banks';

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
        'code', 'name', 'ac_name', 'ac_no', 'branch', 'balance'
    ];

    /**
     * @param $id
     * @return bool
     */
    public static function updateBankBalance($id) {
        $bank = Bank::findOrFail($id);

        $debit = Transaction::whereBankId($bank->id)->sum('debit');
        $credit = Transaction::whereBankId($bank->id)->sum('credit');

        $balance = $debit - $credit;

        if($bank->update(['balance' => $balance])) {
            return true;
        }
        return false;
    }

}
