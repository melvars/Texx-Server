<?php declare(strict_types=1);

/*
 * This file is part of Evenement.
 *
 * (c) Igor Wiedler <igor@wiedler.ch>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Evenement;

interface EventEmitterInterface
{
    public function on($event, callable $listener);

    public function once($event, callable $listener);

    public function removeListener($event, callable $listener);

    public function removeAllListeners($event = NULL);

    public function listeners($event = NULL);

    public function emit($event, array $arguments = []);
}
