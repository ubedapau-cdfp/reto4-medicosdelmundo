<?php
    class ContenidoExterno {
        private $id_url;
        private $url_externas;
        private $id_bloque;

    public function __construct($id_url, $url_externas, $id_bloque) {
        $this->id_url = $id_url;
        $this->url_externas = $url_externas;
        $this->id_bloque = $id_bloque;
    }

    /**
     * Obtiene el ID de la URL
     * @return int
     */
    public function getIdUrl() {
        return $this->id_url;
    }

    /**
     * Obtiene la URL externa
     * @return string
     */
    public function getUrlExternas() {
        return $this->url_externas;
    }

    /**
     * Obtiene el ID del bloque asociado
     * @return int
     */
    public function getIdBloque() {
        return $this->id_bloque;
    }

    /**
     * Establece la URL externa
     * @param string $url_externas
     */
    public function setUrlExternas($url_externas) {
        $this->url_externas = $url_externas;
    }

    /**
     * Establece el ID del bloque
     * @param int $id_bloque
     */
    public function setIdBloque($id_bloque) {
        $this->id_bloque = $id_bloque;
    }

    // ---- MÉTODOS ESTÁTICOS DE BD ----

    /**
     * Obtiene todas las URLs externas
     * @param PDO $db Conexión a la base de datos
     * @return array|null Array de objetos ContenidoExterno
     */
    public static function obtenerTodas($db) {
        $contenidos = [];
        $sql = "SELECT id_url, url_externas, id_bloque FROM contenido ORDER BY id_url ASC";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $contenidos[] = new self($row['id_url'], $row['url_externas'], $row['id_bloque']);
            }
        } catch (PDOException $e) {
            echo "Error al obtener contenidos externos: " . $e->getMessage();
            return null;
        }
        return $contenidos;
    }

    /**
     * Obtiene un contenido externo por su ID
     * @param PDO $db Conexión a la base de datos
     * @param int $id_url ID del contenido externo
     * @return ContenidoExterno|null
     */
    public static function obtenerPorId($db, $id_url) {
        $sql = "SELECT id_url, url_externas, id_bloque FROM contenido WHERE id_url = :id_url";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_url', intval($id_url), PDO::PARAM_INT);
            $stmt->execute();
            
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return new self($row['id_url'], $row['url_externas'], $row['id_bloque']);
            }
        } catch (PDOException $e) {
            echo "Error al obtener contenido externo: " . $e->getMessage();
        }
        return null;
    }

    /**
     * Obtiene todas las imágenes asociadas a un bloque (URLs que terminan en extensiones de imagen)
     * @param PDO $db Conexión a la base de datos
     * @param int $id_bloque ID del bloque
     * @return array|null Array de URLs de imágenes
     */
    public static function obtenerImagenesPorBloqueId($db, $id_bloque) {
        $imagenes = [];
        $sql = "SELECT url_externas FROM contenido WHERE id_bloque = :id_bloque AND (url_externas LIKE '%.jpg' OR url_externas LIKE '%.jpeg' OR url_externas LIKE '%.png' OR url_externas LIKE '%.gif' OR url_externas LIKE '%.webp' OR url_externas LIKE '%.svg') ORDER BY id_url ASC";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_bloque', intval($id_bloque), PDO::PARAM_INT);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $imagenes[] = $row['url_externas'];
            }
        } catch (PDOException $e) {
            echo "Error al obtener imágenes por bloque: " . $e->getMessage();
            return null;
        }
        return $imagenes;
    }

    /**
     * Obtiene todos los enlaces externos asociados a un bloque (URLs que NO terminan en extensiones de imagen)
     * @param PDO $db Conexión a la base de datos
     * @param int $id_bloque ID del bloque
     * @return array|null Array de objetos ContenidoExterno
     */
    public static function obtenerEnlacesPorBloqueId($db, $id_bloque) {
        $contenidos = [];
        $sql = "SELECT id_url, url_externas, id_bloque FROM contenido WHERE id_bloque = :id_bloque AND NOT (url_externas LIKE '%.jpg' OR url_externas LIKE '%.jpeg' OR url_externas LIKE '%.png' OR url_externas LIKE '%.gif' OR url_externas LIKE '%.webp' OR url_externas LIKE '%.svg') ORDER BY id_url ASC";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_bloque', intval($id_bloque), PDO::PARAM_INT);
            $stmt->execute();
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $contenidos[] = new self($row['id_url'], $row['url_externas'], $row['id_bloque']);
            }
        } catch (PDOException $e) {
            echo "Error al obtener enlaces por bloque: " . $e->getMessage();
            return null;
        }
        return $contenidos;
    }

    /**
     * Inserta este contenido externo en la base de datos
     * @param PDO $db Conexión a la base de datos
     * @return int ID del nuevo contenido o false si hay error
     */
    public function insertar($db) {
        $sql = "INSERT INTO contenido (url_externas, id_bloque) VALUES (:url_externas, :id_bloque)";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':url_externas', $this->url_externas, PDO::PARAM_STR);
            $stmt->bindValue(':id_bloque', intval($this->id_bloque), PDO::PARAM_INT);
            $stmt->execute();
            $this->id_url = $db->lastInsertId();
            return $this->id_url;
        } catch (PDOException $e) {
            echo "Error al insertar contenido externo: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Actualiza este contenido externo en la base de datos
     * @param PDO $db Conexión a la base de datos
     * @return bool true si se actualizó correctamente
     */
    public function actualizar($db) {
        $sql = "UPDATE contenido SET url_externas = :url_externas, id_bloque = :id_bloque WHERE id_url = :id_url";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':url_externas', $this->url_externas, PDO::PARAM_STR);
            $stmt->bindValue(':id_bloque', intval($this->id_bloque), PDO::PARAM_INT);
            $stmt->bindValue(':id_url', intval($this->id_url), PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al actualizar contenido externo: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Elimina este contenido externo
     * @param PDO $db Conexión a la base de datos
     * @return bool true si se eliminó correctamente
     */
    public function eliminar($db) {
        $sql = "DELETE FROM contenido WHERE id_url = :id_url";
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id_url', intval($this->id_url), PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Error al eliminar contenido externo: " . $e->getMessage();
            return false;
        }
    }
    }
?>