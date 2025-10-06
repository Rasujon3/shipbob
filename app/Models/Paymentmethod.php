<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paymentmethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mobile_no',
        'account_holder',
        'account_number',
        'bank_name',
        'branch_name',
        'routing_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function cashOut()
    {
        return $this->hasOne(Cashout::class);
    }
}
