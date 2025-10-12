<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Gift extends Model
{
    use HasFactory;

    protected $table = 'gifts';

    protected $fillable = [
        'user_id',
        'task_will_block',
        'frozen_amounts',
        'frozen_amount_task_will_block',
        'frozen_value',
        'frozen_unit'
    ];

    public static function rules($id = null)
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                Rule::unique('gifts', 'user_id')->ignore($id)
            ],
            'task_will_block' => 'required|numeric|min:1|max:999999',
            'frozen_amounts' => 'nullable|required_with:frozen_amount_task_will_block|numeric|min:1',
            'frozen_amount_task_will_block' => 'nullable|required_with:frozen_amounts|numeric|min:1|max:999999',
            'frozen_value' => 'nullable|required_with:frozen_amounts|numeric|min:1|max:999999',
            'frozen_unit' => 'nullable|required_with:frozen_amounts|string|in:Taka,X',

            // Gift boxes validation
            'gift_boxes' => 'required|array|min:1|max:3',
            'gift_boxes.*.value' => 'required|numeric|min:1',
            'gift_boxes.*.unit' => 'required|string|in:Taka,X',

            // Active gift validation - only one can be active
            'active_gift' => 'required|integer|min:0|max:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function giftBox()
    {
        return $this->hasMany(GiftBox::class);
    }
}
