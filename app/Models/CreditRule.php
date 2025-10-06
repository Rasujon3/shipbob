<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CreditRule extends Model
{
    use HasFactory;

    protected $table = 'credit_rules';

    protected $fillable = [
        'description'
    ];
}
