<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class TableReservationRequest extends FormRequest
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
            "persons"=> "required|numeric",
            "reservation_date"=> 'required|date|date_format:Y-m-d',
            "from_time"=> "required|date_format:H:i:s",
            "to_time"=> "required|date_format:H:i:s",
        ];
    }
}
