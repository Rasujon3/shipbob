<?php

namespace App\Http\Requests;

use App\Models\Cashin;
use Illuminate\Foundation\Http\FormRequest;

class StoreCashInRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'cash_in_amount' => 'required|numeric',
        ];
    }
}
