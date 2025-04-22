<?php

namespace ScoobEco\Core\Support;

use ScoobEco\Core\Contracts\CollectionInterface;
use Iterator;

class BaseCollection implements CollectionInterface, Iterator
{
    protected array $data;
    protected bool  $asObject = true;
    protected int   $position = 0;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function all(): array
    {
        return $this->data;
    }

    public function first(): mixed
    {
        return $this->data[0] ?? null;
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function pluck(string $column): array
    {
        return array_map(fn($row) => $row[$column] ?? null, $this->data);
    }

    public function map(callable $callback): array
    {
        return array_map($callback, $this->data);
    }

    public function filter(callable $callback): array
    {
        return array_values(array_filter($this->data, $callback));
    }

    public function groupBy(string $column): array
    {
        $grouped = [];
        foreach ($this->data as $row) {
            $key             = $row[$column] ?? '__undefined';
            $grouped[$key][] = $row;
        }
        return $grouped;
    }

    public function toJson(int $flags = 0): string
    {
        return json_encode($this->data, $flags);
    }

    public function sum(string $column): float|int
    {
        return array_reduce($this->data, function ($carry, $item) use ($column) {
            return $carry + ($item[$column] ?? 0);
        }, 0);
    }

    public function avg(string $column): float
    {
        $count = $this->count();
        return $count ? $this->sum($column) / $count : 0.0;
    }

    public function paginate(int $perPage, int $page = 1): array
    {
        $total  = $this->count();
        $offset = ($page - 1) * $perPage;
        $items  = array_slice($this->data, $offset, $perPage);

        return [
            'current_page' => $page,
            'per_page'     => $perPage,
            'total'        => $total,
            'last_page'    => ceil($total / $perPage),
            'data'         => $items,
        ];
    }

    public function toArray(): array
    {
        $this->asObject = false;
        return $this->data;
    }

    public function toObject(): array
    {
        $this->asObject = true;
        return array_map(fn($item) => (object)$item, $this->data);
    }

    public function firstAsObject(): object|null
    {
        return isset($this->data[0]) ? (object)$this->data[0] : null;
    }

    public function getResults()
    {
        return $this->asObject ? $this->toObject() : $this->toArray();
    }

    public function current(): mixed
    {
        return $this->data[$this->position];
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position++;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->data[$this->position]);
    }
}
