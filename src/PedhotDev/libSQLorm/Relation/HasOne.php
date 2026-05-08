<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Relation;

use Generator;

final class HasOne extends Relation
{
    public function __construct(\PedhotDev\libSQLorm\Model\Model $parent, private readonly string $related, private readonly string $foreignKey, private readonly string $localKey)
    {
        parent::__construct($parent);
    }

    public function getResults(): Generator
    {
        return yield from $this->related::query()->where($this->foreignKey, '=', $this->parent->getAttribute($this->localKey))->firstModel();
    }
}
