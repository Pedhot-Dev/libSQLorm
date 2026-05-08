<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\DI;

use cooldogedev\libSQL\ConnectionPool;
use PedhotDev\libSQLorm\Contract\ConnectionManagerInterface;
use PedhotDev\libSQLorm\Contract\DriverInterface;
use PedhotDev\libSQLorm\Contract\EventDispatcherInterface;
use PedhotDev\libSQLorm\Contract\HydratorInterface;
use PedhotDev\libSQLorm\Database\ConnectionManager;
use PedhotDev\libSQLorm\Database\Driver\MySQLDriver;
use PedhotDev\libSQLorm\Database\Driver\SQLiteDriver;
use PedhotDev\libSQLorm\Event\ModelEventDispatcher;
use PedhotDev\libSQLorm\Hydration\ModelHydrator;
use PedhotDev\libSQLorm\Metadata\ModelMetadataFactory;
use PedhotDev\libSQLorm\Migration\MigrationRepository;
use PedhotDev\libSQLorm\Migration\MigrationRunner;
use PedhotDev\libSQLorm\Relation\RelationLoader;
use PedhotDev\libSQLorm\Schema\SchemaManager;
use PedhotDev\NepotismFree\Contract\ModuleConfiguratorInterface;
use PedhotDev\NepotismFree\Contract\ModuleInterface;

final class OrmModule implements ModuleInterface
{
    public function __construct(
        private readonly ConnectionPool $pool,
        private readonly string $provider = 'sqlite',
        private readonly array $migrationClasses = [],
    )
    {
    }

    public function configure(ModuleConfiguratorInterface $configurator): void
    {
        $pool = $this->pool;
        $provider = strtolower($this->provider);
        $configurator->bind(ConnectionManagerInterface::class, static fn(): ConnectionManager => new ConnectionManager($pool))->singleton(ConnectionManagerInterface::class);
        $configurator->bind(
            DriverInterface::class,
            static fn($container): DriverInterface => match ($provider) {
                'mysql' => $container->get(MySQLDriver::class),
                default => $container->get(SQLiteDriver::class),
            }
        )->singleton(DriverInterface::class);
        $configurator->singleton(SQLiteDriver::class);
        $configurator->singleton(MySQLDriver::class);
        $configurator->bind(EventDispatcherInterface::class, ModelEventDispatcher::class)->singleton(EventDispatcherInterface::class);
        $configurator->bind(HydratorInterface::class, ModelHydrator::class)->singleton(HydratorInterface::class);
        $configurator->singleton(ModelMetadataFactory::class);
        $configurator->singleton(RelationLoader::class);
        $configurator->singleton(SchemaManager::class);
        $configurator->singleton(MigrationRepository::class);
        $migrationClasses = $this->migrationClasses;
        $configurator->bind(MigrationRunner::class, static fn($container): MigrationRunner => new MigrationRunner($container->get(MigrationRepository::class), $migrationClasses));
        $configurator->singleton(MigrationRunner::class);
    }

    public function getExposedServices(): array
    {
        return [DriverInterface::class, EventDispatcherInterface::class, HydratorInterface::class, SchemaManager::class, MigrationRunner::class];
    }
}
