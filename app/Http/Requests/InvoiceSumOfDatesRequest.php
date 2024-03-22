<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InvoiceSumOfDatesRequest extends FormRequest
{
    public function failedValidation(Validator $validator){
        throw new HttpResponseException(response()->json($validator->errors(), 400));
    }
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'startDate'=>'required|date',
            'endDate'=>'required|date'
        ];
    }
}
