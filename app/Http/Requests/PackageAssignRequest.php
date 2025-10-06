<?php

namespace App\Http\Requests;

use App\Models\AssignPackage;
use App\Models\FrozenAmount;
use Illuminate\Foundation\Http\FormRequest;

class PackageAssignRequest extends FormRequest
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
        $assignPackage = $this->route('package_assign');
        $id = $assignPackage?->id ?? null;

        return AssignPackage::rules($id);
    }
}
