<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Production extends Model
{
    use HasFactory;
    protected $table = 'productions';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'production_date',
        'quantity_produced',
        'material_used',
        'status',
        'user_id',
        'product_detail_id',
        'material_id',
        'report_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')
            ->where('role', ['Owner', 'Production Staff']);
    }

    public function productDetail()
    {
        return $this->belongsTo(ProductDetail::class, 'product_detail_id');
    }

    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
