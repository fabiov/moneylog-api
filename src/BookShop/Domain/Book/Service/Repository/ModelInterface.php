<?php

namespace App\BookShop\Domain\Book\Service\Repository;

interface ModelInterface
{
    public function add(array $values): string;
    public function find(array $filter): array;
    public function findOne(string $id): array;
}
