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
    }
?>