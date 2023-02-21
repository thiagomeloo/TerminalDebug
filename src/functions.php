<?php

if (!function_exists('td')) {
    
    /**
     * Print a message in terminal debug.
     * @param string|aray|object $element
     */
    return function (string|array|object $element) {

        $client = new \ThiagoMeloo\TerminalDebug\Socket\Client([
            'host' => '127.0.0.1',
            'port' => 8015
        ]);
    
        $client->setMessage($element)->run();
    };

}
