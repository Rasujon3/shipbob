<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public static function rules()
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'num_of_task' => ['required', 'numeric', 'max:191'],
            #'commission' => ['required', 'string', 'max:191'],
            #'time_duration' => ['required', 'numeric', 'max:191'],
            #'time_unit' => ['required', 'string', 'max:191'],
        ];
    }
    public function assignTask()
    {
        return $this->hasMany(AssignTask::class);
    }
    public function assignTrialTask()
    {
        return $this->hasOne(AssignedTrialTask::class);
    }
    public function order()
    {
        return $this->hasMany(Order::class);
    }
}
