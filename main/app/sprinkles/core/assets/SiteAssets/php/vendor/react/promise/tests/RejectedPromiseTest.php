<?php

namespace React\Promise;

use React\Promise\PromiseAdapter\CallbackPromiseAdapter;

class RejectedPromiseTest extends TestCase
{
    use PromiseTest\PromiseSettledTestTrait,
        PromiseTest\PromiseRejectedTestTrait;

    public function getPromiseTestAdapter(callable $canceller = NULL) {
        $promise = NULL;

        return new CallbackPromiseAdapter([
            'promise' => function () use (&$promise) {
                if (!$promise) {
                    throw new \LogicException('RejectedPromise must be rejected before obtaining the promise');
                }

                return $promise;
            },
            'resolve' => function () {
                throw new \LogicException('You cannot call resolve() for React\Promise\RejectedPromise');
            },
            'reject' => function ($reason = NULL) use (&$promise) {
                if (!$promise) {
                    $promise = new RejectedPromise($reason);
                }
            },
            'notify' => function () {
                // no-op
            },
            'settle' => function ($reason = NULL) use (&$promise) {
                if (!$promise) {
                    $promise = new RejectedPromise($reason);
                }
            },
        ]);
    }

    /** @test */
    public function shouldThrowExceptionIfConstructedWithAPromise() {
        $this->setExpectedException('\InvalidArgumentException');

        return new RejectedPromise(new RejectedPromise());
    }
}
