<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RTTTask extends Model
{
    use HasFactory;

    protected $table = 'r_t_t_tasks';

    protected $fillable = [
        'title',
        'num_of_task',
    ];

    public static function rules()
    {
        return [
            'title' => ['required', 'string', 'max:191'],
            'num_of_task' => ['required', 'numeric', 'max:191'],
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
