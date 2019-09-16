<?php

namespace App\Models\Settings;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
Use DB;

class Product extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'products';

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
        'code',
        'name',
        'model',
        'barcode',
        'price',
        'sale_price',
        'unit',
        'description',
        'stock',
        'in_quantity',
        'out_quantity',
        'category_id',
        'photo_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('App\Models\Settings\Category');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function photo()
    {
        return $this->belongsTo('App\Models\Photo\Photo');
    }


    /**
     * Product Stock, In and Out quantity
     * @param $id
     * @return bool
     */
    public static function updateProductStockAndOutQty($id)
    {
        $item = Product::findOrFail($id);

        $itemInQty = DB::table('purchase_products')
            ->whereProductId($item->id)
            ->whereNull('deleted_at')
            ->sum('quantity');

        $itemOutQty = DB::table('invoice_products')
            ->whereProductId($item->id)
            ->whereNull('deleted_at')
            ->sum('quantity');

        $item->update([
            'stock' => ($itemInQty - $itemOutQty),
            'in_quantity' => $itemInQty,
            'out_quantity' => $itemOutQty
        ]);

        return true;
    }


    /**
     * set code attribute as accessor
     * @param $date
     */
    public function setCodeAttribute($code)
    {
        $this->attributes['code'] = str_random(15);
    }
}
