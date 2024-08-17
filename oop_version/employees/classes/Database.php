<?php

class Database
{
    private $connection;
    private $host = 'localhost';
    private $dbname = 'company';
    private $username = 'root';
    private $password = '';

    public function __construct()
    {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password
            );
            
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (Exception $e) {
            echo "Connection failed: " . $e->getMessage();
            exit();
        }
    }

    public function getConnection()
    {
        return $this->connection;
    }

   


}
