<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Example\Model;

use PedhotDev\libSQLorm\Attribute\Column;
use PedhotDev\libSQLorm\Attribute\Table;
use PedhotDev\libSQLorm\Model\Model;
use PedhotDev\libSQLorm\Relation\Relation;

#[Table('guilds')]
final class Guild extends Model
{
    #[Column]
    public int $id;
    #[Column]
    public string $name;
    protected array $fillable = ['id', 'name'];
    protected array $guarded = [];

    public function users(): Relation
    {
        return $this->hasMany(User::class, 'guildId', 'id');
    }
}
