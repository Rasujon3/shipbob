<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourierSectionContent extends Model
{
    use HasFactory;

    protected $table = 'courier_section_contents';

    protected $fillable = [
        'title',
        'description',
        'video'
    ];
}
