<?php

namespace Sergiors\Daemon;

interface TrackableInterface
{
    const INIT    = 2;
    const START   = 4;
    const SUCCESS = 8;
    const ERROR   = 16;
    const FAILURE = 32;
    const FINISH  = 128;

    /**
     * @param int      $event
     * @param callable $callback
     */
    public function on($event, callable $callback);

    /**
     * @param string|int $event
     */
    public function trigger($event);
}