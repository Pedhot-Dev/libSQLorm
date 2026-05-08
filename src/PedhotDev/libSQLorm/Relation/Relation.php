<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Relation;

use Generator;
use PedhotDev\libSQLorm\Model\Model;

abstract class Relation
{
    public function __construct(protected Model $parent)
    {
    }

    abstract public function getResults(): Generator;
}
