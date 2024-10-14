<?php

namespace App\Repositories;

use App\Core\Database;

abstract class BaseRepository
{
    protected $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    // Example of common fetch method for multiple rows
    protected function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Common method to fetch a single row
    protected function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindParam($key, $value);
        }
        $stmt->execute();
        $result = $stmt->fetch();
        return $result ?: null;
    }

    // New method to execute an insert, update, or delete query
    protected function executeQuery(string $sql, array $params = []): bool
    {
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        return $stmt->execute();
    }

    // New method to handle inserts and return the last insert ID
    protected function insert(string $sql, array $params = []): int
    {
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);  // bindValue might work better than bindParam in some cases
        }
        $stmt->execute();

        return $this->db->lastInsertId();
    }


    // New method to update rows
    protected function update(string $sql, array $params = []): bool
    {
        return $this->executeQuery($sql, $params);
    }
}
