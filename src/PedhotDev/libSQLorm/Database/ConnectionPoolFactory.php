<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Database;

use cooldogedev\libSQL\ConnectionPool;
use PedhotDev\libSQLorm\Config\OrmConfig;
use pocketmine\plugin\PluginBase;

final readonly class ConnectionPoolFactory
{
    public function create(PluginBase $plugin, OrmConfig $config): ConnectionPool
    {
        return new ConnectionPool($plugin, $config->poolConfiguration);
    }
}
