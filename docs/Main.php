<?php

declare(strict_types=1);

namespace YourPluginNamespace;

use PedhotDev\libSQLorm\Example\Model\User;
use PedhotDev\libSQLorm\Example\Migration\CreateGuildsTableMigration;
use PedhotDev\libSQLorm\Example\Migration\CreateUsersTableMigration;
use PedhotDev\libSQLorm\Migration\MigrationRunner;
use PedhotDev\libSQLorm\libSQLorm;
use pocketmine\plugin\PluginBase;
use SOFe\AwaitGenerator\Await;

final class Main extends PluginBase{

    protected function onEnable() : void{
        // 1) Boot ORM from class entrypoint (no bootstrap.php file required)
        [$container, $ormConfig] = libSQLorm::boot($this, migrationClasses: [
            CreateGuildsTableMigration::class,
            CreateUsersTableMigration::class,
        ]);

        // 2) Run migrations and do a smoke-test query asynchronously
        Await::f2c(function() use ($container) : \Generator{
            /** @var MigrationRunner $migrationRunner */
            $migrationRunner = $container->get(MigrationRunner::class);
            yield from $migrationRunner->migrate();

            // Example create/update flow
            $user = yield from User::find("xuid-demo");
            if($user === null){
                $user = new User();
                $user->fill([
                    "xuid" => "xuid-demo",
                    "coins" => 0,
                ]);
            }

            $user->coins += 1000;
            yield from $user->save();

            // Example query builder flow
            $topUsers = yield from User::query()
                ->where("coins", ">", 100)
                ->orderBy("coins", "DESC")
                ->limit(10)
                ->getModels();

            $this->getLogger()->info("ORM ready. Top users count: " . count($topUsers->all()));
        });
    }

    protected function onDisable() : void{
        $this->getLogger()->info("Plugin disabled.");
    }
}
