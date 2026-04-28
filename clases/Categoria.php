<?php
class Categoria {
    private $id_categoria;
    private $titulo;
    private $descripcion;
    private $icono;
    private $id_madre; // FK
    private $fecha_actualizacion;

    public function __construct(
        $id_categoria, 
        $titulo, 
        $descripcion = null, 
        $icono = null, 
        $id_madre = null, 
        $fecha_actualizacion = ''
    ) {
        $this->id_categoria = $id_categoria;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->icono = $icono;
        $this->id_madre = $id_madre;
        $this->fecha_actualizacion = $fecha_actualizacion ?: date('Y-m-d');
    }

    // Getters necesarios
    public function getTitulo(){ 
    return $this->titulo; 
    }
    public function getIdCategoria(){ 
    return $this->id_categoria; 
    }
    public function getDescripcion() {
        return $this->descripcion;
    }
    public function getIdMadre() {
        return $this->id_madre;
    }

    public function mostrarDatos() {
        echo "<h1>" . $this->titulo . "</h1>";
        if (!empty($this->descripcion)) {
            echo "<p><i>" . $this->descripcion . "</i></p>";
        }
    }

    // --- MÉTODOS ESTÁTICOS DE BÚSQUEDA ---

    public static function obtenerTodas($db) {
        $sql = "SELECT * FROM CATEGORIA ORDER BY titulo ASC";
        return self::ejecutarConsulta($db, $sql);
    }

    /**
     * Función auxiliar interna para evitar repetir código de mapeo
     */
    private static function ejecutarConsulta($db, $sql, $params = []) {
        $categorias = [];
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $categorias[] = new self(
                    $row['id_categoria'],
                    $row['titulo'],
                    $row['descripcion'],
                    $row['icono'],
                    $row['id_madre'],
                    $row['fecha_actualizacion']
                );
            }
        } catch (PDOException $e) {
            echo "Error en la consulta: " . $e->getMessage();
        }
        return $categorias;
    }


    // NUEVO: Obtener solo las categorías principales (Madres)
    public static function obtenerCategoriasMadre($db) {
        $sql = "SELECT * FROM CATEGORIA WHERE id_madre IS NULL ORDER BY id_categoria ASC";
        return self::ejecutarConsulta($db, $sql);
    }

    // NUEVO: Obtener las subcategorías de una madre específica
    public static function obtenerSubcategorias($db, $id_madre) {
        $sql = "SELECT * FROM CATEGORIA WHERE id_madre = :id_madre ORDER BY id_categoria ASC";
        return self::ejecutarConsulta($db, $sql, [':id_madre' => $id_madre]);
    }

    // NUEVO: Obtener una categoría por su ID
    public static function obtenerPorId($db, $id_categoria) {
        $sql = "SELECT * FROM CATEGORIA WHERE id_categoria = :id_categoria";
        $categorias = self::ejecutarConsulta($db, $sql, [':id_categoria' => $id_categoria]);
        return !empty($categorias) ? $categorias[0] : null;
    }

    // Método para insertar una nueva categoría
    public static function insertar($db, $titulo, $descripcion, $icono, $id_madre) {
        $sql = "INSERT INTO CATEGORIA (titulo, descripcion, icono, id_madre) VALUES (:titulo, :descripcion, :icono, :id_madre)";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':titulo' => $titulo,
                ':descripcion' => $descripcion,
                ':icono' => $icono,
                ':id_madre' => $id_madre
            ]);
            return $db->lastInsertId();
        } catch (PDOException $e) {
            echo "Error al insertar categoría: " . $e->getMessage();
            return false;
        }
    }

    // Método para actualizar una categoría
    public static function actualizar($db, $id_categoria, $titulo, $descripcion, $icono, $id_madre) {
        $sql = "UPDATE CATEGORIA SET titulo = :titulo, descripcion = :descripcion, icono = :icono, id_madre = :id_madre, fecha_actualizacion = CURRENT_DATE WHERE id_categoria = :id_categoria";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':titulo' => $titulo,
                ':descripcion' => $descripcion,
                ':icono' => $icono,
                ':id_madre' => $id_madre,
                ':id_categoria' => $id_categoria
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Error al actualizar categoría: " . $e->getMessage();
            return false;
        }
    }

    // Método para eliminar una categoría
    public static function eliminar($db, $id_categoria) {
        $sql = "DELETE FROM CATEGORIA WHERE id_categoria = :id_categoria";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([':id_categoria' => $id_categoria]);
            return true;
        } catch (PDOException $e) {
            echo "Error al eliminar categoría: " . $e->getMessage();
            return false;
        }
    }
}

?>