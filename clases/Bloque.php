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
    }
?>