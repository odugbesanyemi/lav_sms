<?php

namespace App\Http\Requests\Setup;

use Illuminate\Foundation\Http\FormRequest;

class SchoolRecordCreate extends FormRequest
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
            'name' => 'required|string|min:6|max:150|unique:schools,name',
            'address' => 'sometimes|nullable|string|min:6|max:150',
            'email' => 'required|string|min:6|max:150',
            'generic_name' => 'required|string|min:6|max:255',
            'principal' => 'sometimes|nullable|string|min:6|max:150',
            'phone' => 'required',
            'telephone' => 'sometimes|nullable|string|min:6|max:150',
            'nationality' => 'sometimes|nullable',
            'state' => 'sometimes|nullable',
            'lga' => 'sometimes|nullable',
            'active' => 'sometimes|nullable',
            'logo' => 'sometimes|nullable',
        ];
    }

    public function attributes()
    {
        return [
            'name'=> 'school name',
            'state'=> 'State',
            'lga'=> 'LGA',
            'logo'=> 'School Logo'
        ];
    }
}
