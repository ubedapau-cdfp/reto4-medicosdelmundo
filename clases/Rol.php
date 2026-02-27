<?php
    class Rol {
        public $id_rol;
        public $nombre_rol;

    public function __construct($id_rol, $nombre_rol) {
        $this->id_rol = $id_rol;
        $this->nombre_rol = $nombre_rol;
    }
    }
?>