<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forecasting extends Model
{
    use HasFactory;
    protected $table = 'forecastings';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'forecast_date',
        'week_used',
        'predicted_demand',
        'accuracy'
    ];

    public function historyDemand()
    {
        return $this->hasMany(HistoryDemand::class, 'forecasting_id');
    }
}
