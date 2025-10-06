<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignUpContent extends Model
{
    use HasFactory;

    protected $table = 'sign_up_contents';

    protected $fillable = [
        'title'
    ];
}
