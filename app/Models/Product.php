<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'product_name',
        'product_color',
        'product_size',
        'product_stock',
        'product_price',
    ];

    public function productDetail()
    {
        return $this->hasMany(ProductDetail::class, 'product_id');
    }

    public function shipmentDetails()
    {
        return $this->hasMany(ShipmentDetail::class, 'product_id');
    }
}
