<?php

// Clase Database - versión sencilla y comentada para principiantes
class Database {
    // Datos de conexión (modifica estos valores si es necesario)
    private $host = "192.168.4.18";
    private $port = "5432";
    private $dbname = "medicosDelMundo";
    private $user = "postgres";
    private $password = "P@ssw0rd";

    // Aquí guardamos la conexión PDO una vez creada
    private $conn = null;

    // Constructor opcional: permite pasar otros parámetros si se desea
    public function __construct($host = null, $port = null, $dbname = null, $user = null, $password = null) {
        if ($host) $this->host = $host;
        if ($port) $this->port = $port;
        if ($dbname) $this->dbname = $dbname;
        if ($user) $this->user = $user;
        if ($password) $this->password = $password;
    }

    // Conectar a la base de datos (devuelve un objeto PDO)
    // Uso básico:
    // $db = new Database();
    // $conn = $db->conectar();
    public function conectar() {
        // Si ya existe la conexión, la reutilizamos
        if ($this->conn) {
            return $this->conn;
        }

        try {
            $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->dbname}";
            $this->conn = new PDO($dsn, $this->user, $this->password);
            // Establecer modo de error a excepciones (útil para depuración)
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Devolver resultados como arrays asociativos por defecto (más sencillo)
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Mensaje simple de error
            echo "Conexión fallida: " . $e->getMessage();
            exit; // Paramos la ejecución si no hay conexión
        }

        return $this->conn;
    }
}

?>