<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Relation;

use Generator;

final class BelongsToMany extends Relation
{
    public function __construct(\PedhotDev\libSQLorm\Model\Model $parent, private readonly string $related, private readonly string $pivot, private readonly string $foreignPivotKey, private readonly string $relatedPivotKey, private readonly string $parentKey, private readonly string $relatedKey)
    {
        parent::__construct($parent);
    }

    public function getResults(): Generator
    {
        return yield from $this->related::query()->join($this->pivot, $this->related::tableName() . '.' . $this->relatedKey, '=', $this->pivot . '.' . $this->relatedPivotKey)->where($this->pivot . '.' . $this->foreignPivotKey, '=', $this->parent->getAttribute($this->parentKey))->getModels();
    }
}
