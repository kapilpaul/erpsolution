<?php

namespace App\Models\Common;

use App\Models\Settings\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Returns extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'returns';

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
        'code', 'type', 'usertype', 'receiver_id', 'total', 'description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_return', 'return_id', 'product_id')
                    ->withPivot('return_id', 'product_id', 'quantity', 'price')
                    ->withTimestamps();
    }
}
