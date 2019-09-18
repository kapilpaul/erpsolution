<?php

namespace App\Models\Settings\Accounts;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'accounts';

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
    protected $fillable = ['code', 'name', 'balance'];

    /**
     * @param $date
     * @return string
     */
    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d M Y h:i:s A');
    }

    /**
     * @param $date
     * @return string
     */
    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d M Y h:i:s A');
    }

    /**
     * set name attribute as accessor
     * @param $name
     */
    public function setNameAttribute($name)
    {
        $this->attributes['name'] = ucwords($name);
    }

    /**
     * @param $id
     * @return bool
     */
    public static function balanceUpdate($id)
    {
        if($account = Account::find($id) ) {
            $total = Transaction::where('type', 'payment')->where('category', 'office')->where('receiver_id', $account->id)->sum('credit');

            $account->update([
                'balance' => $total
            ]);
            return $account;
        }

        return false;
    }
}
