<?php

namespace ThiagoMeloo\TerminalDebug\Socket;

use ThiagoMeloo\TerminalDebug\Contracts\Runner;
use ThiagoMeloo\TerminalDebug\Helpers\PrettyJson;
use ThiagoMeloo\TerminalDebug\Helpers\PrintConsole;

/**
 * Socket Server to accept connections and print messages
 */
class Server implements Runner
{

    protected $port = 8015;
    protected $host = '127.0.0.1';

    protected $socket = null;

    protected $responseMessage = 'message received!';

    public function __construct(array $config = [])
    {
        $this->port = $config['port'] ?? $this->port;
        $this->host = $config['host'] ?? $this->host;

        $this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        $this->responseMessage = $config['responseMessage'] ?? $this->responseMessage;
    }

    public function run()
    {
        //hide warnings socket bind
        error_reporting(E_ALL ^ E_WARNING);

        if (socket_bind($this->socket, $this->host, $this->port) === false) {
            PrintConsole::server('Error: ' . socket_strerror(socket_last_error($this->socket)))->error();
            PrintConsole::server('Wait a moment and try again', ['type' => 'warning'])->warning();
            exit;
        }

        if (socket_listen($this->socket, 5) === false) {
            PrintConsole::server('Error: ' . socket_strerror(socket_last_error($this->socket)), ['type' => 'error']);
            exit;
        }

        //show server running
        PrintConsole::server('Server running on ' . $this->host . ':' . $this->port, ['type' => 'success'])->success();

        while (true) {

            $client = socket_accept($this->socket);
            $data = socket_read($client, 1024);

            if (PrettyJson::isJson($data)) {
                PrintConsole::debug($data)->json();
            } else {
                PrintConsole::debug($data)->default();
            }

            socket_write($client, $this->responseMessage, strlen($this->responseMessage));
            socket_close($client);
        }

        socket_close($this->socket);
    }
}
