<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Query;

use Generator;
use PedhotDev\libSQLorm\Collection\Collection;
use PedhotDev\libSQLorm\Contract\DriverInterface;
use PedhotDev\libSQLorm\Contract\HydratorInterface;
use PedhotDev\libSQLorm\Relation\RelationLoader;

final class ModelQueryBuilder extends QueryBuilder
{
    public function __construct(DriverInterface $driver, private readonly HydratorInterface $hydrator, private readonly RelationLoader $relationLoader, private readonly string $modelClass)
    {
        parent::__construct($driver);
        $this->table($modelClass::tableName());
    }

    public function getModels(): Generator
    {
        $rows = yield from $this->get();
        $models = $this->hydrator->hydrateMany($rows, $this->modelClass);
        if ($this->with !== []) {
            yield from $this->relationLoader->eagerLoad($models, $this->with);
        }
        return new Collection($models);
    }

    public function firstModel(): Generator
    {
        $row = yield from $this->first();
        if ($row === null) {
            return null;
        }
        $model = $this->hydrator->hydrate($row, $this->modelClass);
        if ($this->with !== []) {
            yield from $this->relationLoader->eagerLoad([$model], $this->with);
        }
        return $model;
    }
}
