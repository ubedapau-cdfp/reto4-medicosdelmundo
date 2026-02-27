<?php
    class Faq {
        public int $id_faq;
        public string $pregunta;
        public string $respuesta;
        public string $fecha_actualizacion;
        public int $id_categoria;
    }

    public function __construct(int $id_faq, string $pregunta, string $respuesta, int $id_categoria, string $fecha_actualizacion = '') {
        $this->id_faq = $id_faq;
        $this->pregunta = $pregunta;
        $this->respuesta = $respuesta;
        $this->id_categoria = $id_categoria;
        $this->fecha_actualizacion = $fecha_actualizacion ?: date('Y-m-d');
    }
?>