<?php

namespace App\Service\Domain\Repository;

interface ModelInterface
{
    public function add(array $values): string;
    public function find(array $filter): array;
    public function findOne(string $id): array;
}
