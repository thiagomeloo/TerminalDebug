<?php

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
            } else if (count($elements) > 1) {
                ob_start();
                var_dump($elements);
                $data = ob_get_clean();
            } else {
                ob_start();
                var_dump($elements[0]);
                $data = ob_get_clean();
            }

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
