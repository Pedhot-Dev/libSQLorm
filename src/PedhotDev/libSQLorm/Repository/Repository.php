<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Repository;

use Generator;
use PedhotDev\libSQLorm\Contract\RepositoryInterface;
use PedhotDev\libSQLorm\Model\Model;

abstract class Repository implements RepositoryInterface
{
    public function findById(mixed $id): Generator
    {
        $class = $this->modelClass();
        return yield from $class::find($id);
    }

    abstract protected function modelClass(): string;

    public function findAll(): Generator
    {
        $class = $this->modelClass();
        return yield from $class::query()->getModels();
    }

    public function save(Model $model): Generator
    {
        return yield from $model->save();
    }

    public function delete(Model $model): Generator
    {
        return yield from $model->delete();
    }
}
