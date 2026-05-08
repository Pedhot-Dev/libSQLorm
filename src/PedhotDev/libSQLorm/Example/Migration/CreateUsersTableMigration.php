<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Example\Migration;

use Generator;
use PedhotDev\libSQLorm\Migration\Migration;
use PedhotDev\libSQLorm\Schema\Blueprint;
use PedhotDev\libSQLorm\Schema\Schema;

final class CreateUsersTableMigration extends Migration
{
    public function up(): Generator
    {
        yield from Schema::create('users', static function (Blueprint $table): void {
            $table->string('xuid')->primary();
            $table->integer('coins')->default(0);
            $table->integer('guild_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): Generator
    {
        yield from Schema::dropIfExists('users');
    }
}
