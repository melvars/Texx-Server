<?php

namespace React\Promise;

class LazyPromise implements ExtendedPromiseInterface, CancellablePromiseInterface
{
    private $factory;
    private $promise;

    public function __construct(callable $factory) {
        $this->factory = $factory;
    }

    public function then(callable $onFulfilled = NULL, callable $onRejected = NULL, callable $onProgress = NULL) {
        return $this->promise()->then($onFulfilled, $onRejected, $onProgress);
    }

    public function done(callable $onFulfilled = NULL, callable $onRejected = NULL, callable $onProgress = NULL) {
        return $this->promise()->done($onFulfilled, $onRejected, $onProgress);
    }

    public function otherwise(callable $onRejected) {
        return $this->promise()->otherwise($onRejected);
    }

    public function always(callable $onFulfilledOrRejected) {
        return $this->promise()->always($onFulfilledOrRejected);
    }

    public function progress(callable $onProgress) {
        return $this->promise()->progress($onProgress);
    }

    public function cancel() {
        return $this->promise()->cancel();
    }

    /**
     * @internal
     * @see Promise::settle()
     */
    public function promise() {
        if (NULL === $this->promise) {
            try {
                $this->promise = resolve(call_user_func($this->factory));
            } catch (\Throwable $exception) {
                $this->promise = new RejectedPromise($exception);
            } catch (\Exception $exception) {
                $this->promise = new RejectedPromise($exception);
            }
        }

        return $this->promise;
    }
}
