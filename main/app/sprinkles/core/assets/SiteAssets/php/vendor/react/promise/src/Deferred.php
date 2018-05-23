<?php

namespace React\Promise;

class Deferred implements PromisorInterface
{
    private $promise;
    private $resolveCallback;
    private $rejectCallback;
    private $notifyCallback;
    private $canceller;

    public function __construct(callable $canceller = NULL) {
        $this->canceller = $canceller;
    }

    public function promise() {
        if (NULL === $this->promise) {
            $this->promise = new Promise(function ($resolve, $reject, $notify) {
                $this->resolveCallback = $resolve;
                $this->rejectCallback = $reject;
                $this->notifyCallback = $notify;
            }, $this->canceller);
        }

        return $this->promise;
    }

    public function resolve($value = NULL) {
        $this->promise();

        call_user_func($this->resolveCallback, $value);
    }

    public function reject($reason = NULL) {
        $this->promise();

        call_user_func($this->rejectCallback, $reason);
    }

    public function notify($update = NULL) {
        $this->promise();

        call_user_func($this->notifyCallback, $update);
    }

    /**
     * @deprecated 2.2.0
     * @see Deferred::notify()
     */
    public function progress($update = NULL) {
        $this->notify($update);
    }
}
