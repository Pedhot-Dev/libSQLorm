<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Config;
final readonly class OrmConfig
{
    public function __construct(public string $defaultConnection, public array $poolConfiguration)
    {
    }
}
