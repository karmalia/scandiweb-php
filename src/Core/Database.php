<?php 

namespace App\Core;

use PDO;
use PDOException;


class Database
{
    private $host = 'localhost';
    private $db_name = 'my_database';
    private $username = 'root';
    private $password = '';
    private $conn;
    
    public function __construct()
    {
        $config = require __DIR__ . '/../../config.php';
        $dbConfig = $config['database'];

        // Set up the connection using the config variables
        $this->host = $dbConfig['host'];
        $this->db_name = $dbConfig['db_name'];
        $this->username = $dbConfig['username'];
        $this->password = $dbConfig['password'];
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

    public function prepare($query)
    {

        if ($this->conn === null) {
            $this->connect(); // Ensure the connection is established
        }

        try {
            $stmt = $this->conn->prepare($query);
            return $stmt;
        } catch (PDOException $e) {
            echo "Prepare error: " . $e->getMessage();
        }

        return null;
    }
}