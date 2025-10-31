<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    protected $table = 'shipments';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'shipment_date',
        'destination_address',
        'total_price',
        'shipment_status',
        'user_id',
        'report_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->where('role', ['Owner', 'Distribution Staff']);
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function shipmentDetail()
    {
        return $this->hasMany(ShipmentDetail::class, 'shipment_id');
    }

    public function historyDemand()
    {
        return $this->hasMany(HistoryDemand::class, 'shipment_id');
    }
}
