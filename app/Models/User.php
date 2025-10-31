<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $keyType = 'int';

    protected $fillable = [
        'username',
        'email',
        'password',
        'name',
        'phone_number',
        'address',
        'role',
        'account_id',
    ];

    protected $hidden = [
        'password',
    ];

    public function materials()
    {
        return $this->hasMany(Material::class, 'user_id')
            ->where('role', ['Owner', 'Production Staff']);
    }

    public function productions()
    {
        return $this->hasMany(Production::class, 'user_id')
            ->where('role', ['Owner', 'Production Staff']);
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'user_id')
            ->where('role', ['Owner', 'Distribution Staff']);
    }
}
