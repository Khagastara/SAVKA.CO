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
        'material_quantity',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->where('role', ['Owner', 'Production Staff']);
    }

    public function procurementDetail()
    {
        return $this->hasMany(ProcurementDetail::class, 'material_id');
    }

    public function production()
    {
        return $this->hasMany(Production::class, 'material_id');
    }
}
