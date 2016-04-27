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
        $this->expectOutputString('INIT1INIT2INIT1INIT2');

        $process = $this->createProcess();
        $process->on(TrackableInterface::INIT | TrackableInterface::FINISH, function () {
            echo 'INIT1';
        });

        $process->on(TrackableInterface::INIT | TrackableInterface::FINISH, function () {
            echo 'INIT2';
        });

        $process->trigger(TrackableInterface::INIT);
        $process->trigger(TrackableInterface::ERROR);
        $process->trigger(TrackableInterface::FINISH);
    }

    public function createProcess()
    {
        return new Process(function () {
        });
    }
}