<?php

namespace App\Http\Requests\Setup;

use Illuminate\Foundation\Http\FormRequest;

class MarkingPeriodCreate extends FormRequest
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
            'mp_type'=>'required',
            'title'=>'required',
            'short_name'=>'required',
            'parent_id'=>'integer',
            'start_date'=>'date|required',
            'end_date'=>'date|required',
            'post_start_date'=>'sometimes',
            'post_start_date'=>'sometimes',
            'does_grade'=>'sometimes',
            'does_exams'=>'sometimes',
            'does_comments'=>'sometimes',
        ];
    }
}
