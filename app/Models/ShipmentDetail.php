<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentDetail extends Model
{
    use HasFactory;
    protected $table = 'shipment_details';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'product_quantity',
        'sub_total',
        'shipment_id',
        'product_detail_id',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id', 'id');
    }

    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id', 'id');
    }
}
