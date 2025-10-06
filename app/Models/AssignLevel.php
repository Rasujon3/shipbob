<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AssignLevel extends Model
{
    use HasFactory;

    public static function rules($id = null)
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('assign_levels', 'user_id')->ignore($id)
            ],
            'level_id' => 'required|integer|exists:levels,id',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function level()
    {
        return $this->belongsTo(Level::class);
    }
}
