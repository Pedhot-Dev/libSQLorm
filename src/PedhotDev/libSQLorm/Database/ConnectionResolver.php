<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Database;

use PedhotDev\libSQLorm\Contract\DriverInterface;

final readonly class ConnectionResolver
{
    public function __construct(private DriverInterface $driver)
    {
    }

    public function driver(): DriverInterface
    {
        return $this->driver;
    }
}
