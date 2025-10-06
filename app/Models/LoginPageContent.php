<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginPageContent extends Model
{
    use HasFactory;

    protected $table = 'login_page_contents';

    protected $fillable = [
        'name',
        'title',
        'img',
        'description'
    ];
}
