<?php

namespace Sergiors\Daemon\Tests;

use Sergiors\Daemon\Process;
use Sergiors\Daemon\TrackableInterface;

class ProcessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function shouldExecute()
    {
        $process = new Process(function () {
            return 10;
        });

        $this->assertEquals(10, $process->execute());
    }

    public function shouldTriggerHandler()
    {
        $this->expectOutputString('INIT');

        $process = $this->createProcess();
        $process->on(TrackableInterface::INIT, function () {
            echo 'INIT';
        });
        $process->trigger(TrackableInterface::INIT);
    }

    /**
     * @test
     */
    public function shouldTriggerHandlerTwoTimes()
    {
        $this->expectOutputString('INITINIT');

        $process = $this->createProcess();
        $process->on(TrackableInterface::INIT | TrackableInterface::FINISH, function () {
            echo 'INIT';
        });

        $process->trigger(TrackableInterface::INIT);
        $process->trigger(TrackableInterface::FINISH);
    }

    public function createProcess()
    {
        return new Process(function () {
        });
    }
}