<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    public static function rules()
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'description' => 'required|string',
        ];
    }
    public function assignLevel()
    {
        return $this->hasMany(AssignLevel::class);
    }
}
