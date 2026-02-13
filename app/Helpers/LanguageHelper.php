<?php

if (!function_exists('msg')) {
    /**
     * Get translated message
     *
     * @param string $key
     * @param array $replace
     * @param string $locale
     * @return string
     */
    function msg($key, $replace = [], $locale = null)
    {
        $locale = $locale ?? session('locale', 'id');

        $file = resource_path("lang/{$locale}/messages.php");

        if (!file_exists($file)) {
            return $key;
        }

        $messages = include $file;

        if (!isset($messages[$key])) {
            return $key;
        }

        $message = $messages[$key];

        // Replace placeholders
        foreach ($replace as $key => $value) {
            $message = str_replace(":{$key}", $value, $message);
        }

        return $message;
    }
}

if (!function_exists('get_locale')) {
    /**
     * Get current locale
     *
     * @return string
     */
    function get_locale()
    {
        return session('locale', 'id');
    }
}

if (!function_exists('set_locale')) {
    /**
     * Set current locale
     *
     * @param string $locale
     * @return void
     */
    function set_locale($locale)
    {
        session(['locale' => $locale]);
    }
}
