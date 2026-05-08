# libSQLorm API Reference

API-only reference generated from `src/PedhotDev/libSQLorm`.

## `src/PedhotDev/libSQLorm/Async/Async.php`

- Namespace: `PedhotDev\libSQLorm\Async`
- Types:
  - `final class Async`
- API Members:
  - `private function __construct()`
  - `public static function concurrent(array $tasks) : Generator`
  - `public static function awaitAll(array $tasks) : Generator`
  - `public static function race(array $tasks) : Generator`

## `src/PedhotDev/libSQLorm/Async/CoroutineExecutor.php`

- Namespace: `PedhotDev\libSQLorm\Async`
- Types:
  - `final class CoroutineExecutor`
- API Members:
  - `public function run(Generator $coroutine, ?callable $onComplete = null) : void`

## `src/PedhotDev/libSQLorm/Attribute/Column.php`

- Namespace: `PedhotDev\libSQLorm\Attribute`
- Types:
  - `final class Column`
- API Members:
  - `public function __construct(/** Override the column name. If null, uses the property name. */ public readonly ?string $name = null,)`

## `src/PedhotDev/libSQLorm/Attribute/Table.php`

- Namespace: `PedhotDev\libSQLorm\Attribute`
- Types:
  - `final class Table`
- API Members:
  - `public function __construct(public readonly string $name,)`

## `src/PedhotDev/libSQLorm/Bootstrap/OrmBootstrap.php`

- Namespace: `PedhotDev\libSQLorm\Bootstrap`
- Types:
  - `readonly class OrmBootstrap`
- API Members:
  - `public function __construct(private ContainerInterface $container)`
  - `public function boot() : void`

## `src/PedhotDev/libSQLorm/Collection/Collection.php`

- Namespace: `PedhotDev\libSQLorm\Collection`
- Types:
  - `class Collection`
- API Members:
  - `public function __construct(private array $items = [])`
  - `public function getIterator() : Traversable`
  - `public function map(Closure $callback) : self`
  - `public function filter(Closure $callback) : self`
  - `public function reduce(Closure $callback, mixed $initial = null) : mixed`
  - `public function first() : mixed`
  - `public function pluck(string $field) : self`
  - `public function chunk(int $size) : self`
  - `public function sort(?Closure $comparator = null) : self`
  - `public function all() : array`

## `src/PedhotDev/libSQLorm/Config/OrmConfig.php`

- Namespace: `PedhotDev\libSQLorm\Config`
- Types:
  - `readonly class OrmConfig`
- API Members:
  - `public function __construct(public string $defaultConnection, public array $poolConfiguration)`

## `src/PedhotDev/libSQLorm/Contract/ConnectionManagerInterface.php`

- Namespace: `PedhotDev\libSQLorm\Contract`
- Types:
  - `interface ConnectionManagerInterface`
- API Members:
  - `public function submit(SQLQuery $query, ?Closure $onSuccess = null, ?Closure $onFail = null) : void`
  - `public function submitAsync(SQLQuery $query) : Generator`
  - `public function isConnected() : bool`

## `src/PedhotDev/libSQLorm/Contract/DriverInterface.php`

- Namespace: `PedhotDev\libSQLorm\Contract`
- Types:
  - `interface DriverInterface`
- API Members:
  - `public function executeQuery(string $sql, array $bindings = []) : Generator`
  - `public function executeStatement(string $sql, array $bindings = []) : Generator`
  - `public function lastInsertId() : Generator`
  - `public function getGrammarClass() : string`

## `src/PedhotDev/libSQLorm/Contract/EventDispatcherInterface.php`

- Namespace: `PedhotDev\libSQLorm\Contract`
- Types:
  - `interface EventDispatcherInterface`
- API Members:
  - `public function listen(string $event, callable $listener) : void`
  - `public function dispatch(ModelEvent $event) : bool`
  - `public function flush(string $event) : void`

## `src/PedhotDev/libSQLorm/Contract/HydratorInterface.php`

- Namespace: `PedhotDev\libSQLorm\Contract`
- Types:
  - `interface HydratorInterface`
- API Members:
  - `public function hydrate(array $row, string $modelClass) : Model`
  - `public function hydrateMany(array $rows, string $modelClass) : array`
  - `public function dehydrate(Model $model) : array`

## `src/PedhotDev/libSQLorm/Contract/MigrationInterface.php`

- Namespace: `PedhotDev\libSQLorm\Contract`
- Types:
  - `interface MigrationInterface`
- API Members:
  - `public function up() : Generator`
  - `public function down() : Generator`

## `src/PedhotDev/libSQLorm/Contract/QueryBuilderInterface.php`

- Namespace: `PedhotDev\libSQLorm\Contract`
- Types:
  - `interface QueryBuilderInterface`
- API Members:
  - `public function table(string $table) : static`
  - `public function select(string|array $columns) : static`
  - `public function where(string $column, string $operator, mixed $value) : static`
  - `public function orWhere(string $column, string $operator, mixed $value) : static`
  - `public function whereIn(string $column, array $values) : static`
  - `public function whereNull(string $column) : static`
  - `public function whereNotNull(string $column) : static`
  - `public function join(string $table, string $first, string $operator, string $second) : static`
  - `public function leftJoin(string $table, string $first, string $operator, string $second) : static`
  - `public function orderBy(string $column, string $direction = 'ASC') : static`
  - `public function groupBy(string ...$columns) : static`
  - `public function limit(int $limit) : static`
  - `public function offset(int $offset) : static`
  - `public function with(array $relations) : static`
  - `public function get() : Generator`
  - `public function first() : Generator`
  - `public function count() : Generator`
  - `public function sum(string $column) : Generator`
  - `public function avg(string $column) : Generator`
  - `public function insert(array $data) : Generator`
  - `public function update(array $data) : Generator`
  - `public function delete() : Generator`

## `src/PedhotDev/libSQLorm/Contract/RepositoryInterface.php`

- Namespace: `PedhotDev\libSQLorm\Contract`
- Types:
  - `interface RepositoryInterface`
- API Members:
  - `public function findById(mixed $id) : Generator`
  - `public function findAll() : Generator`
  - `public function save(Model $model) : Generator`
  - `public function delete(Model $model) : Generator`

## `src/PedhotDev/libSQLorm/Contract/SchemaManagerInterface.php`

- Namespace: `PedhotDev\libSQLorm\Contract`
- Types:
  - `interface SchemaManagerInterface`
- API Members:
  - `public function create(string $table, Closure $callback) : Generator`
  - `public function drop(string $table) : Generator`
  - `public function dropIfExists(string $table) : Generator`
  - `public function table(string $table, Closure $callback) : Generator`
  - `public function hasTable(string $table) : Generator`

## `src/PedhotDev/libSQLorm/DI/OrmModule.php`

- Namespace: `PedhotDev\libSQLorm\DI`
- Types:
  - `final class OrmModule`
- API Members:
  - `public function __construct(private readonly ConnectionPool $pool, private readonly array $migrationClasses = [])`
  - `public function configure(ModuleConfiguratorInterface $configurator) : void`
  - `public function getExposedServices() : array`

## `src/PedhotDev/libSQLorm/Database/ConnectionManager.php`

- Namespace: `PedhotDev\libSQLorm\Database`
- Types:
  - `readonly class ConnectionManager`
- API Members:
  - `public function __construct(private ConnectionPool $pool)`
  - `public function submitAsync(SQLQuery $query) : Generator`
  - `public function submit(SQLQuery $query, ?Closure $onSuccess = null, ?Closure $onFail = null) : void`
  - `public function isConnected() : bool`

## `src/PedhotDev/libSQLorm/Database/ConnectionPoolFactory.php`

- Namespace: `PedhotDev\libSQLorm\Database`
- Types:
  - `readonly class ConnectionPoolFactory`
- API Members:
  - `public function create(PluginBase $plugin, OrmConfig $config) : ConnectionPool`

## `src/PedhotDev/libSQLorm/Database/ConnectionResolver.php`

- Namespace: `PedhotDev\libSQLorm\Database`
- Types:
  - `readonly class ConnectionResolver`
- API Members:
  - `public function __construct(private DriverInterface $driver)`
  - `public function driver() : DriverInterface`

## `src/PedhotDev/libSQLorm/Database/Driver/SQLiteDriver.php`

- Namespace: `PedhotDev\libSQLorm\Database\Driver`
- Types:
  - `readonly class SQLiteDriver`
- API Members:
  - `public function __construct(private ConnectionManagerInterface $manager)`
  - `public function executeStatement(string $sql, array $bindings = []) : Generator`
  - `public function lastInsertId() : Generator`
  - `public function executeQuery(string $sql, array $bindings = []) : Generator`
  - `public function getGrammarClass() : string`

## `src/PedhotDev/libSQLorm/Database/Driver/SQLiteGrammar.php`

- Namespace: `PedhotDev\libSQLorm\Database\Driver`
- Types:
  - `final class SQLiteGrammar`
- API Members:
  - `public function wrap(string $identifier) : string`

## `src/PedhotDev/libSQLorm/Database/Query/RawSQLiteQuery.php`

- Namespace: `PedhotDev\libSQLorm\Database\Query`
- Types:
  - `final class RawSQLiteQuery`
- API Members:
  - `public function __construct(private readonly string $sql, private readonly array $bindings = [], private readonly bool $statement = false)`
  - `public function onRun(SQLite3 $connection) : void`

## `src/PedhotDev/libSQLorm/Event/ModelEvent.php`

- Namespace: `PedhotDev\libSQLorm\Event`
- Types:
  - `readonly class ModelEvent`
- API Members:
  - `public function __construct(public string $name, public Model $model)`

## `src/PedhotDev/libSQLorm/Event/ModelEventDispatcher.php`

- Namespace: `PedhotDev\libSQLorm\Event`
- Types:
  - `final class ModelEventDispatcher`
- API Members:
  - `public function listen(string $event, callable $listener) : void`
  - `public function dispatch(ModelEvent $event) : bool`
  - `public function flush(string $event) : void`

## `src/PedhotDev/libSQLorm/Example/Migration/CreateGuildsTableMigration.php`

- Namespace: `PedhotDev\libSQLorm\Example\Migration`
- Types:
  - `final class CreateGuildsTableMigration`
- API Members:
  - `public function up() : Generator`
  - `public function down() : Generator`

## `src/PedhotDev/libSQLorm/Example/Migration/CreateUsersTableMigration.php`

- Namespace: `PedhotDev\libSQLorm\Example\Migration`
- Types:
  - `final class CreateUsersTableMigration`
- API Members:
  - `public function up() : Generator`
  - `public function down() : Generator`

## `src/PedhotDev/libSQLorm/Example/Model/Guild.php`

- Namespace: `PedhotDev\libSQLorm\Example\Model`
- Types:
  - `final class Guild`
- API Members:
  - `public function users() : Relation`

## `src/PedhotDev/libSQLorm/Example/Model/User.php`

- Namespace: `PedhotDev\libSQLorm\Example\Model`
- Types:
  - `final class User`
- API Members:
  - `public function guild() : Relation`

## `src/PedhotDev/libSQLorm/Example/Usage/OrmUsageExample.php`

- Namespace: `PedhotDev\libSQLorm\Example\Usage`
- Types:
  - `final class OrmUsageExample`
- API Members:
  - `public function __construct(private readonly UserRepository $repository)`
  - `public function run() : Generator`

## `src/PedhotDev/libSQLorm/Exception/DriverException.php`

- Namespace: `PedhotDev\libSQLorm\Exception`
- Types:
  - `final class DriverException`
- API Members:
  - none

## `src/PedhotDev/libSQLorm/Exception/MassAssignmentException.php`

- Namespace: `PedhotDev\libSQLorm\Exception`
- Types:
  - `final class MassAssignmentException`
- API Members:
  - none

## `src/PedhotDev/libSQLorm/Exception/OrmException.php`

- Namespace: `PedhotDev\libSQLorm\Exception`
- Types:
  - `class OrmException`
- API Members:
  - none

## `src/PedhotDev/libSQLorm/Hydration/ModelHydrator.php`

- Namespace: `PedhotDev\libSQLorm\Hydration`
- Types:
  - `readonly class ModelHydrator`
- API Members:
  - `public function __construct(private ModelMetadataFactory $metadataFactory)`
  - `public function hydrateMany(array $rows, string $modelClass) : array`
  - `public function hydrate(array $row, string $modelClass) : Model`
  - `public function dehydrate(Model $model) : array`

## `src/PedhotDev/libSQLorm/Metadata/ModelMetadata.php`

- Namespace: `PedhotDev\libSQLorm\Metadata`
- Types:
  - `readonly class ModelMetadata`
- API Members:
  - `public function __construct(public string $table, public string $primaryKey, public array $columns, public bool $timestamps)`

## `src/PedhotDev/libSQLorm/Metadata/ModelMetadataFactory.php`

- Namespace: `PedhotDev\libSQLorm\Metadata`
- Types:
  - `final class ModelMetadataFactory`
- API Members:
  - `public function get(string $modelClass) : ModelMetadata`

## `src/PedhotDev/libSQLorm/Migration/Migration.php`

- Namespace: `PedhotDev\libSQLorm\Migration`
- Types:
  - `abstract class Migration`
- API Members:
  - none

## `src/PedhotDev/libSQLorm/Migration/MigrationRepository.php`

- Namespace: `PedhotDev\libSQLorm\Migration`
- Types:
  - `readonly class MigrationRepository`
- API Members:
  - `public function __construct(private DriverInterface $driver)`
  - `public function ensureTable() : Generator`
  - `public function log(string $migration, int $batch) : Generator`
  - `public function delete(string $migration) : Generator`
  - `public function allRan() : Generator`
  - `public function nextBatch() : Generator`

## `src/PedhotDev/libSQLorm/Migration/MigrationRunner.php`

- Namespace: `PedhotDev\libSQLorm\Migration`
- Types:
  - `readonly class MigrationRunner`
- API Members:
  - `public function __construct(private MigrationRepository $repository, private iterable $migrations)`
  - `public function migrate() : Generator`
  - `public function rollback() : Generator`

## `src/PedhotDev/libSQLorm/Model/Model.php`

- Namespace: `PedhotDev\libSQLorm\Model`
- Types:
  - `abstract class Model`
- API Members:
  - `public static function setContext(ModelContext $context, ModelMetadataFactory $metadataFactory) : void`
  - `public static function tableName() : string`
  - `private static function meta() : ModelMetadataFactory`
  - `public static function find(mixed $id) : Generator`
  - `public static function query() : ModelQueryBuilder`
  - `private static function ctx() : ModelContext`
  - `public function fill(array $attributes) : static`
  - `protected function isFillable(string $key) : bool`
  - `public function setAttribute(string $key, mixed $value) : void`
  - `public function save() : Generator`
  - `public function getAttribute(string $key) : mixed`
  - `public function syncOriginal() : void`
  - `public function delete() : Generator`
  - `public function isDirty(?string $key = null) : bool`
  - `public function markAsExisting() : void`
  - `public function toArray() : array`
  - `public function toCollection() : Collection`
  - `public function loadRelation(string $name) : Generator`
  - `protected function hasOne(string $related, string $foreignKey, string $localKey) : Relation`
  - `protected function hasMany(string $related, string $foreignKey, string $localKey) : Relation`
  - `protected function belongsTo(string $related, string $foreignKey, string $ownerKey) : Relation`
  - `protected function belongsToMany(string $related, string $pivot, string $foreignPivotKey, string $relatedPivotKey, string $parentKey, string $relatedKey) : Relation`

## `src/PedhotDev/libSQLorm/Model/ModelContext.php`

- Namespace: `PedhotDev\libSQLorm\Model`
- Types:
  - `readonly class ModelContext`
- API Members:
  - `public function __construct(public DriverInterface $driver, public HydratorInterface $hydrator, public EventDispatcherInterface $events, public RelationLoader $relationLoader)`
  - `public static function fromContainer(ContainerInterface $container) : self`

## `src/PedhotDev/libSQLorm/Query/ModelQueryBuilder.php`

- Namespace: `PedhotDev\libSQLorm\Query`
- Types:
  - `final class ModelQueryBuilder`
- API Members:
  - `public function __construct(DriverInterface $driver, private readonly HydratorInterface $hydrator, private readonly RelationLoader $relationLoader, private readonly string $modelClass)`
  - `public function getModels() : Generator`
  - `public function firstModel() : Generator`

## `src/PedhotDev/libSQLorm/Query/QueryBuilder.php`

- Namespace: `PedhotDev\libSQLorm\Query`
- Types:
  - `class QueryBuilder`
- API Members:
  - `public function __construct(protected readonly DriverInterface $driver)`
  - `public function table(string $table) : static`
  - `public function where(string $column, string $operator, mixed $value) : static`
  - `private function bind(mixed $value) : string`
  - `public function orWhere(string $column, string $operator, mixed $value) : static`
  - `public function whereIn(string $column, array $values) : static`
  - `public function whereNull(string $column) : static`
  - `public function whereNotNull(string $column) : static`
  - `public function join(string $table, string $first, string $operator, string $second) : static`
  - `public function leftJoin(string $table, string $first, string $operator, string $second) : static`
  - `public function orderBy(string $column, string $direction = 'ASC') : static`
  - `public function groupBy(string ...$columns) : static`
  - `public function offset(int $offset) : static`
  - `public function with(array $relations) : static`
  - `public function count() : Generator`
  - `public function select(string|array $columns) : static`
  - `public function first() : Generator`
  - `public function limit(int $limit) : static`
  - `public function get() : Generator`
  - `protected function toSelectSql() : string`
  - `protected function whereSql() : string`
  - `public function sum(string $column) : Generator`
  - `public function avg(string $column) : Generator`
  - `public function insert(array $data) : Generator`
  - `public function update(array $data) : Generator`
  - `public function delete() : Generator`

## `src/PedhotDev/libSQLorm/Query/QueryState.php`

- Namespace: `PedhotDev\libSQLorm\Query`
- Types:
  - `final class QueryState`
- API Members:
  - none

## `src/PedhotDev/libSQLorm/Relation/BelongsTo.php`

- Namespace: `PedhotDev\libSQLorm\Relation`
- Types:
  - `final class BelongsTo`
- API Members:
  - `public function __construct(\PedhotDev\libSQLorm\Model\Model $parent, private readonly string $related, private readonly string $foreignKey, private readonly string $ownerKey)`
  - `public function getResults() : Generator`

## `src/PedhotDev/libSQLorm/Relation/BelongsToMany.php`

- Namespace: `PedhotDev\libSQLorm\Relation`
- Types:
  - `final class BelongsToMany`
- API Members:
  - `public function __construct(\PedhotDev\libSQLorm\Model\Model $parent, private readonly string $related, private readonly string $pivot, private readonly string $foreignPivotKey, private readonly string $relatedPivotKey, private readonly string $parentKey, private readonly string $relatedKey)`
  - `public function getResults() : Generator`

## `src/PedhotDev/libSQLorm/Relation/HasMany.php`

- Namespace: `PedhotDev\libSQLorm\Relation`
- Types:
  - `final class HasMany`
- API Members:
  - `public function __construct(\PedhotDev\libSQLorm\Model\Model $parent, private readonly string $related, private readonly string $foreignKey, private readonly string $localKey)`
  - `public function getResults() : Generator`

## `src/PedhotDev/libSQLorm/Relation/HasOne.php`

- Namespace: `PedhotDev\libSQLorm\Relation`
- Types:
  - `final class HasOne`
- API Members:
  - `public function __construct(\PedhotDev\libSQLorm\Model\Model $parent, private readonly string $related, private readonly string $foreignKey, private readonly string $localKey)`
  - `public function getResults() : Generator`

## `src/PedhotDev/libSQLorm/Relation/Relation.php`

- Namespace: `PedhotDev\libSQLorm\Relation`
- Types:
  - `abstract class Relation`
- API Members:
  - `public function __construct(protected Model $parent)`

## `src/PedhotDev/libSQLorm/Relation/RelationLoader.php`

- Namespace: `PedhotDev\libSQLorm\Relation`
- Types:
  - `final class RelationLoader`
- API Members:
  - `public function eagerLoad(array $models, array $relations) : Generator`

## `src/PedhotDev/libSQLorm/Repository/Repository.php`

- Namespace: `PedhotDev\libSQLorm\Repository`
- Types:
  - `abstract class Repository`
- API Members:
  - `public function findById(mixed $id) : Generator`
  - `public function findAll() : Generator`
  - `public function save(Model $model) : Generator`
  - `public function delete(Model $model) : Generator`

## `src/PedhotDev/libSQLorm/Repository/UserRepository.php`

- Namespace: `PedhotDev\libSQLorm\Repository`
- Types:
  - `final class UserRepository`
- API Members:
  - `public function findRichUsers() : Generator`
  - `protected function modelClass() : string`

## `src/PedhotDev/libSQLorm/Schema/Blueprint.php`

- Namespace: `PedhotDev\libSQLorm\Schema`
- Types:
  - `final class Blueprint`
- API Members:
  - `public function __construct(private readonly string $table)`
  - `public function integer(string $name) : ColumnDefinition`
  - `private function push(ColumnDefinition $column) : ColumnDefinition`
  - `public function bigInteger(string $name) : ColumnDefinition`
  - `public function timestamps() : void`
  - `public function string(string $name) : ColumnDefinition`
  - `public function toCreateSql() : string`

## `src/PedhotDev/libSQLorm/Schema/ColumnDefinition.php`

- Namespace: `PedhotDev\libSQLorm\Schema`
- Types:
  - `final class ColumnDefinition`
- API Members:
  - `public function __construct(public string $name, public string $type, public bool $primary = false, public bool $nullable = false, public mixed $default = null)`
  - `public function primary() : self`
  - `public function nullable() : self`
  - `public function default(mixed $value) : self`

## `src/PedhotDev/libSQLorm/Schema/Schema.php`

- Namespace: `PedhotDev\libSQLorm\Schema`
- Types:
  - `final class Schema`
- API Members:
  - `public static function setManager(SchemaManager $manager) : void`
  - `public static function create(string $table, \Closure $callback) : Generator`
  - `private static function manager() : SchemaManager`
  - `public static function drop(string $table) : Generator`
  - `public static function dropIfExists(string $table) : Generator`

## `src/PedhotDev/libSQLorm/Schema/SchemaManager.php`

- Namespace: `PedhotDev\libSQLorm\Schema`
- Types:
  - `readonly class SchemaManager`
- API Members:
  - `public function __construct(private DriverInterface $driver)`
  - `public function create(string $table, Closure $callback) : Generator`
  - `public function drop(string $table) : Generator`
  - `public function dropIfExists(string $table) : Generator`
  - `public function table(string $table, Closure $callback) : Generator`
  - `public function hasTable(string $table) : Generator`

## `src/PedhotDev/libSQLorm/bootstrap.php`

- Namespace: `(global)`
- Types:
  - `none`
- API Members:
  - `public static function __invoke(PluginBase $plugin) : array`

