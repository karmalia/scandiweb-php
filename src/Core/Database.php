<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $conn;

    public function __construct()
    {
        
        $config = require __DIR__ . '/../../config.php';
 
        $this->host = $config['database']['host'];
        $this->db_name = $config['database']['db_name'];
        $this->username = $config['database']['username'];
        $this->password = $config['database']['password'];
    }

    public function connect()
    {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db_name}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Connection error: " . $e->getMessage();
        }

        return $this->conn;
    }
}
