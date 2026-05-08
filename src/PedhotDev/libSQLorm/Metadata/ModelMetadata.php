<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Metadata;
final readonly class ModelMetadata
{
    public function __construct(public string $table, public string $primaryKey, public array $columns, public bool $timestamps)
    {
    }
}
