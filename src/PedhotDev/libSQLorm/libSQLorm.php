<?php

declare(strict_types=1);

namespace PedhotDev\libSQLorm;

use PedhotDev\libSQLorm\Bootstrap\OrmBootstrap;
use PedhotDev\libSQLorm\Config\OrmConfig;
use PedhotDev\libSQLorm\Database\ConnectionPoolFactory;
use PedhotDev\libSQLorm\DI\OrmModule;
use PedhotDev\NepotismFree\Builder\ContainerBuilder;
use pocketmine\plugin\PluginBase;

final class libSQLorm
{
    /**
     * @param list<class-string> $migrationClasses
     * @return array{0: mixed, 1: OrmConfig}
     */
    public static function boot(PluginBase $plugin, ?OrmConfig $config = null, array $migrationClasses = []): array
    {
        $config ??= new OrmConfig(
            defaultConnection: 'default',
            poolConfiguration: [
                'provider' => 'sqlite',
                'threads' => 2,
                'sqlite' => ['path' => 'database.sqlite'],
            ],
        );

        $pool = (new ConnectionPoolFactory())->create($plugin, $config);

        $builder = new ContainerBuilder();
        $builder->addModule(new OrmModule(
            $pool,
            (string) ($config->poolConfiguration['provider'] ?? 'sqlite'),
            $migrationClasses,
        ));

        $container = $builder->build();
        (new OrmBootstrap($container))->boot();

        return [$container, $config];
    }
}
