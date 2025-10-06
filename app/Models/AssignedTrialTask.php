<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class AssignedTrialTask extends Model
{
    use HasFactory;

    public static function rules($id = null)
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('assigned_trial_tasks', 'user_id')->ignore($id)
            ],
            'trial_task_id' => 'required|integer|exists:trial_tasks,id',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function trialTask()
    {
        return $this->belongsTo(TrialTask::class);
    }
}
