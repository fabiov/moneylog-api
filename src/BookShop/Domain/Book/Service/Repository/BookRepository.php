<?php

namespace App\BookShop\Domain\Book\Service\Repository;

use Doctrine\DBAL\DBALException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\DBAL\Statement;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;

class BookRepository implements ModelInterface
{
    /**
     * @var Connection
     */
    private $dbConn;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->dbConn = $entityManager->getConnection();
    }

    /**
     * @param array $values
     * @throws DBALException
     * @throws UniqueConstraintViolationException
     */
    public function add(array $values): string
    {
        $sql = 'INSERT INTO book VALUES (null, :author_id, :title, :isbn, :description, :price, :availability)';
        $stmt = $this->dbConn->prepare($sql);
        $stmt->execute($values);
        return $this->dbConn->lastInsertId();
    }

    public function find(array $filters): array
    {
        $data = [];
        $queryParameters = [];
        $query = self::bookQuery();

        if (isset($filters['title'])) {
            $query .= ' AND b.title LIKE :b_title';
            $queryParameters['b_title'] = $filters['title'];
        }
        if (isset($filters['isbn'])) {
            $query .= ' AND b.isbn LIKE :b_isbn';
            $queryParameters['b_isbn'] = $filters['isbn'];
        }
        if (isset($filters['author.name'])) {
            $query .= ' AND a.name LIKE :a_name';
            $queryParameters['a_name'] = $filters['author.name'];
        }

        /* @var Statement $stmt */
        $stmt = $this->dbConn->prepare($query);
        $stmt->execute($queryParameters);

        while ($row = $stmt->fetch()) {
            $data[] = self::serialize($row);
        }
        return $data;
    }

    public function findOne(string $id): array
    {
        $stmt = $this->dbConn->prepare(self::bookQuery() . ' AND b.id=:id');
        $stmt->execute(['id' => $id]);

        if (empty($row = $stmt->fetch())) {
            return [];
        }
        return self::serialize($row);
    }

    private static function bookQuery(): string
    {
        return <<< EOL
SELECT
    b.id AS b_id,
    b.title AS b_title,
    b.isbn AS b_isbn,
    b.description AS b_description,
    b.price AS b_price,
    b.availability AS b_availability,
    a.id AS a_id,
    a.name AS a_name
FROM book b INNER JOIN author a ON a.id = b.author_id WHERE 1=1
EOL;
    }

    private static function serialize($row): array
    {
        return [
            'id'           => $row['b_id'],
            'title'        => $row['b_title'],
            'isbn'         => $row['b_isbn'],
            'description'  => $row['b_description'],
            'price'        => $row['b_price'],
            'availability' => $row['b_availability'],
            'author'       => ['id' => $row['a_id'], 'name' => $row['a_name']],
        ];
    }
}
