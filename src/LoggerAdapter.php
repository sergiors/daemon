<?php

namespace Sergiors\Daemon;

use Psr\Log\LoggerInterface;

class LoggerAdapter
{
    /**
     * @var LoggerInterface|null
     */
    protected $logger;

    /**
     * @param LoggerInterface|null $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function info($message, array $context = [])
    {
        null === $this->logger ?: $this->logger->info($message, $context);
    }

    /**
     * @param string $message
     * @param array  $context
     */
    public function alert($message, array $context = [])
    {
        null === $this->logger ?: $this->logger->alert($message, $context);
    }
}