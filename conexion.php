<?php

class Database {
    // Atributos privados con tus datos originales
    private $host = "192.168.4.18";
    private $port = "5432";
    private $dbname = "medicosDelMundo";
    private $user = "postgres";
    private $password = "P@ssw0rd";
    public $conn;

    // Método para conectar
    public function conectar() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->dbname, 
                $this->user, 
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Conexión fallida: " . $e->getMessage();
            die();
        }

        return $this->conn;
    }
}

?>