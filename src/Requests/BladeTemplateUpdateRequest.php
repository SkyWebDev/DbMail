<?php

namespace SkyWebDev\DbMail\Requests;

use Illuminate\Foundation\Http\FormRequest;
use SkyWebDev\DbMail\Rules\InternalLinkOnly;

/**
 * @property string $template_class
 * @property string $template_name
 * @property string $template_type
 * @property string $subject
 * @property string $body
 */
class BladeTemplateUpdateRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'template_class' => ['required', 'string'],
            'template_name' => ['required', 'string'],
            'subject' => 'required|string',
            'body' => ['required', 'string', new InternalLinkOnly()],
        ];
    }
}
