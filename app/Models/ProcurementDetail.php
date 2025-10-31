<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcurementDetail extends Model
{
    use HasFactory;
    protected $table = 'procurement_details';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'quantity',
        'procurement_id',
        'material_id',
    ];

    public function procurement()
    {
        return $this->belongsTo(Procurement::class, 'procurement_id', 'id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'id');
    }
}
