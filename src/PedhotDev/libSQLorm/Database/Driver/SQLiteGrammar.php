<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Database\Driver;
final class SQLiteGrammar
{
    public function wrap(string $identifier): string
    {
        return '"' . str_replace('"', '""', $identifier) . '"';
    }
}
