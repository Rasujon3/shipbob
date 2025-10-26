<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WelcomeBonus extends Model
{
    use HasFactory;

    protected $table = 'welcome_bonuses';

    protected $fillable = [
        'user_id',
        'amount',
        'num_of_tasks',
        'completed_tasks',
        'remaining_tasks',
        'status',
    ];

    public static function rules($id = null)
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'amount' => 'required|numeric|min:1|max:999999.99',
            'num_of_tasks' => 'required|integer|min:0|max:999999',
            'completed_tasks' => 'nullable|integer|min:0|max:999999',
            'remaining_tasks' => 'nullable|integer|min:0|max:999999',
            'status' => 'nullable|in:Complete,Incomplete',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
