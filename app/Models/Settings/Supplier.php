<?php

namespace App\Models\Settings;

use App\Models\Purchase\Purchase;
use App\Models\Settings\Accounts\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'suppliers';

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
    protected $fillable = ['code', 'name', 'mobile', 'address', 'details', 'balance'];

    /**
     * @return $this
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class)->orderBy('purchase_date', 'desc');
    }

    /**
     * @param $id
     * @return bool
     */
    public static function balanceUpdate($id)
    {
        $supplier = Supplier::findOrFail($id);

        $grandTotal = Purchase::whereSupplierId($id)->sum('total_amount') + Transaction::whereType('receipt')->whereCategory('supplier')->whereReceiverId($id)->sum('debit');
        $paidAmount = self::paidAmount($id);

        $supplier->update([
            'balance' => ($grandTotal - $paidAmount)
        ]);
        return $supplier;
    }

    /**
     * paid amount to supplier
     * @param $id
     * @return mixed
     */
    public static function paidAmount($id)
    {
        $paidAmount = Transaction::whereType('payment')->whereCategory('supplier')->whereReceiverId($id)->sum('credit');
        return $paidAmount;
    }
}
