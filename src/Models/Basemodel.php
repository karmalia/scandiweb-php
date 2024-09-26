<?php

namespace Models;

use App\Core\Database;

abstract class BaseModel {
    protected $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }

    abstract public function findById($id);
    abstract public function findAll();

    public function insert($data, $table) {
        $keys = array_keys($data);
        $fields = implode(',', $keys);
        $placeholders = implode(',', array_fill(0, count($keys), '?'));

        $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));

        return $this->db->lastInsertId();
    }

    
}
