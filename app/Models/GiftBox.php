<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiftBox extends Model
{
    use HasFactory;

    protected $table = 'gift_boxes';

    protected $fillable = [
        'gift_id',
        'value',
        'unit',
        'is_active'
    ];

    public function gift()
    {
        return $this->belongsTo(Gift::class);
    }
}
