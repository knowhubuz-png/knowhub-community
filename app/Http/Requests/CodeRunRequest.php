<?php


// file: app/Http/Requests/CodeRunRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CodeRunRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }
    public function rules(): array {
        return [
            'language' => ['required','in:javascript,python,php,js'],
            'source' => ['required','string','max:20000'],
            'post_slug' => ['nullable','string'],
            'comment_id' => ['nullable','integer','exists:comments,id'],
        ];
    }
}

