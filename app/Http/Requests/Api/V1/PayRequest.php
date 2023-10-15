<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class PayRequest extends FormRequest
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
            "reservation_id"=> "required|numeric|exists:reservations,id",
            "checkout"=> "required|string|in:service_only,service_tax",
            "service"=> "sometimes|nullable|numeric|min:0",
            "tax"=> "sometimes|nullable|numeric|min:0",
        ];
    }
}
