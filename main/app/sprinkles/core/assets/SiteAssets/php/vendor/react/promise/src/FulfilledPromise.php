<?php

namespace React\Promise;

class FulfilledPromise implements ExtendedPromiseInterface, CancellablePromiseInterface
{
    private $value;

    public function __construct($value = NULL) {
        if ($value instanceof PromiseInterface) {
            throw new \InvalidArgumentException('You cannot create React\Promise\FulfilledPromise with a promise. Use React\Promise\resolve($promiseOrValue) instead.');
        }

        $this->value = $value;
    }

    public function then(callable $onFulfilled = NULL, callable $onRejected = NULL, callable $onProgress = NULL) {
        if (NULL === $onFulfilled) {
            return $this;
        }

        try {
            return resolve($onFulfilled($this->value));
        } catch (\Throwable $exception) {
            return new RejectedPromise($exception);
        } catch (\Exception $exception) {
            return new RejectedPromise($exception);
        }
    }

    public function done(callable $onFulfilled = NULL, callable $onRejected = NULL, callable $onProgress = NULL) {
        if (NULL === $onFulfilled) {
            return;
        }

        $result = $onFulfilled($this->value);

        if ($result instanceof ExtendedPromiseInterface) {
            $result->done();
        }
    }

    public function otherwise(callable $onRejected) {
        return $this;
    }

    public function always(callable $onFulfilledOrRejected) {
        return $this->then(function ($value) use ($onFulfilledOrRejected) {
            return resolve($onFulfilledOrRejected())->then(function () use ($value) {
                return $value;
            });
        });
    }

    public function progress(callable $onProgress) {
        return $this;
    }

    public function cancel() {
    }
}
