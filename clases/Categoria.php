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
}

?>