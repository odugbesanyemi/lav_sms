<?php

namespace App\Http\Requests\Setup;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class AcademicYearCreate extends FormRequest
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
            'title'=> [
                'required',
                'unique:academic_calendar,title,NULL,id,school_id,' . $this->school_id,
            ],
            'default'=> 'nullable',
            'start_date'=>'required',
            'end_date'=>'required'
        ];
    }
}
