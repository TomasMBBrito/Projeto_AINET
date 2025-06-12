<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompleteOrderRequest extends FormRequest
{
    public function authorize()
    {
        // Aqui podes controlar quem pode completar a encomenda
        // Exemplo: só admin e board
        return in_array($this->user()->type, ['admin', 'board']);
    }

    public function rules()
    {
        return [
            // Nenhuma regra de validação necessária, pois só mudamos estado
        ];
    }
}
