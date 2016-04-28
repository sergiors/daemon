<?php

namespace Sergiors\Daemon;

interface TrackableInterface
{
    /**
     * @param int      $event
     * @param callable $callback
     */
    public function on($event, callable $callback);

    /**
     * @param int $event
     */
    public function trigger($event);
}