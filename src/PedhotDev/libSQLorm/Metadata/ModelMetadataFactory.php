<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Metadata;

use PedhotDev\libSQLorm\Attribute\Column;
use PedhotDev\libSQLorm\Attribute\Table;
use ReflectionClass;

final class ModelMetadataFactory
{
    private array $cache = [];

    public function get(string $modelClass): ModelMetadata
    {
        if (isset($this->cache[$modelClass])) return $this->cache[$modelClass];
        $r = new ReflectionClass($modelClass);
        $table = strtolower($r->getShortName()) . 's';
        $attrs = $r->getAttributes(Table::class);
        if ($attrs !== []) {
            $table = $attrs[0]->newInstance()->name;
        }
        $columns = [];
        foreach ($r->getProperties() as $p) {
            $a = $p->getAttributes(Column::class);
            if ($a === []) continue;
            $i = $a[0]->newInstance();
            $columns[$p->getName()] = $i->name ?? $p->getName();
        }
        return $this->cache[$modelClass] = new ModelMetadata($table, 'id', $columns, true);
    }
}
