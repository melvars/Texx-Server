<?php

namespace React\Promise;

class CancellationQueue
{
    private $started = FALSE;
    private $queue = [];

    public function __invoke() {
        if ($this->started) {
            return;
        }

        $this->started = TRUE;
        $this->drain();
    }

    public function enqueue($cancellable) {
        if (!method_exists($cancellable, 'then') || !method_exists($cancellable, 'cancel')) {
            return;
        }

        $length = array_push($this->queue, $cancellable);

        if ($this->started && 1 === $length) {
            $this->drain();
        }
    }

    private function drain() {
        for ($i = key($this->queue); isset($this->queue[$i]); $i++) {
            $cancellable = $this->queue[$i];

            $exception = NULL;

            try {
                $cancellable->cancel();
            } catch (\Throwable $exception) {
            } catch (\Exception $exception) {
            }

            unset($this->queue[$i]);

            if ($exception) {
                throw $exception;
            }
        }

        $this->queue = [];
    }
}
