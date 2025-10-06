<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashInImg extends Model
{
    use HasFactory;

    protected $table = 'cash_in_imgs';

    protected $fillable = [
        'img'
    ];
}
