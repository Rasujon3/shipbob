<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AssignPackage extends Model
{
    use HasFactory;

    public static function rules($id = null)
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('assign_packages', 'user_id')->ignore($id)
            ],
            'package_id' => 'required|integer|exists:packages,id',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
