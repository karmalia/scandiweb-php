<?php

declare(strict_types = 1);
//Repository Class

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Core\Database;
class UserRepository implements UserRepositoryInterface {
    protected $users = [];
    private $conn;

    public function __construct(){
        $this->conn = new Database();
    }

    public function getAllUsers(){
        $query = 'SELECT * FROM users';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function createUser($firstname, $lastname){
        $this->users[] = [
            'id' => count($this->users) + 1,
            'firstname' => $firstname,
            'lastname' => $lastname
        ];
    }
}