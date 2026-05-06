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

    public function getIcono() {
        return $this->icono;
    }

    public function getIdMadre() {
        return $this->id_madre;
    }

    // Setters para actualización
    public function setTitulo($titulo) {
        $this->titulo = $titulo;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    public function setIcono($icono) {
        $this->icono = $icono;
    }

    public function setIdMadre($id_madre) {
        $this->id_madre = $id_madre;
    }

    public function mostrarDatos() {
        echo "<h1>" . $this->titulo . "</h1>";
        if (!empty($this->descripcion)) {
            echo "<p>" . $this->descripcion . "</p>";
        }
    }

    // --- MÉTODOS ESTÁTICOS DE BÚSQUEDA ---

    public static function obtenerTodas($db) {
        $sql = "SELECT * FROM CATEGORIA ORDER BY titulo ASC";
        return self::ejecutarConsulta($db, $sql);
    }

    /**
     * Función auxiliar interna
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


    // Obtener solo las categorías principales (Madres)
    public static function obtenerCategoriasMadre($db) {
        $sql = "SELECT * FROM CATEGORIA WHERE id_madre IS NULL ORDER BY id_categoria ASC";
        return self::ejecutarConsulta($db, $sql);
    }

    // Obtener las subcategorías de una madre específica
    public static function obtenerSubcategorias($db, $id_madre) {
        $sql = "SELECT * FROM CATEGORIA WHERE id_madre = :id_madre ORDER BY id_categoria ASC";
        $id_madre = (int)$id_madre; // Asegurar que sea entero
        return self::ejecutarConsulta($db, $sql, [':id_madre' => $id_madre]);
    }

    // Obtener una categoría por su ID
    public static function obtenerPorId($db, $id_categoria) {
        $sql = "SELECT * FROM CATEGORIA WHERE id_categoria = :id_categoria";
        $id_categoria = (int)$id_categoria; // Asegurar que sea entero
        $categorias = self::ejecutarConsulta($db, $sql, [':id_categoria' => $id_categoria]);
        return !empty($categorias) ? $categorias[0] : null;
    }

    /**
     * Inserta esta categoría en la base de datos
     * @param PDO $db Conexión a la base de datos
     * @return int ID de la nueva categoría o false si hay error
     */
    public function insertar($db) {
        $sql = "INSERT INTO CATEGORIA (titulo, descripcion, icono, id_madre) VALUES (:titulo, :descripcion, :icono, :id_madre)";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':titulo' => $this->titulo,
                ':descripcion' => $this->descripcion,
                ':icono' => $this->icono,
                ':id_madre' => $this->id_madre
            ]);
            $this->id_categoria = $db->lastInsertId();
            return $this->id_categoria;
        } catch (PDOException $e) {
            echo "Error al insertar categoría: " . $e->getMessage();
            return false;
        }
    }

    /**
     * Actualiza esta categoría en la base de datos
     * @param PDO $db Conexión a la base de datos
     * @return bool true si se actualizó correctamente
     */
    public function actualizar($db) {
        $sql = "UPDATE CATEGORIA SET titulo = :titulo, descripcion = :descripcion, icono = :icono, id_madre = :id_madre, fecha_actualizacion = CURRENT_DATE WHERE id_categoria = :id_categoria";
        try {
            $stmt = $db->prepare($sql);
            $stmt->execute([
                ':titulo' => $this->titulo,
                ':descripcion' => $this->descripcion,
                ':icono' => $this->icono,
                ':id_madre' => $this->id_madre,
                ':id_categoria' => $this->id_categoria
            ]);
            return true;
        } catch (PDOException $e) {
            echo "Error al actualizar categoría: " . $e->getMessage();
            return false;
        }
    }

    // Método para eliminar esta categoría
    public function eliminar($db) {
        try {
            // Eliminar primero los bloques asociados a la categoría actual
            $sqlBloques = "DELETE FROM BLOQUE WHERE id_categoria = :id_categoria";
            $stmtBloques = $db->prepare($sqlBloques);
            $stmtBloques->execute([':id_categoria' => $this->id_categoria]);

            // Eliminar subcategorías hijas
            $sqlHijas = "SELECT id_categoria FROM CATEGORIA WHERE id_madre = :id_madre";
            $stmtHijas = $db->prepare($sqlHijas);
            $stmtHijas->execute([':id_madre' => $this->id_categoria]);
            while ($row = $stmtHijas->fetch(PDO::FETCH_ASSOC)) {
                $hijaCategoria = self::obtenerPorId($db, $row['id_categoria']);
                if ($hijaCategoria) {
                    $hijaCategoria->eliminar($db);
                }
            }

            // Eliminar la categoría actual
            $sql = "DELETE FROM CATEGORIA WHERE id_categoria = :id_categoria";
            $stmt = $db->prepare($sql);
            $stmt->execute([':id_categoria' => $this->id_categoria]);

            return true;
        } catch (PDOException $e) {
            echo "Error al eliminar categoría: " . $e->getMessage();
            return false;
        }
    }
}

?>