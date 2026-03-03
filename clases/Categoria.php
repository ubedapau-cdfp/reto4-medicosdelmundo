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

    // Getter para el título (lo usaremos en el <option> del HTML)
    public function getTitulo() {
        return $this->titulo;
    }

    // Función para mostrar la cabecera de la categoría
    public function mostrarDatos() {
        echo "<h1>" . $this->titulo . "</h1>";
        if (!empty($this->descripcion)) {
            echo "<p><i>" . $this->descripcion . "</i></p>";
        }
    }

    public static function obtenerTodas($db) {
        $categorias = [];
        // Consulta simple para traer todas las categorías
        $sql = "SELECT * FROM CATEGORIA ORDER BY titulo ASC";
        
        try {
            $stmt = $db->query($sql);
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Creamos una nueva instancia de Categoria por cada fila
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
            echo "Error al obtener categorías: " . $e->getMessage();
        }

        return $categorias;
    }
}
?>