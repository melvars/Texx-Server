<?php

namespace Infrastructure\Console\Commands;

use Illuminate\Console\Command;

class StartServerCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'server';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->line('Starting server...');
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec("wsl bash infrastructure/Console/Commands/StartServer.sh");
        } else {
            exec("bash infrastructure/Console/Commands/StartServer.sh");
        }
    }
}