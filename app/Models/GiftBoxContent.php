<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftBoxContent extends Model
{
    use HasFactory;

    protected $table = 'gift_box_contents';

    protected $fillable = [
        'title'
    ];
}
