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
        'user_id',
        'report_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->where('role', 'Owner');
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id', 'id');
    }

    public function procurementDetail()
    {
        return $this->hasMany(ProcurementDetail::class, 'procurement_id', 'id');
    }
}
