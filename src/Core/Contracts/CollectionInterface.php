<?php

namespace ScoobEco\Core\Contracts;

interface CollectionInterface
{
    public function all(): array;

    public function first(): mixed;

    public function count(): int;

    public function pluck(string $column): array;

    public function map(callable $callback): array;

    public function filter(callable $callback): array;

    public function groupBy(string $column): array;

    public function toJson(int $flags = 0): string;

    public function sum(string $column): float|int;

    public function avg(string $column): float;

    public function paginate(int $perPage, int $page = 1): array;

    public function toArray(): array;

    public function toObject(): array;

    public function firstAsObject(): object|null;

    public function getResults();

    public function current(): mixed;

    public function key(): mixed;

    public function next(): void;

    public function rewind(): void;

    public function valid(): bool;
}
