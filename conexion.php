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
    public $conn = null;

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
            // Mensaje simple para principiantes
            echo "Conexión fallida: " . $e->getMessage();
            exit; // Paramos la ejecución si no hay conexión
        }

        return $this->conn;
    }

    // Método sencillo: devuelve las categorías madre (id_madre IS NULL)
    // Devuelve un array asociativo similar a conexionVIEJA.php
    public function obtenerCategoriasMadre() {
        $db = $this->conectar();
        $sql = "SELECT * FROM CATEGORIA WHERE id_madre IS NULL ORDER BY id_categoria ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Método sencillo: devuelve las subcategorías de una madre
    public function obtenerSubcategorias($id_madre) {
        $db = $this->conectar();
        $sql = "SELECT * FROM CATEGORIA WHERE id_madre = :id_madre ORDER BY id_categoria ASC";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_madre', $id_madre, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}

?>