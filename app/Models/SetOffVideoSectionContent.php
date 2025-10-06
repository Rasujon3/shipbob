<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetOffVideoSectionContent extends Model
{
    use HasFactory;

    protected $table = 'set_off_video_section_contents';

    protected $fillable = [
        'video'
    ];
}
