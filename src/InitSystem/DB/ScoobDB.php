<?php

namespace ScoobEco\InitSystem\DB;

use PDO;
use ScoobEco\Core\Support\BaseCollection;

class ScoobDB
{
    public         $connection;
    private string $table  = '';
    private array  $wheres = [];

    public function __construct()
    {
        $this->connection();
    }

    public function connection(): self
    {
        $driver = $_ENV['DB_DRIVER'];

        if ($driver == 'mysql') {
            $this->connection = BootMysql::getConnection();
        }

        if ($driver == 'sqlite') {
            $this->connection = BootSqlite::getConnection();
        }

        if ($driver == 'sqlsrv') {
            $this->connection = BootSqlsrv::getConnection();
        }

        return $this;
    }

    public function getTables(string $database): array|string
    {
        $db     = $this->connection->query("SHOW TABLES;");
        $result = $db->fetchAll(PDO::FETCH_ASSOC);
        $result = array_column($result, 'Tables_in_' . $database);
        return $result ?? [];
    }

    public function getDatabase(): string|null
    {
        $db     = $this->connection->query("SELECT DATABASE();");
        $result = $db->fetch(PDO::FETCH_ASSOC);
        return $result['DATABASE()'] ?? null;
    }

    public function table(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    public function where(string $column, mixed $operatorOrValue, mixed $value = null): self
    {
        if (func_num_args() === 2) {
            $operator = '=';
            $value    = $operatorOrValue;
        } else {
            $operator = $operatorOrValue;
        }

        $this->wheres[] = [
            'column'   => $column,
            'operator' => strtoupper($operator),
            'value'    => $value,
        ];

        return $this;
    }

    protected function normalizeData(array|object $data): array
    {
        return is_object($data) ? get_object_vars($data) : $data;
    }

    public function get(): BaseCollection
    {
        $sql = "SELECT * FROM {$this->table}";

        if (!empty($this->wheres)) {
            $conditions = array_map(fn($w) => "{$w['column']} {$w['operator']} ?", $this->wheres);
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->connection->prepare($sql);
        $values = array_column($this->wheres, 'value');
        $stmt->execute($values);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return new BaseCollection($data);
    }

    public function first(): mixed
    {
        $sql = "SELECT * FROM {$this->table}";

        if (!empty($this->wheres)) {
            $conditions = array_map(fn($w) => "{$w['column']} {$w['operator']} ?", $this->wheres);
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $values = array_column($this->wheres, 'value');
        $stmt->execute($values);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? (object) $data : null;
    }

    public function find(mixed $value): mixed
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ? LIMIT 1";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([$value]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? (object) $data : null;
    }

    public function insert(array|object $data): bool
    {
        $data = $this->normalizeData($data);

        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";

        $stmt = $this->connection->prepare($sql);
        return $stmt->execute(array_values($data));
    }

    public function insertMany(array $data): bool
    {
        if (empty($data)) return false;

        $first = $this->normalizeData($data[0]);
        $columns = implode(", ", array_keys($first));
        $placeholders = [];
        $values = [];

        foreach ($data as $row) {
            $row = $this->normalizeData($row);
            $placeholders[] = "(" . implode(", ", array_fill(0, count($row), "?")) . ")";
            $values = array_merge($values, array_values($row));
        }

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES " . implode(", ", $placeholders);

        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($values);
    }

    public function update(array|object $data, mixed $id): bool
    {
        $data = $this->normalizeData($data);

        $set = implode(", ", array_map(fn($col) => "$col = ?", array_keys($data)));
        $sql = "UPDATE {$this->table} SET {$set} WHERE id = ?";

        $stmt = $this->connection->prepare($sql);
        return $stmt->execute(array_merge(array_values($data), [$id]));
    }

    public function delete(mixed $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function deleteMany(array $ids): bool
    {
        if (empty($ids)) return false;

        $placeholders = implode(",", array_fill(0, count($ids), "?"));
        $sql = "DELETE FROM {$this->table} WHERE id IN ({$placeholders})";
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($ids);
    }
}
