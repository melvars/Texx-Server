<?php
require '../../vendor/autoload.php';

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Websocket\ChatProcessor;

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new ChatProcessor()
        )
    ),
    1338
);

$server->run();