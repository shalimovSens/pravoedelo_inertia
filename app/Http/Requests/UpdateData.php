<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateData extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() : array
    {
        return [
            'id' => 'required|integer|exists:users',
            'first_name' => 'sometimes|required|string',
            'middle_name' => 'sometimes|required|string',
            'last_name' => 'sometimes|required|string',
            'lawyer_id' => 'sometimes|required|integer',
            'manager_id' => 'sometimes|required|integer',
            'first_date' => 'sometimes|required|integer|between:1,31',
            'second_date' => 'sometimes|required|integer|between:1,31',
        ];
    }
}
