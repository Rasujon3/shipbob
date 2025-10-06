<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Package extends Model
{
    use HasFactory;

    public static function rules()
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'description' => 'nullable|string',
            'recharge_amount' => 'required|integer|min:1',
            'bonus_amount' => 'required|integer|min:1',
        ];
    }
    public function assignPackage()
    {
        return $this->hasMany(AssignPackage::class);
    }
}
