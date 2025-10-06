<?php

namespace App\Http\Requests;

use App\Models\AssignLevel;
use App\Models\AssignPackage;
use App\Models\FrozenAmount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class LevelAssignRequest extends FormRequest
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
        $assignLevel = $this->route('level_assign');
        $id = $assignLevel?->id ?? null;

        return AssignLevel::rules($id);
    }
}
