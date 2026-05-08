<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Hydration;

use PedhotDev\libSQLorm\Contract\HydratorInterface;
use PedhotDev\libSQLorm\Metadata\ModelMetadataFactory;
use PedhotDev\libSQLorm\Model\Model;

final readonly class ModelHydrator implements HydratorInterface
{
    public function __construct(private ModelMetadataFactory $metadataFactory)
    {
    }

    public function hydrateMany(array $rows, string $modelClass): array
    {
        return array_map(fn(array $row): Model => $this->hydrate($row, $modelClass), $rows);
    }

    public function hydrate(array $row, string $modelClass): Model
    {
        $model = new $modelClass();
        $metadata = $this->metadataFactory->get($modelClass);
        foreach ($metadata->columns as $property => $column) {
            if (array_key_exists($column, $row)) {
                $model->setAttribute($property, $row[$column]);
            }
        }
        $model->markAsExisting();
        $model->syncOriginal();
        return $model;
    }

    public function dehydrate(Model $model): array
    {
        $metadata = $this->metadataFactory->get($model::class);
        $data = [];
        foreach ($metadata->columns as $property => $column) {
            $data[$column] = $model->getAttribute($property);
        }
        return $data;
    }
}
