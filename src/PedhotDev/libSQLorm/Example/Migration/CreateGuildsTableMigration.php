<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Example\Migration;

use Generator;
use PedhotDev\libSQLorm\Migration\Migration;
use PedhotDev\libSQLorm\Schema\Blueprint;
use PedhotDev\libSQLorm\Schema\Schema;

final class CreateGuildsTableMigration extends Migration
{
    public function up(): Generator
    {
        yield from Schema::create('guilds', static function (Blueprint $table): void {
            $table->integer('id')->primary();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): Generator
    {
        yield from Schema::dropIfExists('guilds');
    }
}
