<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSectionContent extends Model
{
    use HasFactory;

    protected $table = 'hero_section_contents';

    protected $fillable = [
        'title',
        'sub_title',
        'slogan',
        'banner_img',
        'video_url'
    ];
}
