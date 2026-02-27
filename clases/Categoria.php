<?php
    class Categoria {
        public int $id_categoria;
        public string $titulo;
        public string $descripcion;
        public string $icono;
        public int $id_madre; // Autorreferencia para subcategorías
        public string $fecha_actualizacion;
    }

    public function __construct(
        int $id_categoria, 
        string $titulo, 
        int $id_madre = null, 
        string $descripcion = null, 
        string $icono = null, 
        string $fecha_actualizacion = ''
    ) {
        $this->id_categoria = $id_categoria;
        $this->titulo = $titulo;
        $this->id_madre = $id_madre;
        $this->descripcion = $descripcion;
        $this->icono = $icono;
        $this->fecha_actualizacion = $fecha_actualizacion ?: date('Y-m-d');
    }
?>