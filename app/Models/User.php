<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 
        'last_name',
        'middle_name',
        'manager_id',
        'lawyer_id',
        'type',
        'price',
        'payment_per_month',
        'plan',
        'first_date',
        'second_date',
        'transfer_start',
        'transfer_stop',
        'email',
        'password',
        'date_registration'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function roles() 
    {
        return $this->belongsToMany(Role::class);
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function account()
    {
        return $this->hasOne(Account::class);
    }

    public function remittances()
    {
        return $this->hasMany(Remittance::class);
    }

}
