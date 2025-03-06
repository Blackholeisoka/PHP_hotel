<?php
require_once 'db_conn.php';  

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Fonction d'enregistrement des données utilisateur
    public function register($name, $email) {
        $stmt = $this->pdo->prepare('SELECT * FROM client WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return false;
        }

        $stmt = $this->pdo->prepare('INSERT INTO client (nom, email) VALUES (:nom, :email)');
        return $stmt->execute(['nom' => $name, 'email' => $email]);
    }
}
?>