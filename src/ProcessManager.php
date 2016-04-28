<?php

namespace Sergiors\Daemon;

use Psr\Log\LoggerInterface;

class ProcessManager
{
    /**
     * @var ProcessInterface
     */
    protected $process;

    /**
     * @var LoggerAdapter
     */
    protected $logger;

    /**
     * @param ProcessInterface     $process
     * @param LoggerInterface|null $logger
     */
    public function __construct(ProcessInterface $process, LoggerInterface $logger = null)
    {
        $this->process = $process;
        $this->logger = new LoggerAdapter($logger);

        $this->trigger(TrackableEvents::INIT);
    }

    public function start()
    {
        if ($this->fork()) {
            return;
        }

        fclose(STDIN);
        fclose(STDOUT);
        fclose(STDERR);

        $this->silentTrigger(TrackableEvents::START);

        try {
            $this->process->execute() ?: $this->silentTrigger(TrackableEvents::SUCCESS);
        } catch (\ErrorException $e) {
            $this->silentTrigger(TrackableEvents::ERROR, ['exception' => $e]);
        } catch (\Exception $e) {
            $this->silentTrigger(TrackableEvents::FAILURE, ['exception' => $e]);
        }

        $this->silentTrigger(TrackableEvents::FINISH);
    }

    protected function fork()
    {
        $pid = pcntl_fork();

        if (-1 === $pid) {
            throw new \RuntimeException('Could not fork');
        }

        return $pid;
    }

    /**
     * @see http://php.net/manual/en/language.operators.bitwise.php#91291
     *
     * @param int   $event
     * @param array $args
     */
    protected function trigger($event, array $args = [])
    {
        if (!$this->process instanceof TrackableInterface) {
            return;
        }

        $this->process->trigger($event);

        if ($event & (TrackableEvents::ERROR | TrackableEvents::FAILURE)) {
            $this->logger->alert(sprintf('Could not run event "%s".', (string) $event), $args);
            return;
        }

        $this->logger->info(sprintf('Triggered event "%s".', (string) $event));
    }

    /**
     * @param int $event
     */
    protected function silentTrigger($event)
    {
        $args = array_slice(func_get_args(), 1);

        try {
            $this->trigger($event, $args);
        } catch (\Exception $e) {
        }
    }
}