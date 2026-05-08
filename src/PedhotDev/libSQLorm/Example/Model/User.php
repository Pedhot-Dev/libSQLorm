<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Example\Model;

use PedhotDev\libSQLorm\Attribute\Column;
use PedhotDev\libSQLorm\Attribute\Table;
use PedhotDev\libSQLorm\Model\Model;
use PedhotDev\libSQLorm\Relation\Relation;

#[Table('users')]
final class User extends Model
{
    #[Column]
    public string $xuid;
    #[Column]
    public int $coins = 0;
    #[Column(name: 'guild_id')]
    public ?int $guildId = null;
    protected array $fillable = ['xuid', 'coins', 'guildId'];
    protected array $guarded = [];

    public function guild(): Relation
    {
        return $this->belongsTo(Guild::class, 'guildId', 'id');
    }
}
