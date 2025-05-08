<?php

namespace SkyWebDev\DbMail\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $template_name
 * @property string $class_name
 * @property string $subject
 * @property string $body
 */
class BladeTemplateCreateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'class_name' => 'required|string|unique:blade_templates,class_name',
            'template_name' => 'required|string|unique:blade_templates,template_name',
            'subject' => 'nullable|string',
            'body' => 'required|string',
        ];
    }
}
