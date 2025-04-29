<?php

namespace ScoobEco\InitSystem\DB;

use PDO;
use ScoobEco\Core\Support\BaseCollection;

/**
 * Class ScoobDB
 *
 * Gerenciador de conexão e operações de banco de dados.
 *
 * @package ScoobEco\InitSystem\DB
 */
class ScoobDB
{
    /**
     * Conexão ativa com o banco de dados.
     *
     * @var PDO
     */
    public PDO $connection;

    /**
     * Nome da tabela usada nas operações.
     *
     * @var string
     */
    private string $table = '';

    /**
     * Condições para consulta (where).
     *
     * @var array
     */
    private array $wheres = [];

    /**
     * ScoobDB constructor.
     */
    public function __construct()
    {
        $this->connection();
    }

    /**
     * Inicializa a conexão com o banco de dados.
     */
    public function connection()
    {
        $driver = $_ENV['DB_DRIVER'] ?? 'mysql';

        return match ($driver) {
            'mysql'  => $this->connection = BootMysql::getConnection(),
            'sqlite' => $this->connection = BootSqlite::getConnection(),
            'sqlsrv' => $this->connection = BootSqlsrv::getConnection(),
            default  => throw new \Exception("Unsupported DB driver: $driver"),
        };
    }

    /**
     * Desconecta do banco de dados.
     *
     * @return void
     */
    public function disconnect(): void
    {
        $driver = $_ENV['DB_DRIVER'] ?? 'mysql';

        match ($driver) {
            'mysql'  => BootMysql::disconnect(),
            'sqlite' => BootSqlite::disconnect(),
            'sqlsrv' => BootSqlsrv::disconnect(),
            default  => null,
        };
    }

    /**
     * Obtém todas as tabelas do banco de dados informado.
     *
     * @param string $database
     * @return array
     */
    public function getTables(string $database): array
    {
        $db     = $this->connection->query("SHOW TABLES;");
        $result = $db->fetchAll(PDO::FETCH_ASSOC);

        return $result ? array_column($result, 'Tables_in_' . $database) : [];
    }

    /**
     * Obtém o banco de dados atualmente selecionado.
     *
     * @return string|null
     */
    public function getDatabase(): ?string
    {
        $db     = $this->connection->query("SELECT DATABASE();");
        $result = $db->fetch(PDO::FETCH_ASSOC);

        return $result['DATABASE()'] ?? null;
    }

    /**
     * Define a tabela a ser utilizada.
     *
     * @param string $table
     * @return $this
     */
    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Adiciona uma condição WHERE à consulta.
     *
     * @param string $column
     * @param mixed $operatorOrValue
     * @param mixed|null $value
     * @return $this
     */
    public function where(string $column, mixed $operatorOrValue, mixed $value = null): self
    {
        if (func_num_args() === 2) {
            $operator = '=';
            $value    = $operatorOrValue;
        }
        else {
            $operator = $operatorOrValue;
        }

        $this->wheres[] = [
            'column'   => $column,
            'operator' => strtoupper($operator),
            'value'    => $value,
        ];

        return $this;
    }

    /**
     * Normaliza os dados para array.
     *
     * @param array|object $data
     * @return array
     */
    protected function normalizeData(array|object $data): array
    {
        return (array)$data;
    }

    /**
     * Executa a consulta e retorna os resultados como uma coleção.
     *
     * @return BaseCollection
     */
    public function get(): BaseCollection
    {
        $sql = "SELECT * FROM `{$this->table}`";

        if (!empty($this->wheres)) {
            $conditions = array_map(fn($w) => "`{$w['column']}` {$w['operator']} ?", $this->wheres);
            $sql        .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array_column($this->wheres, 'value'));

        return new BaseCollection($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Retorna o primeiro registro encontrado.
     *
     * @return object|null
     */
    public function first(): mixed
    {
        $sql = "SELECT * FROM `{$this->table}`";

        if (!empty($this->wheres)) {
            $conditions = array_map(fn($w) => "`{$w['column']}` {$w['operator']} ?", $this->wheres);
            $sql        .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute(array_column($this->wheres, 'value'));

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? (object)$data : null;
    }

    /**
     * Busca um registro pelo ID.
     *
     * @param mixed $value
     * @return object|null
     */
    public function find(mixed $value): mixed
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE id = ? LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$value]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? (object)$data : null;
    }

    /**
     * Insere um novo registro na tabela.
     *
     * @param array|object $data
     * @return bool
     */
    public function insert(array|object $data): bool
    {
        $data = $this->normalizeData($data);

        $columns      = implode(", ", array_map(fn($col) => "`$col`", array_keys($data)));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));

        $sql = "INSERT INTO `{$this->table}` ($columns) VALUES ($placeholders)";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute(array_values($data));
    }

    /**
     * Insere múltiplos registros de uma vez.
     *
     * @param array $data
     * @return bool
     */
    public function insertMany(array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        $first        = $this->normalizeData($data[0]);
        $columns      = implode(", ", array_map(fn($col) => "`$col`", array_keys($first)));
        $placeholders = [];
        $values       = [];

        foreach ($data as $row) {
            $row            = $this->normalizeData($row);
            $placeholders[] = "(" . implode(", ", array_fill(0, count($row), "?")) . ")";
            $values         = array_merge($values, array_values($row));
        }

        $sql = "INSERT INTO `{$this->table}` ($columns) VALUES " . implode(", ", $placeholders);

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute($values);
    }

    /**
     * Atualiza um registro baseado no ID.
     *
     * @param array|object $data
     * @param mixed $id
     * @return bool
     */
    public function update(array|object $data, mixed $id): bool
    {
        $data = $this->normalizeData($data);

        $set = implode(", ", array_map(fn($col) => "`$col` = ?", array_keys($data)));
        $sql = "UPDATE `{$this->table}` SET $set WHERE id = ?";

        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([
            ...array_values($data),
            $id
        ]);
    }

    /**
     * Deleta um registro baseado no ID.
     *
     * @param mixed $id
     * @return bool
     */
    public function delete(mixed $id): bool
    {
        $sql  = "DELETE FROM `{$this->table}` WHERE id = ?";
        $stmt = $this->connection->prepare($sql);

        return $stmt->execute([$id]);
    }

    /**
     * Deleta múltiplos registros.
     *
     * @param array $ids
     * @return bool
     */
    public function deleteMany(array $ids): bool
    {
        if (empty($ids)) {
            return false;
        }

        $placeholders = implode(",", array_fill(0, count($ids), "?"));
        $sql          = "DELETE FROM `{$this->table}` WHERE id IN ($placeholders)";
        $stmt         = $this->connection->prepare($sql);

        return $stmt->execute($ids);
    }

    /**
     * Cria um novo banco de dados com charset e collation opcionais.
     *
     * @param string $name
     * @param array $options
     * @return bool
     */
    public function createDatabase(string $name, array $options = []): bool
    {
        $stmt = $this->connection->query("SHOW DATABASES LIKE '$name'");

        if ($stmt->fetch()) {
            return false;
        }

        $charset   = $options['charset'] ?? 'utf8mb4';
        $collation = $options['collation'] ?? 'utf8mb4_unicode_ci';

        $sql = "CREATE DATABASE `$name` CHARACTER SET $charset COLLATE $collation";

        return $this->connection->exec($sql) === 0;
    }

    /**
     * Atualiza (renomeia e/ou altera charset/collation) um banco de dados.
     *
     * @param string $oldName
     * @param string $newName
     * @param array $options
     * @return bool
     */
    public function updateDatabase(string $oldName, string $newName, array $options = []): bool
    {
        $charset   = $options['charset'] ?? 'utf8mb4';
        $collation = $options['collation'] ?? 'utf8mb4_unicode_ci';

        $this->connection->exec("CREATE DATABASE `$newName` CHARACTER SET $charset COLLATE $collation");

        $tables = $this->connection->query("SHOW TABLES FROM `$oldName`")->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $table) {
            $this->connection->exec("RENAME TABLE `$oldName`.`$table` TO `$newName`.`$table`");
        }

        $this->connection->exec("DROP DATABASE `$oldName`");

        return true;
    }

    /**
     * Cria uma nova tabela no banco de dados.
     *
     * @param string $name
     * @param array $columns
     * @return bool
     */
    public function createTable(string $name, array $columns): bool
    {
        $dbName = $this->getDatabase();

        if (!$dbName) {
            return false;
        }

        $stmt = $this->connection->query("SHOW TABLES LIKE '$name'");

        if ($stmt->fetch()) {
            return true;
        }

        $columnsSql = implode(", ", $columns);
        $sql        = "CREATE TABLE `$name` ($columnsSql)";

        return $this->connection->exec($sql) === 0;
    }

    /**
     * Atualiza uma tabela existente (adiciona ou modifica colunas).
     *
     * @param string $name
     * @param array $modifications
     * @return bool
     */
    public function updateTable(string $name, array $modifications): bool
    {
        if (empty($modifications)) {
            return false;
        }

        $sql = "ALTER TABLE `$name` " . implode(", ", $modifications);

        return $this->connection->exec($sql) !== false;
    }

    /**
     * Deleta uma tabela do banco de dados.
     *
     * @param string $name
     * @return bool
     */
    public function deleteTable(string $name): bool
    {
        $sql = "DROP TABLE IF EXISTS `$name`";

        return $this->connection->exec($sql) !== false;
    }
}
