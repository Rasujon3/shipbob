<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RTTAssignTask extends Model
{
    use HasFactory;

    protected $table = 'r_t_t_assign_tasks';

    protected $fillable = [
        'user_id',
        'rtt_task_id',
        'num_of_tasks',
        'status',
    ];

    public static function rules($id = null)
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'rtt_task_id' => ['required', 'integer', 'exists:r_t_t_tasks,id'],
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function rttTask()
    {
        return $this->belongsTo(RTTTask::class);
    }
}
