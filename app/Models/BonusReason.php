<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusReason extends Model
{
    use HasFactory;

    protected $table = 'bonus_reasons';
    protected $fillable = [
        'title'
    ];
}
