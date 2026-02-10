<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProdutosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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
            'nome' => ['required','string','max:255', 'min:3'],
            'descricao' => ['nullable','string','max:255'],
            'preco' => ['required','numeric','min:0'],
            'estoque' => ['nullable','integer','min:0'],
            'categorias' => ['nullable','string'],
        ];
    }
}
