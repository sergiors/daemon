<?php

namespace Sergiors\Daemon\Tests\Unit;

use Sergiors\Daemon\Process;
use Sergiors\Daemon\TrackableEvents;

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

    /**
     * @test
     */
    public function shouldTriggerHandler()
    {
        $this->expectOutputString('INIT');

        $process = $this->createProcess();
        $process->on(TrackableEvents::INIT, function () {
            echo 'INIT';
        });
        $process->trigger(TrackableEvents::INIT);
    }

    /**
     * @test
     */
    public function shouldTriggerHandlerTwoTimes()
    {
        $this->expectOutputString('INIT1INIT2INIT1INIT2');

        $process = $this->createProcess();
        $process->on(TrackableEvents::INIT | TrackableEvents::FINISH, function () {
            echo 'INIT1';
        });

        $process->on(TrackableEvents::INIT | TrackableEvents::FINISH, function () {
            echo 'INIT2';
        });

        $process->trigger(TrackableEvents::INIT);
        $process->trigger(TrackableEvents::ERROR);
        $process->trigger(TrackableEvents::FINISH);
    }

    public function createProcess()
    {
        return new Process(function () {
        });
    }
}