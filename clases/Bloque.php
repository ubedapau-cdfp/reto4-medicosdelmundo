<?php
    class Bloque {
        public $id_bloque;
        public $titulo;
        public $subtitulo;
        public $contenido;
        public $orden;
        public $fecha_actualizacion;
        public $id_categoria;

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
    }
?>