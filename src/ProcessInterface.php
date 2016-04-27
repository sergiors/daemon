<?php

namespace Sergiors\Daemon;

interface ProcessInterface
{
    /**
     * @return mixed
     */
    public function execute();
}