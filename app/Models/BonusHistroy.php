<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BonusHistroy extends Model
{
    use HasFactory;

    protected $table = 'bonus_histroys';
    protected $fillable = [
        'user_id',
        'title',
        'amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
