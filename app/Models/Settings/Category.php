<?php

namespace App\Models\Settings;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'categories';

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
    protected $fillable = ['slug', 'name'];

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d M Y h:i:s A');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('d M Y h:i:s A');
    }
}
