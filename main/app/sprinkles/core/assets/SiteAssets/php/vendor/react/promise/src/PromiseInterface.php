<?php

namespace React\Promise;

interface PromiseInterface
{
    /**
     * @return PromiseInterface
     */
    public function then(callable $onFulfilled = NULL, callable $onRejected = NULL, callable $onProgress = NULL);
}
