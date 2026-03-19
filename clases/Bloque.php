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

    //funcion mostrar datos
    public function mostrarDatos() {
        echo "<h2>" . $this->titulo . "</h2>";
        if (!empty($this->subtitulo)) {
            echo "<h3>" . $this->subtitulo . "</h3>";
        }
        echo "<p>" . $this->contenido . "</p>";
        echo "<hr>";
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
