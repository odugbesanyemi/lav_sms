<?php

namespace App\Http\Requests\Setup;

use Illuminate\Foundation\Http\FormRequest;

class GradeLevelsCreate extends FormRequest
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
            'school_id'=>'required',
            'acad_year_id'=>'required',
            'title'=>'required',
            'short_name'=>'required',
            'next_grade_id'=>'sometimes'
        ];
    }
}
