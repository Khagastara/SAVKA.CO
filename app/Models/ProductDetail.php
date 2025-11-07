<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDetail extends Model
{
    use HasFactory;
    protected $table = 'product_details';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'product_size',
        'product_stock',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function shipmentDetails()
    {
        return $this->hasMany(ShipmentDetail::class, 'product_detail_id', 'id');
    }
}
