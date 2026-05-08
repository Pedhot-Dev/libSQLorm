<?php

declare(strict_types=1);

namespace PedhotDev\libSQLorm\Contract;

use Generator;

interface MigrationInterface
{
    /**
     * Apply the migration (create tables, add columns, etc.).
     *
     * @return Generator<mixed, mixed, mixed, void>
     */
    public function up(): Generator;

    /**
     * Revert the migration.
     *
     * @return Generator<mixed, mixed, mixed, void>
     */
    public function down(): Generator;
}
