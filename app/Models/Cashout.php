<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cashout extends Model
{
    use HasFactory;

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(Paymentmethod::class, 'paymentmethod_id');
    }
}
