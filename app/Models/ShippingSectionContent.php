<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingSectionContent extends Model
{
    use HasFactory;

    protected $table = 'shipping_section_contents';

    protected $fillable = [
        'img',
        'title',
        'description',
        'img2'
    ];
}
