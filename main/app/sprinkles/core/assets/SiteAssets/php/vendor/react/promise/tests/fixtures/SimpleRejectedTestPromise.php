<?php

namespace React\Promise;

class SimpleRejectedTestPromise implements PromiseInterface
{
    public function then(callable $onFulfilled = NULL, callable $onRejected = NULL, callable $onProgress = NULL) {
        try {
            if ($onRejected) {
                $onRejected('foo');
            }

            return new self();
        } catch (\Throwable $exception) {
            return new RejectedPromise($exception);
        } catch (\Exception $exception) {
            return new RejectedPromise($exception);
        }
    }
}
