<?php

declare(strict_types=1);

namespace PedhotDev\libSQLorm\Contract;

use PedhotDev\libSQLorm\Event\ModelEvent;

interface EventDispatcherInterface
{
    /**
     * Register a listener for a model lifecycle event.
     *
     * @param callable(ModelEvent): void $listener
     */
    public function listen(string $event, callable $listener): void;

    /**
     * Dispatch a model lifecycle event to all registered listeners.
     * Returns false if a listener cancels the event (e.g. "creating" returning false).
     */
    public function dispatch(ModelEvent $event): bool;

    /**
     * Remove all listeners for a given event.
     */
    public function flush(string $event): void;
}
