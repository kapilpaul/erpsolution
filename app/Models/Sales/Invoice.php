<?php

namespace App\Models\Sales;

use App\Models\Customer\Customer;
use App\Models\Settings\Product;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'invoices';

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
        'date', 'vehicle_no', 'destination', 'invoice_no', 'details', 'total_amount', 'total_tax', 'total_tax', 'total_discount', 'grand_total', 'customer_id'
    ];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'invoice_products', 'invoice_id', 'product_id')
            ->withPivot('invoice_id', 'product_id', 'quantity', 'price', 'discount', 'total')
            ->withTimestamps();
    }

    /**
     * @param $date
     * @return string
     */
    public function getDateAttribute($date)
    {
        return Carbon::parse($date)->format('d M Y');
    }

    /**
     * set Date attribute as accessor
     * @param $date
     */
    public function setDateAttribute($date)
    {
        $this->attributes['date'] = Carbon::parse($date)->format('Y-m-d');
    }

}
