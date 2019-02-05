<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LinkRequest extends FormRequest
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
        $regex = '/^(https*?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/';

        return [
            'standard_link' => 'required|min:5|max:255|unique:links|regex:' . $regex,
        ];
    }

    /**
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'Введите ссылку!',
            'min'      => 'Поле имеет минимальный порог вхождения в 5 символов!',
            'max'      => 'Длинна URL имеет ограничение в 255 символов!',
            'unique'   => 'Данный адрес сайта уже существует!',
            'regex'    => 'Адрес сайта должен иметь http(s)!',
        ];
    }
}
