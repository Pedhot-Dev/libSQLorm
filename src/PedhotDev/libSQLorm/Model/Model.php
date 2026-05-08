<?php
declare(strict_types=1);

namespace PedhotDev\libSQLorm\Model;

use Generator;
use PedhotDev\libSQLorm\Collection\Collection;
use PedhotDev\libSQLorm\Event\ModelEvent;
use PedhotDev\libSQLorm\Exception\MassAssignmentException;
use PedhotDev\libSQLorm\Metadata\ModelMetadataFactory;
use PedhotDev\libSQLorm\Query\ModelQueryBuilder;
use PedhotDev\libSQLorm\Relation\BelongsTo;
use PedhotDev\libSQLorm\Relation\BelongsToMany;
use PedhotDev\libSQLorm\Relation\HasMany;
use PedhotDev\libSQLorm\Relation\HasOne;
use PedhotDev\libSQLorm\Relation\Relation;

abstract class Model
{
    protected static ?ModelContext $context = null;
    protected static ?ModelMetadataFactory $metadataFactory = null;
    protected array $attributes = [];
    protected array $original = [];
    protected array $relations = [];
    protected array $fillable = [];
    protected array $guarded = ['*'];
    protected array $hidden = [];
    protected bool $exists = false;
    protected bool $timestamps = true;
    protected string $primaryKey = 'id';

    public static function setContext(ModelContext $context, ModelMetadataFactory $metadataFactory): void
    {
        self::$context = $context;
        self::$metadataFactory = $metadataFactory;
    }

    public static function tableName(): string
    {
        return self::meta()->get(static::class)->table;
    }

    private static function meta(): ModelMetadataFactory
    {
        if (self::$metadataFactory === null) {
            throw new \RuntimeException('ModelMetadataFactory has not been initialized');
        }
        return self::$metadataFactory;
    }

    public static function find(mixed $id): Generator
    {
        return yield from static::query()->where((new static())->primaryKey, '=', $id)->firstModel();
    }

    public static function query(): ModelQueryBuilder
    {
        return new ModelQueryBuilder(self::ctx()->driver, self::ctx()->hydrator, self::ctx()->relationLoader, static::class);
    }

    private static function ctx(): ModelContext
    {
        if (self::$context === null) {
            throw new \RuntimeException('ModelContext has not been initialized');
        }
        return self::$context;
    }

    public function fill(array $attributes): static
    {
        foreach ($attributes as $k => $v) {
            if (!$this->isFillable((string)$k)) {
                throw new MassAssignmentException("Attribute '$k' is not mass assignable");
            }
            $this->setAttribute((string)$k, $v);
        }
        return $this;
    }

    protected function isFillable(string $key): bool
    {
        if ($this->fillable !== []) {
            return in_array($key, $this->fillable, true);
        }
        if ($this->guarded === ['*']) {
            return false;
        }
        return !in_array($key, $this->guarded, true);
    }

    public function setAttribute(string $key, mixed $value): void
    {
        $this->attributes[$key] = $value;
        $this->{$key} = $value;
    }

    public function save(): Generator
    {
        $event = $this->exists ? 'updating' : 'creating';
        self::ctx()->events->dispatch(new ModelEvent($event, $this));
        if ($this->timestamps) {
            $now = date('Y-m-d H:i:s');
            $this->attributes['updated_at'] = $now;
            if (!$this->exists) {
                $this->attributes['created_at'] = $now;
            }
        }
        $builder = static::query();
        $data = self::ctx()->hydrator->dehydrate($this);
        if ($this->exists) {
            $affected = yield from $builder->where($this->primaryKey, '=', $this->getAttribute($this->primaryKey))->update($data);
            $ok = $affected >= 0;
            self::ctx()->events->dispatch(new ModelEvent('updated', $this));
        } else {
            $ok = yield from $builder->insert($data);
            $this->exists = $ok;
            self::ctx()->events->dispatch(new ModelEvent('created', $this));
        }
        $this->syncOriginal();
        return $ok;
    }

    public function getAttribute(string $key): mixed
    {
        return $this->attributes[$key] ?? null;
    }

    public function syncOriginal(): void
    {
        $this->original = $this->attributes;
    }

    public function delete(): Generator
    {
        self::ctx()->events->dispatch(new ModelEvent('deleting', $this));
        $affected = yield from static::query()->where($this->primaryKey, '=', $this->getAttribute($this->primaryKey))->delete();
        self::ctx()->events->dispatch(new ModelEvent('deleted', $this));
        return $affected > 0;
    }

    public function isDirty(?string $key = null): bool
    {
        if ($key !== null) {
            return ($this->attributes[$key] ?? null) !== ($this->original[$key] ?? null);
        }
        return $this->attributes !== $this->original;
    }

    public function markAsExisting(): void
    {
        $this->exists = true;
    }

    public function toArray(): array
    {
        return array_diff_key($this->attributes, array_flip($this->hidden));
    }

    public function toCollection(): Collection
    {
        return new Collection([$this]);
    }

    public function loadRelation(string $name): Generator
    {
        $relation = $this->{$name}();
        $this->relations[$name] = yield from $relation->getResults();
        return $this->relations[$name];
    }

    protected function hasOne(string $related, string $foreignKey, string $localKey): Relation
    {
        return new HasOne($this, $related, $foreignKey, $localKey);
    }

    protected function hasMany(string $related, string $foreignKey, string $localKey): Relation
    {
        return new HasMany($this, $related, $foreignKey, $localKey);
    }

    protected function belongsTo(string $related, string $foreignKey, string $ownerKey): Relation
    {
        return new BelongsTo($this, $related, $foreignKey, $ownerKey);
    }

    protected function belongsToMany(string $related, string $pivot, string $foreignPivotKey, string $relatedPivotKey, string $parentKey, string $relatedKey): Relation
    {
        return new BelongsToMany($this, $related, $pivot, $foreignPivotKey, $relatedPivotKey, $parentKey, $relatedKey);
    }
}
