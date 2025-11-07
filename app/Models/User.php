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

    public function procurement()
    {
        return $this->hasMany(Procurement::class, 'user_id', 'id')
            ->where('role', 'Owner');
    }

    public function productions()
    {
        return $this->hasMany(Production::class, 'user_id', 'id');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'user_id', 'id')
            ->whereIn('role', ['Owner', 'Distribution Staff']);
    }
}
