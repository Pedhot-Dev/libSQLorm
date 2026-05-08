<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Relation;

use Generator;
use PedhotDev\libSQLorm\Async\Async;

final class RelationLoader
{
    public function eagerLoad(array $models, array $relations): Generator
    {
        $tasks = [];
        foreach ($models as $i => $model) {
            foreach ($relations as $relation) {
                $tasks[$i . ':' . $relation] = (function () use ($model, $relation): Generator {
                    $result = yield from $model->loadRelation($relation);
                    return $result;
                })();
            }
        }
        if ($tasks !== []) {
            yield from Async::awaitAll($tasks);
        }
        return null;
    }
}
