<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Event;

use PedhotDev\libSQLorm\Contract\EventDispatcherInterface;

final class ModelEventDispatcher implements EventDispatcherInterface
{
    private array $listeners = [];

    public function listen(string $event, callable $listener): void
    {
        $this->listeners[$event] ??= [];
        $this->listeners[$event][] = $listener;
    }

    public function dispatch(ModelEvent $event): bool
    {
        foreach ($this->listeners[$event->name] ?? [] as $listener) {
            $listener($event);
        }
        return true;
    }

    public function flush(string $event): void
    {
        unset($this->listeners[$event]);
    }
}
