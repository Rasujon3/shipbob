<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class FrozenAmount extends Model
{
    use HasFactory;

    public static function rules($id)
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('assign_packages', 'user_id')->ignore($id)
            ],
            'amount' => 'required|numeric|min:1',
            'task_will_block' => 'required|numeric|min:1',
            'value' => 'required|numeric|min:1',
            'unit' => 'required|in:X,TAKA',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
