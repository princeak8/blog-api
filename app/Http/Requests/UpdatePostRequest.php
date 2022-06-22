<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePostRequest extends FormRequest
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
            // 'title' => 'required|max:255|unique:posts,title,null,id,user_id,'.auth::user()->id,
            'title' => 'string|max:255',
            'content' => 'string|min:1',
            'category_id' => 'integer',
            'tags_id' => 'array',
            'tags_id.*' => 'integer',
            'cover_photo_id' => 'integer'
        ];
    }

    /**
     * If validator fails return the exception in json form
     * @param Validator $validator
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'statusCode' => 422,
                'errors' => $validator->errors()
            ], 422)
        );
    }
}
