<?php

namespace Sergiors\Daemon;

final class TrackableEvents
{
    const INIT    = 2;
    const START   = 4;
    const SUCCESS = 8;
    const ERROR   = 16;
    const FAILURE = 32;
    const FINISH  = 128;

    private function __construct()
    {
    }
}