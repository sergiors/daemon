<?php

declare(strict_types = 1);

namespace Sergiors\Daemon;

interface TrackableInterface
{
    /**
     * @param int      $event
     * @param callable $callback
     */
    public function on(int $event, callable $callback);

    /**
     * @param int $event
     */
    public function trigger(int $event);
}
