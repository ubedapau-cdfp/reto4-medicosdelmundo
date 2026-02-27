<?php
    class Categoria {
        public $id_categoria;
        public $titulo;
        public $descripcion;
        public $icono;
        public $id_madre; // Autorreferencia para subcategorías
        public $fecha_actualizacion;

    public function __construct(
        $id_categoria, 
        $titulo, 
        $id_madre = null, 
        $descripcion = null, 
        $icono = null, 
        $fecha_actualizacion = ''
    ) {
        $this->id_categoria = $id_categoria;
        $this->titulo = $titulo;
        $this->id_madre = $id_madre;
        $this->descripcion = $descripcion;
        $this->icono = $icono;
        $this->fecha_actualizacion = $fecha_actualizacion ?: date('Y-m-d');
    }
    }
?>