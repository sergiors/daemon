<?php

namespace Sergiors\Daemon;

class Process implements ProcessInterface, TrackableInterface
{
    /**
     * @var callable
     */
    protected $callback;

    /**
     * @var array
     */
    protected $handlers = [];

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * @return array
     */
    public function getHandlers()
    {
        return $this->handlers;
    }

    /**
     * @see http://php.net/manual/en/language.operators.bitwise.php#91291
     *
     * {@inheritdoc}
     */
    public function trigger($event)
    {
        $handlers = array_filter($this->handlers, function ($eventKey) use ($event) {
            return ($eventKey & $event) === $event;
        }, ARRAY_FILTER_USE_KEY);

        if (empty($handlers)) {
            return;
        }

        $handlers = array_shift($handlers);

        foreach ($handlers as $handler) {
            call_user_func($handler);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function on($event, callable $callback)
    {
        $this->handlers[$event][] = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        return call_user_func($this->callback);
    }
}