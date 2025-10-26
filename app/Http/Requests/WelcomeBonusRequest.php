<?php

namespace App\Http\Requests;

use App\Models\Credit;
use App\Models\Event;
use App\Models\WelcomeBonus;
use Illuminate\Foundation\Http\FormRequest;

class WelcomeBonusRequest extends FormRequest
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
        // Get the route name and apply null-safe operator
        $routeName = $this->route()?->getName();

        if ($routeName === 'welcome-bonuses.update') {
            return WelcomeBonus::rules();
        }

        return WelcomeBonus::rules();
    }
}
