<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Model;

use PedhotDev\libSQLorm\Contract\DriverInterface;
use PedhotDev\libSQLorm\Contract\EventDispatcherInterface;
use PedhotDev\libSQLorm\Contract\HydratorInterface;
use PedhotDev\libSQLorm\Relation\RelationLoader;
use PedhotDev\NepotismFree\Contract\ContainerInterface;

final readonly class ModelContext
{
    public function __construct(public DriverInterface $driver, public HydratorInterface $hydrator, public EventDispatcherInterface $events, public RelationLoader $relationLoader)
    {
    }

    public static function fromContainer(ContainerInterface $container): self
    {
        return new self($container->get(DriverInterface::class), $container->get(HydratorInterface::class), $container->get(EventDispatcherInterface::class), $container->get(RelationLoader::class));
    }
}
