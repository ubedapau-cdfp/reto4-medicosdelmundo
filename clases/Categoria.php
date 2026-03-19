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

    // Función propia del objeto
    function mostrarDatos() {
        echo "<h1>" . $this->titulo . "</h1>";
        echo "<p><i>" . $this->descripcion . "</i></p>";
    }


    public static function mostrarBloquePorCategoria($categoria){
            include 'conexion.php'; 
            $sql = "SELECT b.* FROM BLOQUE b 
            INNER JOIN CATEGORIA c ON b.id_categoria = c.id_categoria 
            WHERE c.titulo = :nombre 
            ORDER BY b.orden";

        $stmt = $conn->prepare($sql); //conexion
        $stmt->bindParam(':nombre', $categoria);
        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            foreach($result as $row) {
                // Se crea el objeto
                $nuevoBloque = new Bloque(
                    $row['id_bloque'],
                    $row['id_categoria'],
                    $row['titulo'],
                    $row['subtitulo'],
                    $row['contenido'],
                    $row['orden']
                    
                );
                $nuevoBloque->mostrarDatos();
            }
        }
    }
}
?>
