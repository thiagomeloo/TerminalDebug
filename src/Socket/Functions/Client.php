<?php

namespace ThiagoMeloo\TerminalDebug\Socket\Functions;

use ThiagoMeloo\TerminalDebug\Socket\Client;

/**
 * Function global to send message to server
 * 
 * @param string|array|object $text
 * @return void
 */
function debugT($text){
    
    $client = new Client([
        'host' => '127.0.0.1',
        'port' => 8015
    ]);

    $client->setMessage($text)->run();
}