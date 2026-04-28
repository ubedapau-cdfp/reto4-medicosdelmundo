<?php
    class Bloque {
        private $id_bloque;
        private $titulo;
        private $subtitulo;
        private $contenido;
        private $orden;
        private $fecha_actualizacion;
        private $id_categoria; //FK

        //Constructor
    public function __construct(
        $id_bloque, 
        $id_categoria,
        $titulo = null, 
        $subtitulo = null, 
        $contenido = null, 
        $orden = null,
        $fecha_actualizacion = ''
    ) {
        $this->id_bloque = $id_bloque;
        $this->id_categoria = $id_categoria;
        $this->titulo = $titulo;
        $this->subtitulo = $subtitulo;
        $this->contenido = $contenido;
        $this->orden = $orden;
        $this->fecha_actualizacion = $fecha_actualizacion ?: date('Y-m-d');
    }

    // Ejemplo de "Getter" por si necesitaramos el título en otro archivo
    public function getTitulo() {
        return $this->titulo;
    }

    public function getIdBloque() {
        return $this->id_bloque;
    }

    public function getSubtitulo() {
        return $this->subtitulo;
    }

    public function getContenido() {
        return $this->contenido;
    }

    public function getOrden() {
        return $this->orden;
    }

    public function getIdCategoria() {
        return $this->id_categoria;
    }

    //funcion mostrar datos
    public function mostrarDatos() {
        echo "<h2>" . $this->titulo . "</h2>";
        if (!empty($this->subtitulo)) {
            echo "<h3>" . $this->subtitulo . "</h3>";
        }
        echo "<p>" . $this->contenido . "</p>";
        echo "<hr>";
    }

    // --- NUEVO MÉTODO ESTÁTICO ---
    public static function obtenerPorCategoria($db, $nombreCategoria) {
        $bloques = [];
        $sql = "SELECT b.* FROM BLOQUE b 
                INNER JOIN CATEGORIA c ON b.id_categoria = c.id_categoria 
                WHERE c.titulo = :nombre 
                ORDER BY b.orden";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':nombre', $nombreCategoria);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Cada fila de la BD se convierte en una instancia de esta clase
            $bloques[] = new self(
                $row['id_bloque'],
                $row['id_categoria'],
                $row['titulo'],
                $row['subtitulo'],
                $row['contenido'],
                $row['orden'],
                $row['fecha_actualizacion']
            );
        }
        return $bloques;
    }

    // NUEVO: Obtener bloques por ID de categoría
    public static function obtenerPorCategoriaId($db, $id_categoria) {
        $bloques = [];
        $sql = "SELECT * FROM BLOQUE WHERE id_categoria = :id_categoria ORDER BY orden";

        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_categoria', $id_categoria, PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $bloques[] = new self(
                $row['id_bloque'],
                $row['id_categoria'],
                $row['titulo'],
                $row['subtitulo'],
                $row['contenido'],
                $row['orden'],
                $row['fecha_actualizacion']
            );
        }
        return $bloques;
    }

    // Método para obtener un bloque por ID
    public static function obtenerPorId($db, $id_bloque) {
        $sql = "SELECT * FROM BLOQUE WHERE id_bloque = :id_bloque";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id_bloque', $id_bloque, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new self(
                $row['id_bloque'],
                $row['id_categoria'],
                $row['titulo'],
                $row['subtitulo'],
                $row['contenido'],
                $row['orden'],
                $row['fecha_actualizacion']
            );
        }
        return null;
    }

    /**
     * Inserta este bloque en la base de datos
     * @param PDO $db Conexión a la base de datos
     * @return int ID del nuevo bloque o false si hay error
     */
    public function insertar($db) {
        $sql = "INSERT INTO BLOQUE (titulo, subtitulo, contenido, orden, id_categoria) VALUES (:titulo, :subtitulo, :contenido, :orden, :id_categoria)";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':titulo' => $this->titulo,
                ':subtitulo' => $this->subtitulo,
                ':contenido' => $this->contenido,
                ':orden' => $this->orden,
                ':id_categoria' => $this->id_categoria
            ]);
            $this->id_bloque = $db->lastInsertId();
            return $this->id_bloque;
        } catch (PDOException $e) {
            echo "Error al insertar bloque: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Actualiza este bloque en la base de datos
     * @param PDO $db Conexión a la base de datos
     * @return bool true si se actualizó correctamente
     */
    public function actualizar($db) {
        $sql = "UPDATE BLOQUE SET titulo = :titulo, subtitulo = :subtitulo, contenido = :contenido, orden = :orden, id_categoria = :id_categoria, fecha_actualizacion = CURRENT_DATE WHERE id_bloque = :id_bloque";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':titulo' => $this->titulo,
                ':subtitulo' => $this->subtitulo,
                ':contenido' => $this->contenido,
                ':orden' => $this->orden,
                ':id_categoria' => $this->id_categoria,
                ':id_bloque' => $this->id_bloque
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Error al actualizar bloque: " . $e->getMessage();
            return false;
        }
    }

    // Método para eliminar un bloque
    public static function eliminar($db, $id_bloque) {
        $sql = "DELETE FROM BLOQUE WHERE id_bloque = :id_bloque";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':id_bloque' => $id_bloque]);
            return true;
        } catch (PDOException $e) {
            echo "Error al eliminar bloque: " . $e->getMessage();
            return false;
        }
    }
}
?>
