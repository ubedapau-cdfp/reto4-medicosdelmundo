<?php
        class Usuario {
        public $id_usuario;
        public $email;
        public $password;
        public $nombre;
        public $id_rol; // Relación con Rol

    public function __construct($id_usuario, $email, $password, $id_rol, $nombre = null) {
        $this->id_usuario = $id_usuario;
        $this->email = $email;
        $this->password = $password;
        $this->id_rol = $id_rol;
        $this->nombre = $nombre;
    }
}
?>