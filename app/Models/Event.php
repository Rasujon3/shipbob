<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    public static function rules()
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'file' => 'required|file',
        ];
    }

    public static function updateRules()
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'file' => 'nullable|file',
        ];
    }
}
