<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Remittance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'paid',
        'type',
        'amount',
        'payed_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
