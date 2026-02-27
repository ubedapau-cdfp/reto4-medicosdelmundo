<?php
    class Faq {
        public $id_faq;
        public $pregunta;
        public $respuesta;
        public $fecha_actualizacion;
        public $id_categoria;

    public function __construct($id_faq, $pregunta, $respuesta, $id_categoria, $fecha_actualizacion = '') {
        $this->id_faq = $id_faq;
        $this->pregunta = $pregunta;
        $this->respuesta = $respuesta;
        $this->id_categoria = $id_categoria;
        $this->fecha_actualizacion = $fecha_actualizacion ?: date('Y-m-d');
    }
    }
?>