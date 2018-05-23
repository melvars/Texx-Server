<?php

namespace React\Promise;

class SimpleTestCancellable
{
    public $cancelCalled = FALSE;

    public function cancel() {
        $this->cancelCalled = TRUE;
    }
}
