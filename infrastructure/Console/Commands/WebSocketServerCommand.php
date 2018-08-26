<?php
namespace Infrastructure\Console\Commands;

use Illuminate\Console\Command;
use Infrastructure\Http\WebSocketController;
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

class WebSocketServerCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'websocket';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new WebSocketController()
                )
            ),
            1337
        );
        $this->line('Starting websocket...');
        $server->run();
    }
}