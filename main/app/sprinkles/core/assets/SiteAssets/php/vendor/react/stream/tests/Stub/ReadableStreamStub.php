<?php

namespace React\Tests\Stream\Stub;

use Evenement\EventEmitter;
use React\Stream\ReadableStreamInterface;
use React\Stream\WritableStreamInterface;
use React\Stream\Util;

class ReadableStreamStub extends EventEmitter implements ReadableStreamInterface
{
    public $readable = TRUE;
    public $paused = FALSE;

    public function isReadable() {
        return TRUE;
    }

    // trigger data event
    public function write($data) {
        $this->emit('data', array($data));
    }

    // trigger error event
    public function error($error) {
        $this->emit('error', array($error));
    }

    // trigger end event
    public function end() {
        $this->emit('end', array());
    }

    public function pause() {
        $this->paused = TRUE;
    }

    public function resume() {
        $this->paused = FALSE;
    }

    public function close() {
        $this->readable = FALSE;

        $this->emit('close');
    }

    public function pipe(WritableStreamInterface $dest, array $options = array()) {
        Util::pipe($this, $dest, $options);

        return $dest;
    }
}
