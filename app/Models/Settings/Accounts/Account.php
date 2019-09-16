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
    protected $fillable = ['code', 'name'];

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
     * @param $date
     */
    public function setNameAttribute($name)
    {
        $this->attributes['name'] = ucwords($name);
    }
}
