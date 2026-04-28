<?php
class Usuario {
    public $id_usuario;
    public $email;
    public $password;
    public $nombre;
    public $id_rol; // Relación con Rol

    public function __construct($id_usuario, $email, $password, $id_rol, $nombre = null) {
        $this->id_usuario = $id_usuario;
        $this->email = $email;
        $this->password = $password;
        $this->id_rol = $id_rol;
        $this->nombre = $nombre;
    }

    public function getId() {
        return $this->id_usuario;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRol() {
        return $this->id_rol;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setRol($id_rol) {
        $this->id_rol = intval($id_rol);
    }

    public static function RecuperarByID($id) {
        require_once __DIR__ . '/../conexion.php';
        $database = new Database();
        $conn = $database->conectar();
        $sql = 'SELECT id_usuario, nombre, email, id_rol FROM USUARIOS WHERE id_usuario = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', intval($id), PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return new Usuario($data['id_usuario'], $data['email'], null, intval($data['id_rol']), $data['nombre']);
    }

    public function guardar() {
        require_once __DIR__ . '/../conexion.php';
        $database = new Database();
        $conn = $database->conectar();

        $sql = 'UPDATE USUARIOS SET nombre = :nombre, email = :email, id_rol = :id_rol WHERE id_usuario = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
        $stmt->bindValue(':id_rol', intval($this->id_rol), PDO::PARAM_INT);
        $stmt->bindValue(':id', intval($this->id_usuario), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public static function EliminarByID($id) {
        require_once __DIR__ . '/../conexion.php';
        $database = new Database();
        $conn = $database->conectar();

        $sql = 'DELETE FROM USUARIOS WHERE id_usuario = :id';
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', intval($id), PDO::PARAM_INT);

        return $stmt->execute();
    }
}
?>