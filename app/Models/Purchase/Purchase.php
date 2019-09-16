<?php

namespace App\Models\Purchase;

use App\Models\Settings\Product;
use App\Models\Settings\Supplier;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'purchases';

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
        'id', 'invoice_no', "vehicle_no", 'purchase_no', 'purchase_date', 'details', 'total_amount', 'supplier_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'purchase_products', 'purchase_id', 'product_id')
                    ->withPivot('purchase_id', 'product_id', 'quantity', 'price', 'total')
                    ->withTimestamps();
    }

    /**
     * @param $date
     * @return string
     */
    public function getPurchaseDateAttribute($date)
    {
        return Carbon::parse($date)->format('d M Y');
    }
}
