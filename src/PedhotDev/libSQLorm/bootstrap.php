<?php
declare(strict_types=1);

use PedhotDev\libSQLorm\libSQLorm;
use PedhotDev\libSQLorm\Config\OrmConfig;
use PedhotDev\libSQLorm\Example\Migration\CreateGuildsTableMigration;
use PedhotDev\libSQLorm\Example\Migration\CreateUsersTableMigration;
use pocketmine\plugin\PluginBase;

return static function (PluginBase $plugin): array {
    $config = new OrmConfig(
        defaultConnection: 'default',
        poolConfiguration: [
            'provider' => 'sqlite',
            'threads' => 2,
            'sqlite' => ['path' => 'database.sqlite'],
            // For MySQL:
            // 'provider' => 'mysql',
            // 'mysql' => ['127.0.0.1', 'username', 'password', 'database', 3306],
        ],
    );

    return libSQLorm::boot($plugin, $config, [
        CreateGuildsTableMigration::class,
        CreateUsersTableMigration::class,
    ]);
};
