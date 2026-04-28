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

    // NUEVO: Crear una categoría madre para el header
    public static function crearCategoriaMadre($db, $titulo, $descripcion = null, $icono = null) {
        $sql = "INSERT INTO CATEGORIA (titulo, descripcion, icono, id_madre) VALUES (:titulo, :descripcion, :icono, NULL)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':titulo', $titulo, PDO::PARAM_STR);
        $stmt->bindValue(':descripcion', $descripcion, PDO::PARAM_STR);
        $stmt->bindValue(':icono', $icono, PDO::PARAM_STR);
        return $stmt->execute();
    }

    // NUEVO: Eliminar una categoría por su ID
    public static function eliminarPorId($db, $id_categoria) {
        $sql = "DELETE FROM CATEGORIA WHERE id_categoria = :id_categoria";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_categoria', $id_categoria, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // NUEVO: Verificar si una categoría madre tiene subcategorías
    public static function tieneSubcategorias($db, $id_categoria) {
        $sql = "SELECT COUNT(*) AS total FROM CATEGORIA WHERE id_madre = :id_categoria";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_categoria', $id_categoria, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn() > 0;
    }

    // NUEVO: Verificar si una categoría madre tiene bloques asociados
    public static function tieneBloques($db, $id_categoria) {
        $sql = "SELECT COUNT(*) AS total FROM BLOQUE WHERE id_categoria = :id_categoria";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id_categoria', $id_categoria, PDO::PARAM_INT);
        $stmt->execute();
        return (int)$stmt->fetchColumn() > 0;
    }
}

?>