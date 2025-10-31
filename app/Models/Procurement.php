<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procurement extends Model
{
    use HasFactory;
    protected $table = 'procurements';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'procurement_date',
        'total_cost',
        'report_id',
    ];

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }

    public function procurementDetail()
    {
        return $this->hasMany(ProcurementDetail::class, 'procurement_id');
    }
}
