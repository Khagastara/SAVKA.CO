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
        'shipment_id',
        'product_id',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
