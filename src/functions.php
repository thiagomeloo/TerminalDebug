<?php

if (!function_exists('tDebug')) {

    /**
     * Print a message in terminal debug.
     * @param string|aray|object $element
     */
    function tDebug(string|array|object $element)
    {
        try {
            $client = new \ThiagoMeloo\TerminalDebug\Socket\Client([
                'host' => '127.0.0.1',
                'port' => 8015
            ]);

            $client->setMessage($element)->run();
        } catch (\Throwable $th) {
            return false;
        }
    };
}
