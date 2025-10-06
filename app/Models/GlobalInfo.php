<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalInfo extends Model
{
    use HasFactory;

    protected $table = 'global_infos';

    protected $fillable = [
        'title',
        'description',
        'img'
    ];
}
