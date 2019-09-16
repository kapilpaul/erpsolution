<?php

namespace App\Models\Bank;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Transaction extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'banktransactions';

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
        'date', 'debit', 'credit', 'slip_id', 'description', 'bank_id'
    ];


    /**
     * @param $date
     * @return string
     */
    public function getDateAttribute($date)
    {
        return Carbon::parse($date)->format('d M Y');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    /**
     * set Date attribute formatted as Y-m-d
     * @param $value
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = Carbon::parse($value)->format('Y-m-d');
    }

}
