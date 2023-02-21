<?php

namespace ThiagoMeloo\TerminalDebug\Helpers;


class PrettyJson {

    /**
     * Transform a json string in a pretty json string with line breaks
     * 
     * @param string|object|array $json
     * @return string
     */
    public static function parse(string|object|array $json): string
    {
        if (is_string($json)) {
            $json = json_decode($json, true);
        }

        if (is_object($json)) {
            $json = json_decode(json_encode($json), true);
        }

        $json = json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return PHP_EOL.$json;

    }

    /**
     * Check if a string is a json
     * 
     * @param string $string
     * @return bool
     */
    public static function isJson(string $string): bool
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

}