<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Database\Query;

use cooldogedev\libSQL\query\SQLiteQuery;
use SQLite3;
use SQLite3Result;

final class RawSQLiteQuery extends SQLiteQuery
{
    public function __construct(private readonly string $sql, private readonly array $bindings = [], private readonly bool $statement = false)
    {
    }

    public function onRun(SQLite3 $connection): void
    {
        $stmt = $connection->prepare($this->sql);
        foreach ($this->bindings as $key => $value) {
            $name = str_starts_with((string)$key, ':') ? (string)$key : ':' . $key;
            $stmt->bindValue($name, $value);
        }
        $result = $stmt->execute();
        if ($this->statement) {
            $this->setResult($connection->changes());
            return;
        }
        if (!$result instanceof SQLite3Result) {
            $this->setResult([]);
            return;
        }
        $rows = [];
        while (($row = $result->fetchArray(SQLITE3_ASSOC)) !== false) {
            $rows[] = $row;
        }
        $this->setResult($rows);
    }
}
