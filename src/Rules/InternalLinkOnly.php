<?php

namespace SkyWebDev\DbMail\Rules;

use Illuminate\Contracts\Validation\Rule;

class InternalLinkOnly implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $url_regex = '/(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/';
        if (preg_match_all($url_regex, $value, $matches)) {
            foreach ($matches[0] as $url) {
                $host = parse_url($url, PHP_URL_HOST);
                $app_host = parse_url(config('app.client_front_url'), PHP_URL_HOST);
                if ($host != $app_host) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'External link(s) are forbidden in templates!';
    }
}
