<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "reservation_id"    => [request()->ismethod("POST") ? "required": "sometimes", "numeric", "exists:reservations,id"],
            "type"              => "required|string|in:sync,increment",
            "meals"             => "required|array",
            "meals.*.id"        => "required|numeric|exists:meals,id",
            "meals.*.amount"    => "required|numeric|min:1",
        ];
    }
}
