<?php

namespace React\Promise;

class SimpleFulfilledTestThenable
{
    public function then(callable $onFulfilled = NULL, callable $onRejected = NULL, callable $onProgress = NULL) {
        try {
            if ($onFulfilled) {
                $onFulfilled('foo');
            }

            return new self();
        } catch (\Throwable $exception) {
            return new RejectedPromise($exception);
        } catch (\Exception $exception) {
            return new RejectedPromise($exception);
        }
    }
}
