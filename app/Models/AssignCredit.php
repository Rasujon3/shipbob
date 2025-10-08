<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignCredit extends Model
{
    use HasFactory;

    public static function rules($id = null)
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'credit_id' => 'required|integer|exists:credits,id',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function credit()
    {
        return $this->belongsTo(Credit::class, 'credit_id');
    }
}
