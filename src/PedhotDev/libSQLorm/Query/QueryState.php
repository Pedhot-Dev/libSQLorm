<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Query;
final class QueryState
{
    public string $table = '';
    public array $selects = ['*'];
    public array $wheres = [];
    public array $joins = [];
    public array $groupBy = [];
    public array $orderBy = [];
    public ?int $limit = null;
    public ?int $offset = null;
}
