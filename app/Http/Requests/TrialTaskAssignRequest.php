<?php

namespace App\Http\Requests;

use App\Models\AssignedTrialTask;
use App\Models\AssignLevel;
use App\Models\AssignPackage;
use App\Models\AssignTask;
use App\Models\FrozenAmount;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class TrialTaskAssignRequest extends FormRequest
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
        $trial_task_assign = $this->route('trial_task_assign');
        $id = $trial_task_assign?->id ?? null;

        return AssignedTrialTask::rules($id);
    }
}
