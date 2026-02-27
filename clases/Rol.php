<?php
    class Rol {
        public int $id_rol;
        public string $nombre_rol;

    public function __construct(int $id_rol, string $nombre_rol) {
        $this->id_rol = $id_rol;
        $this->nombre_rol = $nombre_rol;
    }
    }
?>