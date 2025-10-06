<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class InvitationCode extends Model
{
    use HasFactory;

    protected $table = 'invitation_codes';

    protected $fillable = [
        'user_id',
        'code',
        'note'
    ];

    public static function rules($id)
    {
        return [
            'user_id' => 'nullable|integer|exists:users,id',
            #'code' => 'required|string|unique::invitation_codes,code',
            'code' => [
                'required',
                'string',
                Rule::unique('invitation_codes', 'code')->ignore($id)
            ],
            'note' => 'nullable|string',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
