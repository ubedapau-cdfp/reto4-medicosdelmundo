<?php
    class Rol {
        private $id_rol;
        private $nombre_rol;

    public function __construct($id_rol, $nombre_rol) {
        $this->id_rol = $id_rol;
        $this->nombre_rol = $nombre_rol;
    }

    /**
     * Obtiene el ID del rol
     * @return int
     */
    public function getIdRol() {
        return $this->id_rol;
    }

    /**
     * Obtiene el nombre del rol
     * @return string
     */
    public function getNombreRol() {
        return $this->nombre_rol;
    }

    /**
     * Establece el nombre del rol
     * @param string $nombre_rol
     */
    public function setNombreRol($nombre_rol) {
        $this->nombre_rol = $nombre_rol;
    }

    // ---- MÉTODOS ESTÁTICOS DE BD ----

    /**
     * Obtiene todos los roles de la base de datos
     * @param PDO $db Conexión a la base de datos
     * @return array|null Array de objetos Rol
     */
    public static function obtenerTodos($db) {
        $roles = [];
        $sql = "SELECT id_rol, nombre_rol FROM rol ORDER BY id_rol ASC";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $roles[] = new self($row['id_rol'], $row['nombre_rol']);
            }
        } catch (PDOException $e) {
            echo "Error al obtener roles: " . $e->getMessage();
            return null;
        }
        return $roles;
    }

    /**
     * Obtiene un rol por su ID
     * @param PDO $db Conexión a la base de datos
     * @param int $id_rol ID del rol
     * @return Rol|null
     */
    public static function obtenerPorId($db, $id_rol) {
        $sql = "SELECT id_rol, nombre_rol FROM rol WHERE id_rol = :id_rol";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_rol', intval($id_rol), PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new self($row['id_rol'], $row['nombre_rol']);
            }
        } catch (PDOException $e) {
            echo "Error al obtener rol: " . $e->getMessage();
        }
        return null;
    }

    /**
     * Inserta este rol en la base de datos
     * @param PDO $db Conexión a la base de datos
     * @return int ID del nuevo rol o false si hay error
     */
    public function insertar($db) {
        $sql = "INSERT INTO rol (nombre_rol) VALUES (:nombre_rol)";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':nombre_rol', $this->nombre_rol, PDO::PARAM_STR);
            $stmt->execute();
            $this->id_rol = $db->lastInsertId();
            return $this->id_rol;
        } catch (PDOException $e) {
            echo "Error al insertar rol: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Actualiza este rol en la base de datos
     * @param PDO $db Conexión a la base de datos
     * @return bool true si se actualizó correctamente
     */
    public function actualizar($db) {
        $sql = "UPDATE rol SET nombre_rol = :nombre_rol WHERE id_rol = :id_rol";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':nombre_rol', $this->nombre_rol, PDO::PARAM_STR);
            $stmt->bindValue(':id_rol', intval($this->id_rol), PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al actualizar rol: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Elimina este rol de la base de datos
     * @param PDO $db Conexión a la base de datos
     * @return bool true si se eliminó correctamente
     */
    public function eliminar($db) {
        $sql = "DELETE FROM rol WHERE id_rol = :id_rol";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_rol', intval($this->id_rol), PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al eliminar rol: " . $e->getMessage();
            return false;
        }
    }
    }
?>