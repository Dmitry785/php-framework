<?php

namespace App\ORM;

use PDO;
use PDOException;

class Database{
    private PDO $pdo;

    public function __construct(
        string $database,
        string $username,
        string $password,
        array $options
    )
    {
        try{
            $this->pdo = new PDO($database,
            $username,
            $password,
            $options);
        }
        catch (PDOException $e){
            die("Error occured while connecting to database: " . $e->getMessage());
        }
    }
}