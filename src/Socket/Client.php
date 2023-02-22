<?php

namespace ThiagoMeloo\TerminalDebug\Socket;

use ThiagoMeloo\TerminalDebug\Contracts\Runner;
use ThiagoMeloo\TerminalDebug\Helpers\PrintConsole;

/**
 * Socket Client to send messages to server
 */
class Client implements Runner
{

    protected $port = 8015;
    protected $host = '127.0.0.1';
    protected $socket = null;

    protected $message = '';

    public function __construct(array $config)
    {
        $this->port = $config['port'] ?? $this->port;
        $this->host = $config['host'] ?? $this->host;

        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    }

    public function setMessage(String|array|object $message)
    {
        if (is_array($message) || is_object($message)) {
            $this->message = json_encode($message);
        } else {
            $this->message = (string) $message;
        }

        return $this;
    }

    public function run()
    {
        //hide warnings socket bind
        error_reporting(E_ALL ^ E_WARNING);

        $result = socket_connect($this->socket, $this->host, $this->port);

        if ($result === false) {
            PrintConsole::client('Error: ' . socket_strerror(socket_last_error($this->socket)))->error();
            return false;
        }

        socket_write($this->socket, $this->message, strlen($this->message));

        $out = socket_read($this->socket, 1024);

        PrintConsole::client($out)->success();

        socket_close($this->socket);
    }
}
