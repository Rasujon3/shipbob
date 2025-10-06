<?php

namespace App\Http\Requests;

use App\Models\InvitationCode;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class InvitationCodeRequest extends FormRequest
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
        $data = $this->route('invitation_code');
        $id = $data?->id ?? null;

        return InvitationCode::rules($id);
    }
}
