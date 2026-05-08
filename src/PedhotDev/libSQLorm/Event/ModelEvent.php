<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Event;

use PedhotDev\libSQLorm\Model\Model;

final readonly class ModelEvent
{
    public function __construct(public string $name, public Model $model)
    {
    }
}
