<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryDemand extends Model
{
    use HasFactory;
    protected $table = 'history_demands';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'week_number',
        'month',
        'year',
        'demand_quantity',
    ];

    public function shipment()
    {
        return $this->hasMany(Shipment::class, 'shipment_id', 'id');
    }

    public function forecasting()
    {
        return $this->hasOne(Forecasting::class, 'forecasting_id', 'id');
    }
}
