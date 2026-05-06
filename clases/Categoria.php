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

    // --- GETTERS ---
    public function getIdCategoria() { return $this->id_categoria; }
    public function getTitulo() { return $this->titulo; }
    public function getDescripcion() { return $this->descripcion; }
    public function getIcono() { return $this->icono; }
    public function getIdMadre() { return $this->id_madre; }
    public function getFechaActualizacion() { return $this->fecha_actualizacion; }

    // --- SETTERS ---
    public function setTitulo($titulo) { $this->titulo = $titulo; }
    public function setDescripcion($descripcion) { $this->descripcion = $descripcion; }
    public function setIcono($icono) { $this->icono = $icono; }
    public function setIdMadre($id_madre) { $this->id_madre = $id_madre; }

    // --- MÉTODOS DE VISUALIZACIÓN ---
    public function mostrarDatos() {
        echo "<h1>" . htmlspecialchars($this->titulo) . "</h1>";
        if (!empty($this->descripcion)) {
            echo "<p>" . htmlspecialchars($this->descripcion) . "</p>";
        }
    }

    // --- MÉTODO AUXILIAR INTERNO (DRY) ---
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
            // En entorno de producción, es mejor usar logs que 'echo'
            error_log("Error en la consulta: " . $e->getMessage());
        }
        return $categorias;
    }

    // --- MÉTODOS ESTÁTICOS DE BÚSQUEDA ---

    public static function obtenerTodas($db) {
        $sql = "SELECT * FROM CATEGORIA ORDER BY titulo ASC";
        return self::ejecutarConsulta($db, $sql);
    }

    public static function obtenerCategoriasMadre($db) {
        $sql = "SELECT * FROM CATEGORIA WHERE id_madre IS NULL ORDER BY id_categoria ASC";
        return self::ejecutarConsulta($db, $sql);
    }

    // Mantiene compatibilidad con ambos nombres de método solicitados
    public static function obtenerSubcategorias($db, $id_madre) {
        $sql = "SELECT * FROM CATEGORIA WHERE id_madre = :id_madre ORDER BY id_categoria ASC";
        return self::ejecutarConsulta($db, $sql, [':id_madre' => (int)$id_madre]);
    }

    public static function obtenerHijas($db, $id_madre) {
        return self::obtenerSubcategorias($db, $id_madre);
    }

    public static function obtenerPorId($db, $id_categoria) {
        $sql = "SELECT * FROM CATEGORIA WHERE id_categoria = :id_categoria";
        $categorias = self::ejecutarConsulta($db, $sql, [':id_categoria' => (int)$id_categoria]);
        return !empty($categorias) ? $categorias[0] : null;
    }

    // --- MÉTODOS DE PERSISTENCIA ---

    /**
     * Inserta la categoría y actualiza el ID del objeto
     */
    public function insertar($db) {
        $sql = "INSERT INTO CATEGORIA (titulo, descripcion, icono, id_madre, fecha_actualizacion) 
                VALUES (:titulo, :descripcion, :icono, :id_madre, CURRENT_DATE)";
        try {
            $stmt = $db->prepare($sql);
            $resultado = $stmt->execute([
                ':titulo' => $this->titulo,
                ':descripcion' => $this->descripcion,
                ':icono' => $this->icono,
                ':id_madre' => $this->id_madre
            ]);
            if ($resultado) {
                $this->id_categoria = $db->lastInsertId();
                return $this->id_categoria;
            }
            return false;
        } catch (PDOException $e) {
            error_log("Error al insertar categoría: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Actualiza los datos de la categoría existente
     */
    public function actualizar($db) {
        $sql = "UPDATE CATEGORIA SET 
                titulo = :titulo, 
                descripcion = :descripcion, 
                icono = :icono, 
                id_madre = :id_madre, 
                fecha_actualizacion = CURRENT_DATE 
                WHERE id_categoria = :id_categoria";
        try {
            $stmt = $db->prepare($sql);
            return $stmt->execute([
                ':titulo' => $this->titulo,
                ':descripcion' => $this->descripcion,
                ':icono' => $this->icono,
                ':id_madre' => $this->id_madre,
                ':id_categoria' => $this->id_categoria
            ]);
        } catch (PDOException $e) {
            error_log("Error al actualizar categoría: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Elimina la categoría, sus bloques asociados y sus subcategorías (recursivo)
     */
    public function eliminar($db) {
        try {
            // 1. Eliminar primero los bloques asociados a esta categoría
            $sqlBloques = "DELETE FROM BLOQUE WHERE id_categoria = :id_categoria";
            $stmtBloques = $db->prepare($sqlBloques);
            $stmtBloques->execute([':id_categoria' => $this->id_categoria]);

            // 2. Buscar y eliminar subcategorías hijas recursivamente
            $sqlHijas = "SELECT id_categoria FROM CATEGORIA WHERE id_madre = :id_madre";
            $stmtHijas = $db->prepare($sqlHijas);
            $stmtHijas->execute([':id_madre' => $this->id_categoria]);
            
            while ($row = $stmtHijas->fetch(PDO::FETCH_ASSOC)) {
                $hijaCategoria = self::obtenerPorId($db, $row['id_categoria']);
                if ($hijaCategoria) {
                    $hijaCategoria->eliminar($db); // Llamada recursiva
                }
            }

            // 3. Finalmente, eliminar la categoría actual
            $sql = "DELETE FROM CATEGORIA WHERE id_categoria = :id_categoria";
            $stmt = $db->prepare($sql);
            return $stmt->execute([':id_categoria' => $this->id_categoria]);

        } catch (PDOException $e) {
            error_log("Error al eliminar categoría: " . $e->getMessage());
            return false;
        }
    }
}
?>