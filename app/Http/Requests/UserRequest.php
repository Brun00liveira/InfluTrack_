<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'cpf' => 'required|string|max:255',
            'phone' => ['required'],
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'cep' => 'nullable|string|max:255',
            'birth_date' => 'nullable|date',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
            'is_active' => 'nullable'
        ];

        if ($this->isMethod('put') || $this->isMethod('patch')) {

            $rules['name'] = 'nullable|string|max:255';
            $rules['email'] = 'nullable|email|unique:users,email,' . $this->route('user');
            $rules['cpf'] = 'nullable|string|max:255';
            $rules['phone'] = ['nullable', 'regex:/^\+?\d{10,15}$/'];
            $rules['address'] = 'nullable|string|max:255';
            $rules['city'] = 'nullable|string|max:255';
            $rules['state'] = 'nullable|string|max:255';
            $rules['country'] = 'nullable|string|max:255';
            $rules['cep'] = 'nullable|string|max:255';
            $rules['birth_date'] = 'nullable|date';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.max' => 'O nome deve ter no máximo 255 caracteres.',

            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'O e-mail deve ser válido.',
            'email.unique' => 'Este e-mail já está em uso.',

            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo 8 caracteres.',

            'phone.required' => 'O número de telefone é obrigatório.',
            'phone.regex' => 'O número de telefone deve ser válido (apenas números, com 10 a 15 dígitos e pode incluir o símbolo de + no início).',

            'photo.image' => 'A foto deve ser uma imagem.',
            'photo.max' => 'A foto não pode ultrapassar 2MB.',
        ];
    }
}