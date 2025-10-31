<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;
    protected $table = 'reports';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'report_date',
        'report_content',
        'income',
        'expenses',
    ];

    public function procurement()
    {
        return $this->hasMany(Procurement::class, 'report_id', 'id');
    }

    public function production()
    {
        return $this->hasMany(Production::class, 'report_id', 'id');
    }

    public function shipment()
    {
        return $this->hasMany(Shipment::class, 'report_id', 'id');
    }
}
