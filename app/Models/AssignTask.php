<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AssignTask extends Model
{
    use HasFactory;

    public static function rules($id = null)
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'task_id' => 'required|integer|exists:tasks,id',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
