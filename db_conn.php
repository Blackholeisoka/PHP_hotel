<?php

// Etablir la connexion à la base de données
class Database
{
    private $host = "localhost"; 
    private $dbName = "reservationhotel";
    private $username = "root";  
    private $password = "";    
    private $conn = null;  

    public function connect()
    {
        if ($this->conn === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
                $this->conn = new PDO($dsn, $this->username, $this->password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
                    PDO::ATTR_EMULATE_PREPARES => false, 
                ]);
            } catch (PDOException $e) {
                throw new Exception("Erreur de connexion à la base de données : " . $e->getMessage());
            }
        }

        return $this->conn;
    }

    public function disconnect()
    {
        $this->conn = null;
    }
}
?>
