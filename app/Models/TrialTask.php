<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrialTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'num_of_task',
        'commission',
        'time_duration',
        'time_unit',
    ];
}
