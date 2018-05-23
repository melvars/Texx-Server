<?php

namespace React\Promise;

class SimpleTestCancellableThenable
{
    public $cancelCalled = FALSE;

    public function then(callable $onFulfilled = NULL, callable $onRejected = NULL, callable $onProgress = NULL) {
        return new self();
    }

    public function cancel() {
        $this->cancelCalled = TRUE;
    }
}
