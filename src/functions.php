<?php

use ThiagoMeloo\TerminalDebug\Helpers\Dump;

if (!function_exists('tDebug')) {

    /**
     * Print params in terminal debug server.
     * 
     * @param mixed ...$elements 
     */
    function tDebug(...$elements)
    {
        try {
            if (!count($elements)) {
                return false;
            }

            $data = Dump::toString(...$elements);

            $client = new \ThiagoMeloo\TerminalDebug\Socket\Client([
                'host' => '127.0.0.1',
                'port' => 8015
            ]);

            $client->setMessage($data)->run();

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    };
}
