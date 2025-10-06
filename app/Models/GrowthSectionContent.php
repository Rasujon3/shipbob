<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrowthSectionContent extends Model
{
    use HasFactory;

    protected $table = 'growth_section_contents';

    protected $fillable = [
        'img',
        'title',
        'sub_title',
        'description'
    ];
}
