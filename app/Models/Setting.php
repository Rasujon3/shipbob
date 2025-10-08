<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'trial_amount',
        'frozen_amount',
        'no_of_trial_task',
        'task_timing',
        'telegram_group_link',
        'company_name',
        'company_logo',
        'daily_task_limit',
    ];
}
