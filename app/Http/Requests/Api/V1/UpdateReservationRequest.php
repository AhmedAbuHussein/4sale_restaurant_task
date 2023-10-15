<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReservationRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            "table_id"=> "required|numeric|exists:tables,id",
            "status"=> "required|string|in:confirmed,cancel,waiting",
            "reservation_date"=> "sometimes|nullable|date|date_format:Y-m-d",
        ];
    }
}
