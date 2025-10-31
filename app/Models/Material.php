<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'material_name',
        'material_color',
        'material_quantity',
    ];

    public function procurementDetail()
    {
        return $this->hasMany(ProcurementDetail::class, 'material_id', 'id');
    }

    public function production()
    {
        return $this->hasMany(Production::class, 'material_id', 'id');
    }
}
