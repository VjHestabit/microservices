<?php

namespace App\Http\Requests;

use App\Helpers\CustomHelper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
        return   [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'address' => 'required'
        ]+
        ($this->isMethod('POST') ? $this->store() : $this->update());
    }
    protected function store()
    {
       $rules = [
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            ];
        if($this->input('role_id') == CustomHelper::STUDENT){
                $rules['current_school'] = 'required';
                $rules['previous_school'] = 'required';
                $rules['father_name'] = 'required';
                $rules['mother_name'] = 'required';
            }
        if($this->input('role_id') == CustomHelper::TEACHER){
            $rules['exp'] ='required';
            $rules['subject_name'] = 'required';
        }
        return $rules;
    }

    protected function update()
    {
        $rules = [
            // 'token' => 'required',
            'email' => 'required|email|unique:users,email,'.$this->user()->id,
            ];

            if($this->input('role_id') == CustomHelper::STUDENT){
                $rules['current_school'] = 'required';
                $rules['previous_school'] = 'required';
                $rules['father_name'] = 'required';
                $rules['mother_name'] = 'required';
            }
        if($this->input('role_id') == CustomHelper::TEACHER){
            $rules['exp'] ='required';
            $rules['subject_name'] = 'required';
        }

        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $validator->errors()
        ]));
    }
}
