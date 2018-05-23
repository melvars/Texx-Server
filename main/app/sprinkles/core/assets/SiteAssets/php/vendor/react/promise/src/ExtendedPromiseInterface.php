<?php

namespace React\Promise;

interface ExtendedPromiseInterface extends PromiseInterface
{
    /**
     * @return void
     */
    public function done(callable $onFulfilled = NULL, callable $onRejected = NULL, callable $onProgress = NULL);

    /**
     * @return ExtendedPromiseInterface
     */
    public function otherwise(callable $onRejected);

    /**
     * @return ExtendedPromiseInterface
     */
    public function always(callable $onFulfilledOrRejected);

    /**
     * @return ExtendedPromiseInterface
     */
    public function progress(callable $onProgress);
}
