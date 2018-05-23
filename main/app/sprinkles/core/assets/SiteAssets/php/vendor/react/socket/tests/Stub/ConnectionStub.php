<?php

namespace React\Tests\Socket\Stub;

use Evenement\EventEmitter;
use React\Socket\ConnectionInterface;
use React\Stream\WritableStreamInterface;
use React\Stream\Util;

class ConnectionStub extends EventEmitter implements ConnectionInterface
{
    private $data = '';

    public function isReadable() {
        return TRUE;
    }

    public function isWritable() {
        return TRUE;
    }

    public function pause() {
    }

    public function resume() {
    }

    public function pipe(WritableStreamInterface $dest, array $options = array()) {
        Util::pipe($this, $dest, $options);

        return $dest;
    }

    public function write($data) {
        $this->data .= $data;

        return TRUE;
    }

    public function end($data = NULL) {
    }

    public function close() {
    }

    public function getData() {
        return $this->data;
    }

    public function getRemoteAddress() {
        return '127.0.0.1';
    }
}
