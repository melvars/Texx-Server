<?php

namespace React\Promise;

class RejectedPromise implements ExtendedPromiseInterface, CancellablePromiseInterface
{
    private $reason;

    public function __construct($reason = NULL) {
        if ($reason instanceof PromiseInterface) {
            throw new \InvalidArgumentException('You cannot create React\Promise\RejectedPromise with a promise. Use React\Promise\reject($promiseOrValue) instead.');
        }

        $this->reason = $reason;
    }

    public function then(callable $onFulfilled = NULL, callable $onRejected = NULL, callable $onProgress = NULL) {
        if (NULL === $onRejected) {
            return $this;
        }

        try {
            return resolve($onRejected($this->reason));
        } catch (\Throwable $exception) {
            return new RejectedPromise($exception);
        } catch (\Exception $exception) {
            return new RejectedPromise($exception);
        }
    }

    public function done(callable $onFulfilled = NULL, callable $onRejected = NULL, callable $onProgress = NULL) {
        if (NULL === $onRejected) {
            throw UnhandledRejectionException::resolve($this->reason);
        }

        $result = $onRejected($this->reason);

        if ($result instanceof self) {
            throw UnhandledRejectionException::resolve($result->reason);
        }

        if ($result instanceof ExtendedPromiseInterface) {
            $result->done();
        }
    }

    public function otherwise(callable $onRejected) {
        if (!_checkTypehint($onRejected, $this->reason)) {
            return $this;
        }

        return $this->then(NULL, $onRejected);
    }

    public function always(callable $onFulfilledOrRejected) {
        return $this->then(NULL, function ($reason) use ($onFulfilledOrRejected) {
            return resolve($onFulfilledOrRejected())->then(function () use ($reason) {
                return new RejectedPromise($reason);
            });
        });
    }

    public function progress(callable $onProgress) {
        return $this;
    }

    public function cancel() {
    }
}
