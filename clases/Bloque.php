<?php
    class Bloque {
        public int $id_bloque;
        public ?string $titulo;
        public ?string $subtitulo;
        public ?string $contenido;
        public ?int $orden;
        public string $fecha_actualizacion;
        public int $id_categoria;
    }

    public function __construct(
        int $id_bloque, 
        int $id_categoria,
        ?string $titulo = null, 
        ?string $subtitulo = null, 
        ?string $contenido = null, 
        ?int $orden = null,
        string $fecha_actualizacion = ''
    ) {
        $this->id_bloque = $id_bloque;
        $this->id_categoria = $id_categoria;
        $this->titulo = $titulo;
        $this->subtitulo = $subtitulo;
        $this->contenido = $contenido;
        $this->orden = $orden;
        $this->fecha_actualizacion = $fecha_actualizacion ?: date('Y-m-d');
    }
?>