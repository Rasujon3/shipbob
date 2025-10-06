<?php

namespace App\Http\Requests;

use App\Models\FrozenAmount;
use Illuminate\Foundation\Http\FormRequest;

class FrozenAmountRequest extends FormRequest
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
        $frozenAmount = $this->route('frozen-amounts');
        $id = $frozenAmount?->id ?? null;

        return FrozenAmount::rules($id);
    }
}
