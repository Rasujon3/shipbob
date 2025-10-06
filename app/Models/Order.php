<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'product_id',
        'amount',
        'completed_at',
        'is_trial_task',
        'task_id',
        'is_completed',
    ];

    public static function rules()
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
