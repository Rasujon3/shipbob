<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RTTProduct extends Model
{
    use HasFactory;

    protected $table = 'r_t_t_products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'file',
        'commission',
    ];

}
