<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RTTOrder extends Model
{
    use HasFactory;

    protected $table = 'r_t_t_orders';

    protected $fillable = [
        'order_number',
        'user_id',
        'rtt_task_id',
        'rtt_product_id',
        'amount',
        'status',
        'completed_at',
    ];

    public static function rules()
    {
        return [
            'product_id' => 'required|integer|exists:r_t_t_products,id',
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

    public function rttProduct()
    {
        return $this->belongsTo(RTTProduct::class);
    }
}
