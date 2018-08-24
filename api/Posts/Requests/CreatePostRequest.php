<?php

namespace Api\Posts\Requests;

use Infrastructure\Http\ApiRequest;

class CreatePostRequest extends ApiRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'post' => 'array|required',
            'post.email' => 'required|email',
            'post.name' => 'required|string',
            'post.password' => 'required|string|min:8'
        ];
    }

    public function attributes()
    {
        return [
            'post.email' => 'the post\'s email'
        ];
    }
}
